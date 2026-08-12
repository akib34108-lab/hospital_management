<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Appointment</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="add-appointment.php" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Appointment</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table datatable mb-0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Patient</th>
                                <th>Serial No</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Doctor</th>
                                <th>Problem Details</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                // Fetch department from the database
                                if(isset($_GET['page']) && is_numeric($_GET['page'])){
                                    $page = (int)$_GET['page'];
                                } else {
                                    $page = 1;
                                }
                                $appointment = $crud->common_query("SELECT appointments.*, patients.name as patient_name, schedules.start_time, schedules.end_time, doctors.name as doctor_name FROM `appointments` join patients on patients.id=appointments.patient_id join doctors on doctors.id=appointments.doctor_id join schedules on schedules.id=appointments.app_schedule_id WHERE appointments.deleted_at is null order by appointments.id desc LIMIT 10 OFFSET ".(($page-1)*10));
                                
                                if($appointment['status']){
                                foreach ($appointment['data'] as $i => $appointment) { ?>
                                <td><?= $page*10 + $i + 1 ?></td>
                                <td><?= $appointment->patient_name ?></td>
                                <td><?= $appointment->serial_no ?></td>
                                <td><?= $appointment->appointment_date ?></td>
                                <td><?= $appointment->start_time ?> - <?= $appointment->end_time ?></td>
                                <td><?= $appointment->doctor_name ?></td>
                                <td><?= $appointment->note ?></td>
                                <td>
                                    <?php
                                        if($appointment->status == 1) {
                                            echo '<span class="badge bg-warning">Pending</span>';
                                        } elseif ($appointment->status == 2) {
                                            echo '<span class="badge bg-success">Accepted</span>';
                                        } elseif ($appointment->status == 3) {
                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                        }
                                    ?>
                                </td>                                                                
                                
                                <td class="text-center">
                                    <a href="<?= $base_url ?>appointment/delete_appoint.php?id=<?= $appointment->id ?>" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <?php require_once "../component/footer.php" ?>