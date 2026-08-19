<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; ?>
<?php
    // get last admission number
    $query = "SELECT count(*) as total FROM rooms ORDER BY id DESC";
    $result = $crud->common_query($query);
    $last_admission_no = str_pad($result['data'][0]->total + 1 ?? '0001', 4, '0', STR_PAD_LEFT);
    $admission_no = "RM-" . $last_admission_no;
?>
        <div class="page-wrapper">
            <div class="content">
                <h4 class="page-title">Add Room</h4>
                <form action="<?= $base_url; ?>ward/rooms/store_room.php" method="POST" class="p-4">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="room_number">Room ID</label>
                                        <input class="form-control" type="text" id="room_number" name="room_number" value="<?= $admission_no; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
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
                                            <?php   }
                                            } else { ?>
                                            <option value="">No patients available</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
							        <div class="form-group">
								        <label for="room_type">Room Type</label>
                                        <select class="form-control" id="room_type" name="room_type" required>
                                            <option value="">Select Room Type</option>
                                            <option value="1">General</option>
                                            <option value="2">Semi-private</option>
                                            <option value="3">Private</option>
                                            <option value="4">Deluxe</option>
                                            <option value="5">VIP</option>
                                            <option value="6">ICU</option>
                                            <option value="7">CCU</option>
                                            <option value="8">NICU</option>
                                            <option value="9">Isolation</option>
                                            <option value="10">OT</option>
                                            <option value="11">Observation</option>
                                            <option value="12">Delivery</option>
                                        </select>
							        </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="room_variant">Room Variant</label>
                                        <select class="form-control" id="room_variant" name="room_variant" required>
                                            <option value="">Select Room Variant</option>
                                            <option value="1">General</option>
                                            <option value="2">AC</option>
                                            <option value="3">Non-AC</option>
                                            <option value="4">Single Cabin</option>
                                            <option value="5">Double Cabin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="floor">Floor</label>
                                        <select class="form-control" id="floor" name="floor" required>
                                            <option value="">Select Floor</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                            <option value="5">5th</option>
                                            <option value="6">6th</option>
                                            <option value="7">7th</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="available_beds">Number of Beds</label>
                                        <input class="form-control" type="text" id="available_beds" name="available_beds" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="room_charge">Charge Per Day(taka)</label>
                                        <input class="form-control" type="text" id="room_charge" name="room_charge" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="1">Available</option>
                                            <option value="2">Occupied</option>
                                            <option value="3">Under Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="m-t-20 text-center">
                                        <button class="btn btn-primary submit-btn">Create Room</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <h4>Room Information</h4>
                            <table border="1" class="table table-bordered" style="width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Floor</th>
                                        <th>Room Type</th>
                                        <th>Available Beds</th>
                                        <th>Charge Per Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">1st</td>
                                        <td>General</td>
                                        <td>40</td>
                                        <td>1,200৳</td>
                                    </tr>
                                    <tr>
                                        <td>Semi-private</td>
                                        <td>20</td>
                                        <td>2,000৳</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">2nd</td>
                                        <td>Private</td>
                                        <td>10</td>
                                        <td>3,500৳</td>
                                    </tr>
                                    <tr>
                                        <td>Deluxe</td>
                                        <td>5</td>
                                        <td>5,000৳</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">3rd</td>
                                        <td>VIP</td>
                                        <td>2</td>
                                        <td>10,000৳</td>
                                    </tr>
                                    <tr>
                                        <td>ICU</td>
                                        <td>5</td>
                                        <td>15,000৳</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">4th</td>
                                        <td>CCU</td>
                                        <td>3</td>
                                        <td>20,000৳</td>
                                    </tr>
                                    <tr>
                                        <td>NICU</td>
                                        <td>4</td>
                                        <td>25,000৳</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">5th</td>
                                        <td>Isolation</td>
                                        <td>6</td>
                                        <td>30,000৳</td>
                                    </tr>
                                    <tr>
                                        <td>OT</td>
                                        <td>2</td>
                                        <td>50,000৳</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">6th</td>
                                        <td>Observation</td>
                                        <td>8</td>
                                        <td>5,000৳</td>
                                    </tr>
                                    <tr>    
                                        <td>Delivery</td>
                                        <td>3</td>
                                        <td>7,500৳</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php require_once "../../component/footer.php"; ?>
