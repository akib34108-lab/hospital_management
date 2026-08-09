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
                                        <th>ID</th>
										<th>Appoint ID</th>
										<th>Serial No</th>
										<th>Doctors ID</th>
										<th>Date</th>
										<th>Time</th>
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
                                        $appointment = $crud->common_select("appointments",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($appointment['status']){
                                        foreach ($appointment['data'] as $appointment) { ?>
                                        <td><?= $appointment->id ?></td>
                                        <td><?= $appointment->patient_id ?></td>
                                        <td><?= $appointment->doctor_id ?></td>
                                        <td><?= $appointment->appointment_date ?></td>
                                        <td><?= $appointment->appointment_time ?></td>
                                        <td><?= $appointment->serial_no ?></td>
                                        <td><?= $appointment->problem ?></td>
                                        <td><?= $appointment->status ?></td>                                                                
                                        
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>appointment/edit_appoint.php?id=<?= $appointment->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a href="<?= $base_url ?>patients/delete_appoint.php?id=<?= $appointment->id ?>" class="btn btn-sm btn-danger">Delete</a>
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