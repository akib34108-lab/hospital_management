<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->
<?php
$doctors_result = $crud->common_select(
    "doctors",
    "*",
    [],
    "AND",
    "name",
    "ASC"
);
$doctors = $doctors_result["data"] ?? [];
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Add Appointment</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="<?= $base_url; ?>appointment/store_appointment.php" method="post">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                
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
                             <span class="text-danger">Click on the row to select the patient</span>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Doctor</label><br>
                                <select class="form-control" name="doctor_id" id="doctor_id">
                                    <option value=""> Select Doctor </option>
                                    <?php
                                        if (!empty($doctors)) { ?>
                                        <?php foreach ($doctors as $doc) { ?>

                                            <option value="<?= (int)$doc->id ?>">
                                                <?= htmlspecialchars($doc->name) ?>
                                            </option>

                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="cal-icon">
                                    <input onblur="getDoctorSchedule(this.value)" type="date" name="appointment_date"  class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Schedule</label>
                                <div class="time-icon">
                                    <select onchange="getSerial(this)" class="form-control" name="schedule_id" id="schedule_id">
                                        <option value=""> Select Schedule </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Serial No</label>
                                <input class="form-control" name="serial_no" type="text">
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <label>Problem Details</label>
                                <textarea cols="30" rows="4" name="problem" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="display-block">Appointment Status</label>
                            <select class="form-control" name="status">
                                <option value="1">Pending</option>
                                <option value="2">Accepted</option>
                                <option value="3">Cancelled</option>
                            </select>
                        </div>
                        <div class="m-t-20 text-center">
                            <button class="btn btn-primary submit-btn">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>          
<?php require_once "../component/footer.php" ?> 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.querySelector('input[name="appointment_date"]');

        function getDoctorSchedule(app_date) {
            const doctorId = doctorSelect.value;
            if (!doctorId || !app_date) {
                return;
            }

            const formData = new FormData();
            formData.append('doctor_id', doctorId);
            formData.append('appointment_date', app_date);

            fetch('get_doctor_schedule.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                const scheduleSelect = document.getElementById('schedule_id');
                scheduleSelect.innerHTML = data;
            })
            .catch(error => {
                console.error('Error fetching doctor schedule:', error);
            });
        }

        if (dateInput) {
            dateInput.addEventListener('change', function () {
                getDoctorSchedule(this.value);
            });
            dateInput.addEventListener('blur', function () {
                getDoctorSchedule(this.value);
            });
        }

        if (doctorSelect) {
            doctorSelect.addEventListener('change', function () {
                if (dateInput && dateInput.value) {
                    getDoctorSchedule(dateInput.value);
                }
            });
        }
    });

    function getSerial(scheduleSelect) {
        const selectedOption = scheduleSelect.options[scheduleSelect.selectedIndex];
        const remainingAppointments = selectedOption.getAttribute('data-remaining');
        const serialInput = document.querySelector('input[name="serial_no"]');

        if (remainingAppointments !== null) {
            serialInput.value = remainingAppointments;
        } else {
            serialInput.value = '';
        }
    }

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
</script>