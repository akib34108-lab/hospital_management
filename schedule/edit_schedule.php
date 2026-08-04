<?php require_once "../component/header.php";?>
<!-- sidebar -->
<?php require_once "../component/sidebar.php";?>

<?php
$id = $_GET['id'];
$msg = "";

// Purono data anbo
$schedule = $crud->common_select("schedules","*",["id" => $id]);
if(!$schedule['status'] || empty($schedule['data'])){
    $_SESSION['message'] = array('danger','Error', 'Schedule not found.');
    echo "<script>window.location.href = '".$base_url."schedule/schedule.php';</script>";
    exit;
}
$schedule = $schedule['data'][0];

// Update korle
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = [
        'doctor_id' => $_POST['doctor_id'],
        'day_of_week' => $_POST['day_of_week'],
        'start_time' => date("H:i:s", strtotime($_POST['start_time'])),
        'end_time' => date("H:i:s", strtotime($_POST['end_time'])),
        'appointment_qty' => $_POST['appointment_qty'],
        'status' => $_POST['status'] // 1 or 0 ashbe
    ];

    $update = $crud->common_update("schedules", $data, ["id" => $id]);

    if($update){
        $_SESSION['message'] = array('success','Success', 'Schedule Updated Successfully');
        echo "<script>window.location.href = '".$base_url."schedule/schedule.php';</script>";
        exit;
    } else {
        $msg = "<div class='alert alert-danger'>Update Failed</div>";
    }
}

// Doctor list anbo
$doctors = $crud->common_select("doctors","*");
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Edit Schedule</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?php echo $msg;?>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?= $schedule->id?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Doctor <span class="text-danger">*</span></label>
                                <select name="doctor_id" class="select form-control" required>
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors['data'] as $d){?>
                                    <option value="<?= $d->id?>" <?= ($d->id == $schedule->doctor_id)? 'selected' : ''?>>
                                        <?= $d->name?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Day of Week <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="select form-control" required>
                                    <option value="">Select Days</option>
                                    <?php
                                    $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                    foreach($days as $day){
                                        $selected = ($day == $schedule->day_of_week)? 'selected' : '';
                                        echo "<option value='$day' $selected>$day</option>";
                                    }
                                   ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Time <span class="text-danger">*</span></label>
                                <div class='input-group date' id='datetimepicker3'>
                                    <input type="text" class="form-control" name="start_time" value="<?= date('h:i A', strtotime($schedule->start_time))?>" required />
                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Time <span class="text-danger">*</span></label>
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type="text" class="form-control" name="end_time" value="<?= date('h:i A', strtotime($schedule->end_time))?>" required />
                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Appointment Qty <span class="text-danger">*</span></label>
                                <input type="number" name="appointment_qty" class="form-control" value="<?= $schedule->appointment_qty?>" required />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="display-block">Schedule Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="1" <?= ($schedule->status == 1)? 'checked' : ''?>>
                            <label class="form-check-label">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="0" <?= ($schedule->status == 0)? 'checked' : ''?>>
                            <label class="form-check-label">Inactive</label>
                        </div>
                    </div>

                    <div class="m-t-20 text-center">
                        <button type="submit" class="btn btn-primary submit-btn">Update Schedule</button>
                        <a href="<?= $base_url?>schedule/schedule.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#datetimepicker3, #datetimepicker4').datetimepicker({
        format: 'LT' // 12 hour format: 10:30 AM
    });
});
</script>

<?php require_once "../component/footer.php";?>