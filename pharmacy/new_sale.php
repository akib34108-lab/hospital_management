<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

/*
|--------------------------------------------------------------------------
| Demo Branches
|--------------------------------------------------------------------------
*/
$branches = [
    [
        "id" => 1,
        "name" => "SHIFA Main Pharmacy",
        "location" => "Dhaka"
    ],
    [
        "id" => 2,
        "name" => "SHIFA Chattogram Pharmacy",
        "location" => "Chattogram"
    ],
    [
        "id" => 3,
        "name" => "SHIFA Agrabad Pharmacy",
        "location" => "Agrabad"
    ]
];


/*
|--------------------------------------------------------------------------
| Demo Medicines
|--------------------------------------------------------------------------
*/
$medicines = [
    [
        "id" => 1,
        "name" => "Napa",
        "generic" => "Paracetamol",
        "strength" => "500mg",
        "form" => "Tablet",
        "price" => 10
    ],
    [
        "id" => 2,
        "name" => "Seclo",
        "generic" => "Omeprazole",
        "strength" => "20mg",
        "form" => "Capsule",
        "price" => 60
    ],
    [
        "id" => 3,
        "name" => "Napa Extra",
        "generic" => "Paracetamol + Caffeine",
        "strength" => "500mg + 65mg",
        "form" => "Tablet",
        "price" => 15
    ]
];


/*
|--------------------------------------------------------------------------
| Demo Availability
|--------------------------------------------------------------------------
*/
$availability = [
    1 => [
        1 => 120,
        2 => 75,
        3 => 0
    ],

    2 => [
        1 => 40,
        2 => 0,
        3 => 25
    ],

    3 => [
        1 => 60,
        2 => 35,
        3 => 0
    ]
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    New Pharmacy Sale
                </h4>

            </div>

            <div class="col-sm-5 col-6 text-right">

                <a href="sales.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-arrow-left"></i>
                    Sales History

                </a>

            </div>

        </div>


        <!-- Sale Form -->
        <div class="card">

            <div class="card-header">

                <h4 class="card-title">
                    <i class="fa fa-cart-plus"></i>
                    Create New Sale
                </h4>

                <p class="text-muted mb-0">
                    Select a branch and medicine to create a pharmacy sale.
                </p>

            </div>


            <div class="card-body">

                <form method="POST"
                      action=""
                      id="saleForm">


                    <div class="row">

                        <!-- Branch -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Pharmacy Branch
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="branch_id"
                                        id="branchSelect"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        Select Pharmacy Branch
                                    </option>

                                    <?php foreach ($branches as $branch): ?>

                                        <option
                                            value="<?= $branch['id']; ?>">

                                            <?= htmlspecialchars(
                                                $branch['name']
                                            ); ?>

                                            -
                                            <?= htmlspecialchars(
                                                $branch['location']
                                            ); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- Medicine -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Medicine
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="medicine_id"
                                        id="medicineSelect"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        Select Medicine
                                    </option>

                                    <?php foreach ($medicines as $medicine): ?>

                                        <option
                                            value="<?= $medicine['id']; ?>"
                                            data-price="<?= $medicine['price']; ?>">

                                            <?= htmlspecialchars(
                                                $medicine['name']
                                            ); ?>

                                            -
                                            <?= htmlspecialchars(
                                                $medicine['strength']
                                            ); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                    </div>


                    <!-- Medicine Information -->
                    <div id="medicineInfo"
                         class="alert alert-info"
                         style="display:none;">

                        <div class="row">

                            <div class="col-md-4">

                                <strong>
                                    Generic:
                                </strong>

                                <span id="genericName">
                                    -
                                </span>

                            </div>


                            <div class="col-md-4">

                                <strong>
                                    Form:
                                </strong>

                                <span id="medicineForm">
                                    -
                                </span>

                            </div>


                            <div class="col-md-4">

                                <strong>
                                    Unit Price:
                                </strong>

                                ৳<span id="displayPrice">
                                    0.00
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- Availability -->
                    <div id="availabilityBox"
                         class="card"
                         style="display:none;">

                        <div class="card-body">

                            <div class="row align-items-center">

                                <div class="col-md-1 text-center">

                                    <i class="fa fa-cubes"
                                       style="
                                       font-size:35px;
                                       color:#009efb;
                                       ">
                                    </i>

                                </div>


                                <div class="col-md-7">

                                    <h5 class="mb-1">
                                        Branch Availability
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Available stock in selected branch
                                    </p>

                                </div>


                                <div class="col-md-4 text-right">

                                    <h2 class="text-primary mb-0">

                                        <span id="availableQuantity">
                                            0
                                        </span>

                                    </h2>

                                    <small class="text-muted">
                                        units available
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <!-- Quantity -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Quantity
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       class="form-control"
                                       min="1"
                                       value="1"
                                       required>

                                <small id="stockWarning"
                                       class="text-danger"
                                       style="display:none;">
                                </small>

                            </div>

                        </div>


                        <!-- Unit Price -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Unit Price
                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            ৳
                                        </span>
                                    </div>

                                    <input type="number"
                                           name="unit_price"
                                           id="unitPrice"
                                           class="form-control"
                                           step="0.01"
                                           readonly>

                                </div>

                            </div>

                        </div>


                        <!-- Discount -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Discount
                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            ৳
                                        </span>
                                    </div>

                                    <input type="number"
                                           name="discount"
                                           id="discount"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           value="0">

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Total -->
                    <div class="card"
                         style="
                         background:#f8fbff;
                         border:1px solid #e5eef7;
                         ">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Subtotal
                                    </small>

                                    <h4>
                                        ৳<span id="subtotal">
                                            0.00
                                        </span>
                                    </h4>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Discount
                                    </small>

                                    <h4>
                                        ৳<span id="discountDisplay">
                                            0.00
                                        </span>
                                    </h4>

                                </div>


                                <div class="col-md-4 text-right">

                                    <small class="text-muted">
                                        Grand Total
                                    </small>

                                    <h2 class="text-primary">
                                        ৳<span id="grandTotal">
                                            0.00
                                        </span>
                                    </h2>

                                    <input type="hidden"
                                           name="total_amount"
                                           id="totalAmount">

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <!-- Payment Method -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Payment Method
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="payment_method"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        Select Payment Method
                                    </option>

                                    <option value="Cash">
                                        Cash
                                    </option>

                                    <option value="Card">
                                        Card
                                    </option>

                                    <option value="Mobile Banking">
                                        Mobile Banking
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- Payment Status -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Payment Status
                                </label>

                                <select name="payment_status"
                                        class="form-control">

                                    <option value="Paid">
                                        Paid
                                    </option>

                                    <option value="Pending">
                                        Pending
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- Customer Name -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Customer Name
                                </label>

                                <input type="text"
                                       name="customer_name"
                                       class="form-control"
                                       placeholder="Enter customer name">

                            </div>

                        </div>


                        <!-- Customer Phone -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Customer Phone
                                </label>

                                <input type="text"
                                       name="customer_phone"
                                       class="form-control"
                                       placeholder="Enter customer phone">

                            </div>

                        </div>


                        <!-- Notes -->
                        <div class="col-md-12">

                            <div class="form-group">

                                <label>
                                    Sale Notes
                                </label>

                                <textarea name="notes"
                                          rows="3"
                                          class="form-control"
                                          placeholder="Additional notes..."></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- Buttons -->
                    <div class="text-right">

                        <a href="sales.php"
                           class="btn btn-secondary">

                            Cancel

                        </a>


                        <button type="submit"
                                name="create_sale"
                                id="createSaleBtn"
                                class="btn btn-primary">

                            <i class="fa fa-check"></i>
                            Create Sale

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Important Notice -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-lightbulb-o"
                           style="
                           font-size:40px;
                           color:#f5a623;
                           ">
                        </i>

                    </div>


                    <div class="col-md-11">

                        <h5>
                            Smart Stock Validation
                        </h5>

                        <p class="text-muted mb-0">

                            The system checks the selected branch's
                            available quantity before allowing a sale.
                            Staff cannot sell more medicine than the
                            available branch stock.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php
    require_once "../component/footer.php";
    ?>

</div>


<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Demo Availability
    |--------------------------------------------------------------------------
    */

    var availability = <?= json_encode($availability); ?>;

    var medicines = <?= json_encode($medicines); ?>;


    /*
    |--------------------------------------------------------------------------
    | Medicine Select
    |--------------------------------------------------------------------------
    */

    $("#medicineSelect").on("change", function () {

        var medicineId = $(this).val();

        if (medicineId === "") {

            $("#medicineInfo").hide();
            $("#availabilityBox").hide();

            $("#unitPrice").val("");

            return;
        }


        var medicine = medicines.find(function (item) {

            return item.id == medicineId;

        });


        if (medicine) {

            $("#genericName").text(
                medicine.generic
            );

            $("#medicineForm").text(
                medicine.form
            );

            $("#displayPrice").text(
                parseFloat(medicine.price).toFixed(2)
            );

            $("#unitPrice").val(
                medicine.price
            );

            $("#medicineInfo").slideDown();

        }


        updateAvailability();

        calculateTotal();

    });


    /*
    |--------------------------------------------------------------------------
    | Branch Select
    |--------------------------------------------------------------------------
    */

    $("#branchSelect").on("change", function () {

        updateAvailability();

    });


    /*
    |--------------------------------------------------------------------------
    | Quantity Change
    |--------------------------------------------------------------------------
    */

    $("#quantity").on(
        "input",
        function () {

            validateQuantity();

            calculateTotal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Discount Change
    |--------------------------------------------------------------------------
    */

    $("#discount").on(
        "input",
        function () {

            calculateTotal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Update Availability
    |--------------------------------------------------------------------------
    */

    function updateAvailability() {

        var branchId =
            $("#branchSelect").val();

        var medicineId =
            $("#medicineSelect").val();


        if (
            branchId === "" ||
            medicineId === ""
        ) {

            $("#availabilityBox").hide();

            return;

        }


        var quantity = 0;


        if (
            availability[medicineId] &&
            availability[medicineId][branchId]
        ) {

            quantity =
                availability[medicineId][branchId];

        }


        $("#availableQuantity").text(
            quantity
        );


        $("#availabilityBox").slideDown();


        validateQuantity();

    }


    /*
    |--------------------------------------------------------------------------
    | Validate Quantity
    |--------------------------------------------------------------------------
    */

    function validateQuantity() {

        var quantity =
            parseInt($("#quantity").val()) || 0;

        var available =
            parseInt($("#availableQuantity").text()) || 0;


        if (available <= 0) {

            $("#stockWarning")
                .text("This medicine is out of stock in the selected branch.")
                .show();

            $("#createSaleBtn").prop(
                "disabled",
                true
            );

            return false;

        }


        if (quantity > available) {

            $("#stockWarning")
                .text(
                    "Only " +
                    available +
                    " units are available."
                )
                .show();

            $("#createSaleBtn").prop(
                "disabled",
                true
            );

            return false;

        }


        $("#stockWarning").hide();

        $("#createSaleBtn").prop(
            "disabled",
            false
        );

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Total
    |--------------------------------------------------------------------------
    */

    function calculateTotal() {

        var quantity =
            parseFloat($("#quantity").val()) || 0;

        var price =
            parseFloat($("#unitPrice").val()) || 0;

        var discount =
            parseFloat($("#discount").val()) || 0;


        var subtotal =
            quantity * price;


        var grandTotal =
            subtotal - discount;


        if (grandTotal < 0) {

            grandTotal = 0;

        }


        $("#subtotal").text(
            subtotal.toFixed(2)
        );


        $("#discountDisplay").text(
            discount.toFixed(2)
        );


        $("#grandTotal").text(
            grandTotal.toFixed(2)
        );


        $("#totalAmount").val(
            grandTotal.toFixed(2)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Form Submit Validation
    |--------------------------------------------------------------------------
    */

    $("#saleForm").on("submit", function (event) {

        if (!validateQuantity()) {

            event.preventDefault();

            return false;

        }

    });

});
</script>