<?php
require_once "../../component/header.php";
require_once "../../component/sidebar.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$discharge_id = (int)$_GET['id'];

$result = $crud->common_query("
    SELECT d.*,p.name AS patient_name,p.phone,p.gender,p.age,p.blood_group,
    pa.admission_no,pa.doctor_id,pa.admission_date,pa.admission_time,
    r.room_number,r.room_charge
    FROM discharges d
    LEFT JOIN patients p ON p.id=d.patient_id
    LEFT JOIN patient_admissions pa ON pa.id=d.admission_id
    LEFT JOIN rooms r ON r.id=pa.room_id
    WHERE d.discharge_id=$discharge_id
    LIMIT 1
");

if (!$result["status"] || empty($result["data"])) {
    header("Location: discharge.php");
    exit;
}

$data = $result["data"][0];

$admission_date = strtotime($data->admission_date);
$discharge_date = strtotime($data->discharge_date);

$days = ceil(($discharge_date - $admission_date) / 86400);

if ($days < 1) {
    $days = 1;
}

$room_charge = (float)$data->room_charge;
$bed_bill = $days * $room_charge;

$tests = $crud->common_query("
    SELECT plt.*,lc.test_name,lc.test_accessor
    FROM patient_lab_tests plt
    LEFT JOIN lab_category lc ON lc.id=plt.test_id
    WHERE plt.patient_id={$data->patient_id}
    AND plt.admission_id={$data->admission_id}
    AND plt.deleted_at IS NULL
    ORDER BY plt.id ASC
");

$test_bill = 0;

if ($tests["status"] && !empty($tests["data"])) {
    foreach ($tests["data"] as $test) {
        $test_bill += (float)$test->test_price;
    }
}

$doctor_fee = 0;
$medicine_bill = 0;
$service_bill = 0;
$other_bill = 0;
$discount = 0;

$existing = $crud->common_query("
    SELECT *
    FROM discharge_invoices
    WHERE discharge_id=$discharge_id
    LIMIT 1
");

if ($existing["status"] && !empty($existing["data"])) {
    $invoice = $existing["data"][0];
    $invoice_id = $invoice->invoice_id;
    $invoice_no = $invoice->invoice_no;
    $doctor_fee = (float)$invoice->doctor_fee;
    $medicine_bill = (float)$invoice->medicine_bill;
    $service_bill = (float)$invoice->service_bill;
    $other_bill = (float)$invoice->other_bill;
    $discount = (float)$invoice->discount;
}

if (isset($_POST['save_invoice'])) {

    $doctor_fee = (float)$_POST['doctor_fee'];
    $medicine_bill = (float)$_POST['medicine_bill'];
    $service_bill = (float)$_POST['service_bill'];
    $other_bill = (float)$_POST['other_bill'];
    $discount = (float)$_POST['discount'];

    $total_amount = $bed_bill + $test_bill + $doctor_fee + $medicine_bill + $service_bill + $other_bill - $discount;

    if ($total_amount < 0) {
        $total_amount = 0;
    }

    if (isset($invoice_id)) {

        $crud->common_update("discharge_invoices",[
            "bed_bill"=>$bed_bill,
            "doctor_fee"=>$doctor_fee,
            "test_bill"=>$test_bill,
            "medicine_bill"=>$medicine_bill,
            "service_bill"=>$service_bill,
            "other_bill"=>$other_bill,
            "discount"=>$discount,
            "total_amount"=>$total_amount,
            "due_amount"=>$total_amount
        ],["invoice_id"=>$invoice_id]);

    } else {

        $invoice_no = "DIS-" . date("YmdHis");

        $crud->common_insert("discharge_invoices",[
            "discharge_id"=>$discharge_id,
            "patient_id"=>$data->patient_id,
            "invoice_no"=>$invoice_no,
            "bed_bill"=>$bed_bill,
            "doctor_fee"=>$doctor_fee,
            "test_bill"=>$test_bill,
            "medicine_bill"=>$medicine_bill,
            "service_bill"=>$service_bill,
            "other_bill"=>$other_bill,
            "discount"=>$discount,
            "total_amount"=>$total_amount,
            "paid_amount"=>0,
            "due_amount"=>$total_amount,
            "payment_status"=>"Due",
            "payment_method"=>"",
            "created_at"=>date("Y-m-d H:i:s")
        ]);
    }

    echo "<script>alert('Invoice saved successfully');window.location.href='discharge_invoice.php?id=$discharge_id';</script>";
    exit;
}

if (!isset($total_amount)) {
    $total_amount = $bed_bill + $test_bill + $doctor_fee + $medicine_bill + $service_bill + $other_bill - $discount;

    if ($total_amount < 0) {
        $total_amount = 0;
    }
}

$total_paid = 0;

if (isset($invoice_id)) {

    $payments = $crud->common_query("
        SELECT *
        FROM payments
        WHERE invoice_id=$invoice_id
        ORDER BY id ASC
    ");

    if ($payments["status"] && !empty($payments["data"])) {
        foreach ($payments["data"] as $payment) {
            $total_paid += (float)$payment->amount;
        }
    }
}

$due_amount = $total_amount - $total_paid;

if ($due_amount < 0) {
    $due_amount = 0;
}

if ($total_paid == 0) {
    $payment_status = "Due";
} elseif ($due_amount == 0) {
    $payment_status = "Paid";
} else {
    $payment_status = "Partial";
}

if (isset($_POST['save_payment']) && isset($invoice_id)) {

    $payment_amount = (float)$_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];
    $payment_date = $_POST['payment_date'];

    if ($payment_amount > 0 && $payment_amount <= $due_amount) {

        $crud->common_insert("payments",[
            "invoice_id"=>$invoice_id,
            "amount"=>$payment_amount,
            "payment_method"=>$payment_method,
            "payment_date"=>$payment_date
        ]);

        $new_paid = $total_paid + $payment_amount;
        $new_due = $total_amount - $new_paid;

        if ($new_due < 0) {
            $new_due = 0;
        }

        if ($new_due == 0) {
            $new_status = "Paid";
        } else {
            $new_status = "Partial";
        }

        $crud->common_update("discharge_invoices",[
            "paid_amount"=>$new_paid,
            "due_amount"=>$new_due,
            "payment_status"=>$new_status,
            "payment_method"=>$payment_method
        ],["invoice_id"=>$invoice_id]);

        echo "<script>alert('Payment saved successfully');window.location.href='discharge_invoice.php?id=$discharge_id';</script>";
        exit;
    }
}


?>

<div class="page-wrapper">
<div class="content">

<div class="row">
<div class="col-sm-7 col-6">
<h4 class="page-title">Discharge Invoice</h4>
</div>

<div class="col-sm-5 col-6 text-right">
<button onclick="window.print()" class="btn btn-primary">
<i class="fa fa-print"></i> Print
</button>

<a href="discharge_view.php?id=<?php echo $discharge_id; ?>" class="btn btn-secondary">
<i class="fa fa-arrow-left"></i> Back
</a>
</div>
</div>

<div class="card">
<div class="card-body">

<div class="text-center mb-4">
<h2>SHIFA Hospital</h2>
<h4>Discharge Invoice</h4>
<?php if(isset($invoice_no)){ ?>
<p><strong>Invoice No:</strong> <?php echo $invoice_no; ?></p>
<?php } ?>
</div>

<div class="row mb-4">

<div class="col-md-6">
<h5>Patient Information</h5>
<p><strong>Patient Name:</strong> <?php echo $data->patient_name; ?></p>
<p><strong>Patient ID:</strong> <?php echo $data->patient_id; ?></p>
<p><strong>Phone:</strong> <?php echo $data->phone; ?></p>
<p><strong>Gender:</strong> <?php echo $data->gender; ?></p>
<p><strong>Age:</strong> <?php echo $data->age; ?></p>
<p><strong>Blood Group:</strong> <?php echo $data->blood_group; ?></p>
</div>

<div class="col-md-6">
<h5>Admission Information</h5>
<p><strong>Admission No:</strong> <?php echo $data->admission_no; ?></p>
<p><strong>Doctor ID:</strong> <?php echo $data->doctor_id; ?></p>
<p><strong>Room No:</strong> <?php echo $data->room_number; ?></p>
<p><strong>Admission Date:</strong> <?php echo $data->admission_date; ?></p>
<p><strong>Discharge Date:</strong> <?php echo $data->discharge_date; ?></p>
<p><strong>Total Stay:</strong> <?php echo $days; ?> Day(s)</p>
</div>

</div>

<form method="POST">

<h5>Bill Details</h5>

<table class="table table-bordered">

<tr>
<th>Bed / Room Bill</th>
<td>৳ <?php echo number_format($bed_bill,2); ?></td>
</tr>

<tr>
<th>Test Bill</th>
<td>৳ <?php echo number_format($test_bill,2); ?></td>
</tr>

<tr>
<th>Doctor Fee</th>
<td>
<input type="number" name="doctor_fee" class="form-control amount" value="<?php echo $doctor_fee; ?>" min="0" step="0.01">
</td>
</tr>

<tr>
<th>Medicine Bill</th>
<td>
<input type="number" name="medicine_bill" class="form-control amount" value="<?php echo $medicine_bill; ?>" min="0" step="0.01">
</td>
</tr>

<tr>
<th>Service Bill</th>
<td>
<input type="number" name="service_bill" class="form-control amount" value="<?php echo $service_bill; ?>" min="0" step="0.01">
</td>
</tr>

<tr>
<th>Other Bill</th>
<td>
<input type="number" name="other_bill" class="form-control amount" value="<?php echo $other_bill; ?>" min="0" step="0.01">
</td>
</tr>

<tr>
<th>Discount</th>
<td>
<input type="number" name="discount" class="form-control amount" value="<?php echo $discount; ?>" min="0" step="0.01">
</td>
</tr>

<tr>
<th>Grand Total</th>
<td>
<strong>৳ <span id="grand_total"><?php echo number_format($total_amount,2); ?></span></strong>
</td>
</tr>

</table>

<div class="text-right">
<button type="submit" name="save_invoice" class="btn btn-success">
<i class="fa fa-save"></i> Save Invoice
</button>
</div>

</form>

<h5 class="mt-4">Payment Summary</h5>

<table class="table table-bordered">

<tr>
<th>Grand Total</th>
<td>৳ <?php echo number_format($total_amount,2); ?></td>
</tr>

<tr>
<th>Total Paid</th>
<td>৳ <?php echo number_format($total_paid,2); ?></td>
</tr>

<tr>
<th>Due Amount</th>
<td><strong>৳ <?php echo number_format($due_amount,2); ?></strong></td>
</tr>

<tr>
<th>Payment Status</th>
<td><strong><?php echo $payment_status; ?></strong></td>
</tr>

</table>

<?php if(isset($invoice_id) && $due_amount > 0){ ?>

<h5 class="mt-4">Add Payment</h5>

<form method="POST">

<div class="row">

<div class="col-md-4">
<label>Amount *</label>
<input type="number" name="payment_amount" class="form-control" min="0.01" max="<?php echo $due_amount; ?>" step="0.01" required>
</div>

<div class="col-md-4">
<label>Payment Method *</label>
<select name="payment_method" class="form-control" required>
<option value="Cash">Cash</option>
<option value="bKash">bKash</option>
<option value="Nagad">Nagad</option>
<option value="Card">Card</option>
<option value="Bank">Bank</option>
</select>
</div>

<div class="col-md-4">
<label>Payment Date *</label>
<input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
</div>

</div>

<div class="text-right mt-3">
<button type="submit" name="save_payment" class="btn btn-success">
<i class="fa fa-money"></i> Add Payment
</button>
</div>

</form>

<?php } ?>

<?php if(isset($invoice_id)){ ?>

<h5 class="mt-4">Payment History</h5>

<table class="table table-bordered">

<thead>
<tr>
<th>#</th>
<th>Date</th>
<th>Amount</th>
<th>Method</th>
</tr>
</thead>

<tbody>

<?php if(isset($payments) && $payments["status"] && !empty($payments["data"])){ ?>

<?php $sl=1; ?>

<?php foreach($payments["data"] as $payment){ ?>

<tr>
<td><?php echo $sl++; ?></td>
<td><?php echo $payment->payment_date; ?></td>
<td>৳ <?php echo number_format((float)$payment->amount,2); ?></td>
<td><?php echo $payment->payment_method; ?></td>
</tr>

<?php } ?>

<?php }else{ ?>

<tr>
<td colspan="4" class="text-center">No payment made yet.</td>
</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

<div class="text-center mt-4">
<p>Thank you for choosing SHIFA Hospital.</p>
</div>

</div>
</div>

</div>

<?php require_once "../../component/footer.php"; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded",function(){

var bedBill=<?php echo $bed_bill; ?>;
var testBill=<?php echo $test_bill; ?>;

document.querySelectorAll(".amount").forEach(function(input){
input.addEventListener("input",calculateTotal);
});

function calculateTotal(){

var doctor=parseFloat(document.querySelector('[name="doctor_fee"]').value)||0;
var medicine=parseFloat(document.querySelector('[name="medicine_bill"]').value)||0;
var service=parseFloat(document.querySelector('[name="service_bill"]').value)||0;
var other=parseFloat(document.querySelector('[name="other_bill"]').value)||0;
var discount=parseFloat(document.querySelector('[name="discount"]').value)||0;

var total=bedBill+testBill+doctor+medicine+service+other-discount;

if(total<0){
total=0;
}

document.getElementById("grand_total").innerHTML=total.toFixed(2);
}

});
</script>

<style>
@media print{
.page-wrapper{margin:0;}
.btn{display:none!important;}
.sidebar{display:none!important;}
input{border:none!important;background:transparent!important;}
}
</style>