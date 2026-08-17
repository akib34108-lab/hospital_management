<?php

require_once "../../component/connection.php";
require_once "../../component/header.php";
require_once "../../component/sidebar.php";

$discharges = $crud->common_select("discharges", "*", [], "AND", "discharge_id", "DESC");

?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Discharge</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="add_discharge.php" class="btn btn-primary btn-rounded">
                    <i class="fa fa-plus"></i> Add Discharge
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">Discharged Patients</h4>
                        <p class="text-muted mb-0">Manage patient discharge records</p>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient ID</th>
                                        <th>Admission ID</th>
                                        <th>Doctor ID</th>
                                        <th>Discharge Date</th>
                                        <th>Type</th>
                                        <th>Condition</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php if ($discharges["status"]) { ?>

                                    <?php $sl = 1; ?>

                                    <?php foreach ($discharges["data"] as $discharge) { ?>

                                        <tr>
                                            <td><?php echo $sl++; ?></td>

                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo htmlspecialchars($discharge->patient_id); ?>
                                                </span>
                                            </td>

                                            <td><?php echo htmlspecialchars($discharge->admission_id); ?></td>

                                            <td>
                                                <?php echo $discharge->doctor_id ? htmlspecialchars($discharge->doctor_id) : "N/A"; ?>
                                            </td>

                                            <td>
                                                <?php echo date("d M Y, h:i A", strtotime($discharge->discharge_date)); ?>
                                            </td>

                                            <td>
                                                <?php
                                                $type = $discharge->discharge_type;

                                                if ($type == "Normal") {
                                                    echo '<span class="badge badge-success">Normal</span>';
                                                } elseif ($type == "Referred") {
                                                    echo '<span class="badge badge-warning">Referred</span>';
                                                } elseif ($type == "LAMA") {
                                                    echo '<span class="badge badge-danger">LAMA</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary">' . htmlspecialchars($type) . '</span>';
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                $condition = $discharge->discharge_condition;

                                                if ($condition == "Stable") {
                                                    echo '<span class="badge badge-success">Stable</span>';
                                                } elseif ($condition == "Improved") {
                                                    echo '<span class="badge badge-info">Improved</span>';
                                                } elseif ($condition == "Critical") {
                                                    echo '<span class="badge badge-danger">Critical</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary">' . htmlspecialchars($condition ?: "N/A") . '</span>';
                                                }
                                                ?>
                                            </td>

                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="discharge_view.php?id=<?php echo $discharge->discharge_id; ?>">
                                                            <i class="fa fa-eye m-r-5"></i> View
                                                        </a>

                                                        <a class="dropdown-item" href="edit_discharge.php?id=<?php echo $discharge->discharge_id; ?>">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>

                                                        <a class="dropdown-item" href="discharge_invoice.php?id=<?php echo $discharge->discharge_id; ?>">
                                                            <i class="fa fa-file-text-o m-r-5"></i> Invoice
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> No discharge records found.
                                        </td>
                                    </tr>

                                <?php } ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php require_once "../../component/footer.php" ?> 
</div>