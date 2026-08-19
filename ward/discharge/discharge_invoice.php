<?php
require_once "../../component/connection.php";
require_once "../../component/header.php";
require_once "../../component/sidebar.php";

$discharge_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($discharge_id <= 0) {
    echo "<script>window.location='discharge.php';</script>";
    exit;
}

$discharge = $crud->common_select("discharges", "*", ["discharge_id" => $discharge_id]);

if (!$discharge["status"] || empty($discharge["data"])) {
    echo "<script>alert('Discharge record not found');window.location='discharge.php';</script>";
    exit;
}

$d = $discharge["data"][0];
$patient_id = $d->patient_id;
$admission_id = $d->admission_id;

$patient = $crud->common_select("patients", "*", ["id" => $patient_id]);
$admission = $crud->common_select("patient_admissions", "*", ["id" => $admission_id]);

$patient_name = "";
if ($patient["status"] && !empty($patient["data"])) {
    $patient_name = $patient["data"][0]->name;
}

$room_number = "N/A";
$room_charge = 0;
$stay_days = 1;
$room_bill = 0;

if ($admission["status"] && !empty($admission["data"])) {
    $a = $admission["data"][0];

    $admission_date = $a->admission_date;
    $discharge_date = !empty($a->discharge_date) ? $a->discharge_date : $d->discharge_date;

    if (!empty($admission_date) && !empty($discharge_date)) {
        $start = new DateTime($admission_date);
        $end = new DateTime($discharge_date);
        $stay_days = $start->diff($end)->days;

        if ($stay_days < 1) {
            $stay_days = 1;
        }
    }

    if (!empty($a->room_id)) {
        $room = $crud->common_select("rooms", "*", ["id" => $a->room_id]);

        if ($room["status"] && !empty($room["data"])) {
            $r = $room["data"][0];

            $room_number = $r->room_number;

            if (isset($r->charge_per_day)) {
                $room_charge = (float)$r->charge_per_day;
            } elseif (isset($r->room_charge)) {
                $room_charge = (float)$r->room_charge;
            }

            $room_bill = $room_charge * $stay_days;
        }
    }
}

$lab_result = $crud->common_select("lab_category", "*");
$lab_tests = [];

if ($lab_result["status"] && !empty($lab_result["data"])) {
    $lab_tests = $lab_result["data"];
}

$existing = $crud->common_select("discharge_invoices", "*", ["discharge_id" => $discharge_id]);
$invoice = null;

if ($existing["status"] && !empty($existing["data"])) {
    $invoice = $existing["data"][0];
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

    $total_amount = $room_bill + $doctor_fee + $test_bill + $medicine_bill + $service_bill + $other_bill - $discount;

    if ($total_amount < 0) {
        $total_amount = 0;
    }

    if ($paid_amount < 0) {
        $paid_amount = 0;
    }

    if ($paid_amount > $total_amount) {
        $paid_amount = $total_amount;
    }

    $due_amount = $total_amount - $paid_amount;

    if ($paid_amount == 0) {
        $payment_status = "Unpaid";
    } elseif ($paid_amount < $total_amount) {
        $payment_status = "Partial";
    } else {
        $payment_status = "Paid";
    }

    $data = [
        "patient_id" => $patient_id,
        "bed_bill" => $room_bill,
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

    if ($invoice) {
        $update = $crud->common_update("discharge_invoices", $data, ["invoice_id" => $invoice->invoice_id]);

        if ($update["status"]) {
            echo "<script>alert('Invoice updated successfully');window.location='discharge_invoice_view.php?id=$discharge_id';</script>";
            exit;
        }
    } else {
        $data["discharge_id"] = $discharge_id;
        $data["invoice_no"] = "DIN-" . date("YmdHis");

        $insert = $crud->common_insert("discharge_invoices", $data);

        if ($insert["status"]) {
            echo "<script>alert('Invoice generated successfully');window.location='discharge_invoice_view.php?id=$discharge_id';</script>";
            exit;
        }
    }
}
?>

<div class="page-wrapper">
<div class="content">
<div class="page-header">
<div class="page-title">
<h4>Discharge Invoice</h4>
<h6>Patient final billing</h6>
</div>
<div class="page-btn">
<a href="discharge_view.php?id=<?php echo $discharge_id; ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
</div>

<div class="card">
<div class="card-header">
<h4 class="card-title">Patient Information</h4>
</div>
<div class="card-body">
<div class="row">
<div class="col-md-4">
<label>Patient Name</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($patient_name); ?>" readonly>
</div>
<div class="col-md-4">
<label>Patient ID</label>
<input type="text" class="form-control" value="<?php echo $patient_id; ?>" readonly>
</div>
<div class="col-md-4">
<label>Admission ID</label>
<input type="text" class="form-control" value="<?php echo $admission_id; ?>" readonly>
</div>
</div>

<div class="row mt-3">
<div class="col-md-4">
<label>Room No</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($room_number); ?>" readonly>
</div>
<div class="col-md-4">
<label>Stay Days</label>
<input type="text" id="stay_days" class="form-control" value="<?php echo $stay_days; ?>" readonly>
</div>
<div class="col-md-4">
<label>Room Charge / Day</label>
<input type="text" class="form-control" value="<?php echo number_format($room_charge,2); ?> TK" readonly>
</div>
</div>
</div>
</div>

<form method="post">

<div class="card">
<div class="card-header">
<h4 class="card-title">Lab Tests</h4>
</div>
<div class="card-body">

<div class="row">
<div class="col-md-8">
<label>Select Test</label>
<select id="lab_test" class="form-control">
<option value="">-- Select Test --</option>
<?php foreach ($lab_tests as $test) { ?>
<option value="<?php echo htmlspecialchars($test->test_name); ?>" data-price="<?php echo $test->price; ?>" data-accessor="<?php echo htmlspecialchars($test->test_accessor); ?>">
<?php echo htmlspecialchars($test->test_name); ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-4">
<label>Price</label>
<input type="text" id="lab_price" class="form-control" readonly>
</div>
</div>

<div class="mt-3">
<button type="button" id="add_test" class="btn btn-primary"><i class="fa fa-plus"></i> Add Test</button>
</div>

<div class="table-responsive mt-3">
<table class="table table-bordered" id="test_table">
<thead>
<tr>
<th>#</th>
<th>Test Name</th>
<th>Test Accessor</th>
<th>Price</th>
<th>Action</th>
</tr>
</thead>
<tbody></tbody>
</table>
</div>

<div class="text-right">
<b>Test Total: <span id="test_total">0.00</span> TK</b>
</div>

<input type="hidden" name="test_bill" id="test_bill" value="<?php echo $invoice ? $invoice->test_bill : 0; ?>">

</div>
</div>

<div class="card">
<div class="card-header">
<h4 class="card-title">Billing</h4>
</div>
<div class="card-body">

<table class="table table-bordered">
<tr>
<td>Room Bill</td>
<td>
<input type="text" id="room_bill" class="form-control" value="<?php echo number_format($room_bill,2,'.',''); ?>" readonly>
</td>
</tr>

<tr>
<td>Doctor Fee</td>
<td>
<input type="number" step="0.01" id="doctor_fee" name="doctor_fee" class="form-control bill" value="<?php echo $invoice ? $invoice->doctor_fee : 0; ?>">
</td>
</tr>

<tr>
<td>Test Bill</td>
<td>
<input type="text" id="show_test_bill" class="form-control" value="<?php echo $invoice ? $invoice->test_bill : 0; ?>" readonly>
</td>
</tr>

<tr>
<td>Medicine Bill</td>
<td>
<input type="number" step="0.01" id="medicine_bill" name="medicine_bill" class="form-control bill" value="<?php echo $invoice ? $invoice->medicine_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Service Bill</td>
<td>
<input type="number" step="0.01" id="service_bill" name="service_bill" class="form-control bill" value="<?php echo $invoice ? $invoice->service_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Other Bill</td>
<td>
<input type="number" step="0.01" id="other_bill" name="other_bill" class="form-control bill" value="<?php echo $invoice ? $invoice->other_bill : 0; ?>">
</td>
</tr>

<tr>
<td>Discount</td>
<td>
<input type="number" step="0.01" id="discount" name="discount" class="form-control bill" value="<?php echo $invoice ? $invoice->discount : 0; ?>">
</td>
</tr>

<tr>
<th>Total Amount</th>
<th>
<input type="text" id="total_amount" class="form-control" readonly>
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
<input type="text" id="due_amount" class="form-control" readonly>
</th>
</tr>

<tr>
<td>Payment Method</td>
<td>
<select name="payment_method" class="form-control">
<option value="Cash">Cash</option>
<option value="Card">Card</option>
<option value="Mobile Banking">Mobile Banking</option>
</select>
</td>
</tr>
</table>

<div class="text-right">
<button type="submit" name="save_invoice" class="btn btn-success">
<i class="fa fa-save"></i> <?php echo $invoice ? "Update Invoice" : "Generate Invoice"; ?>
</button>

<?php if ($invoice) { ?>
<a href="discharge_invoice_view.php?id=<?php echo $discharge_id; ?>" class="btn btn-info">
<i class="fa fa-eye"></i> View Invoice
</a>
<?php } ?>
</div>

</div>
</div>

</form>
</div>
</div>

<script>
var testSelect = document.getElementById("lab_test");
var testPrice = document.getElementById("lab_price");
var testTable = document.querySelector("#test_table tbody");

testSelect.addEventListener("change", function(){
    var option = this.options[this.selectedIndex];
    testPrice.value = option.getAttribute("data-price") || "";
});

document.getElementById("add_test").addEventListener("click", function(){
    if(testSelect.value == ""){
        alert("Please select a test");
        return;
    }

    var option = testSelect.options[testSelect.selectedIndex];
    var name = testSelect.value;
    var price = parseFloat(option.getAttribute("data-price")) || 0;
    var accessor = option.getAttribute("data-accessor") || "N/A";

    var rows = testTable.querySelectorAll("tr");

    for(var i=0;i<rows.length;i++){
        if(rows[i].querySelector(".test_name").innerText == name){
            alert("Test already added");
            return;
        }
    }

    var row = document.createElement("tr");

    row.innerHTML = "<td class='sl'></td><td class='test_name'>"+name+"</td><td>"+accessor+"</td><td class='test_price'>"+price.toFixed(2)+"</td><td><button type='button' class='btn btn-danger btn-sm remove_test'>X</button></td>";

    testTable.appendChild(row);
    updateTests();

    testSelect.value = "";
    testPrice.value = "";
});

testTable.addEventListener("click", function(e){
    if(e.target.classList.contains("remove_test")){
        e.target.closest("tr").remove();
        updateTests();
    }
});

function updateTests(){
    var total = 0;
    var rows = testTable.querySelectorAll("tr");

    for(var i=0;i<rows.length;i++){
        rows[i].querySelector(".sl").innerText = i + 1;
        total += parseFloat(rows[i].querySelector(".test_price").innerText) || 0;
    }

    document.getElementById("test_total").innerText = total.toFixed(2);
    document.getElementById("test_bill").value = total.toFixed(2);
    document.getElementById("show_test_bill").value = total.toFixed(2);

    calculateTotal();
}

function calculateTotal(){
    var room = parseFloat(document.getElementById("room_bill").value) || 0;
    var doctor = parseFloat(document.getElementById("doctor_fee").value) || 0;
    var test = parseFloat(document.getElementById("test_bill").value) || 0;
    var medicine = parseFloat(document.getElementById("medicine_bill").value) || 0;
    var service = parseFloat(document.getElementById("service_bill").value) || 0;
    var other = parseFloat(document.getElementById("other_bill").value) || 0;
    var discount = parseFloat(document.getElementById("discount").value) || 0;
    var paid = parseFloat(document.getElementById("paid_amount").value) || 0;

    var total = room + doctor + test + medicine + service + other - discount;

    if(total < 0) total = 0;
    if(paid > total) paid = total;

    document.getElementById("paid_amount").value = paid.toFixed(2);
    document.getElementById("total_amount").value = total.toFixed(2);
    document.getElementById("due_amount").value = (total - paid).toFixed(2);
}

document.querySelectorAll(".bill").forEach(function(input){
    input.addEventListener("input", calculateTotal);
});

document.getElementById("paid_amount").addEventListener("input", calculateTotal);

calculateTotal();
</script>

<?php require_once "../../component/footer.php"; ?>