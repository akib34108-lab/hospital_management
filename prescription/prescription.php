<?php require_once "../component/header.php";?>
<?php require_once "../component/sidebar.php";?>
<?php
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
                    <div class="text-right mb-3 no-print">
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
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Medicine Name</th>
                                    <th width="10%">Dosage</th>
                                    <th width="15%">Frequency</th>
                                    <th width="10%">Duration</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=1;
                            // Notun table theke medicine gula tana
                            $med_sql = "SELECT * FROM prescription_medicines WHERE prescription_id='$pres_id' ORDER BY id ASC";
                            $med_result = mysqli_query($conn, $med_sql);

                            if(mysqli_num_rows($med_result) > 0){
                                while($med = mysqli_fetch_assoc($med_result)){ ?>
                                <tr>
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $med['medicine_name'];?></td>
                                    <td><?php echo $med['dosage'];?></td>
                                    <td><?php echo $med['frequency'];?></td>
                                    <td><?php echo $med['duration'];?></td>
                                    <td><?php echo $med['instructions'];?></td>
                                </tr>
                            <?php }} else { 
                                echo "<tr><td colspan='6' class='text-center'>No Medicine Added</td></tr>"; 
                            } ?>
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

<style>
@media print {
    /* 1. Sidebar, Header, Button hide */
    .sidebar, 
    .header, 
    .page-title, 
    .breadcrumb, 
    .no-print, 
    nav, 
    footer {
        display: none !important;
    }

    /* 2. Pura width nibe */
    .page-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    
    .content {
        margin: 0 !important;
        padding: 20px !important;
    }

    /* 3. Card er border/shadow bad */
    .card-box {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }

    body {
        background: white !important;
        -webkit-print-color-adjust: exact;
    }
    
    table {
        page-break-inside: avoid;
    }
}
</style>

<?php require_once "../component/footer.php"?>