<?php require_once "../component/header.php";?>
<?php require_once "../component/sidebar.php";?>
<?php require_once "../component/connection.php";?>

<?php
$conn = $crud->conn;


$sql = "SELECT p.*, pt.name as patient_name, d.name as doctor_name 
        FROM prescriptions p 
        LEFT JOIN patients pt ON p.patient_id = pt.id 
        LEFT JOIN doctors d ON p.doctor_id = d.id 
        ORDER BY p.id DESC";
$result = $conn->query($sql);
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Prescription List</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="add_prescription.php" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Prescription
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Next Visit</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if($result && $result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                        ?>
                            <tr>
                                <td><?php echo $row['id'];?></td>
                                <td><?php echo $row['patient_name'];?></td>
                                <td> <?php echo $row['doctor_name'];?></td>
                                <td><?php echo $row['Obj_date'];?></td>
                                <td>After <?php echo $row['Next_visit_day'];?> Days</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="prescription.php?id=<?php echo $row['id'];?>"><i class="fa fa-eye m-r-5"></i> View</a>
                                            <a class="dropdown-item" href="edit_prescription.php?id=<?php echo $row['id'];?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="delete_prescription.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No Data Found</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../component/footer.php";?>