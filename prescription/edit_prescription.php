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
        $id = $_GET['id']; // url theke id nibo

        // Data gula age niye nisi
        $pres = $conn->query("SELECT * FROM prescriptions WHERE id='$id'")->fetch_object();
        $meds = $conn->query("SELECT * FROM prescription_medicines WHERE prescription_id='$id'");

        if(isset($_POST['update'])){
            // 1. Main Prescription Update
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $doctor_id = $conn->real_escape_string($_POST['doctor_id']);
            $Obj_date = $conn->real_escape_string($_POST['Obj_date']);
            $Next_visit_day = $conn->real_escape_string($_POST['Next_visit_day']);
            $cc = $conn->real_escape_string($_POST['cc']);
            $diagnosis = $conn->real_escape_string($_POST['diagnosis']);
            $investigation = $conn->real_escape_string($_POST['investigation']);

            $sql = "UPDATE `prescriptions` SET 
                    `patient_id`='$patient_id',`doctor_id`='$doctor_id',`Obj_date`='$Obj_date',
                    `Next_visit_day`='$Next_visit_day',`Cc`='$cc',`Dx`='$diagnosis',`Inv`='$investigation' 
                    WHERE id='$id'";

            if($conn->query($sql)){
                // 2. Age puran medicine delete kore dibo
                $conn->query("DELETE FROM prescription_medicines WHERE prescription_id='$id'");

                // 3. Notun gula abar insert korbo
                if(isset($_POST['medicine_name'])){
                    for($i=0; $i<count($_POST['medicine_name']); $i++){
                        if(!empty($_POST['medicine_name'][$i])){
                            $med = $conn->real_escape_string($_POST['medicine_name'][$i]);
                            $dos = $conn->real_escape_string($_POST['dosage'][$i]);
                            $freq = $conn->real_escape_string($_POST['frequency'][$i]);
                            $dur = $conn->real_escape_string($_POST['duration'][$i]);
                            $inst = $conn->real_escape_string($_POST['instructions'][$i]);
                            
                            $conn->query("INSERT INTO `prescription_medicines` 
                            (`prescription_id`, `medicine_name`, `dosage`, `frequency`, `duration`, `instructions`) 
                            VALUES ('$id','$med','$dos','$freq','$dur','$inst')");
                        }
                    }
                }

                echo "<script>alert('Prescription Updated Successfully'); window.location='prescription_list.php';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>DB Error: ".$conn->error."</div>";
            }
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title">Prescription Details</h4>
                    <form method="post" action="">
                        
                        <!-- Row 1: Patient + Doctor -->
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Patient *</label>
                            <div class="col-md-4">
                                <select name="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    <?php 
                                    $patients = $crud->common_select("patients","*");
                                    if($patients['status']){
                                        foreach ($patients['data'] as $p) { 
                                            $sel = ($p->id == $pres->patient_id) ? 'selected' : '';
                                            echo "<option value='".$p->id."' $sel>".$p->name."</option>"; 
                                        } 
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-form-label col-md-2">Doctor *</label>
                            <div class="col-md-4">
                                <select name="doctor_id" class="form-control" required>
                                    <option value="">-- Select Doctor --</option>
                                    <?php 
                                    $doctors = $crud->common_select("doctors","*");
                                    if($doctors['status']){
                                        foreach ($doctors['data'] as $d) { 
                                            $sel = ($d->id == $pres->doctor_id) ? 'selected' : '';
                                            echo "<option value='".$d->id."' $sel>".$d->name."</option>"; 
                                        } 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Date + Next Visit -->
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Prescription Date *</label>
                            <div class="col-md-4">
                                <input type="date" name="Obj_date" value="<?php echo $pres->Obj_date;?>" class="form-control" required>
                            </div>
                            <label class="col-form-label col-md-2">Next Visit Day</label>
                            <div class="col-md-4">
                                <input type="number" name="Next_visit_day" value="<?php echo $pres->Next_visit_day;?>" class="form-control" placeholder="Example: 7">
                            </div>
                        </div>

                        <!-- Row 3: CC + DX + INV Pashapashi -->
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Chief Complaints CC</label>
                                <textarea name="cc" class="form-control" rows="3"><?php echo $pres->Cc;?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Diagnosis Dx</label>
                                <textarea name="diagnosis" class="form-control" rows="3"><?php echo $pres->Dx;?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Investigation Inv</label>
                                <textarea name="investigation" class="form-control" rows="3"><?php echo $pres->Inv;?></textarea>
                            </div>
                        </div>

                        <!-- MEDICINE TABLE -->
                        <div class="card-box">
                            <h4 class="text-blue h4">Medicines</h4>
                            <button type="button" id="addMedicine" class="btn btn-primary mb-3">+ Add Medicine</button>

                            <div class="table-responsive">
                            <table class="table table-bordered" id="medicineTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Medicine Name</th>
                                        <th>Dosage</th>
                                        <th>Frequency</th>
                                        <th>Duration</th>
                                        <th>Instructions</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($meds->num_rows > 0){
                                        while($m = $meds->fetch_object()){ ?>
                                        <tr>
                                            <td><input type="text" name="medicine_name[]" value="<?php echo $m->medicine_name;?>" class="form-control" required></td>
                                            <td><input type="text" name="dosage[]" value="<?php echo $m->dosage;?>" class="form-control"></td>
                                            <td><input type="text" name="frequency[]" value="<?php echo $m->frequency;?>" class="form-control"></td>
                                            <td><input type="text" name="duration[]" value="<?php echo $m->duration;?>" class="form-control"></td>
                                            <td><input type="text" name="instructions[]" value="<?php echo $m->instructions;?>" class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                        </tr>
                                    <?php } 
                                    } else { ?>
                                        <tr>
                                            <td><input type="text" name="medicine_name[]" class="form-control" required></td>
                                            <td><input type="text" name="dosage[]" class="form-control"></td>
                                            <td><input type="text" name="frequency[]" class="form-control"></td>
                                            <td><input type="text" name="duration[]" class="form-control"></td>
                                            <td><input type="text" name="instructions[]" class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="update" class="btn btn-success">Update Prescription</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery load korsi karon header e chilo na -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Notun row add
    $("#addMedicine").click(function(){
        var newRow = `<tr>
            <td><input type="text" name="medicine_name[]" class="form-control" required></td>
            <td><input type="text" name="dosage[]" class="form-control"></td>
            <td><input type="text" name="frequency[]" class="form-control"></td>
            <td><input type="text" name="duration[]" class="form-control"></td>
            <td><input type="text" name="instructions[]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
        </tr>`;
        $("#medicineTable tbody").append(newRow);
    });

    // Row delete
    $(document).on('click', '.removeRow', function(){
        if($("#medicineTable tbody tr").length > 1){
            $(this).closest('tr').remove();
        } else {
            alert("Minimum 1 ta medicine thaktei hobe");
        }
    });
});
</script>

<?php require_once "../component/footer.php" ?>