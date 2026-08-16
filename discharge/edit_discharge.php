<?php

require_once "../component/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$discharge_id = intval($_GET['id']);

$discharge = $crud->common_select("discharges", "*", ["discharge_id" => $discharge_id]);

if (!$discharge["status"]) {
    echo "<script>alert('Discharge record not found'); window.location.href='discharge.php';</script>";
    exit;
}

$data = $discharge["data"][0];

if (isset($_POST['update_discharge'])) {

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

    $update_data = [
        "patient_id" => $patient_id,
        "admission_id" => $admission_id,
        "discharge_date" => $discharge_date,
        "discharge_type" => $discharge_type,
        "diagnosis" => $diagnosis,
        "treatment_summary" => $treatment_summary,
        "discharge_condition" => $discharge_condition,
        "advice" => $advice,
        "follow_up_date" => $follow_up_date,
        "notes" => $notes
    ];

    $result = $crud->common_update("discharges", $update_data, ["discharge_id" => $discharge_id]);

    if ($result["status"]) {

        $crud->common_update(
            "patient_admissions",
            [
                "discharge_date" => date("Y-m-d", strtotime($discharge_date)),
                "discharge_time" => date("H:i:s", strtotime($discharge_date))
            ],
            ["id" => $admission_id]
        );

        echo "<script>alert('Discharge updated successfully'); window.location.href='discharge_view.php?id=$discharge_id';</script>";
        exit;

    } else {
        $error = $result["message"];
    }
}

$patients = $crud->common_select("patients", "id, name, phone", [], "AND", "name", "ASC");

$admissions = $crud->common_query("
    SELECT pa.id, pa.admission_no, pa.patient_id, p.name AS patient_name
    FROM patient_admissions pa
    LEFT JOIN patients p ON p.id = pa.patient_id
    WHERE pa.deleted_at IS NULL
    ORDER BY pa.id DESC
");

require_once "../component/header.php";
require_once "../component/sidebar.php";

?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Edit Discharge</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="discharge_view.php?id=<?php echo $discharge_id; ?>" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
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

                                        <option value="<?php echo $patient->id; ?>" <?php echo ($data->patient_id == $patient->id) ? "selected" : ""; ?>>
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

                                        <option value="<?php echo $admission->id; ?>" <?php echo ($data->admission_id == $admission->id) ? "selected" : ""; ?>>
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

                                <input type="datetime-local" name="discharge_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($data->discharge_date)); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Type</label>

                                <select name="discharge_type" class="form-control">
                                    <option value="Normal" <?php echo ($data->discharge_type == "Normal") ? "selected" : ""; ?>>Normal</option>
                                    <option value="Referred" <?php echo ($data->discharge_type == "Referred") ? "selected" : ""; ?>>Referred</option>
                                    <option value="LAMA" <?php echo ($data->discharge_type == "LAMA") ? "selected" : ""; ?>>LAMA</option>
                                    <option value="Death" <?php echo ($data->discharge_type == "Death") ? "selected" : ""; ?>>Death</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Condition <span class="text-danger">*</span></label>

                                <select name="discharge_condition" class="form-control" required>
                                    <option value="">Select Condition</option>
                                    <option value="Stable" <?php echo ($data->discharge_condition == "Stable") ? "selected" : ""; ?>>Stable</option>
                                    <option value="Improved" <?php echo ($data->discharge_condition == "Improved") ? "selected" : ""; ?>>Improved</option>
                                    <option value="Critical" <?php echo ($data->discharge_condition == "Critical") ? "selected" : ""; ?>>Critical</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Follow-up Date</label>

                                <input type="date" name="follow_up_date" class="form-control" value="<?php echo !empty($data->follow_up_date) ? $data->follow_up_date : ""; ?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Final Diagnosis</label>

                                <textarea name="diagnosis" class="form-control" rows="3"><?php echo htmlspecialchars($data->diagnosis ?? ""); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Treatment Summary</label>

                                <textarea name="treatment_summary" class="form-control" rows="3"><?php echo htmlspecialchars($data->treatment_summary ?? ""); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Discharge Advice</label>

                                <textarea name="advice" class="form-control" rows="3"><?php echo htmlspecialchars($data->advice ?? ""); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Notes</label>

                                <textarea name="notes" class="form-control" rows="3"><?php echo htmlspecialchars($data->notes ?? ""); ?></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="text-right">
                        <a href="discharge_view.php?id=<?php echo $discharge_id; ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_discharge" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Discharge
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <?php require_once "../component/footer.php"; ?>
</div>