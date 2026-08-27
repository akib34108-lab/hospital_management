<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>

<?php
$edit_id = isset($_GET['edit_id']) ? $crud->conn->real_escape_string($_GET['edit_id']) : '';
$edit_data = null;
$edit_items = [];

$lab_tests = $crud->common_select("lab_category","*")['data'];
$patients = $crud->common_select("patients","*")['data'];

if($edit_id != ''){
    $edit_result = $crud->conn->query("SELECT * FROM invoices WHERE id='$edit_id'");
    if($edit_result && $edit_result->num_rows > 0){
        $edit_data = $edit_result->fetch_object();
    }

    $edit_items_result = $crud->conn->query("SELECT * FROM invoice_details WHERE invoice_id='$edit_id' AND deleted_at IS NULL");

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

    $patient_id = $crud->conn->real_escape_string($_POST['patient_id']);
    $invoice_type = $crud->conn->real_escape_string($_POST['invoice_type']);
    $invoice_date = $crud->conn->real_escape_string($_POST['invoice_date']);

    $admission_id = !empty($_POST['admission_id'])
        ? $crud->conn->real_escape_string($_POST['admission_id'])
        : '';

    $status = 1;

    $invoice_id_post = isset($_POST['invoice_id'])
        ? $crud->conn->real_escape_string($_POST['invoice_id'])
        : '';

    $sub_amount = isset($_POST['sub_amount'])
        ? (float)$_POST['sub_amount']
        : 0;

    $discount_tk = isset($_POST['discount_tk'])
        ? (float)$_POST['discount_tk']
        : 0;

    $tax_tk = isset($_POST['tax_tk'])
        ? (float)$_POST['tax_tk']
        : 0;

    /* ================= UPDATE ================= */

    if($invoice_id_post != ''){

        if($admission_id == ''){

            $sql = "UPDATE invoices SET
                    patient_id='$patient_id',
                    invoice_type='$invoice_type',
                    admission_id=NULL,
                    sub_amount='$sub_amount',
                    discount='$discount_tk',
                    tax='$tax_tk',
                    invoice_date='$invoice_date'
                    WHERE id='$invoice_id_post'";

        }else{

            $sql = "UPDATE invoices SET
                    patient_id='$patient_id',
                    invoice_type='$invoice_type',
                    admission_id='$admission_id',
                    sub_amount='$sub_amount',
                    discount='$discount_tk',
                    tax='$tax_tk',
                    invoice_date='$invoice_date'
                    WHERE id='$invoice_id_post'";
        }

        if(!$crud->conn->query($sql)){
            die("Invoice Update Error: ".$crud->conn->error);
        }

        $last_id = $invoice_id_post;

        $crud->conn->query("DELETE FROM invoice_details WHERE invoice_id='$last_id'");

    }else{

        if($admission_id == ''){

            $sql = "INSERT INTO invoices
                    (patient_id, invoice_type, admission_id, sub_amount, discount, tax, invoice_date, status)
                    VALUES
                    ('$patient_id','$invoice_type',NULL,'$sub_amount','$discount_tk','$tax_tk','$invoice_date','$status')";

        }else{

            $sql = "INSERT INTO invoices
                    (patient_id, invoice_type, admission_id, sub_amount, discount, tax, invoice_date, status)
                    VALUES
                    ('$patient_id','$invoice_type','$admission_id','$sub_amount','$discount_tk','$tax_tk','$invoice_date','$status')";
        }

        if(!$crud->conn->query($sql)){
            die("Invoice Insert Error: ".$crud->conn->error);
        }

        $last_id = $crud->conn->insert_id;
    }


    /* ================= MANUAL TEST CHECK ================= */

    $manual_test_added = false;

    if(isset($_POST['item_name']) && is_array($_POST['item_name'])){

        for($i=0; $i<count($_POST['item_name']); $i++){

            if(!empty($_POST['item_name'][$i])){

                $manual_test_added = true;

                $name = $crud->conn->real_escape_string($_POST['item_name'][$i]);

                $price = isset($_POST['item_price'][$i])
                    ? (float)$_POST['item_price'][$i]
                    : 0;

                $dis = isset($_POST['item_discount'][$i])
                    ? (float)$_POST['item_discount'][$i]
                    : 0;

                $item_tax = isset($_POST['item_tax'][$i])
                    ? (float)$_POST['item_tax'][$i]
                    : 0;

                $crud->conn->query("
                    INSERT INTO invoice_details
                    (invoice_id, Name, price, discount, tax)
                    VALUES
                    ('$last_id','$name','$price','$dis','$item_tax')
                ");
            }
        }
    }


    /* ================= DIAGNOSIS TEST AUTOMATIC ================= */

    if(
        $invoice_type == 'ADMITTED' &&
        $admission_id != '' &&
        $manual_test_added == false
    ){

        $test_sql = "
            SELECT
                plt.test_price,
                lc.test_name
            FROM patient_lab_test plt
            LEFT JOIN lab_category lc
                ON lc.id = plt.test_id
            WHERE plt.admission_id='$admission_id'
            AND plt.deleted_at IS NULL
            AND plt.status=1
            AND lc.deleted_at IS NULL
        ";

        $test_result = $crud->conn->query($test_sql);

        if($test_result){

            while($test = $test_result->fetch_object()){

                if(empty($test->test_name)){
                    continue;
                }

                $name = $crud->conn->real_escape_string($test->test_name);
                $price = (float)$test->test_price;

                $crud->conn->query("
                    INSERT INTO invoice_details
                    (invoice_id, Name, price, discount, tax)
                    VALUES
                    ('$last_id','$name','$price','0','0')
                ");
            }
        }
    }


    $msg = $invoice_id_post != ''
        ? 'Invoice Updated Successfully'
        : 'Invoice Saved Successfully';

    echo "<script>
        alert('$msg');
        window.location='invoice_list.php';
    </script>";

    exit;
}


/* ================= DELETE INVOICE ================= */

if(isset($_GET['delete_id'])){

    $del_id = $crud->conn->real_escape_string($_GET['delete_id']);

    $crud->conn->query("DELETE FROM invoice_details WHERE invoice_id='$del_id'");
    $crud->conn->query("DELETE FROM invoices WHERE id='$del_id'");

    echo "<script>
        alert('Invoice Deleted');
        window.location='invoice_list.php';
    </script>";

    exit;
}


/* ================= VIEW INVOICE ================= */

if(isset($_GET['id']) && !empty($_GET['id'])){

    $id = $crud->conn->real_escape_string($_GET['id']);

    $inv_result = $crud->conn->query("
        SELECT i.*,
               p.name AS patient_name,
               p.address,
               p.phone
        FROM invoices i
        LEFT JOIN patients p ON i.patient_id=p.id
        WHERE i.id='$id'
    ");

    if(!$inv_result || $inv_result->num_rows == 0){
        echo "<div class='alert alert-danger'>Invoice not found.</div>";
        require_once "../component/footer.php";
        exit;
    }

    $inv = $inv_result->fetch_object();

    $items = $crud->conn->query("
        SELECT *
        FROM invoice_details
        WHERE invoice_id='$id'
        AND deleted_at IS NULL
    ");

    $paid_res = $crud->conn->query("
        SELECT SUM(amount) AS total_paid
        FROM payments
        WHERE invoice_id='$id'
    ");

    $total_paid = 0;

    if($paid_res){
        $paid_row = $paid_res->fetch_object();
        $total_paid = $paid_row->total_paid ? $paid_row->total_paid : 0;
    }

    $payable_amount =
        ((float)$inv->sub_amount - (float)$inv->discount)
        + (float)$inv->tax;

    $due = $payable_amount - $total_paid;
?>

<div class="row no-print">
<div class="col-sm-12 text-right m-b-20">

<button onclick="window.print()" class="btn btn-primary">
<i class="fa fa-print"></i> Print
</button>

<a href="invoice_list.php" class="btn btn-secondary">
<i class="fa fa-arrow-left"></i> Back to List
</a>

</div>
</div>

<div class="row" id="print_area">
<div class="col-md-12">

<div class="card-box">

<div class="row">

<div class="col-md-6">

<h3>SHIFA HOSPITAL</h3>

<p>
Chittagong, Bangladesh<br>
Phone: 01xxxxxxxxx
</p>

</div>

<div class="col-md-6 text-right">

<h4 class="text-blue">

<?php
if($inv->invoice_type == 'ADMITTED'){
    echo "ADMITTED PATIENT INVOICE";
}else{
    echo "DIAGNOSTIC INVOICE";
}
?>

</h4>

<p>

<b>Invoice No:</b>
INV-<?php echo str_pad($inv->id,4,'0',STR_PAD_LEFT); ?>

<br>

<b>Date:</b>
<?php echo date('d-m-Y',strtotime($inv->invoice_date)); ?>

</p>

</div>

</div>

<hr>

<div class="row">

<div class="col-md-6">

<h5>Patient Information</h5>

<p>

<b>Name:</b>
<?php echo htmlspecialchars($inv->patient_name ?? ''); ?>

<br>

<b>Phone:</b>
<?php echo htmlspecialchars($inv->phone ?? ''); ?>

<br>

<b>Address:</b>
<?php echo htmlspecialchars($inv->address ?? ''); ?>

</p>

</div>

<div class="col-md-6 text-right">

<p>

<b>Invoice Type:</b>

<?php
if($inv->invoice_type == 'ADMITTED'){
    echo "Admitted Patient";
}else{
    echo "Outdoor / Diagnostic";
}
?>

<?php if($inv->invoice_type == 'ADMITTED' && !empty($inv->admission_id)){ ?>

<br>

<b>Admission ID:</b>
<?php echo $inv->admission_id; ?>

<?php } ?>

</p>

</div>

</div>

<div class="table-responsive m-t-20">

<table class="table table-bordered">

<thead class="bg-light">

<tr>
<th>#</th>
<th>Test Name</th>
<th>Price</th>
<th>Disc %</th>
<th>Tax %</th>
<th class="text-right">Total</th>
</tr>

</thead>

<tbody>

<?php

$sl = 1;

if($items){

while($row = $items->fetch_object()){

$dis_tk =
((float)$row->price * (float)$row->discount) / 100;

$tax_tk_item =
(((float)$row->price - $dis_tk) * (float)$row->tax) / 100;

$item_total =
((float)$row->price - $dis_tk) + $tax_tk_item;

?>

<tr>

<td><?php echo $sl++; ?></td>

<td>
<?php echo htmlspecialchars($row->Name); ?>
</td>

<td>
<?php echo number_format($row->price,2); ?>
</td>

<td>
<?php echo $row->discount; ?>%
</td>

<td>
<?php echo $row->tax; ?>%
</td>

<td class="text-right">
<?php echo number_format($item_total,2); ?>
</td>

</tr>

<?php
}
}
?>

</tbody>

</table>

</div>

<div class="row">

<div class="col-md-6 offset-md-6">

<table class="table table-bordered">

<tr>

<td>Sub Amount:</td>

<td class="text-right">
<?php echo number_format($inv->sub_amount,2); ?> TK
</td>

</tr>

<tr>

<td>Discount:</td>

<td class="text-right">
<?php echo number_format($inv->discount,2); ?> TK
</td>

</tr>

<tr>

<td>TAX:</td>

<td class="text-right">
<?php echo number_format($inv->tax,2); ?> TK
</td>

</tr>

<tr style="background:#f5f5f5;">

<td>
<b>Payable Amount:</b>
</td>

<td class="text-right">

<b>
<?php echo number_format($payable_amount,2); ?> TK
</b>

</td>

</tr>

<tr>

<td>
<b>Total Paid:</b>
</td>

<td class="text-right text-success">

<b>
<?php echo number_format($total_paid,2); ?> TK
</b>

</td>

</tr>

<tr style="background:#ffecec;">

<td>
<b>Due Amount:</b>
</td>

<td class="text-right text-danger">

<b>
<?php echo number_format($due,2); ?> TK
</b>

</td>

</tr>

</table>

</div>

</div>

<div class="row" style="margin-top:50px;">

<div class="col-6">
____________________<br>
Customer Signature
</div>

<div class="col-6 text-right">
____________________<br>
Authorized Signature
</div>

</div>

</div>

</div>
</div>

<?php

}else{

?>

<!-- ================= ADD / EDIT FORM ================= -->

<div class="row">
<div class="col-md-12">
<div class="card-box">

<h4 class="card-title">

<?php echo $edit_id ? 'Edit Invoice' : 'Add Invoice'; ?>

</h4>

<form method="post" action="" id="invoiceForm">

<input
type="hidden"
name="invoice_id"
value="<?php echo htmlspecialchars($edit_id); ?>"
>


<!-- INVOICE TYPE -->

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
if($edit_data && $edit_data->invoice_type == 'OUTDOOR'){
    echo 'selected';
}
?>
>
Outdoor / Diagnostic
</option>

<option value="ADMITTED"
<?php
if($edit_data && $edit_data->invoice_type == 'ADMITTED'){
    echo 'selected';
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
value="<?php
echo $edit_data
? $edit_data->invoice_date
: date('Y-m-d');
?>"
class="form-control"
required
>

</div>

</div>


<!-- PATIENT -->

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

foreach($patients as $p){

$selected =
($edit_data && $edit_data->patient_id == $p->id)
? 'selected'
: '';

$patient_discount =
isset($p->discount_percent)
? $p->discount_percent
: 0;

?>

<option
value="<?php echo $p->id; ?>"
data-discount="<?php echo $patient_discount; ?>"
<?php echo $selected; ?>
>
<?php echo htmlspecialchars($p->name); ?>
</option>

<?php } ?>

</select>

</div>


<div class="col-md-2 admitted-field">

<label class="col-form-label">
Admission ID
</label>

</div>

<div class="col-md-4 admitted-field">

<input
type="number"
name="admission_id"
id="admission_id"
value="<?php echo $edit_data->admission_id ?? ''; ?>"
class="form-control"
placeholder="Enter Admission ID"
>

<small class="text-muted">
Admitted patient হলে Diagnosis page-এর Admission ID দিন।
</small>

</div>

</div>


<!-- ITEMS -->

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

if(!empty($edit_items)){

foreach($edit_items as $item){

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

foreach($lab_tests as $t){

$selected =
($item->Name == $t->test_name)
? 'selected'
: '';

$test_price =
isset($t->price)
? $t->price
: 0;

?>

<option
value="<?php echo htmlspecialchars($t->test_name); ?>"
data-price="<?php echo $test_price; ?>"
<?php echo $selected; ?>
>
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
class="form-control calc item_price"
step="0.01"
>

</td>

<td>

<input
type="number"
name="item_discount[]"
value="<?php echo $item->discount; ?>"
class="form-control calc item_discount"
step="0.01"
>

</td>

<td>

<input
type="number"
name="item_tax[]"
value="<?php echo $item->tax; ?>"
class="form-control calc item_tax"
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

}else{

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

foreach($lab_tests as $t){

$test_price =
isset($t->price)
? $t->price
: 0;

?>

<option
value="<?php echo htmlspecialchars($t->test_name); ?>"
data-price="<?php echo $test_price; ?>"
>
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
value="0"
step="0.01"
>

</td>

<td>

<input
type="number"
name="item_discount[]"
value="0"
class="form-control calc item_discount"
step="0.01"
>

</td>

<td>

<input
type="number"
name="item_tax[]"
value="0"
class="form-control calc item_tax"
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

<?php } ?>

</tbody>

</table>

</div>

</div>


<!-- TOTAL -->

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


<div class="form-group row">

<div class="col-md-12 text-right">

<button
type="submit"
name="save"
class="btn btn-success btn-lg"
>

<?php
echo $edit_id
? 'Update Invoice'
: 'Save Invoice';
?>

</button>

</div>

</div>

</form>

</div>
</div>
</div>

<?php } ?>

</div>
</div>


<script>

document.addEventListener('DOMContentLoaded',function(){

const patientId =
document.getElementById('patient_id');

const invoiceType =
document.getElementById('invoice_type');

const admissionId =
document.getElementById('admission_id');

const itemTable =
document.getElementById('itemTable');

const addItemBtn =
document.getElementById('addItem');


/* ================= INVOICE TYPE ================= */

function checkInvoiceType(){

const admittedFields =
document.querySelectorAll('.admitted-field');

if(invoiceType.value === 'ADMITTED'){

admittedFields.forEach(function(field){
field.style.display = '';
});

admissionId.required = true;

}else{

admittedFields.forEach(function(field){
field.style.display = 'none';
});

admissionId.required = false;
admissionId.value = '';

}

}

invoiceType.addEventListener('change',checkInvoiceType);

checkInvoiceType();


/* ================= PATIENT DISCOUNT ================= */

function getPatientDiscount(){

if(
!patientId ||
patientId.selectedIndex < 0
){
return 0;
}

const option =
patientId.options[patientId.selectedIndex];

return parseFloat(
option.getAttribute('data-discount')
) || 0;

}


patientId.addEventListener('change',function(){

const discount =
getPatientDiscount();

document.querySelectorAll('.item_discount')
.forEach(function(input){

input.value = discount;

});

calculateTotal();

});


/* ================= TEST SELECT ================= */

itemTable.addEventListener('change',function(e){

if(e.target.classList.contains('item_name')){

const select = e.target;

const option =
select.options[select.selectedIndex];

const price =
parseFloat(
option.getAttribute('data-price')
) || 0;

const row =
select.closest('tr');

const priceInput =
row.querySelector('.item_price');

priceInput.value = price;

calculateTotal();

}

});


/* ================= CALCULATE ================= */

function calculateTotal(){

let sub_amount = 0;
let total_discount_tk = 0;
let total_tax_tk = 0;

const rows =
itemTable.querySelectorAll('tbody tr');

rows.forEach(function(row){

const price =
parseFloat(
row.querySelector('.item_price')?.value
) || 0;

const discount =
parseFloat(
row.querySelector('.item_discount')?.value
) || 0;

const tax =
parseFloat(
row.querySelector('.item_tax')?.value
) || 0;

const discount_tk =
(price * discount) / 100;

const tax_tk =
((price - discount_tk) * tax) / 100;

const total =
(price - discount_tk) + tax_tk;

const totalInput =
row.querySelector('.item_total');

if(totalInput){
totalInput.value =
total.toFixed(2);
}

sub_amount += price;
total_discount_tk += discount_tk;
total_tax_tk += tax_tk;

});


const grand_total =
sub_amount -
total_discount_tk +
total_tax_tk;


document.getElementById('sub_amount').value =
sub_amount.toFixed(2);

document.getElementById('discount_tk').value =
total_discount_tk.toFixed(2);

document.getElementById('tax_tk').value =
total_tax_tk.toFixed(2);

document.getElementById('grand_total').value =
grand_total.toFixed(2);

}


/* ================= ADD TEST ================= */

addItemBtn.addEventListener('click',function(){

const tbody =
itemTable.querySelector('tbody');

const firstRow =
tbody.querySelector('tr:first-child');

const newRow =
firstRow.cloneNode(true);

newRow.querySelectorAll('input')
.forEach(function(input){

if(input.classList.contains('item_discount')){

input.value =
getPatientDiscount();

}else{

input.value = '0';

}

});


newRow.querySelectorAll('select')
.forEach(function(select){

select.value = '';

});


tbody.appendChild(newRow);

calculateTotal();

});


/* ================= REMOVE TEST ================= */

itemTable.addEventListener('click',function(e){

const removeButton =
e.target.closest('.removeRow');

if(!removeButton){
return;
}

const rows =
itemTable.querySelectorAll('tbody tr');

if(rows.length > 1){

removeButton.closest('tr').remove();

}else{

const row =
removeButton.closest('tr');

row.querySelector('.item_name').value = '';
row.querySelector('.item_price').value = '0';
row.querySelector('.item_discount').value = '0';
row.querySelector('.item_tax').value = '0';
row.querySelector('.item_total').value = '0';

}

calculateTotal();

});


/* ================= INPUT ================= */

itemTable.addEventListener('input',function(e){

if(e.target.classList.contains('calc')){
calculateTotal();
}

});


calculateTotal();

});

</script>


<style>

@media print{

body *{
visibility:hidden;
}

#print_area,
#print_area *{
visibility:visible;
}

#print_area{
position:absolute;
left:0;
top:0;
width:100%;
}

.no-print{
display:none !important;
}

.sidebar,
.header{
display:none !important;
}

}

</style>

<?php require_once "../component/footer.php"; ?>