<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Patients</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="add-patient.php" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Patient</a>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-border table-striped custom-table datatable mb-0">
								<thead>
									<tr>
                                        <th>ID</th>
										<th>Name</th>
										<th>Gender</th>
										<th>Age</th>
										<th>Blood Group</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Address</th>
										<th>Emergency contact</th>
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
                                        $patients = $crud->common_select("patients",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($patients['status']){
                                        foreach ($patients['data'] as $patient) { ?>
                                        <td><?= $patient->id ?></td>
                                        <td><?= $patient->name ?></td>
                                        <td><?= $patient->gender ?></td>
                                        <td><?= $patient->age ?></td>
                                        <td><?= $patient->blood_group ?></td>
                                        <td><?= $patient->phone ?></td>
                                        <td><?= $patient->email ?></td>
                                        <td><?= $patient->address ?></td>
                                        <td><?= $patient->emergency_contact ?></td>
                                        
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>patients/patient_edit.php?id=<?= $patient->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a href="<?= $base_url ?>patients/patient_delete.php?id=<?= $patient->id ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
   <?php require_once "../component/footer.php" ?>