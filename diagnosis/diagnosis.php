<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

$conn = $crud->conn;

$edit_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$edit_data = null;
$edit_items = [];

/* ================= LAB TESTS ================= */

$lab_result = $crud->common_select("lab_category", "*");

$lab_tests = [];

if ($lab_result["status"] && !empty($lab_result["data"])) {
    $lab_tests = $lab_result["data"];
}


/* ================= PATIENTS ================= */

$patient_result = $crud->common_select("patients", "*");

$patients = [];

if ($patient_result["status"] && !empty($patient_result["data"])) {
    $patients = $patient_result["data"];
}


/* ================= EDIT DATA ================= */

if ($edit_id > 0) {

    $edit_result = $crud->common_query("
        SELECT *
        FROM invoices
        WHERE id = $edit_id
        LIMIT 1
    ");

    if ($edit_result["status"] && !empty($edit_result["data"])) {
        $edit_data = $edit_result["data"][0];
    }


    $edit_items_result = $crud->common_query("
        SELECT *
        FROM invoice_details
        WHERE invoice_id = $edit_id
        AND deleted_at IS NULL
        ORDER BY id ASC
    ");

    if ($edit_items_result["status"] && !empty($edit_items_result["data"])) {
        $edit_items = $edit_items_result["data"];
    }
}
?>


<div class="page-wrapper">
<div class="content">


<?php

/* =========================================================
   SAVE / UPDATE INVOICE
   ========================================================= */

if (isset($_POST['save'])) {

    $patient_id = (int)$_POST['patient_id'];

    $invoice_type = $conn->real_escape_string(
        $_POST['invoice_type']
    );

    $invoice_date = $conn->real_escape_string(
        $_POST['invoice_date']
    );

    $sub_amount = (float)$_POST['sub_amount'];
    $discount_tk = (float)$_POST['discount_tk'];
    $tax_tk = (float)$_POST['tax_tk'];
    $grand_total = (float)$_POST['grand_total'];

    $invoice_id_post = isset($_POST['invoice_id'])
        ? (int)$_POST['invoice_id']
        : 0;

    $admission_id = 0;


    /* =====================================================
       ADMITTED PATIENT
       ===================================================== */

    if ($invoice_type == "ADMITTED") {

        if (
            !isset($_POST['admission_id']) ||
            empty($_POST['admission_id'])
        ) {

            echo "<script>
                alert('Please select an admission.');
                history.back();
            </script>";

            exit;
        }

        $admission_id = (int)$_POST['admission_id'];


        /* Check admission belongs to selected patient */

        $check_admission = $crud->common_query("
            SELECT id
            FROM patient_admissions
            WHERE id = $admission_id
            AND patient_id = $patient_id
            AND deleted_at IS NULL
            LIMIT 1
        ");


        if (
            !$check_admission["status"] ||
            empty($check_admission["data"])
        ) {

            echo "<script>
                alert('Invalid admission for selected patient.');
                history.back();
            </script>";

            exit;
        }

    } else {

        /* OUTDOOR = NO ADMISSION */

        $admission_id = 0;
    }


    /* =====================================================
       UPDATE EXISTING INVOICE
       ===================================================== */

    if ($invoice_id_post > 0) {

        if ($invoice_type == "ADMITTED") {

            $update_data = [
                "patient_id" => $patient_id,
                "invoice_type" => $invoice_type,
                "admission_id" => $admission_id,
                "sub_amount" => $sub_amount,
                "discount" => $discount_tk,
                "tax" => $tax_tk,
                "invoice_date" => $invoice_date
            ];

        } else {

            $update_data = [
                "patient_id" => $patient_id,
                "invoice_type" => $invoice_type,
                "admission_id" => null,
                "sub_amount" => $sub_amount,
                "discount" => $discount_tk,
                "tax" => $tax_tk,
                "invoice_date" => $invoice_date
            ];
        }


        $update_result = $crud->common_update(
            "invoices",
            $update_data,
            [
                "id" => $invoice_id_post
            ]
        );


        if (!$update_result["status"]) {

            die(
                "Invoice Update Error: " .
                $update_result["message"]
            );
        }


        $last_id = $invoice_id_post;


        /* Delete old details before saving updated details */

        $crud->common_query("
            DELETE FROM invoice_details
            WHERE invoice_id = $last_id
        ");


    } else {


        /* =================================================
           INSERT NEW INVOICE
           ================================================= */

        if ($invoice_type == "ADMITTED") {

            $invoice_data = [
                "patient_id" => $patient_id,
                "invoice_type" => $invoice_type,
                "admission_id" => $admission_id,
                "sub_amount" => $sub_amount,
                "discount" => $discount_tk,
                "tax" => $tax_tk,
                "invoice_date" => $invoice_date,
                "status" => 1
            ];

        } else {

            $invoice_data = [
                "patient_id" => $patient_id,
                "invoice_type" => $invoice_type,
                "admission_id" => null,
                "sub_amount" => $sub_amount,
                "discount" => $discount_tk,
                "tax" => $tax_tk,
                "invoice_date" => $invoice_date,
                "status" => 1
            ];
        }


        $insert_result = $crud->common_insert(
            "invoices",
            $invoice_data
        );


        if (!$insert_result["status"]) {

            die(
                "Invoice Save Error: " .
                $insert_result["message"]
            );
        }


        $last_id = (int)$insert_result["data"];
    }


    /* =====================================================
       SAVE TEST DETAILS
       ===================================================== */

    if (
        isset($_POST['item_name']) &&
        is_array($_POST['item_name'])
    ) {

        for (
            $i = 0;
            $i < count($_POST['item_name']);
            $i++
        ) {

            if (
                !isset($_POST['item_name'][$i]) ||
                empty($_POST['item_name'][$i])
            ) {
                continue;
            }


            $name = trim($_POST['item_name'][$i]);

            $price = isset($_POST['item_price'][$i])
                ? (float)$_POST['item_price'][$i]
                : 0;

            $discount = isset($_POST['item_discount'][$i])
                ? (float)$_POST['item_discount'][$i]
                : 0;

            $tax = isset($_POST['item_tax'][$i])
                ? (float)$_POST['item_tax'][$i]
                : 0;


            $detail_data = [
                "invoice_id" => $last_id,
                "Name" => $name,
                "price" => $price,
                "discount" => $discount,
                "tax" => $tax
            ];


            $detail_result = $crud->common_insert(
                "invoice_details",
                $detail_data
            );


            if (!$detail_result["status"]) {

                die(
                    "Test Detail Save Error: " .
                    $detail_result["message"]
                );
            }
        }
    }


    /* =====================================================
       SUCCESS
       ===================================================== */

    echo "<script>
        alert('Invoice Saved Successfully');
        window.location='diagnosis_invoice.php?id=$last_id';
    </script>";

    exit;
}


/* =========================================================
   DELETE INVOICE
   ========================================================= */

if (isset($_GET['delete_id'])) {

    $delete_id = (int)$_GET['delete_id'];


    /* Delete invoice details */

    $crud->common_query("
        DELETE FROM invoice_details
        WHERE invoice_id = $delete_id
    ");


    /* Delete invoice */

    $delete_result = $crud->common_delete(
        "invoices",
        [
            "id" => $delete_id
        ]
    );


    echo "<script>
        alert('Invoice Deleted');
        window.location='invoices.php';
    </script>";

    exit;
}

?>


<!-- =======================================================
     FORM
======================================================= -->

<div class="row">
<div class="col-md-12">

<div class="card-box">

<h4 class="card-title">

<?php

echo $edit_id
    ? "Edit Diagnosis Invoice"
    : "Add Diagnosis Invoice";

?>

</h4>


<form method="post" action="" id="invoiceForm">


<input
    type="hidden"
    name="invoice_id"
    value="<?php echo $edit_id; ?>"
>


<!-- =====================================================
     INVOICE TYPE
===================================================== -->

<div class="form-group row">

<label class="col-form-label col-md-2">
Invoice Type *
</label>


<div class="col-md-4">

<select
    name="invoice_type"
    id="invoice_type"
    class="form-control"
    required
>

<option value="OUTDOOR"
<?php

if (
    $edit_data &&
    $edit_data->invoice_type == "OUTDOOR"
) {
    echo "selected";
}

?>
>
Outdoor / Diagnostic
</option>


<option value="ADMITTED"
<?php

if (
    $edit_data &&
    $edit_data->invoice_type == "ADMITTED"
) {
    echo "selected";
}

?>
>
Admitted Patient
</option>

</select>

</div>


<label class="col-form-label col-md-2">
Invoice Date *
</label>


<div class="col-md-4">

<input
    type="date"
    name="invoice_date"
    class="form-control"
    value="<?php

    echo $edit_data
        ? htmlspecialchars($edit_data->invoice_date)
        : date("Y-m-d");

    ?>"
    required
>

</div>

</div>


<!-- =====================================================
     PATIENT
===================================================== -->

<div class="form-group row">

<label class="col-form-label col-md-2">
Patient *
</label>


<div class="col-md-4">

<select
    name="patient_id"
    id="patient_id"
    class="form-control"
    required
>

<option value="">
-- Select Patient --
</option>


<?php

foreach ($patients as $p) {

    $selected = "";

    if (
        $edit_data &&
        $edit_data->patient_id == $p->id
    ) {
        $selected = "selected";
    }

?>

<option
    value="<?php echo $p->id; ?>"
    data-discount="<?php echo $p->discount_percent ?? 0; ?>"
    <?php echo $selected; ?>
>

<?php echo htmlspecialchars($p->name); ?>

</option>

<?php

}

?>

</select>

</div>


<!-- =====================================================
     ADMISSION LABEL
===================================================== -->

<div class="col-md-2 admitted-field">

<label class="col-form-label">
Admission
</label>

</div>


<!-- =====================================================
     ADMISSION
===================================================== -->

<div class="col-md-4 admitted-field">

<select
    name="admission_id"
    id="admission_id"
    class="form-control"
>

<option value="">
-- Select Admission --
</option>


<?php

$admission_result = $crud->common_query("
    SELECT id, admission_no, patient_id
    FROM patient_admissions
    WHERE deleted_at IS NULL
    ORDER BY id DESC
");


if (
    $admission_result["status"] &&
    !empty($admission_result["data"])
) {

    foreach (
        $admission_result["data"]
        as $a
    ) {

        $selected = "";

        if (
            $edit_data &&
            $edit_data->admission_id == $a->id
        ) {
            $selected = "selected";
        }

?>

<option
    value="<?php echo $a->id; ?>"
    data-patient="<?php echo $a->patient_id; ?>"
    <?php echo $selected; ?>
>

<?php echo htmlspecialchars($a->admission_no); ?>

</option>

<?php

    }
}

?>

</select>


<small class="text-muted">
Admission will be selected automatically for admitted patient.
</small>

</div>

</div>


<!-- =====================================================
     TESTS
===================================================== -->

<div class="card-box">

<h4 class="text-blue h4">
Lab / Diagnostic Tests
</h4>


<button
    type="button"
    id="addItem"
    class="btn btn-primary mb-3"
>
+ Add Test
</button>


<div class="table-responsive">

<table
    class="table table-bordered"
    id="itemTable"
>

<thead class="bg-light">

<tr>

<th>Test Name</th>
<th>Price</th>
<th>Discount %</th>
<th>Tax %</th>
<th>Total</th>
<th width="80">Action</th>

</tr>

</thead>


<tbody>


<?php

if (!empty($edit_items)) {

    foreach ($edit_items as $item) {

?>

<tr>

<td>

<select
    name="item_name[]"
    class="form-control calc item_name"
>

<option value="">
-- Select Test --
</option>


<?php

foreach ($lab_tests as $t) {

    $selected = "";

    if (
        $item->Name ==
        $t->test_name
    ) {
        $selected = "selected";
    }

?>

<option
    value="<?php echo htmlspecialchars($t->test_name); ?>"
    data-price="<?php echo $t->price; ?>"
    <?php echo $selected; ?>
>

<?php echo htmlspecialchars($t->test_name); ?>

</option>

<?php

}

?>

</select>

</td>


<td>

<input
    type="number"
    name="item_price[]"
    value="<?php echo $item->price; ?>"
    class="form-control calc item_price"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_discount[]"
    value="<?php echo $item->discount; ?>"
    class="form-control calc item_discount"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_tax[]"
    value="<?php echo $item->tax; ?>"
    class="form-control calc item_tax"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_total[]"
    class="form-control item_total"
    readonly
>

</td>


<td>

<button
    type="button"
    class="btn btn-danger btn-sm removeRow"
>
X
</button>

</td>

</tr>


<?php

    }

} else {

?>


<tr>

<td>

<select
    name="item_name[]"
    class="form-control calc item_name"
>

<option value="">
-- Select Test --
</option>


<?php

foreach ($lab_tests as $t) {

?>

<option
    value="<?php echo htmlspecialchars($t->test_name); ?>"
    data-price="<?php echo $t->price; ?>"
>

<?php echo htmlspecialchars($t->test_name); ?>

</option>

<?php

}

?>

</select>

</td>


<td>

<input
    type="number"
    name="item_price[]"
    class="form-control calc item_price"
    value="0"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_discount[]"
    class="form-control calc item_discount"
    value="0"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_tax[]"
    class="form-control calc item_tax"
    value="0"
    min="0"
    step="0.01"
>

</td>


<td>

<input
    type="number"
    name="item_total[]"
    class="form-control item_total"
    readonly
>

</td>


<td>

<button
    type="button"
    class="btn btn-danger btn-sm removeRow"
>
X
</button>

</td>

</tr>


<?php

}

?>

</tbody>

</table>

</div>

</div>


<!-- =====================================================
     TOTAL
===================================================== -->

<div class="row">

<div class="col-md-6 offset-md-6">

<table class="table table-bordered">

<tr>

<td>
Sub Amount
</td>

<td>

<input
    type="number"
    id="sub_amount"
    name="sub_amount"
    class="form-control"
    readonly
>

</td>

</tr>


<tr>

<td>
Discount TK
</td>

<td>

<input
    type="number"
    id="discount_tk"
    name="discount_tk"
    class="form-control"
    readonly
>

</td>

</tr>


<tr>

<td>
TAX TK
</td>

<td>

<input
    type="number"
    id="tax_tk"
    name="tax_tk"
    class="form-control"
    readonly
>

</td>

</tr>


<tr>

<td>
<h5>Grand Total</h5>
</td>

<td>

<input
    type="number"
    id="grand_total"
    name="grand_total"
    class="form-control"
    readonly
>

</td>

</tr>

</table>

</div>

</div>


<!-- =====================================================
     SAVE
===================================================== -->

<div class="form-group row">

<div class="col-md-12 text-right">

<button
    type="submit"
    name="save"
    class="btn btn-success btn-lg"
>

<?php

echo $edit_id
    ? "Update Invoice"
    : "Save Invoice";

?>

</button>

</div>

</div>


</form>

</div>

</div>

</div>


</div>
</div>


<script>

document.addEventListener("DOMContentLoaded", function(){

    const patientId =
        document.getElementById("patient_id");

    const invoiceType =
        document.getElementById("invoice_type");

    const admissionId =
        document.getElementById("admission_id");

    const itemTable =
        document.getElementById("itemTable");

    const addItemBtn =
        document.getElementById("addItem");


    /* =====================================================
       CHECK INVOICE TYPE
    ===================================================== */

    function checkInvoiceType(){

        const fields =
            document.querySelectorAll(".admitted-field");


        if(invoiceType.value === "ADMITTED"){

            fields.forEach(function(field){

                field.style.display = "";

            });

            admissionId.required = true;

            selectPatientAdmission();

        }else{

            fields.forEach(function(field){

                field.style.display = "none";

            });

            admissionId.required = false;

            admissionId.value = "";

        }

    }


    /* =====================================================
       AUTO SELECT ADMISSION
    ===================================================== */

    function selectPatientAdmission(){

        const selectedPatient =
            patientId.value;


        if(!selectedPatient){

            admissionId.value = "";

            return;
        }


        let found = false;


        admissionId
        .querySelectorAll("option")
        .forEach(function(option){

            const optionPatient =
                option.getAttribute("data-patient");


            if(
                !found &&
                optionPatient == selectedPatient
            ){

                admissionId.value =
                    option.value;

                found = true;

            }

        });

    }


    /* =====================================================
       PATIENT CHANGE
    ===================================================== */

    patientId.addEventListener(
        "change",
        function(){

            selectPatientAdmission();


            const selectedOption =
                this.options[this.selectedIndex];


            const patientDiscount =
                parseFloat(
                    selectedOption.getAttribute(
                        "data-discount"
                    )
                ) || 0;


            document
            .querySelectorAll(".item_discount")
            .forEach(function(input){

                input.value =
                    patientDiscount;

            });


            calculateTotal();

        }
    );


    /* =====================================================
       INVOICE TYPE CHANGE
    ===================================================== */

    invoiceType.addEventListener(
        "change",
        checkInvoiceType
    );


    checkInvoiceType();


    /* =====================================================
       TEST SELECT
    ===================================================== */

    itemTable.addEventListener(
        "change",
        function(e){

            if(
                e.target.classList.contains(
                    "item_name"
                )
            ){

                const select =
                    e.target;


                const option =
                    select.options[
                        select.selectedIndex
                    ];


                const price =
                    parseFloat(
                        option.getAttribute(
                            "data-price"
                        )
                    ) || 0;


                const row =
                    select.closest("tr");


                row.querySelector(
                    ".item_price"
                ).value = price;


                calculateTotal();

            }

        }
    );


    /* =====================================================
       CALCULATE TOTAL
    ===================================================== */

    function calculateTotal(){

        let subAmount = 0;

        let totalDiscount = 0;

        let totalTax = 0;


        const rows =
            itemTable.querySelectorAll(
                "tbody tr"
            );


        rows.forEach(function(row){

            const priceInput =
                row.querySelector(
                    ".item_price"
                );


            const discountInput =
                row.querySelector(
                    ".item_discount"
                );


            const taxInput =
                row.querySelector(
                    ".item_tax"
                );


            const totalInput =
                row.querySelector(
                    ".item_total"
                );


            const price =
                parseFloat(
                    priceInput.value
                ) || 0;


            const discountPercent =
                parseFloat(
                    discountInput.value
                ) || 0;


            const taxPercent =
                parseFloat(
                    taxInput.value
                ) || 0;


            const discountAmount =
                (price * discountPercent) / 100;


            const taxAmount =
                (
                    (price - discountAmount)
                    * taxPercent
                ) / 100;


            const total =
                (
                    price
                    - discountAmount
                    + taxAmount
                );


            totalInput.value =
                total.toFixed(2);


            subAmount += price;

            totalDiscount +=
                discountAmount;

            totalTax +=
                taxAmount;

        });


        const grandTotal =
            subAmount
            - totalDiscount
            + totalTax;


        document.getElementById(
            "sub_amount"
        ).value =
            subAmount.toFixed(2);


        document.getElementById(
            "discount_tk"
        ).value =
            totalDiscount.toFixed(2);


        document.getElementById(
            "tax_tk"
        ).value =
            totalTax.toFixed(2);


        document.getElementById(
            "grand_total"
        ).value =
            grandTotal.toFixed(2);

    }


    /* =====================================================
       ADD TEST
    ===================================================== */

    addItemBtn.addEventListener(
        "click",
        function(){

            const tbody =
                itemTable.querySelector(
                    "tbody"
                );


            const firstRow =
                tbody.querySelector(
                    "tr:first-child"
                );


            const newRow =
                firstRow.cloneNode(true);


            newRow
            .querySelectorAll("input")
            .forEach(function(input){

                input.value = "0";

            });


            newRow
            .querySelectorAll("select")
            .forEach(function(select){

                select.value = "";

            });


            const selectedOption =
                patientId.options[
                    patientId.selectedIndex
                ];


            const patientDiscount =
                parseFloat(
                    selectedOption.getAttribute(
                        "data-discount"
                    )
                ) || 0;


            const discountInput =
                newRow.querySelector(
                    ".item_discount"
                );


            if(discountInput){

                discountInput.value =
                    patientDiscount;

            }


            tbody.appendChild(newRow);


            calculateTotal();

        }
    );


    /* =====================================================
       REMOVE TEST
    ===================================================== */

    itemTable.addEventListener(
        "click",
        function(e){

            const removeButton =
                e.target.closest(
                    ".removeRow"
                );


            if(!removeButton){

                return;

            }


            const rows =
                itemTable.querySelectorAll(
                    "tbody tr"
                );


            if(rows.length > 1){

                removeButton
                    .closest("tr")
                    .remove();

            }


            calculateTotal();

        }
    );


    /* =====================================================
       INPUT CHANGE
    ===================================================== */

    itemTable.addEventListener(
        "input",
        function(e){

            if(
                e.target.classList.contains(
                    "calc"
                )
            ){

                calculateTotal();

            }

        }
    );


    itemTable.addEventListener(
        "change",
        function(e){

            if(
                e.target.classList.contains(
                    "calc"
                )
            ){

                calculateTotal();

            }

        }
    );


    calculateTotal();

});

</script>


<?php require_once "../component/footer.php"; ?>