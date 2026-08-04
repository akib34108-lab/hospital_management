<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Add Prescription</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="prescription_list.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        
        <?php
        // ===== SAVE LOGIC START =====
        $conn = $crud->conn; 
        if(isset($_POST['save'])){
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $doctor_id = $conn->real_escape_string($_POST['doctor_id']);
            $Obj_date = $conn->real_escape_string($_POST['Obj_date']);
            $Next_visit_day = $conn->real_escape_string($_POST['Next_visit_day']);
            $cc = $conn->real_escape_string($_POST['cc']);
            $diagnosis = $conn->real_escape_string($_POST['diagnosis']);
            $investigation = $conn->real_escape_string($_POST['investigation']);

            $sql = "INSERT INTO `prescriptions`(`patient_id`, `doctor_id`, `Obj_date`, `Next_visit_day`, `Cc`, `Dx`, `Inv`) 
                    VALUES ('$patient_id','$doctor_id','$Obj_date','$Next_visit_day','$cc','$diagnosis','$investigation')";

            if($conn->query($sql)){
                echo "<script>alert('Prescription Saved Successfully'); window.location='prescription_list.php';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>DB Error: ".$conn->error."</div>";
            }
        }
        // ===== SAVE LOGIC END =====
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title">Prescription Details</h4>
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
                                            echo "<option value='".$p->id."'>".$p->name." </option>"; 
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
                                            echo "<option value='".$d->id."'>".$d->name."</option>"; 
                                        } 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Prescription Date *</label>
                            <div class="col-md-10">
                                <input type="date" name="Obj_date" value="<?php echo date('Y-m-d');?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Next Visit Day</label>
                            <div class="col-md-10">
                                <input type="number" name="Next_visit_day" class="form-control" placeholder="Example: 7">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Chief Complaints CC</label>
                            <div class="col-md-10">
                                <textarea name="cc" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Diagnosis Dx</label>
                            <div class="col-md-10">
                                <textarea name="diagnosis" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Investigation Inv</label>
                            <div class="col-md-10">
                                <textarea name="investigation" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" name="save" class="btn btn-primary">Save Prescription</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "../component/footer.php" ?>