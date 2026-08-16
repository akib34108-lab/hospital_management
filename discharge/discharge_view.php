<?php

require_once "../component/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$discharge_id = intval($_GET['id']);

$discharge = $crud->common_query("
    SELECT 
        d.*,
        p.name AS patient_name,
        p.gender,
        p.age,
        p.blood_group,
        p.phone,
        p.email,
        pa.admission_no
    FROM discharges d
    LEFT JOIN patients p ON p.id = d.patient_id
    LEFT JOIN patient_admissions pa ON pa.id = d.admission_id
    WHERE d.discharge_id = $discharge_id
    LIMIT 1
");

require_once "../component/header.php";
require_once "../component/sidebar.php";

?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Discharge Details</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="discharge.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if ($discharge["status"]) { ?>

            <?php $data = $discharge["data"][0]; ?>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Information</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="200">Patient Name</th>
                            <td><?php echo htmlspecialchars($data->patient_name); ?></td>
                        </tr>

                        <tr>
                            <th>Patient ID</th>
                            <td><?php echo htmlspecialchars($data->patient_id); ?></td>
                        </tr>

                        <tr>
                            <th>Admission No</th>
                            <td><?php echo htmlspecialchars($data->admission_no); ?></td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlspecialchars($data->gender); ?></td>
                        </tr>

                        <tr>
                            <th>Age</th>
                            <td><?php echo htmlspecialchars($data->age); ?></td>
                        </tr>

                        <tr>
                            <th>Blood Group</th>
                            <td><?php echo htmlspecialchars($data->blood_group); ?></td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($data->phone); ?></td>
                        </tr>

                    </table>

                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Discharge Information</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="200">Discharge ID</th>
                            <td><?php echo htmlspecialchars($data->discharge_id); ?></td>
                        </tr>

                        <tr>
                            <th>Doctor ID</th>
                            <td><?php echo $data->doctor_id ? htmlspecialchars($data->doctor_id) : "N/A"; ?></td>
                        </tr>

                        <tr>
                            <th>Discharge Date</th>
                            <td><?php echo date("d M Y, h:i A", strtotime($data->discharge_date)); ?></td>
                        </tr>

                        <tr>
                            <th>Discharge Type</th>
                            <td><?php echo htmlspecialchars($data->discharge_type); ?></td>
                        </tr>

                        <tr>
                            <th>Discharge Condition</th>
                            <td><?php echo htmlspecialchars($data->discharge_condition); ?></td>
                        </tr>

                        <tr>
                            <th>Follow-up Date</th>
                            <td>
                                <?php echo !empty($data->follow_up_date) ? date("d M Y", strtotime($data->follow_up_date)) : "N/A"; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Diagnosis</th>
                            <td><?php echo nl2br(htmlspecialchars($data->diagnosis)); ?></td>
                        </tr>

                        <tr>
                            <th>Treatment Summary</th>
                            <td><?php echo nl2br(htmlspecialchars($data->treatment_summary)); ?></td>
                        </tr>

                        <tr>
                            <th>Discharge Advice</th>
                            <td><?php echo nl2br(htmlspecialchars($data->advice)); ?></td>
                        </tr>

                        <tr>
                            <th>Notes</th>
                            <td><?php echo nl2br(htmlspecialchars($data->notes)); ?></td>
                        </tr>

                    </table>

                </div>
            </div>


            <div class="text-right mb-4">

                <a href="edit_discharge.php?id=<?php echo $data->discharge_id; ?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>

                <a href="discharge_invoice.php?id=<?php echo $data->discharge_id; ?>" class="btn btn-info">
                    <i class="fa fa-file-text-o"></i> Invoice
                </a>

            </div>

        <?php } else { ?>

            <div class="alert alert-warning">
                Discharge record not found.
            </div>

        <?php } ?>

    </div>

    <?php require_once "../component/footer.php"; ?>
</div>