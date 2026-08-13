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
        $conn = $crud->conn; 
        if(isset($_POST['save'])){
            // 1. Main Prescription Save
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
                $last_id = $conn->insert_id; // last prescription id

                // 2. Medicine gula loop kore save
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
                            VALUES ('$last_id','$med','$dos','$freq','$dur','$inst')");
                        }
                    }
                }

                echo "<script>alert('Prescription Saved Successfully'); window.location='prescription_list.php';</script>";
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
                                            echo "<option value='".$p->id."'>".$p->name." </option>"; 
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
                                            echo "<option value='".$d->id."'>".$d->name."</option>"; 
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
                                <input type="date" name="Obj_date" value="<?php echo date('Y-m-d');?>" class="form-control" required>
                            </div>
                            <label class="col-form-label col-md-2">Next Visit Day</label>
                            <div class="col-md-4">
                                <input type="number" name="Next_visit_day" class="form-control" placeholder="Example: 7">
                            </div>
                        </div>

                        <!-- Row 3: CC + DX + INV Pashapashi -->
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Chief Complaints CC</label>
                                <textarea name="cc" class="form-control" rows="3" placeholder="problems"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Diagnosis Dx</label>
                                <textarea name="diagnosis" class="form-control" rows="3" placeholder="diseases"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Investigation Inv</label>
                                <textarea name="investigation" class="form-control" rows="3" placeholder="Tests"></textarea>
                            </div>
                        </div>

                        <!-- MEDICINE TABLE -->
                        <div class="card-box">
                            <h4 class="text-blue h4">Medicines</h4>
                            <button type="button" id="addMedicine" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Medicine</button>

                            <div class="table-responsive">
                            <table class="table table-bordered" id="medicineTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Medicine Name *</th>
                                        <th>Dosage</th>
                                        <th>Frequency</th>
                                        <th>Duration</th>
                                        <th>Instructions</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="medicine_name[]" class="form-control" required></td>
                                        <td><input type="text" name="dosage[]" class="form-control" placeholder="0+0+1"></td>
                                        <td><input type="text" name="frequency[]" class="form-control" placeholder="after meal"></td>
                                        <td><input type="text" name="duration[]" class="form-control" placeholder="7 days"></td>
                                        <td><input type="text" name="instructions[]" class="form-control" placeholder="regular"></td>
                                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="save" class="btn btn-primary btn-lg">Save Prescription</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN add korlam -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    // Notun row add - clone na kore direct html
    $("#addMedicine").on('click', function(){
        var newRow = `<tr>
            <td><input type="text" name="medicine_name[]" class="form-control" required></td>
            <td><input type="text" name="dosage[]" class="form-control" placeholder="0+0+1"></td>
            <td><input type="text" name="frequency[]" class="form-control" placeholder="after meal"></td>
            <td><input type="text" name="duration[]" class="form-control" placeholder="7 days"></td>
            <td><input type="text" name="instructions[]" class="form-control" placeholder="regular"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
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