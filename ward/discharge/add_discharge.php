<?php

require_once "../../component/connection.php";

if (isset($_POST['add_discharge'])) {

    $patient_id = $_POST['patient_id'];
    $admission_id = $_POST['admission_id'];
    $discharge_date = $_POST['discharge_date'];
    $discharge_type = $_POST['discharge_type'];
    $diagnosis = trim($_POST['diagnosis']);
    $treatment_summary = trim($_POST['treatment_summary']);
    $discharge_condition = $_POST['discharge_condition'];
    $advice = trim($_POST['advice']);
    $follow_up_date = !empty($_POST['follow_up_date']) ? $_POST['follow_up_date'] : null;
    $notes = trim($_POST['notes']);

    if (empty($patient_id) || empty($admission_id) || empty($discharge_date) || empty($discharge_condition)) {

        $error = "Please fill all required fields.";

    } else {

        $admission = $crud->common_select("patient_admissions", "*", ["id" => $admission_id]);

        if ($admission["status"]) {

            $doctor_id = $admission["data"][0]->doctor_id;

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

            $result = $crud->common_insert("discharges", $data);

            if ($result["status"]) {

                $crud->common_update(
                    "patient_admissions",
                    [
                        "discharge_date" => date("Y-m-d", strtotime($discharge_date)),
                        "discharge_time" => date("H:i:s", strtotime($discharge_date))
                    ],
                    ["id" => $admission_id]
                );

                echo "<script>alert('Discharge added successfully'); window.location.href='discharge.php';</script>";
                exit;

            } else {

                $error = $result["message"];
            }

        } else {

            $error = "Admission record not found.";
        }
    }
}

$patients = $crud->common_select(
    "patients",
    "id, name, phone",
    [],
    "AND",
    "name",
    "ASC"
);

$admissions = $crud->common_query("
    SELECT pa.id, pa.admission_no, pa.patient_id, p.name AS patient_name
    FROM patient_admissions pa
    LEFT JOIN patients p ON p.id = pa.patient_id
    WHERE pa.deleted_at IS NULL
    AND pa.discharge_date IS NULL
    ORDER BY pa.id DESC
");

require_once "../../component/header.php";
require_once "../../component/sidebar.php";

?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Add Discharge</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="discharge.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Discharge Information</h4>
            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient <span class="text-danger">*</span></label>

                                <select name="patient_id" class="form-control" required>
                                    <option value="">Select Patient</option>

                                    <?php
                                    if ($patients["status"]) {
                                        foreach ($patients["data"] as $patient) {
                                    ?>

                                        <option value="<?php echo $patient->id; ?>">
                                            <?php echo htmlspecialchars($patient->name); ?> - <?php echo htmlspecialchars($patient->phone); ?>
                                        </option>

                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Admission <span class="text-danger">*</span></label>

                                <select name="admission_id" class="form-control" required>
                                    <option value="">Select Admission</option>

                                    <?php
                                    if ($admissions["status"]) {
                                        foreach ($admissions["data"] as $admission) {
                                    ?>

                                        <option value="<?php echo $admission->id; ?>">
                                            <?php echo htmlspecialchars($admission->admission_no); ?> - <?php echo htmlspecialchars($admission->patient_name); ?>
                                        </option>

                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="discharge_date" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
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
                        <button type="submit" name="add_discharge" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Discharge
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <?php require_once "../../component/footer.php"; ?>

</div>