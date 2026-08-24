<?php require_once "../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../component/sidebar.php"; ?>


    
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="page-title font-weight-bold">Doctor Information</h4>
                    </div>
                    <div class="col-lg-4 text-right">
                        <a href="doctors.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#009efb;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="<?= $base_url; ?>doctors/store_doctor.php" method="POST" enctype="multipart/form-data" class="p-4">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Doctor Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Phone <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone" placeholder="Ex: 01711111111" required>
                                    </div>
                                </div>
                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Confirm Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="confirm_password" required>
                                    </div>
                                </div> -->
                                <div class="col-sm-4">
                                    <div class="form-group gender-select">
                                        <label class="display-block font-weight-bold">Gender <span class="text-danger">*</span></label>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="gender" class="form-check-input" value="1" <?php echo (isset($doctor) && $doctor->gender == 1) ? 'checked' : ''; ?> required> Male
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="gender" class="form-check-input" value="2" <?php echo (isset($doctor) && $doctor->gender == 2) ? 'checked' : ''; ?> required> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
										<label class="font-weight-bold">Address <span class="text-danger">*</span></label>
										<input type="text" class="form-control " name="address" required>
									</div>
                                </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Qualification</label>
                                    <input class="form-control" type="text" name="qualification" placeholder="Ex: MBBS, FCPS, BDS">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Experience <span>(Years)</span></label>
                                    <input class="form-control" type="number" name="experience" placeholder="Ex: 8">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Department</label>
                                    <select name="department_id" class="form-select form-control">
                                        <option value="">Select Department</option>
                                        <?php
                                        // Fetch all departments for the dropdown
                                    $departments = $crud->common_select('departments');
                                    if($departments['status']){
                                        foreach($departments['data'] as $department) { ?>
                                        <option value="<?php echo $department->id; ?>"><?php echo htmlspecialchars($department->department_name); ?></option>
                                    <?php   }
                                    } else { ?>
                                    <option value="">No departments available</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Designation</label>
                                <select name="designation_id" class="form-select form-control">
                                    <option value="">Select Designation</option>
                                    <?php
                                    // Fetch all designations for the dropdown
                                    $designations = $crud->common_select('designation');
                                    if($designations['status']){
                                        foreach($designations['data'] as $designation) { ?>
                                        <option value="<?php echo $designation->id; ?>"><?php echo htmlspecialchars($designation->designation_name); ?></option>
                                    <?php   }
                                    } else { ?>
                                    <option value="">No designations available</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Shift</label>
                                <select name="shift_id" class="form-select form-control">
                                    <option value="">Select Shift</option>
                                    <?php
                                    // Fetch all shifts for the dropdown
                                    $shifts = $crud->common_select('shift');
                                    if($shifts['status']){
                                        foreach($shifts['data'] as $shift) { ?>
                                        <option value="<?php echo $shift->id; ?>"><?php echo htmlspecialchars($shift->shift_name); ?></option>
                                    <?php   }
                                    } else { ?>
                                    <option value="">No shifts available</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="display-block font-weight-bold">Status</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="status" id="doctor_active" value="Active">
										<label class="form-check-label" for="doctor_active">
										Active
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="doctor_inactive" value="Inactive">
									<label class="form-check-label" for="doctor_inactive">
									Inactive
									</label>
								</div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Specialization</label>
                                <textarea class="form-control" rows="3" cols="30" name="specialization"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="m-t-20 text-center">
                                <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 35px;background:#009efb;color:#fff;border:1px solid #009efb;border-radius:7px;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);"><span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-check" style="font-size:12px;"></i></span> Create Doctor</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php require_once "../component/footer.php"; ?>
