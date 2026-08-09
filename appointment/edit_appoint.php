<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; 

$id = $_GET['id'];
  $Editappoint = $crud->common_select("appointments", "*", ['id' => $id]);
  if (!$Editappoint['status'] || empty($Editappoint['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Shift not found.');
    echo "<script>window.location.href = '".$base_url."appointment/appointment_list.php';</script>";
    exit;
  }

  $Editappoint = $Editappoint['data'][0];
?>

<div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Appointment</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= $base_url; ?>appointment/store_appointment.php" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
										<label>ID</label>
										<input class="form-control" type="text" value="APT-0001" readonly="" value="<?= $EditPatient->id ?>">
									</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
										<label>Appointment ID</label>
										<input class="form-control" type="text" value="<?= $EditPatient->appointment_date ?>">
									</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
										<label>Serial No</label>
										<input class="form-control" type="text" value="<?= $EditPatient->serial_no ?>">
									</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Doctor ID</label><br>
                                        <select class="form-control" style="height:40px; border:none; padding:12px;">
											<option>Dr.636</option>
											<option>Dr.644</option>
                                            <option>Dr.674</option>
                                            <option>Dr.684</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="cal-icon">
                                                <input type="date" class="form-control datetimepicker" value="<?= $EditPatient->appointment_date ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <div class="time-icon">
                                                <input type="time" class="form-control" id="datetimepicker3" value="<?= $EditPatient->appointment_time ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label>Problem Details</label>
                                        <textarea cols="30" rows="4" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="display-block">Appointment Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="product_active" value="option1" <?php echo ($EditPatient->status == 1) ? 'checked' : ''; ?> required> Active
                                        <label class="form-check-label" for="product_active">
                                        Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="product_inactive" value="option2"<?php echo ($EditPatient->status == 2) ? 'checked' : ''; ?> required> Inactive
                                        <label class="form-check-label" for="product_inactive">
                                        Inactive
                                        </label>
                                    </div>
                                </div>
                                <div class="m-t-20 text-center">
                                    <button class="btn btn-primary submit-btn">Create Appointment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
<?php require_once "../component/footer.php" ?>