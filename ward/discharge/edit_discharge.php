<?php
require_once "../../component/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$id = $_GET['id'];

$discharge = $crud->common_query("
    SELECT d.*, 
    p.name AS patient_name,
    p.phone,
    p.gender,
    p.age,
    pa.admission_no,
    pa.doctor_id AS admission_doctor_id,
    pa.room_id,
    pa.bed_id,
    pa.admission_date,
    pa.admission_time,
    r.room_number,
    r.room_charge
    FROM discharges d
    LEFT JOIN patients p ON p.id = d.patient_id
    LEFT JOIN patient_admissions pa ON pa.id = d.admission_id
    LEFT JOIN rooms r ON r.id = pa.room_id
    WHERE d.discharge_id = $id
");

if (!$discharge["status"]) {
    header("Location: discharge.php");
    exit;
}

$data = $discharge["data"][0];

if (isset($_POST['update_discharge'])) {

    $discharge_date = $_POST['discharge_date'];
    $discharge_type = $_POST['discharge_type'];
    $diagnosis = $_POST['diagnosis'];
    $treatment_summary = $_POST['treatment_summary'];
    $discharge_condition = $_POST['discharge_condition'];
    $advice = $_POST['advice'];
    $follow_up_date = !empty($_POST['follow_up_date']) ? $_POST['follow_up_date'] : null;
    $notes = $_POST['notes'];

    $update = [
        "discharge_date" => $discharge_date,
        "discharge_type" => $discharge_type,
        "diagnosis" => $diagnosis,
        "treatment_summary" => $treatment_summary,
        "discharge_condition" => $discharge_condition,
        "advice" => $advice,
        "follow_up_date" => $follow_up_date,
        "notes" => $notes
    ];

    $result = $crud->common_update(
        "discharges",
        $update,
        ["discharge_id" => $id]
    );

    if ($result["status"]) {

        $crud->common_update(
            "patient_admissions",
            [
                "discharge_date" => date("Y-m-d", strtotime($discharge_date)),
                "discharge_time" => date("H:i:s", strtotime($discharge_date))
            ],
            ["id" => $data->admission_id]
        );

        echo "<script>alert('Discharge updated successfully'); window.location.href='discharge_view.php?id=$id';</script>";
        exit;

    } else {
        $error = $result["message"];
    }
}

require_once "../../component/header.php";
require_once "../../component/sidebar.php";
?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Edit Discharge</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="discharge_view.php?id=<?php echo $id; ?>" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Patient & Admission Information</h4>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <label>Patient Name</label>
                        <input type="text" class="form-control" value="<?php echo $data->patient_name; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Patient ID</label>
                        <input type="text" class="form-control" value="<?php echo $data->patient_id; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Admission No</label>
                        <input type="text" class="form-control" value="<?php echo $data->admission_no; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Doctor ID</label>
                        <input type="text" class="form-control" value="<?php echo $data->doctor_id; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Room No</label>
                        <input type="text" class="form-control" value="<?php echo $data->room_number; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Room Charge / Day</label>
                        <input type="text" class="form-control" value="৳ <?php echo $data->room_charge; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Bed ID</label>
                        <input type="text" class="form-control" value="<?php echo $data->bed_id; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Admission Date</label>
                        <input type="text" class="form-control" value="<?php echo $data->admission_date; ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Admission Time</label>
                        <input type="text" class="form-control" value="<?php echo $data->admission_time; ?>" readonly>
                    </div>

                </div>

            </div>
        </div>

        <form method="POST">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Discharge Information</h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Date</label>
                                <input type="datetime-local" name="discharge_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($data->discharge_date)); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Type</label>
                                <select name="discharge_type" class="form-control">
                                    <option value="Normal" <?php if ($data->discharge_type == "Normal") echo "selected"; ?>>Normal</option>
                                    <option value="Referred" <?php if ($data->discharge_type == "Referred") echo "selected"; ?>>Referred</option>
                                    <option value="LAMA" <?php if ($data->discharge_type == "LAMA") echo "selected"; ?>>LAMA</option>
                                    <option value="Death" <?php if ($data->discharge_type == "Death") echo "selected"; ?>>Death</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Discharge Condition</label>
                                <select name="discharge_condition" class="form-control" required>
                                    <option value="Stable" <?php if ($data->discharge_condition == "Stable") echo "selected"; ?>>Stable</option>
                                    <option value="Improved" <?php if ($data->discharge_condition == "Improved") echo "selected"; ?>>Improved</option>
                                    <option value="Critical" <?php if ($data->discharge_condition == "Critical") echo "selected"; ?>>Critical</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Follow-up Date</label>
                                <input type="date" name="follow_up_date" class="form-control" value="<?php echo $data->follow_up_date; ?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Final Diagnosis</label>
                                <textarea name="diagnosis" class="form-control" rows="3"><?php echo $data->diagnosis; ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Treatment Summary</label>
                                <textarea name="treatment_summary" class="form-control" rows="3"><?php echo $data->treatment_summary; ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Discharge Advice</label>
                                <textarea name="advice" class="form-control" rows="3"><?php echo $data->advice; ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="3"><?php echo $data->notes; ?></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="text-right">
                        <a href="discharge_view.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_discharge" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Discharge
                        </button>
                    </div>

                </div>
            </div>

        </form>

    </div>

    <?php require_once "../../component/footer.php"; ?>

</div>