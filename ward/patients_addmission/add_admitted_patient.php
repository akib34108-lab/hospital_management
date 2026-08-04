<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; ?>
<?php
    // get last admission number
    $query = "SELECT count(*) as total FROM patient_admissions ORDER BY id DESC";
    $result = $crud->common_query($query);
    $last_admission_no = str_pad($result['data'][0]->total + 1 ?? '0001', 4, '0', STR_PAD_LEFT);
    $admission_no = "ADM-" . $last_admission_no;
?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-title">Add Admitted Patient</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="<?= $base_url; ?>ward/patients_addmission/store_admitted_patient.php" method="POST" class="p-4">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="admission_number">Admission Number</label>
                                                <input class="form-control" type="text" id="admission_number" name="admission_no" value="<?= $admission_no; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="patient_id">Patient Contact</label>
                                                <input onkeyup="getPatientDetails()" class="form-control" type="text" id="phone" name="phone" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name">Patient Name</label>
                                                <input class="form-control" type="text" id="name" name="name" required>
                                                <input type="hidden" id="patient_id" name="patient_id">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="age">Patient Age</label>
                                                <input class="form-control" type="text" id="age" name="age" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group gender-select">
                                                <label>Gender:</label>
                                                <select name="gender" id="gender" class="form-control" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                    <option value="3">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <!-- here show the patient details under this patient contact number -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                            </tr>
                                        </thead>
                                        <tbody id="patient_details">
                                            <tr>
                                                <td colspan="3" class="text-center">No patient details found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                                            <option value="">Select Doctor</option>
                                            <?php
                                                $doctors = $crud->common_select("doctors", "*", [], "AND", "name", "ASC");
                                                if ($doctors['status']) {
                                                    foreach ($doctors['data'] as $doctor) {
                                                        echo "<option value='{$doctor->id}'>{$doctor->name}</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="room_id">Room</label>
                                        <select class="form-control" onchange="getBedOptions(this.value)" id="room_id" name="room_id" required>
                                            <option value="">Select Room</option>
                                            <?php
                                                $rooms = $crud->common_select("rooms", "*", [], "AND", "room_number", "ASC");
                                                if ($rooms['status']) {
                                                    foreach ($rooms['data'] as $room) {
                                                        echo "<option value='{$room->id}'>{$room->room_number}</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="bed_id">Bed</label>
                                        <select class="form-control" id="bed_id" name="bed_id" required>
                                            <option value="">Select Bed</option>
                                            <?php
                                                $beds = $crud->common_select("beds", "*", [], "AND", "bed_number", "ASC");
                                                if ($beds['status']) {
                                                    foreach ($beds['data'] as $bed) {
                                            ?>
                                                    <option <?php if($bed->is_occupied) echo "disabled"; ?> data-occupied='<?= ($bed->is_occupied ? "1" : "0") ?>' data-room-id='<?= $bed->room_id ?>' value='<?= $bed->id ?>'><?= $bed->bed_number ?> (<?= ($bed->is_occupied ? 'Occupied' : 'Available') ?>)</option>
                                            <?php        }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="admission_date">Admission Date</label>
                                        <input class="form-control" type="date" id="admission_date" name="admission_date" required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="admission_time">Admission Time</label>
                                        <input class="form-control" type="time" id="admission_time" name="admission_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="display-block">Admission Status</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="product_active" value="1" checked>
                                            <label class="form-check-label" for="product_active">
                                            Active
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="product_inactive" value="0">
                                            <label class="form-check-label" for="product_inactive">
                                            Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
							<div class="form-group">
								<label for="reason">Reason for Admission</label>
								<textarea class="form-control" id="reason" name="reason" required></textarea>
							</div>
                            
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary submit-btn">Add Admitted Patient</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once "../../component/footer.php"; ?>
<script>
    function getPatientDetails() {
        AddPatientIdToForm('','','',''); // Clear the form fields when fetching new details
        var phone = document.getElementById("phone").value;
        if (phone.length >= 10) {
            fetch(`<?= $base_url; ?>ward/patients_addmission/get_patient_details.php?phone=${phone}`)
                .then(response => response.json())
                .then(data => {
                    var patientDetails = document.getElementById("patient_details");
                    if (data.status) {
                        patientDetails.innerHTML = `
                            <tr onclick="AddPatientIdToForm(${data.data.id}, '${data.data.name}', ${data.data.age}, '${data.data.gender}')" style="cursor: pointer;">
                                <td>${data.data.name}</td>
                                <td>${data.data.age}</td>
                                <td>${data.data.gender}</td>
                            </tr>
                        `;
                    } else {
                        patientDetails.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center">No patient details found</td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching patient details:', error);
                });
        } else {
            document.getElementById("patient_details").innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">No patient details found</td>
                </tr>
            `;
        }
    }

    function AddPatientIdToForm(patientId, name, age, gender) {
        document.getElementById("patient_id").value = patientId;
        document.getElementById("name").value = name;
        document.getElementById("age").value = age;
        document.getElementById("gender").value = gender;
    }

    function getBedOptions(roomId) {
        var bedSelect = document.getElementById("bed_id");
        var bedOptions = bedSelect.options;
        for (var i = 0; i < bedOptions.length; i++) {
            var option = bedOptions[i];
            if (option.value === "") continue; // Skip the default "Select Bed" option
            if (option.getAttribute("data-room-id") === roomId) {
                option.style.display = "block";
                option.disabled = false;
            } else {
                option.style.display = "none";
                option.disabled = true;
            }
        }
        // Reset the selected bed when changing rooms
        bedSelect.value = "";
    }
</script>