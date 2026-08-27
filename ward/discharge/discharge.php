<?php
require_once "../../component/header.php";
require_once "../../component/sidebar.php";

$error="";

if(!isset($_GET['admission_id']) || empty($_GET['admission_id'])){
    header("Location: ../patients_addmission/admitted_patient_list.php");
    exit;
}

$admission_id=(int)$_GET['admission_id'];

/* ================= ADMISSION + PATIENT ================= */

$admission=$crud->common_query("
    SELECT 
        pa.*,
        p.name AS patient_name,
        p.phone AS patient_phone,
        p.gender,
        p.age,
        p.blood_group,
        d.name AS doctor_name,
        r.room_number,
        r.room_charge
    FROM patient_admissions pa
    LEFT JOIN patients p ON p.id=pa.patient_id
    LEFT JOIN doctors d ON d.id=pa.doctor_id
    LEFT JOIN rooms r ON r.id=pa.room_id
    WHERE pa.id=$admission_id
    AND pa.deleted_at IS NULL
    AND pa.discharge_date IS NULL
    LIMIT 1
");

if(!$admission["status"] || empty($admission["data"])){
    echo "<script>
        alert('Admitted patient not found or already discharged.');
        window.location.href='../patients_addmission/admitted_patient_list.php';
    </script>";
    exit;
}

$data=$admission["data"][0];

/* ================= ADMISSION DATE TIME ================= */

$admission_datetime=$data->admission_date;

if(!empty($data->admission_time)){
    $admission_datetime=$data->admission_date." ".$data->admission_time;
}

/* ================= ROOM BILL ================= */

$room_charge=(float)$data->room_charge;

$default_discharge_date=date("Y-m-d\TH:i");

$admission_timestamp=strtotime($admission_datetime);
$discharge_timestamp=strtotime(date("Y-m-d H:i:s"));

$total_days=ceil(($discharge_timestamp-$admission_timestamp)/86400);

if($total_days<1){
    $total_days=1;
}

$total_room_charge=$total_days*$room_charge;

/* =========================================================
   TESTS FROM DIAGNOSIS INVOICE
   SOURCE: invoices + invoice_details

   SAME PATIENT
   SAME ADMISSION
   ONLY ADMITTED INVOICE
   ========================================================= */

$tests=$crud->common_query("
    SELECT
        invoice_details.id,
        invoice_details.Name AS test_name,
        invoice_details.price AS test_price,
        invoice_details.discount AS test_discount,
        invoice_details.tax AS test_tax,
        invoices.id AS invoice_id,
        invoices.invoice_date
    FROM invoice_details
    INNER JOIN invoices
        ON invoices.id=invoice_details.invoice_id
    WHERE invoices.patient_id=".(int)$data->patient_id."
    AND invoices.admission_id=$admission_id
    AND invoices.invoice_type='ADMITTED'
    AND invoice_details.deleted_at IS NULL
    ORDER BY invoice_details.id ASC
");

$test_bill=0;

if($tests["status"] && !empty($tests["data"])){

    foreach($tests["data"] as $test){

        $price=(float)$test->test_price;
        $discount_percent=(float)$test->test_discount;
        $tax_percent=(float)$test->test_tax;

        $discount_amount=($price*$discount_percent)/100;

        $after_discount=$price-$discount_amount;

        $tax_amount=($after_discount*$tax_percent)/100;

        $test_total=$after_discount+$tax_amount;

        $test_bill+=$test_total;
    }
}

/* ================= SAVE DISCHARGE ================= */

if(isset($_POST['add_discharge'])){

    $discharge_date=$_POST['discharge_date'];
    $discharge_type=$_POST['discharge_type'];
    $diagnosis=trim($_POST['diagnosis']);
    $treatment_summary=trim($_POST['treatment_summary']);
    $discharge_condition=$_POST['discharge_condition'];
    $advice=trim($_POST['advice']);
    $follow_up_date=!empty($_POST['follow_up_date'])?$_POST['follow_up_date']:null;
    $notes=trim($_POST['notes']);

    if(empty($discharge_date) || empty($discharge_condition)){

        $error="Please fill all required fields.";

    }else{

        /* ================= CHECK ADMISSION ================= */

        $check=$crud->common_query("
            SELECT id
            FROM patient_admissions
            WHERE id=$admission_id
            AND deleted_at IS NULL
            AND discharge_date IS NULL
            LIMIT 1
        ");

        if(!$check["status"] || empty($check["data"])){

            $error="This patient is already discharged or admission record not found.";

        }else{

            /* ================= DISCHARGE DATA ================= */

            $discharge_data=[
                "patient_id"=>$data->patient_id,
                "admission_id"=>$admission_id,
                "doctor_id"=>$data->doctor_id,
                "discharge_date"=>$discharge_date,
                "discharge_type"=>$discharge_type,
                "diagnosis"=>$diagnosis,
                "treatment_summary"=>$treatment_summary,
                "discharge_condition"=>$discharge_condition,
                "advice"=>$advice,
                "follow_up_date"=>$follow_up_date,
                "notes"=>$notes
            ];

            $result=$crud->common_insert(
                "discharges",
                $discharge_data
            );

            if($result["status"]){

                /* ================= UPDATE ADMISSION ================= */

                $crud->common_update(
                    "patient_admissions",
                    [
                        "discharge_date"=>date("Y-m-d",strtotime($discharge_date)),
                        "discharge_time"=>date("H:i:s",strtotime($discharge_date)),
                        "status"=>1,
                        "updated_at"=>date("Y-m-d H:i:s")
                    ],
                    [
                        "id"=>$admission_id
                    ]
                );

                /* ================= GET DISCHARGE ID ================= */

                $discharge_id=(int)$result["data"];

                /* ================= CREATE DISCHARGE INVOICE ================= */

                $invoice_no="DIS-".date("YmdHis");

                $bed_bill=$total_room_charge;

                $doctor_fee=0;
                $medicine_bill=0;
                $service_bill=0;
                $other_bill=0;
                $discount=0;

                $total_amount=
                    $bed_bill+
                    $test_bill+
                    $doctor_fee+
                    $medicine_bill+
                    $service_bill+
                    $other_bill-
                    $discount;

                if($total_amount<0){
                    $total_amount=0;
                }

                $invoice_data=[
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
                ];

                $invoice_result=$crud->common_insert(
                    "discharge_invoices",
                    $invoice_data
                );

                if($invoice_result["status"]){

                    echo "<script>
                        alert('Patient discharged and invoice generated successfully.');
                        window.location.href='discharge_invoice.php?id=$discharge_id';
                    </script>";
                    exit;

                }else{

                    $error="Discharge saved, but invoice could not be generated.";
                }

            }else{

                $error=$result["message"];
            }
        }
    }
}

?>

<div class="page-wrapper">
<div class="content">

<div class="row">

<div class="col-sm-7 col-6">
<h4 class="page-title">Patient Discharge</h4>
</div>

<div class="col-sm-5 col-6 text-right">

<a href="../patients_addmission/admitted_patient_list.php"
class="btn btn-secondary btn-rounded">

<i class="fa fa-arrow-left"></i> Back

</a>

</div>

</div>

<?php if(!empty($error)){ ?>

<div class="alert alert-danger">
<i class="fa fa-exclamation-circle"></i>
<?=htmlspecialchars($error)?>
</div>

<?php } ?>

<div class="card">

<div class="card-header">

<h4 class="card-title">
Discharge Patient
</h4>

<p class="text-muted mb-0">
Discharging patient:
<strong><?=htmlspecialchars($data->patient_name)?></strong>
</p>

</div>

<div class="card-body">

<form method="POST">

<!-- ================= PATIENT INFORMATION ================= -->

<h5 class="mb-3">Patient Information</h5>

<div class="row">

<div class="col-md-4">
<div class="form-group">
<label>Patient Name</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->patient_name)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Patient ID</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->patient_id)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Phone</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->patient_phone)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Gender</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->gender)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Age</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->age)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Blood Group</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->blood_group)?>" readonly>
</div>
</div>

</div>

<hr>

<!-- ================= ADMISSION INFORMATION ================= -->

<h5 class="mb-3">Admission Information</h5>

<div class="row">

<div class="col-md-4">
<div class="form-group">
<label>Admission No</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->admission_no)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Doctor</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->doctor_name)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Room No</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->room_number)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Room Charge / Day</label>
<input type="text" id="room_charge"
class="form-control"
value="<?=number_format($room_charge,2)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Admission Date</label>
<input type="text" id="admission_date"
class="form-control"
value="<?=htmlspecialchars($data->admission_date)?>" readonly>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Admission Time</label>
<input type="text" class="form-control"
value="<?=htmlspecialchars($data->admission_time)?>" readonly>
</div>
</div>

</div>

<hr>

<!-- ================= DISCHARGE INFORMATION ================= -->

<h5 class="mb-3">Discharge Information</h5>

<div class="row">

<div class="col-md-6">
<div class="form-group">

<label>
Discharge Date <span class="text-danger">*</span>
</label>

<input type="datetime-local"
name="discharge_date"
id="discharge_date"
class="form-control"
value="<?=htmlspecialchars($default_discharge_date)?>"
required>

</div>
</div>

<div class="col-md-6">
<div class="form-group">

<label>Discharge Type</label>

<select name="discharge_type" class="form-control">

<option value="Normal">Normal</option>
<option value="Referred">Referred</option>
<option value="LAMA">LAMA</option>
<option value="Death">Death</option>

</select>

</div>
</div>

<div class="col-md-6">
<div class="form-group">

<label>
Discharge Condition <span class="text-danger">*</span>
</label>

<select name="discharge_condition"
class="form-control"
required>

<option value="">Select Condition</option>
<option value="Stable">Stable</option>
<option value="Improved">Improved</option>
<option value="Critical">Critical</option>

</select>

</div>
</div>

<div class="col-md-6">
<div class="form-group">

<label>Follow-up Date</label>

<input type="date"
name="follow_up_date"
class="form-control">

</div>
</div>

</div>

<!-- ================= BILL SUMMARY ================= -->

<div class="row">

<div class="col-md-4">
<div class="form-group">

<label>Total Stay</label>

<input type="text"
id="total_days"
class="form-control"
value="<?=$total_days?> Day(s)"
readonly>

</div>
</div>

<div class="col-md-4">
<div class="form-group">

<label>Bed / Room Bill</label>

<input type="text"
id="total_room_charge"
class="form-control"
value="৳ <?=number_format($total_room_charge,2)?>"
readonly>

</div>
</div>

<div class="col-md-4">
<div class="form-group">

<label>Test Bill</label>

<input type="text"
class="form-control"
value="৳ <?=number_format($test_bill,2)?>"
readonly>

</div>
</div>

</div>

<!-- ================= TEST LIST ================= -->

<hr>

<h5 class="mb-3">Diagnosis / Lab Tests</h5>

<?php if($tests["status"] && !empty($tests["data"])){ ?>

<div class="table-responsive">

<table class="table table-bordered">

<thead>

<tr>
<th>#</th>
<th>Test Name</th>
<th>Price</th>
<th>Discount</th>
<th>Tax</th>
<th>Total</th>
</tr>

</thead>

<tbody>

<?php

$sl=1;

foreach($tests["data"] as $test){

    $price=(float)$test->test_price;
    $test_discount=(float)$test->test_discount;
    $test_tax=(float)$test->test_tax;

    $discount_amount=($price*$test_discount)/100;

    $tax_amount=(($price-$discount_amount)*$test_tax)/100;

    $test_total=($price-$discount_amount)+$tax_amount;

?>

<tr>

<td><?=$sl++?></td>

<td><?=htmlspecialchars($test->test_name)?></td>

<td>৳ <?=number_format($price,2)?></td>

<td><?=number_format($test_discount,2)?>%</td>

<td><?=number_format($test_tax,2)?>%</td>

<td>
<strong>৳ <?=number_format($test_total,2)?></strong>
</td>

</tr>

<?php } ?>

<tr>

<th colspan="5" class="text-right">
Total Test Bill
</th>

<th>
৳ <?=number_format($test_bill,2)?>
</th>

</tr>

</tbody>

</table>

</div>

<?php }else{ ?>

<div class="alert alert-warning">

<i class="fa fa-info-circle"></i>

No diagnosis/lab test found for this admitted patient.

</div>

<?php } ?>

<hr>

<!-- ================= FINAL DIAGNOSIS ================= -->

<div class="form-group">

<label>Final Diagnosis</label>

<textarea name="diagnosis"
class="form-control"
rows="3"
placeholder="Enter final diagnosis"></textarea>

</div>

<div class="form-group">

<label>Treatment Summary</label>

<textarea name="treatment_summary"
class="form-control"
rows="3"
placeholder="Enter treatment summary"></textarea>

</div>

<div class="form-group">

<label>Discharge Advice</label>

<textarea name="advice"
class="form-control"
rows="3"
placeholder="Enter discharge advice"></textarea>

</div>

<div class="form-group">

<label>Notes</label>

<textarea name="notes"
class="form-control"
rows="3"
placeholder="Additional notes"></textarea>

</div>

<!-- ================= INFO ================= -->

<div class="alert alert-info">

<i class="fa fa-info-circle"></i>

<strong>Billing:</strong>

Bed/Room Bill and selected diagnosis/lab tests from the patient's
<strong>ADMITTED invoice</strong> will automatically be added to the discharge invoice.

Other charges can be added later from the discharge invoice.

</div>

<!-- ================= BUTTON ================= -->

<div class="text-right">

<a href="../patients_addmission/admitted_patient_list.php"
class="btn btn-secondary">

Cancel

</a>

<button type="submit"
name="add_discharge"
class="btn btn-success"
onclick="return confirm('Are you sure you want to discharge this patient?');">

<i class="fa fa-sign-out"></i>

Confirm Discharge & Generate Invoice

</button>

</div>

</form>

</div>
</div>

</div>

<?php require_once "../../component/footer.php"; ?>

</div>

<script>

function calculateRoomCharge(){

    var admissionDate=document.getElementById("admission_date").value;
    var dischargeDate=document.getElementById("discharge_date").value;

    var roomCharge=parseFloat(
        document.getElementById("room_charge").value.replace(/,/g,'')
    )||0;

    if(!admissionDate || !dischargeDate){
        return;
    }

    var start=new Date(admissionDate+"T00:00:00");
    var end=new Date(dischargeDate);

    var diff=Math.ceil(
        (end-start)/(1000*60*60*24)
    );

    if(diff<1){
        diff=1;
    }

    document.getElementById("total_days").value=
        diff+" Day(s)";

    document.getElementById("total_room_charge").value=
        "৳ "+(diff*roomCharge).toFixed(2);
}

document.getElementById("discharge_date").addEventListener(
    "change",
    calculateRoomCharge
);

</script>