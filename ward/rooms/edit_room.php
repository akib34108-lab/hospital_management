<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php";

  $id = $_GET['id'];
  $room = $crud->common_select("rooms", "*", ['id' => $id]);
  if (!$room['status'] || empty($room['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Room not found.');
    echo "<script>window.location.href = '".$base_url."ward/rooms/rooms.php';</script>";
    exit;
  }

  $room = $room['data'][0];

?>
        <div class="page-wrapper">
            <div class="content">
                <h4 class="page-title">Edit Room</h4>
                <form action="<?= $base_url; ?>ward/rooms/update_room.php?id=<?= $room->id ?>" method="POST" class="p-4">
                    <div class="row">
                        <div class="col-sm-3 offset-sm-2">
                            <div class="form-group">
                                <label for="room_number">Room ID</label>
                                <input class="form-control" type="text" id="room_number" name="room_number" value="<?= $room->room_number; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="patient_id">Select Patient</label>
                                <select name="patient_id" class="form-select form-control">
                                    <option value="">Select Patient</option>
                                    <?php
                                    // Fetch all patients for the dropdown
                                    $patients = $crud->common_select('patient_admissions');
                                    if($patients['status']){
                                        foreach($patients['data'] as $patient) { ?>
                                        <option value="<?php echo $patient->patient_id; ?>"><?php echo htmlspecialchars($patient->admission_no); ?></option>
                                    <?php  }
                                    } else { ?>
                                    <option value="">No patients available</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-3">
							<div class="form-group">
								<label for="room_type">Room Type</label>
                                <select class="form-control" id="room_type" name="room_type" required>
                                            <option value="">Select Room Type</option>
                                            <option value="1" <?= $room->room_type == 1 ? 'selected' : '' ?>>General</option>
                                            <option value="2" <?= $room->room_type == 2 ? 'selected' : '' ?>>Semi-Private</option>
                                            <option value="3" <?= $room->room_type == 3 ? 'selected' : '' ?>>Private</option>
                                            <option value="4" <?= $room->room_type == 4 ? 'selected' : '' ?>>Deluxe</option>
                                            <option value="5" <?= $room->room_type == 5 ? 'selected' : '' ?>>VIP</option>
                                            <option value="6" <?= $room->room_type == 6 ? 'selected' : '' ?>>ICU</option>
                                            <option value="7" <?= $room->room_type == 7 ? 'selected' : '' ?>>CCU</option>
                                            <option value="8" <?= $room->room_type == 8 ? 'selected' : '' ?>>NICU</option>
                                            <option value="9" <?= $room->room_type == 9 ? 'selected' : '' ?>>Isolation</option>
                                            <option value="10" <?= $room->room_type == 10 ? 'selected' : '' ?>>OT</option>
                                            <option value="11" <?= $room->room_type == 11 ? 'selected' : '' ?>>Observation</option>
                                            <option value="12" <?= $room->room_type == 12 ? 'selected' : '' ?>>Delivery</option>
                                </select>
							</div>
                        </div>
                        <div class="col-sm-3 offset-sm-2">
                            <div class="form-group">
                                        <label for="room_variant">Room Variant</label>
                                        <select class="form-control" id="room_variant" name="room_variant" required>
                                            <option value="">Select Room Variant</option>
                                            <option value="1" <?= $room->room_variant == 1 ? 'selected' : '' ?>>General</option>
                                            <option value="2" <?= $room->room_variant == 2 ? 'selected' : '' ?>>AC</option>
                                            <option value="3" <?= $room->room_variant == 3 ? 'selected' : '' ?>>Non AC</option>
                                            <option value="4" <?= $room->room_variant == 4 ? 'selected' : '' ?>>Single Cabin</option>
                                            <option value="5" <?= $room->room_variant == 5 ? 'selected' : '' ?>>Double Cabin</option>
                                        </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                        <label for="floor">Floor</label>
                                        <select class="form-control" id="floor" name="floor" required>
                                            <option value="">Select Floor</option>
                                            <option value="1" <?= $room->floor == 1 ? 'selected' : '' ?>>1st</option>
                                            <option value="2" <?= $room->floor == 2 ? 'selected' : '' ?>>2nd</option>
                                            <option value="3" <?= $room->floor == 3 ? 'selected' : '' ?>>3rd</option>
                                            <option value="4" <?= $room->floor == 4 ? 'selected' : '' ?>>4th</option>
                                            <option value="5" <?= $room->floor == 5 ? 'selected' : '' ?>>5th</option>
                                            <option value="6" <?= $room->floor == 6 ? 'selected' : '' ?>>6th</option>
                                            <option value="7" <?= $room->floor == 7 ? 'selected' : '' ?>>7th</option>
                                        </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                        <label for="available_beds">Number of Beds</label>
                                        <input class="form-control" type="text" id="available_beds" name="available_beds" value="<?= $room->available_beds ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-3 offset-sm-2">
                            <div class="form-group">
                                        <label for="room_charge">Charge Per Day(taka)</label>
                                        <input class="form-control" type="text" id="room_charge" name="room_charge" value="<?= $room->room_charge ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="1" <?= $room->status == 1 ? 'selected' : '' ?>>Available</option>
                                            <option value="2" <?= $room->status == 2 ? 'selected' : '' ?>>Occupied</option>
                                            <option value="3" <?= $room->status == 3 ? 'selected' : '' ?>>Under Maintenance</option>
                                        </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary submit-btn">Update Room</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php require_once "../../component/footer.php"; ?>
