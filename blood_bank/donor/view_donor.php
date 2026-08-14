<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; 

  $id = $_GET['id'];
  $donor = $crud->common_select("donor", "*", ['id' => $id]);
  if (!$donor['status'] || empty($donor['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Donor not found.');
    echo "<script>window.location.href = '".$base_url."donor/donor.php';</script>";
    exit;
  }

  $donor = $donor['data'][0];

?>
<!-- Sidebar End -->
 <div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-5 col-5">
                <h4 style="color: #104d6f; font-size: 24px;">Donor Information</h4>
            </div>
            <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="donor.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
            </div>
        </div>
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
                            <?php if (!empty($donor)) { ?>
                                <td class="font-weight-bold">Name:</td>
                                <td><?= $donor->donor_name ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Age:</td>
                                <td><?= $donor->age ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Gender:</td>
                                <td> <?php if($donor->gender == '1'){ ?>
                                    <span>Male</span>
                                    <?php } else { ?>
                                    <span>Female</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Blood Group:</td>
                                <td><?= $donor->blood_group ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Phone:</td>
                                <td><?= $donor->phone ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address:</td>
                                <td><?= $donor->address ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Last Donated:</td>
                                <td><?= !empty($donor->last_donated) ? htmlspecialchars($donor->last_donated) : 'Not donated yet' ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Donor Eligibility:</td>
                                <td> <?php if($donor->donor_eligibility == '1'){ ?>
                                    <span>Eligible</span>
                                    <?php } else { ?>
                                    <span>Not Eligible</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <a href="<?= $base_url ?>blood_bank/donor/edit_donor.php?id=<?= $donor->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                    <a onclick="return confirm('Are you sure you want to delete this doctor?')" href="<?= $base_url ?>blood_bank/donor/delete_donor.php?id=<?= $donor->id ?>" class="btn btn-sm btn-danger">Delete</a>
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
<?php require_once "../../component/footer.php" ?>   