<?php require_once "component/header.php"; ?>
<!-- sidebar -->
<?php require_once "component/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"><a href="doctors/doctors.php">
                        <div class="dash-widget"  style="border: 1px solid #04bef6;">
							<span class="dash-widget-bg1"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
							<div class="dash-widget-info text-right">
								<h3>
									<?php
										$doctors = $crud->common_select("doctors",'*',[],'AND','id','ASC');
										if ($doctors['status']) {
											echo count($doctors['data']);
										} ?>
								</h3>
								<span class="widget-title1">Doctors <i class="fa fa-check" aria-hidden="true"></i></span>
							</div>
                        </div></a>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"><a href="patients/patients.php">
                        <div class="dash-widget" style="border: 1px solid #4df604;">
                            <span class="dash-widget-bg2"><i class="fa fa-user-o"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>
                                    <?php
                                        $patients = $crud->common_select("patients",'*',[],'AND','id','ASC');
                                        if ($patients['status']) {
                                            echo count($patients['data']);
                                        }
                                    ?>
                                </h3>
                                <span class="widget-title2">Patients <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div></a>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"><a href="appointment/appointment_list.php">
                        <div class="dash-widget"  style="border: 1px solid #81907b;">
                            <span class="dash-widget-bg3"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>
                                    <?php
                                        $appointments = $crud->common_select("appointments",'*',[],'AND','id','ASC');
                                        if ($appointments['status']) {
                                            echo count($appointments['data']);
                                        }
                                    ?>
                                </h3>
                                <span class="widget-title3">Appointments <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div></a>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"><a href="blood_bank/donor/donor.php">
                        <div class="dash-widget"  style="border: 1px solid #c0ba19;">
                            <span class="dash-widget-bg4"><i class="fa fa-tint" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>
                                    <?php
                                        $donors = $crud->common_select("donor",'*',[],'AND','id','ASC');
                                        if ($donors['status']) {
                                            echo count($donors['data']);
                                        }
                                    ?>
                                </h3>
                                <span class="widget-title4">Blood Donors <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div></a>
                </div>
				<div class="row">
					<div class="col-sm-8 offset-sm-2">
						<?php
						$sql = "SELECT COUNT(*) AS total FROM patients WHERE deleted_at IS NULL";
						$result = $crud->conn->query($sql);
						$row = $result->fetch_object();
						$total_patients = $row->total;

						$sql = "SELECT COUNT(*) AS total FROM patient_admissions WHERE discharge_date IS NULL AND deleted_at IS NULL";
						$result = $crud->conn->query($sql);
						$row = $result->fetch_object();
						$total_admitted = $row->total;
						
						$sql = "SELECT COUNT(*) AS total FROM doctors WHERE deleted_at IS NULL";
						$result = $crud->conn->query($sql);
						$row = $result->fetch_object();
						$total_doctors = $row->total;
						
						$sql = "SELECT COUNT(*) AS total FROM donor WHERE deleted_at IS NULL";
						$result = $crud->conn->query($sql);
						$row = $result->fetch_object();
						$total_donors = $row->total;
						?>
						<div class="card">
							<div class="card-body">
								<div class="chart-title">
									<h4>Hospital Overview</h4>
								</div>

								<canvas id="donutgraph"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title d-inline-block">New Patients </h4> <a href="patients/patients.php" class="btn btn-primary float-right">View all</a>
							</div>
							<div class="card-block">
								<div class="table-responsive">
									<table class="table mb-0">
										<tbody>
											<?php
											$patients = $crud->common_select("patients",'*',[],'AND','id','ASC',5,0);
											if ($patients['status']) {
												foreach ($patients['data'] as $key => $value) { ?>
											<tr>
												<td style="color: #427996;"><?= $value->name; ?></td>
												<td><?= $value->email; ?></td>
												<td><?= $value->phone; ?></td>
												<td><?= $value->address; ?></td>
											</tr>
											<?php } } ?>
										</tbody>
									</table>
								</div>
								<div class="card-footer text-center bg-white">
                                	<a href="ward/patients_addmission/admitted_patient_list.php" class="text-muted">View Admitted Patients</a>
                            	</div>
							</div>
						</div>
					</div>
                    <div class="col-sm-4">
                        <div class="card member-panel">
							<div class="card-header bg-white">
								<h4 class="card-title mb-0">Doctors</h4>
							</div>
                            <div class="card-body">
                              <?php
							 	$doctors = $crud->common_select("doctors",'*',[],'AND','id','ASC',5,0);
							 	if ($doctors['status']) {
									foreach ($doctors['data'] as $key => $value) {
										?>
										<ul class="contact-list">
											<li>
												<div>
													<div class="float-left user-img m-r-10">
														<a href="profile.html" title="John Doe"><img src="assets/assets/img/user.jpg" alt="" class="w-40 rounded-circle"><span class="status online"></span></a>
													</div>
													<div>
														<span class="contact-name text-ellipsis" style="color: #427996;"><?php echo $value->name; ?></span>
														<span class="contact-date "><?php echo $value->specialization; ?></span>
													</div>
												</div>
											</li>
										</ul>
										<?php
									}}
							  ?>
                            </div>
                            <div class="card-footer text-center bg-white">
                                <a href="doctors/doctors.php" class="text-muted">View all Doctors</a>
                            </div>
                        </div>
                    </div>
				</div>
					
    
<?php require_once "component/footer.php"; ?>
<script>
    var totalPatients = <?= $total_patients ?>;
    var totalAdmitted = <?= $total_admitted ?>;
    var totalDoctors = <?= $total_doctors ?>;
    var totalDonors = <?= $total_donors ?>;
</script>

<script src="<?= $base_url ?>assets/assets/hospital-dashboard.js"></script>