<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
$edit_data = null;
$edit_items = [];

/* ================= LAB TESTS ================= */

$lab_tests_result = $crud->common_select("lab_category", "*");
$lab_tests = ($lab_tests_result["status"]) ? $lab_tests_result["data"] : [];

/* ================= PATIENTS ================= */

$patients_result = $crud->common_select("patients", "*");
$patients = ($patients_result["status"]) ? $patients_result["data"] : [];

/* ================= EDIT DATA ================= */

if($edit_id != ''){

    $edit_result = $crud->common_query("
        SELECT *
        FROM invoices
        WHERE id='$edit_id'
        LIMIT 1
    ");

    if($edit_result["status"] && !empty($edit_result["data"])){
        $edit_data = $edit_result["data"][0];
    }

    $edit_items_result = $crud->common_query("
        SELECT *
        FROM invoice_details
        WHERE invoice_id='$edit_id'
        AND deleted_at IS NULL
    ");

    if($edit_items_result["status"]){
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

if(isset($_POST["save"])){

    $patient_id = $_POST["patient_id"];
    $invoice_type = $_POST["invoice_type"];
    $invoice_date = $_POST["invoice_date"];

    $admission_id = !empty($_POST["admission_id"])
        ? $_POST["admission_id"]
        : null;

    $invoice_id_post = $_POST["invoice_id"] ?? '';

    $sub_amount = (float)($_POST["sub_amount"] ?? 0);
    $discount_tk = (float)($_POST["discount_tk"] ?? 0);
    $tax_tk = (float)($_POST["tax_tk"] ?? 0);

    /* ================= CHECK ADMISSION ================= */

    if($invoice_type == "ADMITTED"){

        if(empty($admission_id)){
            echo "<script>
                alert('Please select an admission');
                history.back();
            </script>";
            exit;
        }

        $check_admission = $crud->common_query("
            SELECT id
            FROM patient_admissions
            WHERE id='$admission_id'
            AND patient_id='$patient_id'
            AND deleted_at IS NULL
            LIMIT 1
        ");

        if(!$check_admission["status"]){
            echo "<script>
                alert('Invalid admission for selected patient');
                history.back();
            </script>";
            exit;
        }

    }else{

        $admission_id = null;
    }

    /* =========================================================
       UPDATE
       ========================================================= */

    if($invoice_id_post != ''){

        $update_data = [
            "patient_id" => $patient_id,
            "invoice_type" => $invoice_type,
            "sub_amount" => $sub_amount,
            "discount" => $discount_tk,
            "tax" => $tax_tk,
            "invoice_date" => $invoice_date
        ];

        if($admission_id === null){
            $update_data["admission_id"] = null;
        }else{
            $update_data["admission_id"] = $admission_id;
        }

        $update_result = $crud->common_update(
            "invoices",
            $update_data,
            ["id" => $invoice_id_post]
        );

        if(!$update_result["status"]){
            die("Invoice Update Error: ".$update_result["message"]);
        }

        $last_id = $invoice_id_post;

        /* DELETE OLD DETAILS */

        $crud->common_delete(
            "invoice_details",
            ["invoice_id" => $last_id]
        );

        /* DELETE OLD PATIENT LAB TESTS */

        if($invoice_type == "ADMITTED" && !empty($admission_id)){

            $crud->common_delete(
                "patient_lab_tests",
                [
                    "patient_id" => $patient_id,
                    "admission_id" => $admission_id
                ]
            );
        }

    }else{

        /* =====================================================
           INSERT
           ===================================================== */

        $insert_data = [
            "patient_id" => $patient_id,
            "invoice_type" => $invoice_type,
            "sub_amount" => $sub_amount,
            "discount" => $discount_tk,
            "tax" => $tax_tk,
            "invoice_date" => $invoice_date,
            "status" => 1
        ];

        if($admission_id !== null){
            $insert_data["admission_id"] = $admission_id;
        }else{
            $insert_data["admission_id"] = null;
        }

        $insert_result = $crud->common_insert(
            "invoices",
            $insert_data
        );

        if(!$insert_result["status"]){
            die("Invoice Save Error: ".$insert_result["message"]);
        }

        $last_id = $insert_result["data"];
    }

    /* =========================================================
       SAVE INVOICE DETAILS
       ========================================================= */

    if(isset($_POST["item_name"]) && !empty($_POST["item_name"])){

        for($i = 0; $i < count($_POST["item_name"]); $i++){

            if(empty($_POST["item_name"][$i])){
                continue;
            }

            $name = $_POST["item_name"][$i];

            $price = (float)($_POST["item_price"][$i] ?? 0);

            $dis = (float)($_POST["item_discount"][$i] ?? 0);

            $item_tax = (float)($_POST["item_tax"][$i] ?? 0);

            /* ================= INVOICE DETAILS ================= */

            $detail_data = [
                "invoice_id" => $last_id,
                "Name" => $name,
                "price" => $price,
                "discount" => $dis,
                "tax" => $item_tax
            ];

            $detail_result = $crud->common_insert(
                "invoice_details",
                $detail_data
            );

            if(!$detail_result["status"]){
                die("Invoice Details Error: ".$detail_result["message"]);
            }

            /* =====================================================
               FIND TEST ID
               ===================================================== */

            $test_name = addslashes($name);

            $test_result = $crud->common_query("
                SELECT id
                FROM lab_category
                WHERE test_name='$test_name'
                LIMIT 1
            ");

            if($test_result["status"] && !empty($test_result["data"])){

                $test_id = $test_result["data"][0]->id;

                /* =================================================
                   PATIENT LAB TEST - INSERT 1
                   ================================================= */

                if(
                    $invoice_type == "ADMITTED" &&
                    !empty($admission_id)
                ){

                    $lab_data_1 = [
                        "patient_id" => $patient_id,
                        "admission_id" => $admission_id,
                        "test_id" => $test_id,
                        "test_price" => $price,
                        "test_date" => $invoice_date,
                        "status" => 1,
                        "created_at" => date("Y-m-d H:i:s"),
                        "deleted_at" => null,
                        "updated_at" => date("Y-m-d H:i:s")
                    ];

                    $lab_result_1 = $crud->common_insert(
                        "patient_lab_tests",
                        $lab_data_1
                    );

                    if(!$lab_result_1["status"]){
                        die(
                            "Patient Lab Test Save Error: ".
                            $lab_result_1["message"]
                        );
                    }

                    /* =================================================
                       PATIENT LAB TEST - INSERT 2
                       ================================================= */

                    $lab_data_2 = [
                        "patient_id" => $patient_id,
                        "admission_id" => $admission_id,
                        "test_id" => $test_id,
                        "test_price" => $price,
                        "test_date" => $invoice_date,
                        "status" => 1,
                        "created_at" => date("Y-m-d H:i:s"),
                        "deleted_at" => null,
                        "updated_at" => date("Y-m-d H:i:s")
                    ];

                    $lab_result_2 = $crud->common_insert(
                        "patient_lab_tests",
                        $lab_data_2
                    );

                    if(!$lab_result_2["status"]){
                        die(
                            "Patient Lab Test Save Error: ".
                            $lab_result_2["message"]
                        );
                    }
                }
            }
        }
    }

    /* ================= SUCCESS ================= */

    echo "<script>
        alert('Diagnosis Invoice Saved Successfully');
        window.location='diagnosis_invoice.php?id=$last_id';
    </script>";

    exit;
}


/* =========================================================
   DELETE INVOICE
   ========================================================= */

if(isset($_GET["delete_id"])){

    $delete_id = $_GET["delete_id"];

    $crud->common_delete(
        "invoice_details",
        ["invoice_id" => $delete_id]
    );

    $delete_result = $crud->common_delete(
        "invoices",
        ["id" => $delete_id]
    );

    echo "<script>
        alert('Invoice Deleted Successfully');
        window.location='invoices.php';
    </script>";

    exit;
}

?>

<!-- =========================================================
     FORM
     ========================================================= -->

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

<input type="hidden"
       name="invoice_id"
       value="<?php echo htmlspecialchars($edit_id); ?>">

<!-- ================= INVOICE TYPE ================= -->

<div class="form-group row">

<label class="col-form-label col-md-2">
Invoice Type *
</label>

<div class="col-md-4">

<select name="invoice_type"
        id="invoice_type"
        class="form-control"
        required>

<option value="OUTDOOR"
<?php
if(
    !$edit_data ||
    $edit_data->invoice_type == "OUTDOOR"
){
    echo "selected";
}
?>>
Outdoor / Diagnostic
</option>

<option value="ADMITTED"
<?php
if(
    $edit_data &&
    $edit_data->invoice_type == "ADMITTED"
){
    echo "selected";
}
?>>
Admitted Patient
</option>

</select>

</div>

<label class="col-form-label col-md-2">
Invoice Date *
</label>

<div class="col-md-4">

<input type="date"
       name="invoice_date"
       class="form-control"
       required
       value="<?php
       echo $edit_data
           ? $edit_data->invoice_date
           : date("Y-m-d");
       ?>">

</div>

</div>

<!-- ================= PATIENT ================= -->

<div class="form-group row">

<label class="col-form-label col-md-2">
Patient *
</label>

<div class="col-md-4">

<select name="patient_id"
        id="patient_id"
        class="form-control"
        required>

<option value="">
-- Select Patient --
</option>

<?php foreach($patients as $p){ ?>

<option value="<?php echo $p->id; ?>"
data-discount="<?php echo $p->discount_percent ?? 0; ?>"
<?php
if(
    $edit_data &&
    $edit_data->patient_id == $p->id
){
    echo "selected";
}
?>>

<?php echo htmlspecialchars($p->name); ?>

</option>

<?php } ?>

</select>

</div>

<!-- ================= ADMISSION ================= -->

<div class="col-md-2 admitted-field">

<label class="col-form-label">
Admission
</label>

</div>

<div class="col-md-4 admitted-field">

<select name="admission_id"
        id="admission_id"
        class="form-control">

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

if(
    $admission_result["status"] &&
    !empty($admission_result["data"])
){

    foreach($admission_result["data"] as $a){

?>

<option value="<?php echo $a->id; ?>"
data-patient="<?php echo $a->patient_id; ?>"
<?php
if(
    $edit_data &&
    $edit_data->admission_id == $a->id
){
    echo "selected";
}
?>>

<?php echo htmlspecialchars($a->admission_no); ?>

</option>

<?php
    }
}
?>

</select>

<small class="text-muted">
Admission will be selected automatically.
</small>

</div>

</div>

<!-- ================= TESTS ================= -->

<div class="card-box">

<h4 class="text-blue h4">
Lab / Diagnostic Tests
</h4>

<button type="button"
        id="addItem"
        class="btn btn-primary mb-3">
+ Add Test
</button>

<div class="table-responsive">

<table class="table table-bordered"
       id="itemTable">

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

<?php if(!empty($edit_items)){ ?>

<?php foreach($edit_items as $item){ ?>

<tr>

<td>

<select name="item_name[]"
        class="form-control calc item_name">

<option value="">
-- Select Test --
</option>

<?php foreach($lab_tests as $t){ ?>

<option
value="<?php echo htmlspecialchars($t->test_name); ?>"
data-price="<?php echo $t->price; ?>"
<?php
if($item->Name == $t->test_name){
    echo "selected";
}
?>>

<?php echo htmlspecialchars($t->test_name); ?>

</option>

<?php } ?>

</select>

</td>

<td>

<input type="number"
       name="item_price[]"
       value="<?php echo $item->price; ?>"
       class="form-control calc item_price">

</td>

<td>

<input type="number"
       name="item_discount[]"
       value="<?php echo $item->discount; ?>"
       class="form-control calc item_discount">

</td>

<td>

<input type="number"
       name="item_tax[]"
       value="<?php echo $item->tax; ?>"
       class="form-control calc item_tax">

</td>

<td>

<input type="number"
       name="item_total[]"
       class="form-control item_total"
       readonly>

</td>

<td>

<button type="button"
        class="btn btn-danger btn-sm removeRow">
X
</button>

</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td>

<select name="item_name[]"
        class="form-control calc item_name">

<option value="">
-- Select Test --
</option>

<?php foreach($lab_tests as $t){ ?>

<option
value="<?php echo htmlspecialchars($t->test_name); ?>"
data-price="<?php echo $t->price; ?>">

<?php echo htmlspecialchars($t->test_name); ?>

</option>

<?php } ?>

</select>

</td>

<td>

<input type="number"
       name="item_price[]"
       value="0"
       class="form-control calc item_price">

</td>

<td>

<input type="number"
       name="item_discount[]"
       value="0"
       class="form-control calc item_discount">

</td>

<td>

<input type="number"
       name="item_tax[]"
       value="0"
       class="form-control calc item_tax">

</td>

<td>

<input type="number"
       name="item_total[]"
       value="0"
       class="form-control item_total"
       readonly>

</td>

<td>

<button type="button"
        class="btn btn-danger btn-sm removeRow">
X
</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<!-- ================= TOTAL ================= -->

<div class="row">

<div class="col-md-6 offset-md-6">

<table class="table table-bordered">

<tr>

<td>Sub Amount</td>

<td>

<input type="number"
       id="sub_amount"
       name="sub_amount"
       class="form-control"
       readonly>

</td>

</tr>

<tr>

<td>Discount TK</td>

<td>

<input type="number"
       id="discount_tk"
       name="discount_tk"
       class="form-control"
       readonly>

</td>

</tr>

<tr>

<td>Tax TK</td>

<td>

<input type="number"
       id="tax_tk"
       name="tax_tk"
       class="form-control"
       readonly>

</td>

</tr>

<tr>

<td>
<h5>Grand Total</h5>
</td>

<td>

<input type="number"
       id="grand_total"
       name="grand_total"
       class="form-control"
       readonly>

</td>

</tr>

</table>

</div>

</div>

<!-- ================= SAVE ================= -->

<div class="form-group row">

<div class="col-md-12 text-right">

<button type="submit"
        name="save"
        class="btn btn-success btn-lg">

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

const patientId = document.getElementById("patient_id");
const invoiceType = document.getElementById("invoice_type");
const admissionId = document.getElementById("admission_id");
const itemTable = document.getElementById("itemTable");
const addItemBtn = document.getElementById("addItem");

/* ================= INVOICE TYPE ================= */

function checkInvoiceType(){

    const fields =
        document.querySelectorAll(".admitted-field");

    if(invoiceType.value == "ADMITTED"){

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

/* ================= AUTO ADMISSION ================= */

function selectPatientAdmission(){

    const selectedPatient = patientId.value;

    if(!selectedPatient){
        admissionId.value = "";
        return;
    }

    let found = false;

    admissionId.querySelectorAll("option")
    .forEach(function(option){

        const optionPatient =
            option.getAttribute("data-patient");

        if(
            !found &&
            optionPatient == selectedPatient
        ){

            admissionId.value = option.value;
            found = true;
        }
    });
}

/* ================= PATIENT CHANGE ================= */

patientId.addEventListener("change", function(){

    selectPatientAdmission();

    const selectedOption =
        this.options[this.selectedIndex];

    const patientDiscount =
        parseFloat(
            selectedOption.getAttribute("data-discount")
        ) || 0;

    document.querySelectorAll(".item_discount")
    .forEach(function(input){

        input.value = patientDiscount;

    });

    calculateTotal();
});

/* ================= TYPE CHANGE ================= */

invoiceType.addEventListener(
    "change",
    checkInvoiceType
);

checkInvoiceType();

/* ================= TEST CHANGE ================= */

itemTable.addEventListener("change", function(e){

    if(
        e.target.classList.contains("item_name")
    ){

        const select = e.target;

        const option =
            select.options[select.selectedIndex];

        const price =
            parseFloat(
                option.getAttribute("data-price")
            ) || 0;

        const row =
            select.closest("tr");

        row.querySelector(".item_price").value =
            price;

        calculateTotal();
    }

});

/* ================= CALCULATE ================= */

function calculateTotal(){

    let sub_amount = 0;
    let total_discount_tk = 0;
    let total_tax_tk = 0;

    const rows =
        itemTable.querySelectorAll("tbody tr");

    rows.forEach(function(row){

        const priceInput =
            row.querySelector(".item_price");

        const discountInput =
            row.querySelector(".item_discount");

        const taxInput =
            row.querySelector(".item_tax");

        const totalInput =
            row.querySelector(".item_total");

        const price =
            parseFloat(priceInput.value) || 0;

        const discountPercent =
            parseFloat(discountInput.value) || 0;

        const taxPercent =
            parseFloat(taxInput.value) || 0;

        const discountTk =
            (price * discountPercent) / 100;

        const taxTk =
            ((price - discountTk) * taxPercent) / 100;

        const total =
            (price - discountTk) + taxTk;

        totalInput.value =
            total.toFixed(2);

        sub_amount += price;
        total_discount_tk += discountTk;
        total_tax_tk += taxTk;

    });

    const grand_total =
        sub_amount -
        total_discount_tk +
        total_tax_tk;

    document.getElementById("sub_amount").value =
        sub_amount.toFixed(2);

    document.getElementById("discount_tk").value =
        total_discount_tk.toFixed(2);

    document.getElementById("tax_tk").value =
        total_tax_tk.toFixed(2);

    document.getElementById("grand_total").value =
        grand_total.toFixed(2);
}

/* ================= ADD TEST ================= */

addItemBtn.addEventListener("click", function(){

    const tbody =
        itemTable.querySelector("tbody");

    const firstRow =
        tbody.querySelector("tr");

    const newRow =
        firstRow.cloneNode(true);

    newRow.querySelectorAll("input")
    .forEach(function(input){

        if(
            input.classList.contains(
                "item_discount"
            )
        ){

            const selectedOption =
                patientId.options[
                    patientId.selectedIndex
                ];

            const discount =
                parseFloat(
                    selectedOption?.getAttribute(
                        "data-discount"
                    )
                ) || 0;

            input.value = discount;

        }else{

            input.value = "0";
        }
    });

    newRow.querySelectorAll("select")
    .forEach(function(select){

        select.value = "";
    });

    tbody.appendChild(newRow);

    calculateTotal();
});

/* ================= REMOVE TEST ================= */

itemTable.addEventListener("click", function(e){

    const removeButton =
        e.target.closest(".removeRow");

    if(!removeButton){
        return;
    }

    const rows =
        itemTable.querySelectorAll("tbody tr");

    if(rows.length > 1){

        removeButton.closest("tr").remove();
    }

    calculateTotal();
});

/* ================= INPUT ================= */

itemTable.addEventListener("input", function(e){

    if(
        e.target.classList.contains("calc")
    ){

        calculateTotal();
    }
});

calculateTotal();

});

</script>

<?php require_once "../component/footer.php"; ?>