<?php require_once "../component/header.php";?>
<?php require_once "../component/sidebar.php";?>
<?php
    session_start();
    $base_url = "http://localhost/shifa/";
    require_once  ($_SERVER['DOCUMENT_ROOT'] . "/shifa/crud/crud_class.php");
    $crud = new crud_class();
    $conn = $crud->conn; // Eitai main
?>

<?php
if(isset($_GET['id'])){
    $pres_id = intval($_GET['id']);
    
    // Prescription + Patient + Doctor ek sathe
    $sql = "SELECT p.*, pt.name as patient_name, d.name as doctor_name 
            FROM prescriptions p
            LEFT JOIN patients pt ON p.patient_id = pt.id
            LEFT JOIN doctors d ON p.doctor_id = d.id
            WHERE p.id='$pres_id'";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if(!$row){
        echo "<div class='alert alert-danger'>Prescription not found</div>";
        exit;
    }

    // JSON ke array te convert
    $medicines = [];
    if(!empty($row['medicines'])){
        $medicines = json_decode($row['medicines']);
    }
} else {
    echo "<div class='alert alert-danger'>Invalid ID</div>";
    exit;
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <div class="text-right mb-3">
                        <a href="prescription_list.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4><b>Patient:</b> <?php echo $row['patient_name'];?></h4>
                            <p><b>Date:</b> <?php echo $row['Obj_date'];?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4><b>Doctor:</b> <?php echo $row['doctor_name'];?></h4>
                            <p><b>Next Visit:</b> <?php echo $row['Next_visit_day'];?> days</p>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4"><p><b>Chief Complaint:</b><br> <?php echo $row['Cc'];?></p></div>
                        <div class="col-md-4"><p><b>Diagnosis:</b><br> <?php echo $row['Dx'];?></p></div>
                        <div class="col-md-4"><p><b>Investigation:</b><br> <?php echo $row['Inv'];?></p></div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><b>Medicines</b></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr><th>#</th><th>Medicine Name</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instructions</th></tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=1;
                            if(!empty($medicines) && is_array($medicines)){
                                foreach($medicines as $med){ ?>
                                <tr>
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $med->medicationName;?></td>
                                    <td><?php echo $med->dosage;?></td>
                                    <td><?php echo $med->frequency;?></td>
                                    <td><?php echo $med->duration;?></td>
                                    <td><?php echo $med->instructions;?></td>
                                </tr>
                            <?php }} else { echo "<tr><td colspan='6' class='text-center'>No Medicine Added</td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(!empty($row['additional_notes'])){?>
                    <hr><p><b>Additional Notes:</b> <?php echo $row['additional_notes'];?></p>
                    <?php }?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../component/footer.php"?>