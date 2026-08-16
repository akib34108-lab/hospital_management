<?php
require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";

$discharge_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$discharge = $crud->common_select("discharges", "*", ["discharge_id" => $discharge_id]);

if (!$discharge["status"]) {
    echo "<script>alert('Discharge record not found');window.location.href='discharge.php';</script>";
    exit;
}

$d = $discharge["data"][0];

$admission_id = $d->admission_id;
$patient_id = $d->patient_id;

$admission = $crud->common_select("patient_admissions", "*", ["id" => $admission_id]);

$bed_bill = 0;
$stay_days = 0;
$room_number = "N/A";
$charge_per_day = 0;

if ($admission["status"]) {
    $a = $admission["data"][0];

    $admission_date = $a->admission_date;
    $discharge_date = $a->discharge_date;

    if (!empty($discharge_date)) {
        $start = new DateTime($admission_date);
        $end = new DateTime($discharge_date);
        $stay_days = $start->diff($end)->days;

        if ($stay_days < 1) {
            $stay_days = 1;
        }
    }

    if (!empty($a->room_id)) {
        $room = $crud->common_select("rooms", "*", ["id" => $a->room_id]);

        if ($room["status"]) {
            $r = $room["data"][0];
            $room_number = $r->room_number;
            $charge_per_day = (float)$r->charge_per_day;
            $bed_bill = $stay_days * $charge_per_day;
        }
    }
}

if (isset($_POST['save_invoice'])) {

    $doctor_fee = (float)($_POST['doctor_fee'] ?? 0);
    $test_bill = (float)($_POST['test_bill'] ?? 0);
    $medicine_bill = (float)($_POST['medicine_bill'] ?? 0);
    $service_bill = (float)($_POST['service_bill'] ?? 0);
    $other_bill = (float)($_POST['other_bill'] ?? 0);
    $discount = (float)($_POST['discount'] ?? 0);
    $paid_amount = (float)($_POST['paid_amount'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? "Cash";

    $total_amount = $bed_bill + $doctor_fee + $test_bill + $medicine_bill + $service_bill + $other_bill - $discount;

    if ($total_amount < 0) {
        $total_amount = 0;
    }

    $due_amount = $total_amount - $paid_amount;

    if ($due_amount < 0) {
        $due_amount = 0;
    }

    if ($paid_amount <= 0) {
        $payment_status = "Unpaid";
    } elseif ($paid_amount < $total_amount) {
        $payment_status = "Partial";
    } else {
        $payment_status = "Paid";
    }

    $invoice_no = "DIN-" . date("YmdHis");

    $data = [
        "discharge_id" => $discharge_id,
        "patient_id" => $patient_id,
        "invoice_no" => $invoice_no,
        "bed_bill" => $bed_bill,
        "doctor_fee" => $doctor_fee,
        "test_bill" => $test_bill,
        "medicine_bill" => $medicine_bill,
        "service_bill" => $service_bill,
        "other_bill" => $other_bill,
        "discount" => $discount,
        "total_amount" => $total_amount,
        "paid_amount" => $paid_amount,
        "due_amount" => $due_amount,
        "payment_status" => $payment_status,
        "payment_method" => $payment_method
    ];

    $insert = $crud->common_insert("discharge_invoices", $data);

    if ($insert["status"]) {
        echo "<script>alert('Discharge invoice generated successfully');window.location.href='discharge_invoice.php?id=$discharge_id';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . addslashes($insert["message"]) . "');</script>";
    }
}

$existing = $crud->common_select("discharge_invoices", "*", ["discharge_id" => $discharge_id]);

$invoice = $existing["status"] ? $existing["data"][0] : null;

?>

<div class="page-wrapper">
<div class="content">

<div class="row">
<div class="col-sm-7 col-6">
<h4 class="page-title">Discharge Invoice</h4>
</div>
<div class="col-sm-5 col-6 text-right">
<a href="discharge.php" class="btn btn-secondary btn-rounded"><i class="fa fa-arrow-left"></i> Back</a>
</div>
</div>

<div class="card">
<div class="card-header">
<h4 class="card-title">Patient Discharge Invoice</h4>
<p class="text-muted mb-0">Generate discharge bill</p>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">
<div class="form-group">
<label>Patient ID</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($patient_id); ?>" readonly>
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label>Admission ID</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($admission_id); ?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Room</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($room_number); ?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Stay Days</label>
<input type="text" id="stay_days" class="form-control" value="<?php echo $stay_days; ?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Charge Per Day</label>
<input type="text" id="charge_per_day" class="form-control" value="<?php echo number_format($charge_per_day, 2, '.', ''); ?>" readonly>
</div>
</div>

</div>

<form method="POST">

<div class="table-responsive">
<table class="table table-bordered">

<thead>
<tr>
<th>Bill Type</th>
<th width="250">Amount</th>
</tr>
</thead>

<tbody>

<tr>
<td>Bed Bill</td>
<td>
<input type="number" id="bed_bill" name="bed_bill" class="form-control" value="<?php echo number_format($bed_bill, 2, '.', ''); ?>" readonly>
</td>
</tr>

<tr>
<td>Doctor Fee</td>
<td>
<input type="number" step="0.01" id="doctor_fee" name="doctor_fee" class="form-control bill-input" value="<?php echo $invoice ? $invoice->doctor_fee : 0; ?>">
</td>
</tr>

<tr>
<td>Test Bill</td>
<td>
<input type="number" step="0.01" id="test_bill" name="test_bill" class="form-control bill-input" value="<?php echo $invoice ? $invoice->test_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Medicine Bill</td>
<td>
<input type="number" step="0.01" id="medicine_bill" name="medicine_bill" class="form-control bill-input" value="<?php echo $invoice ? $invoice->medicine_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Service Bill</td>
<td>
<input type="number" step="0.01" id="service_bill" name="service_bill" class="form-control bill-input" value="<?php echo $invoice ? $invoice->service_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Other Bill</td>
<td>
<input type="number" step="0.01" id="other_bill" name="other_bill" class="form-control bill-input" value="<?php echo $invoice ? $invoice->other_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Discount</td>
<td>
<input type="number" step="0.01" id="discount" name="discount" class="form-control bill-input" value="<?php echo $invoice ? $invoice->discount : 0; ?>">
</td>
</tr>

<tr>
<th>Total Amount</th>
<th>
<input type="text" id="total_amount" class="form-control font-weight-bold" value="<?php echo $invoice ? $invoice->total_amount : number_format($bed_bill, 2, '.', ''); ?>" readonly>
</th>
</tr>

<tr>
<td>Paid Amount</td>
<td>
<input type="number" step="0.01" id="paid_amount" name="paid_amount" class="form-control" value="<?php echo $invoice ? $invoice->paid_amount : 0; ?>">
</td>
</tr>

<tr>
<th>Due Amount</th>
<th>
<input type="text" id="due_amount" class="form-control font-weight-bold" value="<?php echo $invoice ? $invoice->due_amount : number_format($bed_bill, 2, '.', ''); ?>" readonly>
</th>
</tr>

<tr>
<td>Payment Method</td>
<td>
<select name="payment_method" class="form-control">
<option value="Cash" <?php echo ($invoice && $invoice->payment_method == "Cash") ? "selected" : ""; ?>>Cash</option>
<option value="Card" <?php echo ($invoice && $invoice->payment_method == "Card") ? "selected" : ""; ?>>Card</option>
<option value="Mobile Banking" <?php echo ($invoice && $invoice->payment_method == "Mobile Banking") ? "selected" : ""; ?>>Mobile Banking</option>
</select>
</td>
</tr>

</tbody>

</table>
</div>

<div class="text-right mt-3">
<button type="submit" name="save_invoice" class="btn btn-primary">
<i class="fa fa-save"></i> Generate Invoice
</button>
</div>

</form>

</div>
</div>

</div>

<?php require_once "../component/footer.php"; ?>

</div>

<script>
function calculateInvoice() {
    let bed = parseFloat(document.getElementById("bed_bill").value) || 0;
    let doctor = parseFloat(document.getElementById("doctor_fee").value) || 0;
    let test = parseFloat(document.getElementById("test_bill").value) || 0;
    let medicine = parseFloat(document.getElementById("medicine_bill").value) || 0;
    let service = parseFloat(document.getElementById("service_bill").value) || 0;
    let other = parseFloat(document.getElementById("other_bill").value) || 0;
    let discount = parseFloat(document.getElementById("discount").value) || 0;
    let paid = parseFloat(document.getElementById("paid_amount").value) || 0;

    let total = bed + doctor + test + medicine + service + other - discount;

    if (total < 0) total = 0;

    let due = total - paid;

    if (due < 0) due = 0;

    document.getElementById("total_amount").value = total.toFixed(2);
    document.getElementById("due_amount").value = due.toFixed(2);
}

document.querySelectorAll(".bill-input").forEach(function(input) {
    input.addEventListener("input", calculateInvoice);
});

document.getElementById("paid_amount").addEventListener("input", calculateInvoice);

calculateInvoice();
</script>