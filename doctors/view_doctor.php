<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; 

  $id = $_GET['id'];
  $doctor = $crud->common_select("doctors", "*", ['id' => $id]);
  if (!$doctor['status'] || empty($doctor['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Doctor not found.');
    echo "<script>window.location.href = '".$base_url."doctors/doctors.php';</script>";
    exit;
  }

  $doctor = $doctor['data'][0];

?>
<!-- Sidebar End -->
 <div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <h3>Doctor Details</h3>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th colspan="2" style="font-size: 20px;" class="btn-success">Personal Information</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if (!empty($doctor)) { ?>
                                        <td class="font-weight-bold">Name:</td>
                                        <td><?= $doctor->name ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Gender:</td>
                                        <td> <?php if($doctor->gender == '1'){ ?>
                                                <span>Male</span>
                                            <?php } else { ?>
                                                <span>Female</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Specialization:</td>
                                        <td><?= $doctor->specialization ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Qualification:</td>
                                        <td><?= $doctor->qualification ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Experience(years):</td>
                                        <td><?= $doctor->experience ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Phone:</td>
                                        <td><?= $doctor->phone ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Email:</td>
                                        <td><?= $doctor->email ?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Address:</td>
                                        <td><?= $doctor->address ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <a href="<?= $base_url ?>doctors/edit_doctor.php?id=<?= $doctor->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a onclick="return confirm('Are you sure you want to delete this doctor?')" href="<?= $base_url ?>doctors/delete_doctor.php?id=<?= $doctor->id ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                            <?php } ?>
                                </tbody>
                            </table>
                        </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "../component/footer.php" ?>   