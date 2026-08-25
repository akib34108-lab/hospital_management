<?php
require_once "../../component/header.php";
require_once "../../component/sidebar.php";
$error = "";
if (isset($_POST['add_discharge'])) {
    $admission_id = $_POST['admission_id'];
    $discharge_date = $_POST['discharge_date'];
    $discharge_type = $_POST['discharge_type'];
    $room_number = trim($_POST['room_number']);
    $room_charge = (float)$_POST['room_charge'];
    $diagnosis = trim($_POST['diagnosis']);
    $treatment_summary = trim($_POST['treatment_summary']);
    $discharge_condition = $_POST['discharge_condition'];
    $advice = trim($_POST['advice']);
    $follow_up_date = !empty($_POST['follow_up_date']) ? $_POST['follow_up_date'] : null;
    $notes = trim($_POST['notes']);
    if (empty($admission_id) || empty($discharge_date) || empty($discharge_condition) || empty($room_number) || $room_charge <= 0) {
        $error = "Please fill all required fields.";
    } else {
        $admission = $crud->common_query("SELECT pa.*,p.name AS patient_name,p.phone AS patient_phone,d.name AS doctor_name FROM patient_admissions pa LEFT JOIN patients p ON p.id=pa.patient_id LEFT JOIN doctors d ON d.id=pa.doctor_id WHERE pa.id=".(int)$admission_id." AND pa.deleted_at IS NULL AND pa.discharge_date IS NULL LIMIT 1");
        if ($admission["status"] && !empty($admission["data"])) {
            $admission_data = $admission["data"][0];
            $patient_id = $admission_data->patient_id;
            $doctor_id = $admission_data->doctor_id;
            $admission_date = new DateTime($admission_data->admission_date);
            $discharge_date_obj = new DateTime($discharge_date);
            $total_days = $admission_date->diff($discharge_date_obj)->days;
            if ($total_days < 1) {
                $total_days = 1;
            }
            $total_room_charge = $total_days * $room_charge;
            $data = [
                "patient_id" => $patient_id,
                "admission_id" => $admission_id,
                "doctor_id" => $doctor_id,
                "discharge_date" => $discharge_date,
                "discharge_type" => $discharge_type,
                "diagnosis" => $diagnosis,
                "treatment_summary" => $treatment_summary,
                "discharge_condition" => $discharge_condition,
                "advice" => $advice,
                "follow_up_date" => $follow_up_date,
                "notes" => $notes
            ];
            $result = $crud->common_insert("discharges",$data);
            if ($result["status"]) {
                $crud->common_update("patient_admissions",[
                    "discharge_date" => date("Y-m-d",strtotime($discharge_date)),
                    "discharge_time" => date("H:i:s",strtotime($discharge_date)),
                    "status" => "Discharged",
                    "updated_at" => date("Y-m-d H:i:s")
                ],["id"=>$admission_id]);
                echo "<script>alert('Discharge added successfully');window.location.href='discharge.php';</script>";
                exit;
            } else {
                $error = $result["message"];
            }
        } else {
            $error = "Admitted patient record not found.";
        }
    }
}
$admissions = $crud->common_query("SELECT pa.id,pa.admission_no,pa.patient_id,pa.doctor_id,pa.room_id,pa.bed_id,pa.admission_date,pa.admission_time,p.name AS patient_name,p.phone AS patient_phone,d.name AS doctor_name FROM patient_admissions pa LEFT JOIN patients p ON p.id=pa.patient_id LEFT JOIN doctors d ON d.id=pa.doctor_id WHERE pa.deleted_at IS NULL AND pa.discharge_date IS NULL ORDER BY pa.id DESC");

?>
<div class="page-wrapper">
<div class="content">
<div class="row">
<div class="col-sm-7 col-6"><h4 class="page-title">Add Discharge</h4></div>
<div class="col-sm-5 col-6 text-right"><a href="discharge.php" class="btn btn-secondary btn-rounded"><i class="fa fa-arrow-left"></i> Back</a></div>
</div>
<?php if (!empty($error)) { ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php } ?>
<div class="card">
<div class="card-header">
<h4 class="card-title">Admitted Patient Discharge</h4>
<p class="text-muted mb-0">Select an admitted patient to continue discharge.</p>
</div>
<div class="card-body">
<form method="POST">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>Admitted Patient <span class="text-danger">*</span></label>
<select name="admission_id" id="admission_id" class="form-control" required onchange="loadAdmission(this)">
<option value="">Select Admitted Patient</option>
<?php if ($admissions["status"] && !empty($admissions["data"])) { foreach ($admissions["data"] as $admission) { ?>
<option value="<?= $admission->id ?>" data-patient-name="<?= htmlspecialchars($admission->patient_name) ?>" data-phone="<?= htmlspecialchars($admission->patient_phone) ?>" data-admission-no="<?= htmlspecialchars($admission->admission_no) ?>" data-doctor="<?= htmlspecialchars($admission->doctor_name) ?>" data-admission-date="<?= $admission->admission_date ?>" data-admission-time="<?= $admission->admission_time ?>"><?= htmlspecialchars($admission->admission_no) ?> - <?= htmlspecialchars($admission->patient_name) ?></option>
<?php }} ?>
</select>
</div>
</div>
</div>
<div id="patientInfo" style="display:none;">
<div class="row">
<div class="col-md-4">
<div class="form-group">
<label>Patient</label>
<input type="text" id="patient_name" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Phone</label>
<input type="text" id="patient_phone" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Admission No</label>
<input type="text" id="admission_no" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Doctor</label>
<input type="text" id="doctor_name" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Room No <span class="text-danger">*</span></label>
<?php
$rooms = $crud->common_select('rooms', '*');
    if($rooms['status']){
        foreach ($rooms['data'] as $room) { ?>
<input type="text" value="<?= $room->room_number ?>" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Room Charge / Day <span class="text-danger">*</span></label>
<input type="text" value="<?= $room->room_charge ?>" readonly oninput="calculateRoomCharge()">
<?php }} ?>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Admission Date</label>
<input type="date" id="admission_date" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Admission Time</label>
<input type="time" id="admission_time" class="form-control" >
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label>Discharge Date <span class="text-danger">*</span></label>
<input type="datetime-local" name="discharge_date" id="discharge_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" onchange="calculateRoomCharge()" required>
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
<label>Discharge Condition <span class="text-danger">*</span></label>
<select name="discharge_condition" class="form-control" required>
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
<input type="date" name="follow_up_date" class="form-control">
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Total Days</label>
<input type="text" id="total_days" class="form-control" readonly>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Total Room Charge</label>
<input type="text" id="total_room_charge" class="form-control" readonly>
</div>
</div>
<div class="col-md-12">
<div class="form-group">
<label>Final Diagnosis</label>
<textarea name="diagnosis" class="form-control" rows="3" placeholder="Enter final diagnosis"></textarea>
</div>
</div>
<div class="col-md-12">
<div class="form-group">
<label>Treatment Summary</label>
<textarea name="treatment_summary" class="form-control" rows="3" placeholder="Enter treatment summary"></textarea>
</div>
</div>
<div class="col-md-12">
<div class="form-group">
<label>Discharge Advice</label>
<textarea name="advice" class="form-control" rows="3" placeholder="Enter discharge advice"></textarea>
</div>
</div>
<div class="col-md-12">
<div class="form-group">
<label>Notes</label>
<textarea name="notes" class="form-control" rows="3" placeholder="Additional notes"></textarea>
</div>
</div>
</div>
<div class="text-right">
<a href="discharge.php" class="btn btn-secondary">Cancel</a>
<button type="submit" name="add_discharge" class="btn btn-primary"><i class="fa fa-save"></i> Save Discharge</button>
</div>
</form>
</div>
</div>
</div>
<?php require_once "../../component/footer.php"; ?>
</div>
<script>
function loadAdmission(select) {
    var option=select.options[select.selectedIndex];
    if(!option.value){
        document.getElementById("patientInfo").style.display="none";
        return;
    }
    document.getElementById("patientInfo").style.display="block";
    document.getElementById("patient_name").value=option.getAttribute("data-patient-name");
    document.getElementById("patient_phone").value=option.getAttribute("data-phone");
    document.getElementById("admission_no").value=option.getAttribute("data-admission-no");
    document.getElementById("doctor_name").value=option.getAttribute("data-doctor");
    document.getElementById("admission_date").value=option.getAttribute("data-admission-date");
    document.getElementById("admission_time").value=option.getAttribute("data-admission-time");
    calculateRoomCharge();
}
function calculateRoomCharge() {
    var admissionDate=document.getElementById("admission_date").value;
    var dischargeDate=document.getElementById("discharge_date").value;
    var roomCharge=parseFloat(document.getElementById("room_charge").value)||0;
    if(!admissionDate||!dischargeDate){
        document.getElementById("total_days").value="";
        document.getElementById("total_room_charge").value="";
        return;
    }
    var start=new Date(admissionDate+"T00:00:00");
    var end=new Date(dischargeDate);
    var diff=Math.ceil((end-start)/(1000*60*60*24));
    if(diff<1) diff=1;
    document.getElementById("total_days").value=diff+" Day";
    document.getElementById("total_room_charge").value="৳ "+(diff*roomCharge).toFixed(2);
}
</script>