<?php

require_once "../component/header.php";
require_once "../component/sidebar.php";
require_once "../crud/crud_class.php";

$crud = new crud_class();

$message = "";
$message_type = "";


// ==================================================
// INVOICE NUMBER
// ==================================================

$invoice_no = "INV-" . date("YmdHis");


// ==================================================
// GET ACTIVE BRANCHES
// ==================================================

$branch_sql = "
    SELECT
        branch_id,
        branch_name
    FROM pharmacy_branches
    WHERE status = 'Active'
    AND deleted_at IS NULL
    ORDER BY branch_name ASC
";

$branches = $crud->common_query($branch_sql);


// ==================================================
// GET MEDICINES
// STATUS FILTER REMOVED
// ==================================================

$medicine_sql = "
    SELECT
        medicine_id,
        medicine_name,
        generic_name,
        unit_price
    FROM medicines
    WHERE deleted_at IS NULL
    ORDER BY medicine_name ASC
";

$medicines = $crud->common_query($medicine_sql);


// ==================================================
// SAVE SALE
// ==================================================

if (isset($_POST['save_sale'])) {

    $branch_id = (int)($_POST['branch_id'] ?? 0);

    $customer_name = trim(
        $_POST['customer_name'] ?? ""
    );

    $customer_phone = trim(
        $_POST['customer_phone'] ?? ""
    );

    $payment_method =
        $_POST['payment_method'] ?? "Cash";

    $sale_items =
        $_POST['sale_items'] ?? [];


    // ==============================================
    // VALIDATION
    // ==============================================

    if ($branch_id <= 0) {

        $message = "Please select a branch.";
        $message_type = "danger";

    } elseif (empty($sale_items)) {

        $message = "Please add at least one medicine.";
        $message_type = "danger";

    } else {

        $crud->conn->begin_transaction();

        try {

            // ==========================================
            // CHECK BRANCH
            // ==========================================

            $branch_check_sql = "
                SELECT branch_id
                FROM pharmacy_branches
                WHERE branch_id = '$branch_id'
                AND status = 'Active'
                AND deleted_at IS NULL
                LIMIT 1
            ";

            $branch_check =
                $crud->conn->query(
                    $branch_check_sql
                );


            if (
                !$branch_check ||
                $branch_check->num_rows == 0
            ) {

                throw new Exception(
                    "Selected branch is not available."
                );
            }


            // ==========================================
            // CALCULATE TOTAL
            // ==========================================

            $total_amount = 0;


            foreach ($sale_items as $item) {

                $medicine_id =
                    (int)($item['medicine_id'] ?? 0);

                $quantity =
                    (int)($item['quantity'] ?? 0);


                if ($medicine_id <= 0) {

                    throw new Exception(
                        "Invalid medicine selected."
                    );
                }


                if ($quantity <= 0) {

                    throw new Exception(
                        "Quantity must be greater than 0."
                    );
                }


                // Get price
                $price_sql = "
                    SELECT
                        medicine_name,
                        unit_price
                    FROM medicines
                    WHERE medicine_id = '$medicine_id'
                    AND deleted_at IS NULL
                    LIMIT 1
                ";


                $price_result =
                    $crud->conn->query(
                        $price_sql
                    );


                if (
                    !$price_result ||
                    $price_result->num_rows == 0
                ) {

                    throw new Exception(
                        "Medicine not found."
                    );
                }


                $price_row =
                    $price_result->fetch_assoc();


                $unit_price =
                    (float)$price_row['unit_price'];


                $subtotal =
                    $unit_price * $quantity;


                $total_amount += $subtotal;
            }


            // ==========================================
            // INSERT SALE
            // ==========================================

            $invoice_safe =
                $crud->conn->real_escape_string(
                    $invoice_no
                );

            $customer_name_safe =
                $crud->conn->real_escape_string(
                    $customer_name
                );

            $customer_phone_safe =
                $crud->conn->real_escape_string(
                    $customer_phone
                );

            $payment_safe =
                $crud->conn->real_escape_string(
                    $payment_method
                );


            $sale_sql = "
                INSERT INTO pharmacy_sales
                (
                    invoice_no,
                    branch_id,
                    customer_name,
                    customer_phone,
                    sale_date,
                    total_amount,
                    payment_method,
                    status,
                    deleted_at
                )
                VALUES
                (
                    '$invoice_safe',
                    '$branch_id',
                    '$customer_name_safe',
                    '$customer_phone_safe',
                    NOW(),
                    '$total_amount',
                    '$payment_safe',
                    'Completed',
                    NULL
                )
            ";


            if (
                !$crud->conn->query(
                    $sale_sql
                )
            ) {

                throw new Exception(
                    "Sale could not be created: "
                    . $crud->conn->error
                );
            }


            $sale_id =
                $crud->conn->insert_id;


            // ==========================================
            // INSERT SALE ITEMS
            // ==========================================

            foreach ($sale_items as $item) {

                $medicine_id =
                    (int)($item['medicine_id'] ?? 0);

                $quantity =
                    (int)($item['quantity'] ?? 0);


                // Get medicine price
                $price_sql = "
                    SELECT
                        medicine_name,
                        unit_price
                    FROM medicines
                    WHERE medicine_id = '$medicine_id'
                    AND deleted_at IS NULL
                    LIMIT 1
                ";


                $price_result =
                    $crud->conn->query(
                        $price_sql
                    );


                if (
                    !$price_result ||
                    $price_result->num_rows == 0
                ) {

                    throw new Exception(
                        "Medicine not found."
                    );
                }


                $medicine_row =
                    $price_result->fetch_assoc();


                $medicine_name =
                    $medicine_row['medicine_name'];

                $unit_price =
                    (float)$medicine_row['unit_price'];


                $subtotal =
                    $unit_price * $quantity;


                // ======================================
                // CHECK BRANCH STOCK
                // ======================================

                $stock_sql = "
                    SELECT
                        branch_medicine_id,
                        quantity
                    FROM branch_medicines
                    WHERE branch_id = '$branch_id'
                    AND medicine_id = '$medicine_id'
                    LIMIT 1
                    FOR UPDATE
                ";


                $stock_result =
                    $crud->conn->query(
                        $stock_sql
                    );


                if (!$stock_result) {

                    throw new Exception(
                        "Unable to check stock."
                    );
                }


                if ($stock_result->num_rows == 0) {

                    throw new Exception(
                        $medicine_name .
                        " is not available in this branch."
                    );
                }


                $stock =
                    $stock_result->fetch_assoc();


                $available_quantity =
                    (int)$stock['quantity'];

                $branch_medicine_id =
                    (int)$stock[
                        'branch_medicine_id'
                    ];


                // ======================================
                // CHECK QUANTITY
                // ======================================

                if (
                    $quantity >
                    $available_quantity
                ) {

                    throw new Exception(
                        "Not enough stock for "
                        . $medicine_name
                        . ". Available: "
                        . $available_quantity
                    );
                }


                // ======================================
                // INSERT SALE ITEM
                // ======================================

                $item_sql = "
                    INSERT INTO pharmacy_sale_items
                    (
                        sale_id,
                        medicine_id,
                        quantity,
                        unit_price,
                        subtotal,
                        deleted_at
                    )
                    VALUES
                    (
                        '$sale_id',
                        '$medicine_id',
                        '$quantity',
                        '$unit_price',
                        '$subtotal',
                        NULL
                    )
                ";


                if (
                    !$crud->conn->query(
                        $item_sql
                    )
                ) {

                    throw new Exception(
                        "Unable to save sale item: "
                        . $crud->conn->error
                    );
                }


                // ======================================
                // UPDATE STOCK
                // ======================================

                $new_quantity =
                    $available_quantity - $quantity;


                $update_stock_sql = "
                    UPDATE branch_medicines
                    SET quantity = '$new_quantity'
                    WHERE branch_medicine_id =
                    '$branch_medicine_id'
                ";


                if (
                    !$crud->conn->query(
                        $update_stock_sql
                    )
                ) {

                    throw new Exception(
                        "Unable to update stock: "
                        . $crud->conn->error
                    );
                }
            }


            // ==========================================
            // COMMIT
            // ==========================================

            $crud->conn->commit();


            $message =
                "Sale created successfully! Invoice No: "
                . $invoice_no;

            $message_type = "success";


            $invoice_no =
                "INV-" . date("YmdHis");

        } catch (Exception $e) {

            $crud->conn->rollback();

            $message =
                $e->getMessage();

            $message_type =
                "danger";
        }
    }
}

?>


<style>

.new-sale-page {
    position: relative;
}

.new-sale-page input,
.new-sale-page select,
.new-sale-page textarea,
.new-sale-page button {
    pointer-events: auto !important;
    position: relative;
    z-index: 20;
}

.new-sale-page .form-group {
    position: relative;
    z-index: 20;
}

.new-sale-page .card {
    position: relative;
    z-index: 5;
}

.sale-total {
    font-size: 18px;
    font-weight: bold;
}

</style>


<div class="page-wrapper new-sale-page">

    <div class="content">


        <!-- ==========================================
             PAGE HEADER
        =========================================== -->

        <div class="page-header">

            <div class="page-title">

                <h4>
                    New Sale
                </h4>

                <h6>
                    Create a new pharmacy sale
                </h6>

            </div>


            <div class="page-btn">

                <a
                    href="sales.php"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left"></i>

                    Back to Sales

                </a>

            </div>

        </div>



        <!-- ==========================================
             MESSAGE
        =========================================== -->

        <?php if (!empty($message)) { ?>

            <div
                class="alert alert-<?php
                echo $message_type;
                ?>"
            >

                <?php
                echo htmlspecialchars(
                    $message
                );
                ?>

            </div>

        <?php } ?>



        <!-- ==========================================
             SALE FORM
        =========================================== -->

        <form
            method="POST"
            id="saleForm"
        >


            <!-- ======================================
                 SALE INFORMATION
            ======================================= -->

            <div class="card">

                <div class="card-header">

                    <h4>
                        Sale Information
                    </h4>

                </div>


                <div class="card-body">

                    <div class="row">


                        <!-- Invoice -->

                        <div
                            class="col-lg-4 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Invoice No
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?php
                                    echo htmlspecialchars(
                                        $invoice_no
                                    );
                                    ?>"
                                    readonly
                                >

                            </div>

                        </div>



                        <!-- Branch -->

                        <div
                            class="col-lg-4 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>

                                    Branch
                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select
                                    name="branch_id"
                                    id="branch_id"
                                    class="form-control"
                                    required
                                >

                                    <option value="">
                                        Select Branch
                                    </option>


                                    <?php

                                    if (
                                        isset(
                                            $branches['status']
                                        )
                                        &&
                                        $branches['status']
                                        === true
                                    ) {

                                        foreach (
                                            $branches['data']
                                            as $branch
                                        ) {

                                    ?>

                                        <option
                                            value="<?php
                                            echo (int)
                                                $branch->branch_id;
                                            ?>"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $branch->branch_name
                                            );
                                            ?>

                                        </option>

                                    <?php

                                        }
                                    }

                                    ?>

                                </select>

                            </div>

                        </div>



                        <!-- Customer Name -->

                        <div
                            class="col-lg-4 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Customer Name
                                </label>

                                <input
                                    type="text"
                                    name="customer_name"
                                    class="form-control"
                                    placeholder="Enter customer name"
                                >

                            </div>

                        </div>



                        <!-- Customer Phone -->

                        <div
                            class="col-lg-4 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Customer Phone
                                </label>

                                <input
                                    type="text"
                                    name="customer_phone"
                                    class="form-control"
                                    placeholder="Enter phone number"
                                >

                            </div>

                        </div>



                        <!-- Payment Method -->

                        <div
                            class="col-lg-4 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Payment Method
                                </label>

                                <select
                                    name="payment_method"
                                    class="form-control"
                                >

                                    <option value="Cash">
                                        Cash
                                    </option>

                                    <option value="Card">
                                        Card
                                    </option>

                                    <option
                                        value="Mobile Banking"
                                    >
                                        Mobile Banking
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ==========================================
                 ADD MEDICINE
            =========================================== -->

            <div class="card">

                <div class="card-header">

                    <h4>
                        Add Medicine
                    </h4>

                </div>


                <div class="card-body">

                    <div class="row">


                        <!-- Medicine -->

                        <div
                            class="col-lg-5 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Medicine
                                </label>


                                <select
                                    id="medicine_id"
                                    class="form-control"
                                >

                                    <option value="">
                                        Select Medicine
                                    </option>


                                    <?php

                                    if (
                                        isset(
                                            $medicines['status']
                                        )
                                        &&
                                        $medicines['status']
                                        === true
                                    ) {

                                        foreach (
                                            $medicines['data']
                                            as $medicine
                                        ) {

                                    ?>

                                        <option
                                            value="<?php
                                            echo (int)
                                                $medicine->medicine_id;
                                            ?>"
                                            data-price="<?php
                                            echo htmlspecialchars(
                                                $medicine->unit_price
                                            );
                                            ?>"
                                        >

                                            <?php

                                            echo htmlspecialchars(
                                                $medicine->medicine_name
                                            );


                                            if (
                                                !empty(
                                                    $medicine
                                                    ->generic_name
                                                )
                                            ) {

                                                echo " - "
                                                    .
                                                    htmlspecialchars(
                                                        $medicine
                                                        ->generic_name
                                                    );
                                            }

                                            ?>

                                        </option>

                                    <?php

                                        }
                                    }

                                    ?>

                                </select>

                            </div>

                        </div>



                        <!-- Unit Price -->

                        <div
                            class="col-lg-2 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Unit Price
                                </label>

                                <input
                                    type="number"
                                    id="unit_price"
                                    class="form-control"
                                    step="0.01"
                                    readonly
                                >

                            </div>

                        </div>



                        <!-- Quantity -->

                        <div
                            class="col-lg-2 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    id="quantity"
                                    class="form-control"
                                    min="1"
                                    value="1"
                                >

                            </div>

                        </div>



                        <!-- Add Button -->

                        <div
                            class="col-lg-3 col-sm-6 col-12"
                        >

                            <div class="form-group">

                                <label>
                                    &nbsp;
                                </label>

                                <button
                                    type="button"
                                    id="addMedicine"
                                    class="btn btn-primary w-100"
                                >

                                    <i class="fa fa-plus"></i>

                                    Add Medicine

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ==========================================
                 SALE ITEMS
            =========================================== -->

            <div class="card">

                <div class="card-header">

                    <h4>
                        Sale Items
                    </h4>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered"
                        >

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Medicine
                                    </th>

                                    <th>
                                        Unit Price
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Subtotal
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="saleItemsBody">

                                <tr id="emptyRow">

                                    <td
                                        colspan="6"
                                        class="text-center text-muted"
                                    >

                                        No medicine added yet.

                                    </td>

                                </tr>

                            </tbody>


                            <tfoot>

                                <tr>

                                    <th
                                        colspan="4"
                                        class="text-right"
                                    >

                                        Total Amount

                                    </th>

                                    <th>

                                        ৳

                                        <span
                                            id="totalAmount"
                                            class="sale-total"
                                        >
                                            0.00
                                        </span>

                                    </th>

                                    <th></th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>



            <!-- ==========================================
                 BUTTONS
            =========================================== -->

            <div class="card">

                <div class="card-body text-right">

                    <a
                        href="sales.php"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        name="save_sale"
                        class="btn btn-success"
                    >

                        <i class="fa fa-save"></i>

                        Save Sale

                    </button>

                </div>

            </div>


        </form>

    </div>

</div>



<script>

// ==================================================
// MEDICINE CHANGE
// ==================================================

document
    .getElementById("medicine_id")
    .addEventListener(
        "change",
        function () {

            const option =
                this.options[
                    this.selectedIndex
                ];

            const price =
                option.getAttribute(
                    "data-price"
                );

            document
                .getElementById(
                    "unit_price"
                )
                .value =
                price || "";

        }
    );


// ==================================================
// ADD MEDICINE
// ==================================================

let itemIndex = 0;


document
    .getElementById("addMedicine")
    .addEventListener(
        "click",
        function () {

            const medicineSelect =
                document.getElementById(
                    "medicine_id"
                );


            const medicineId =
                medicineSelect.value;


            if (!medicineId) {

                alert(
                    "Please select a medicine."
                );

                return;
            }


            const selectedOption =
                medicineSelect.options[
                    medicineSelect.selectedIndex
                ];


            const medicineName =
                selectedOption.text;


            const unitPrice =
                parseFloat(
                    document.getElementById(
                        "unit_price"
                    ).value
                );


            const quantity =
                parseInt(
                    document.getElementById(
                        "quantity"
                    ).value
                );


            if (
                isNaN(unitPrice)
                ||
                unitPrice <= 0
            ) {

                alert(
                    "Invalid medicine price."
                );

                return;
            }


            if (
                isNaN(quantity)
                ||
                quantity <= 0
            ) {

                alert(
                    "Please enter a valid quantity."
                );

                return;
            }


            // ======================================
            // DUPLICATE CHECK
            // ======================================

            const existing =
                document.querySelector(
                    'input[data-medicine-id="' +
                    medicineId +
                    '"]'
                );


            if (existing) {

                alert(
                    "This medicine is already added."
                );

                return;
            }


            // ======================================
            // REMOVE EMPTY ROW
            // ======================================

            const emptyRow =
                document.getElementById(
                    "emptyRow"
                );


            if (emptyRow) {

                emptyRow.remove();

            }


            const subtotal =
                unitPrice * quantity;


            const row =
                document.createElement("tr");


            row.innerHTML = `

                <td>
                    ${itemIndex + 1}
                </td>

                <td>

                    ${escapeHtml(medicineName)}

                    <input
                        type="hidden"
                        name="sale_items[${itemIndex}][medicine_id]"
                        value="${medicineId}"
                        data-medicine-id="${medicineId}"
                    >

                </td>

                <td>

                    ৳ ${unitPrice.toFixed(2)}

                    <input
                        type="hidden"
                        name="sale_items[${itemIndex}][unit_price]"
                        value="${unitPrice}"
                    >

                </td>

                <td>

                    ${quantity}

                    <input
                        type="hidden"
                        name="sale_items[${itemIndex}][quantity]"
                        value="${quantity}"
                    >

                </td>

                <td>

                    ৳ ${subtotal.toFixed(2)}

                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm remove-item"
                    >

                        <i class="fa fa-trash"></i>

                    </button>

                </td>

            `;


            document
                .getElementById(
                    "saleItemsBody"
                )
                .appendChild(row);


            itemIndex++;


            calculateTotal();


            // Reset

            medicineSelect.value = "";

            document
                .getElementById(
                    "unit_price"
                )
                .value = "";

            document
                .getElementById(
                    "quantity"
                )
                .value = "1";

        }
    );


// ==================================================
// REMOVE MEDICINE
// ==================================================

document.addEventListener(
    "click",
    function (event) {

        const button =
            event.target.closest(
                ".remove-item"
            );


        if (!button) {
            return;
        }


        const row =
            button.closest("tr");


        if (row) {
            row.remove();
        }


        calculateTotal();


        const rows =
            document.querySelectorAll(
                "#saleItemsBody tr"
            );


        if (rows.length === 0) {

            const emptyRow =
                document.createElement(
                    "tr"
                );


            emptyRow.id =
                "emptyRow";


            emptyRow.innerHTML = `

                <td
                    colspan="6"
                    class="text-center text-muted"
                >

                    No medicine added yet.

                </td>

            `;


            document
                .getElementById(
                    "saleItemsBody"
                )
                .appendChild(
                    emptyRow
                );
        }

    }
);


// ==================================================
// CALCULATE TOTAL
// ==================================================

function calculateTotal() {

    let total = 0;


    const rows =
        document.querySelectorAll(
            "#saleItemsBody tr"
        );


    rows.forEach(
        function (row) {

            const priceInput =
                row.querySelector(
                    'input[name*="[unit_price]"]'
                );


            const quantityInput =
                row.querySelector(
                    'input[name*="[quantity]"]'
                );


            if (
                priceInput &&
                quantityInput
            ) {

                const price =
                    parseFloat(
                        priceInput.value
                    );


                const quantity =
                    parseInt(
                        quantityInput.value
                    );


                total +=
                    price * quantity;
            }

        }
    );


    document
        .getElementById(
            "totalAmount"
        )
        .innerText =
        total.toFixed(2);

}


// ==================================================
// ESCAPE HTML
// ==================================================

function escapeHtml(text) {

    const div =
        document.createElement(
            "div"
        );

    div.textContent =
        text;

    return div.innerHTML;
}


// ==================================================
// FORM VALIDATION
// ==================================================

document
    .getElementById("saleForm")
    .addEventListener(
        "submit",
        function (event) {

            const branch =
                document.getElementById(
                    "branch_id"
                ).value;


            const items =
                document.querySelectorAll(
                    '#saleItemsBody input[name*="[medicine_id]"]'
                );


            if (!branch) {

                event.preventDefault();

                alert(
                    "Please select a branch."
                );

                return;
            }


            if (items.length === 0) {

                event.preventDefault();

                alert(
                    "Please add at least one medicine."
                );

                return;
            }


            if (
                !confirm(
                    "Are you sure you want to save this sale?"
                )
            ) {

                event.preventDefault();

            }

        }
    );

</script>

<?php
require_once "../component/footer.php";
?>