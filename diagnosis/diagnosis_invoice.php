<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<?php
$edit_id = isset($_GET['edit_id']) ? $conn->real_escape_string($_GET['edit_id']) : '';
$edit_data = null;
$edit_items = [];

$lab_tests_result = $crud->common_select("lab_category","*");
$lab_tests = ($lab_tests_result['status'] && !empty($lab_tests_result['data'])) ? $lab_tests_result['data'] : [];

$patients_result = $crud->common_select("patients","*");
$patients = ($patients_result['status'] && !empty($patients_result['data'])) ? $patients_result['data'] : [];

if($edit_id != ''){

    $edit_data_result = $conn->query("SELECT * FROM invoices WHERE id='$edit_id' LIMIT 1");

    if($edit_data_result && $edit_data_result->num_rows > 0){
        $edit_data = $edit_data_result->fetch_object();
    }

    $edit_items_result = $conn->query("
        SELECT *
        FROM invoice_details
        WHERE invoice_id='$edit_id'
        AND deleted_at IS NULL
    ");

    if($edit_items_result){
        while($row = $edit_items_result->fetch_object()){
            $edit_items[] = $row;
        }
    }
}
?>

<div class="page-wrapper">
<div class="content">

<?php

/* ================= SAVE / UPDATE INVOICE ================= */

if(isset($_POST['save'])){

    $patient_id = $conn->real_escape_string($_POST['patient_id']);
    $invoice_type = $conn->real_escape_string($_POST['invoice_type']);

    $admission_id = !empty($_POST['admission_id'])
        ? $conn->real_escape_string($_POST['admission_id'])
        : NULL;

    $invoice_date = $conn->real_escape_string($_POST['invoice_date']);

    $status = 1;

    $invoice_id_post = $conn->real_escape_string($_POST['invoice_id']);

    $sub_amount = (float)($_POST['sub_amount'] ?? 0);
    $discount_tk = (float)($_POST['discount_tk'] ?? 0);
    $tax_tk = (float)($_POST['tax_tk'] ?? 0);
    $grand_total = (float)($_POST['grand_total'] ?? 0);


    /* ================= CHECK ADMISSION ================= */

    if($invoice_type == 'ADMITTED'){

        if(empty($admission_id)){
            echo "<script>
                alert('Please select an admission');
                history.back();
            </script>";
            exit;
        }

        $check_admission = $conn->query("
            SELECT id
            FROM patient_admissions
            WHERE id='$admission_id'
            AND patient_id='$patient_id'
            AND deleted_at IS NULL
            LIMIT 1
        ");

        if(!$check_admission || $check_admission->num_rows == 0){
            echo "<script>
                alert('Invalid admission for selected patient');
                history.back();
            </script>";
            exit;
        }

    }else{

        $admission_id = NULL;

    }


    /* ================= UPDATE ================= */

    if($invoice_id_post != ''){

        if($admission_id === NULL){

            $sql = "
                UPDATE invoices SET
                patient_id='$patient_id',
                invoice_type='$invoice_type',
                admission_id=NULL,
                sub_amount='$sub_amount',
                discount='$discount_tk',
                tax='$tax_tk',
                invoice_date='$invoice_date'
                WHERE id='$invoice_id_post'
            ";

        }else{

            $sql = "
                UPDATE invoices SET
                patient_id='$patient_id',
                invoice_type='$invoice_type',
                admission_id='$admission_id',
                sub_amount='$sub_amount',
                discount='$discount_tk',
                tax='$tax_tk',
                invoice_date='$invoice_date'
                WHERE id='$invoice_id_post'
            ";

        }

        if(!$conn->query($sql)){
            die("Invoice Update Error: ".$conn->error);
        }

        $last_id = $invoice_id_post;


        /* DELETE OLD INVOICE DETAILS */

        $conn->query("
            DELETE FROM invoice_details
            WHERE invoice_id='$last_id'
        ");


        /* DELETE OLD PATIENT LAB TESTS */

        if($invoice_type == 'ADMITTED' && !empty($admission_id)){

            $conn->query("
                DELETE FROM patient_lab_tests
                WHERE patient_id='$patient_id'
                AND admission_id='$admission_id'
            ");

        }

    }else{


        /* ================= INSERT INVOICE ================= */

        if($admission_id === NULL){

            $sql = "
                INSERT INTO invoices
                (
                    patient_id,
                    invoice_type,
                    admission_id,
                    sub_amount,
                    discount,
                    tax,
                    invoice_date,
                    status
                )
                VALUES
                (
                    '$patient_id',
                    '$invoice_type',
                    NULL,
                    '$sub_amount',
                    '$discount_tk',
                    '$tax_tk',
                    '$invoice_date',
                    '$status'
                )
            ";

        }else{

            $sql = "
                INSERT INTO invoices
                (
                    patient_id,
                    invoice_type,
                    admission_id,
                    sub_amount,
                    discount,
                    tax,
                    invoice_date,
                    status
                )
                VALUES
                (
                    '$patient_id',
                    '$invoice_type',
                    '$admission_id',
                    '$sub_amount',
                    '$discount_tk',
                    '$tax_tk',
                    '$invoice_date',
                    '$status'
                )
            ";

        }

        if(!$conn->query($sql)){
            die("Invoice Save Error: ".$conn->error);
        }

        $last_id = $conn->insert_id;
    }


    /* =========================================================
       SAVE TEST DETAILS + PATIENT LAB TEST
       ========================================================= */

    if(isset($_POST['item_name']) && !empty($_POST['item_name'])){

        for($i=0; $i<count($_POST['item_name']); $i++){

            if(empty($_POST['item_name'][$i])){
                continue;
            }


            /* TEST NAME */

            $name = $conn->real_escape_string($_POST['item_name'][$i]);


            /* TEST PRICE */

            $price = (float)($_POST['item_price'][$i] ?? 0);


            /* DISCOUNT */

            $dis = (float)($_POST['item_discount'][$i] ?? 0);


            /* TAX */

            $item_tax = (float)($_POST['item_tax'][$i] ?? 0);


            /* ================= SAVE INVOICE DETAILS ================= */

            $invoice_detail_sql = "
                INSERT INTO invoice_details
                (
                    invoice_id,
                    Name,
                    price,
                    discount,
                    tax
                )
                VALUES
                (
                    '$last_id',
                    '$name',
                    '$price',
                    '$dis',
                    '$item_tax'
                )
            ";

            if(!$conn->query($invoice_detail_sql)){
                die("Invoice Details Error: ".$conn->error);
            }


            /* ================= FIND TEST ID ================= */

$test_name_safe = $conn->real_escape_string($name);

$test_result = $conn->query("
    SELECT id
    FROM lab_category
    WHERE test_name='$test_name_safe'
    LIMIT 1
");

if($test_result && $test_result->num_rows > 0){

    $test_data = $test_result->fetch_object();

    $test_id = (int)$test_data->id;

    /* ================= ONLY ADMITTED PATIENT ================= */

    if(
        $invoice_type === 'ADMITTED' &&
        !empty($admission_id)
    ){

        $patient_lab_test_sql = "
            INSERT INTO patient_lab_tests
            (
                patient_id,
                admission_id,
                test_id,
                test_price,
                test_date,
                status,
                created_at,
                deleted_at,
                updated_at
            )
            VALUES
            (
                '$patient_id',
                '$admission_id',
                '$test_id',
                '$price',
                '$invoice_date',
                1,
                NOW(),
                NULL,
                NOW()
            )
        ";

        if(!$conn->query($patient_lab_test_sql)){
            die(
                'Patient Lab Test Save Error: '
                .$conn->error
            );
        }

    }

}


            /* =====================================================
               SAVE PATIENT LAB TEST

               ONLY FOR ADMITTED PATIENT
               ===================================================== */

            if($invoice_type == 'ADMITTED' && !empty($admission_id)){

                $patient_lab_test_sql = "
                    INSERT INTO patient_lab_tests
                    (
                        patient_id,
                        admission_id,
                        test_id,
                        test_price,
                        test_date,
                        status,
                        created_at,
                        deleted_at,
                        updated_at
                    )
                    VALUES
                    (
                        '$patient_id',
                        '$admission_id',
                        '$test_id',
                        '$price',
                        '$invoice_date',
                        1,
                        NOW(),
                        NULL,
                        NOW()
                    )
                ";

                if(!$conn->query($patient_lab_test_sql)){
                    die("Patient Lab Test Save Error: ".$conn->error);
                }

            }

        }

    }


    /* ================= SUCCESS ================= */

    echo "<script>
        alert('Invoice Saved Successfully');
        window.location='diagnosis_invoice.php?id=$last_id';
    </script>";

    exit;
}


/* ================= DELETE INVOICE ================= */

if(isset($_GET['delete_id'])){

    $del_id = $conn->real_escape_string($_GET['delete_id']);

    $conn->query("
        DELETE FROM invoice_details
        WHERE invoice_id='$del_id'
    ");

    $conn->query("
        DELETE FROM invoices
        WHERE id='$del_id'
    ");

    echo "<script>
        alert('Invoice Deleted');
        window.location='invoices.php';
    </script>";

    exit;
}

?>


<!-- ================= ADD / EDIT FORM ================= -->

<div class="row">
<div class="col-md-12">
<div class="card-box">

<h4 class="card-title">
<?php echo $edit_id ? 'Edit Diagnosis Invoice' : 'Add Diagnosis Invoice'; ?>
</h4>


<form method="post" action="" id="invoiceForm">

<input type="hidden"
       name="invoice_id"
       value="<?php echo $edit_id; ?>">


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
if($edit_data && $edit_data->invoice_type == 'OUTDOOR'){
    echo 'selected';
}
?>>
Outdoor / Diagnostic
</option>

<option value="ADMITTED"
<?php
if($edit_data && $edit_data->invoice_type == 'ADMITTED'){
    echo 'selected';
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
       value="<?php
       echo $edit_data
       ? $edit_data->invoice_date
       : date('Y-m-d');
       ?>"
       class="form-control"
       required>

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

<?php foreach($patients as $p){

$selected =
($edit_data && $edit_data->patient_id == $p->id)
? 'selected'
: '';

?>

<option
value="<?php echo $p->id; ?>"
data-discount="<?php echo $p->discount_percent; ?>"
<?php echo $selected; ?>>

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

$admissions = $conn->query("
    SELECT id, admission_no, patient_id
    FROM patient_admissions
    WHERE deleted_at IS NULL
    ORDER BY id DESC
");

if($admissions){

while($a = $admissions->fetch_object()){

$selected = '';

if(
$edit_data &&
$edit_data->admission_id == $a->id
){
$selected = 'selected';
}

?>

<option
value="<?php echo $a->id; ?>"
data-patient="<?php echo $a->patient_id; ?>"
<?php echo $selected; ?>>

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

<button
type="button"
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

<?php foreach($lab_tests as $t){

$selected =
($item->Name == $t->test_name)
? 'selected'
: '';

?>

<option
value="<?php echo htmlspecialchars($t->test_name); ?>"
data-price="<?php echo $t->price; ?>"
<?php echo $selected; ?>>

<?php echo htmlspecialchars($t->test_name); ?>

</option>

<?php } ?>

</select>

</td>

<td>

<input
type="number"
name="item_price[]"
value="<?php echo $item->price; ?>"
class="form-control calc item_price">

</td>

<td>

<input
type="number"
name="item_discount[]"
value="<?php echo $item->discount; ?>"
class="form-control calc item_discount">

</td>

<td>

<input
type="number"
name="item_tax[]"
value="<?php echo $item->tax; ?>"
class="form-control calc item_tax">

</td>

<td>

<input
type="number"
name="item_total[]"
class="form-control item_total"
readonly>

</td>

<td>

<button
type="button"
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

<input
type="number"
name="item_price[]"
class="form-control calc item_price"
value="0">

</td>

<td>

<input
type="number"
name="item_discount[]"
value="0"
class="form-control calc item_discount">

</td>

<td>

<input
type="number"
name="item_tax[]"
value="0"
class="form-control calc item_tax">

</td>

<td>

<input
type="number"
name="item_total[]"
class="form-control item_total"
readonly>

</td>

<td>

<button
type="button"
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

<input
type="number"
id="sub_amount"
name="sub_amount"
class="form-control"
readonly>

</td>

</tr>

<tr>

<td>Discount TK</td>

<td>

<input
type="number"
id="discount_tk"
name="discount_tk"
class="form-control"
readonly>

</td>

</tr>

<tr>

<td>TAX TK</td>

<td>

<input
type="number"
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

<input
type="number"
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

<button
type="submit"
name="save"
class="btn btn-success btn-lg">

<?php echo $edit_id ? 'Update Invoice' : 'Save Invoice'; ?>

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

document.addEventListener('DOMContentLoaded',function(){

const patientId=document.getElementById('patient_id');
const invoiceType=document.getElementById('invoice_type');
const admissionId=document.getElementById('admission_id');
const itemTable=document.getElementById('itemTable');
const addItemBtn=document.getElementById('addItem');


/* ================= INVOICE TYPE ================= */

function checkInvoiceType(){

    const fields=document.querySelectorAll('.admitted-field');

    if(invoiceType.value === 'ADMITTED'){

        fields.forEach(function(field){
            field.style.display='';
        });

        admissionId.required=true;

        selectPatientAdmission();

    }else{

        fields.forEach(function(field){
            field.style.display='none';
        });

        admissionId.required=false;
        admissionId.value='';

    }

}


/* ================= AUTO ADMISSION ================= */

function selectPatientAdmission(){

    const selectedPatient=patientId.value;

    if(!selectedPatient){
        admissionId.value='';
        return;
    }

    let found=false;

    admissionId.querySelectorAll('option').forEach(function(option){

        const optionPatient=option.getAttribute('data-patient');

        if(!found && optionPatient == selectedPatient){

            admissionId.value=option.value;
            found=true;

        }

    });

}


/* ================= PATIENT CHANGE ================= */

patientId.addEventListener('change',function(){

    selectPatientAdmission();

    const selectedOption=this.options[this.selectedIndex];

    const patientDiscount=
    parseFloat(
        selectedOption.getAttribute('data-discount')
    ) || 0;

    document.querySelectorAll('.item_discount')
    .forEach(function(input){

        input.value=patientDiscount;

    });

    calculateTotal();

});


invoiceType.addEventListener(
    'change',
    checkInvoiceType
);

checkInvoiceType();


/* ================= TEST SELECT ================= */

itemTable.addEventListener('change',function(e){

    if(e.target.classList.contains('item_name')){

        const select=e.target;
        const option=select.options[select.selectedIndex];

        const price=
        parseFloat(
            option.getAttribute('data-price')
        ) || 0;

        const row=select.closest('tr');

        row.querySelector('.item_price').value=price;

        calculateTotal();

    }

});


/* ================= CALCULATE ================= */

function calculateTotal(){

    let sub_amount=0;
    let total_discount_tk=0;
    let total_tax_tk=0;

    const rows=itemTable.querySelectorAll('tbody tr');

    rows.forEach(function(row){

        const priceInput=row.querySelector('.item_price');
        const discountInput=row.querySelector('.item_discount');
        const taxInput=row.querySelector('.item_tax');
        const totalInput=row.querySelector('.item_total');

        const price=
        parseFloat(priceInput?.value) || 0;

        const dis_per=
        parseFloat(discountInput?.value) || 0;

        const tax_per=
        parseFloat(taxInput?.value) || 0;

        const dis_tk=
        (price * dis_per) / 100;

        const tax_tk=
        ((price - dis_tk) * tax_per) / 100;

        const total=
        (price - dis_tk) + tax_tk;

        if(totalInput){
            totalInput.value=total.toFixed(2);
        }

        sub_amount += price;
        total_discount_tk += dis_tk;
        total_tax_tk += tax_tk;

    });


    const grand_total=
    sub_amount -
    total_discount_tk +
    total_tax_tk;


    document.getElementById('sub_amount').value=
    sub_amount.toFixed(2);

    document.getElementById('discount_tk').value=
    total_discount_tk.toFixed(2);

    document.getElementById('tax_tk').value=
    total_tax_tk.toFixed(2);

    document.getElementById('grand_total').value=
    grand_total.toFixed(2);

}


/* ================= ADD TEST ================= */

addItemBtn.addEventListener('click',function(){

    const tbody=itemTable.querySelector('tbody');

    const firstRow=tbody.querySelector('tr:first-child');

    const newRow=firstRow.cloneNode(true);


    newRow.querySelectorAll('input')
    .forEach(function(input){

        if(input.classList.contains('item_discount')){

            const selectedOption=
            patientId.options[patientId.selectedIndex];

            const discount=
            parseFloat(
                selectedOption?.getAttribute('data-discount')
            ) || 0;

            input.value=discount;

        }else{

            input.value='0';

        }

    });


    newRow.querySelectorAll('select')
    .forEach(function(select){

        select.value='';

    });


    tbody.appendChild(newRow);

    calculateTotal();

});


/* ================= REMOVE TEST ================= */

itemTable.addEventListener('click',function(e){

    const removeButton=e.target.closest('.removeRow');

    if(!removeButton){
        return;
    }

    const rows=itemTable.querySelectorAll('tbody tr');

    if(rows.length > 1){

        removeButton.closest('tr').remove();

    }

    calculateTotal();

});


/* ================= INPUT ================= */

itemTable.addEventListener('input',function(e){

    if(e.target.classList.contains('calc')){
        calculateTotal();
    }

});


itemTable.addEventListener('change',function(e){

    if(e.target.classList.contains('calc')){
        calculateTotal();
    }

});


calculateTotal();

});

</script>

<?php require_once "../component/footer.php"; ?>