<?php 
require_once "../component/header.php"; 
require_once "../component/sidebar.php"; 

// Direct DB connection
$conn = mysqli_connect("localhost", "root", "", "shifa");

if(!$conn){
    die("DB Connection Failed: " . mysqli_connect_error());
}

$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $day = mysqli_real_escape_string($conn, $_POST['day_of_week']);
    
    // Time format thik kore nicchi: 10:30 AM -> 10:30:00
    $start = date("H:i:s", strtotime($_POST['start_time']));
    $end = date("H:i:s", strtotime($_POST['end_time']));
    
    $qty = mysqli_real_escape_string($conn, $_POST['appointment_qty']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO `schedules` (`doctor_id`, `day_of_week`, `start_time`, `end_time`, `appointment_qty`, `status`) 
            VALUES ('$doctor_id','$day','$start','$end','$qty','$status')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Schedule Added Successfully'); window.location='schedule.php';</script>";
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Add Schedule</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?php echo $msg; ?>
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Doctor <span class="text-danger">*</span></label>
                                <select name="doctor_id" class="select form-control" required>
                                    <option value="">Select Doctor</option>
                                    <?php
                                    // doctors table theke dynamic data
                                    $doctors = mysqli_query($conn, "SELECT id, name FROM doctors");
                                    while($d = mysqli_fetch_assoc($doctors)){
                                        echo "<option value='".$d['id']."'>".$d['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Day of Week <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="select form-control" required>
                                    <option value="">Select Days</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Time <span class="text-danger">*</span></label>
                                <div class='input-group date' id='datetimepicker3'>
                                    <input type="text" class="form-control" name="start_time" required />
                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Time <span class="text-danger">*</span></label>
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type="text" class="form-control" name="end_time" required />
                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Appointment Qty <span class="text-danger">*</span></label>
                                <input type="number" name="appointment_qty" class="form-control" placeholder="10" required />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                                <label class="display-block"> Status</label>
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

                    <div class="m-t-20 text-center">
                        <button type="submit" class="btn btn-primary submit-btn">Create Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#datetimepicker3, #datetimepicker4').datetimepicker({
        format: 'LT' // 12 hour format dekhabe: 10:30 AM
    });
});
</script>

<?php require_once "../component/footer.php"; ?>