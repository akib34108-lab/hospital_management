<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Edit Prescription</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="prescription_list.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        
        <?php
        $conn = $crud->conn;
        $id = $_GET['id'];

        // DATA UPDATE
        if(isset($_POST['update'])){
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $doctor_id = $conn->real_escape_string($_POST['doctor_id']);
            $Obj_date = $conn->real_escape_string($_POST['Obj_date']);
            $Next_visit_day = $conn->real_escape_string($_POST['Next_visit_day']);
            $cc = $conn->real_escape_string($_POST['cc']);
            $diagnosis = $conn->real_escape_string($_POST['diagnosis']);
            $investigation = $conn->real_escape_string($_POST['investigation']);

            $sql = "UPDATE `prescriptions` SET 
                    `patient_id`='$patient_id',
                    `doctor_id`='$doctor_id',
                    `Obj_date`='$Obj_date',
                    `Next_visit_day`='$Next_visit_day',
                    `Cc`='$cc',
                    `Dx`='$diagnosis',
                    `Inv`='$investigation' 
                    WHERE `id`='$id'";

            if($conn->query($sql)){
                echo "<script>alert('Prescription Updated Successfully'); window.location='prescription_list.php';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
            }
        }

        // OLD DATA FETCH
        $result = $conn->query("SELECT * FROM prescriptions WHERE id='$id'");
        $row = $result->fetch_assoc();
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title">Edit Prescription</h4>
                    <form method="post" action="">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Patient *</label>
                            <div class="col-md-10">
                                <select name="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    <?php 
                                    $patients = $crud->common_select("patients","*");
                                    if($patients['status']){
                                        foreach ($patients['data'] as $p) { 
                                            $selected = ($p->id == $row['patient_id']) ? 'selected' : '';
                                            echo "<option value='".$p->id."' $selected>".$p->name."</option>"; 
                                        } 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Doctor *</label>
                            <div class="col-md-10">
                                <select name="doctor_id" class="form-control" required>
                                    <option value="">-- Select Doctor --</option>
                                    <?php 
                                    $doctors = $crud->common_select("doctors","*");
                                    if($doctors['status']){
                                        foreach ($doctors['data'] as $d) { 
                                            $selected = ($d->id == $row['doctor_id']) ? 'selected' : '';
                                            echo "<option value='".$d->id."' $selected>".$d->name."</option>"; 
                                        } 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Prescription Date *</label>
                            <div class="col-md-10">
                                <input type="date" name="Obj_date" value="<?php echo $row['Obj_date'];?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Next Visit Day</label>
                            <div class="col-md-10">
                                <input type="number" name="Next_visit_day" value="<?php echo $row['Next_visit_day'];?>" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Chief Complaints CC</label>
                            <div class="col-md-10">
                                <textarea name="cc" class="form-control" rows="3"><?php echo $row['Cc'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Diagnosis Dx</label>
                            <div class="col-md-10">
                                <textarea name="diagnosis" class="form-control" rows="3"><?php echo $row['Dx'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Investigation Inv</label>
                            <div class="col-md-10">
                                <textarea name="investigation" class="form-control" rows="3"><?php echo $row['Inv'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" name="update" class="btn btn-primary">Update Prescription</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "../component/footer.php" ?>