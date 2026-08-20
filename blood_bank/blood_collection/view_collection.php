<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; 

  $id = $_GET['id'];
  $blood_collection = $crud->common_select("blood_collection", "*", ['id' => $id]);
  if (!$blood_collection['status'] || empty($blood_collection['data'])) {
    $_SESSION['message'] = array('danger','Error', 'blood collection not found.');
    echo "<script>window.location.href = '".$base_url."blood_bank/blood_collection/collection.php';</script>";
    exit;
  }

  $blood_collection = $blood_collection['data'][0];

?>
<!-- Sidebar End -->
 <div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-5 col-5">
                <h4 style="color: #104d6f; font-size: 24px;">Blood Collection Information</h4>
            </div>
            <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="collection.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 offset-md-2">
                <div class="table-responsive">
                    <table class="table custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th colspan="2" style="font-size: 20px; background-color: #3e79b5; color: #fff;">Donor Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $collection = $crud->common_select("donor", "*", ['id' => $blood_collection->donor_id]);
                                if ($collection['status'] && !empty($collection['data'])) { $donor = $collection['data'][0]; ?>
                            <tr>
                                <td class="font-weight-bold">Name:</td>
                                <td><?= $donor->donor_name ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Blood Group:</td>
                                <td><?php $bloodGroups = [1 => 'A+',2 => 'A-',3 => 'B+',4 => 'B-',5 => 'AB+',6 => 'AB-',7 => 'O+',8 => 'O-']; ?>
                                        <?= htmlspecialchars($bloodGroups[(int)$donor->blood_group] ?? 'N/A') ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="font-weight-bold">Age:</td>
                                <td><?= $donor->age ?></td>
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
                                <td class="font-weight-bold">Last Donation Date:</td>
                                <td><?= $donor->last_donation ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-3">
                <div class="table-responsive">
                    <table class="table custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th colspan="2" style="font-size: 20px; background-color: #3e79b5; color: #fff;">Donation Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php if (!empty($blood_collection)) { ?>
                            <td class="font-weight-bold">Donation ID:</td>
                                <td><?= $blood_collection->donation_id ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Bag ID:</td>
                                <td><?= $blood_collection->bag_id ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Collection Date:</td>
                                <td><?= $blood_collection->collection_date ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Collection Volume (mL):</td>
                                <td><?= $blood_collection->collection_volume ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Collection Location:</td>
                                <td><?= $blood_collection->collection_location ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div><div class="col-md-3">
                <div class="table-responsive">
                    <table class="table custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th colspan="2" style="font-size: 20px; background-color: #3e79b5; color: #fff;">Phlebotomist Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php if (!empty($blood_collection)) { ?>
                            <td class="font-weight-bold">ID:</td>
                                <td><?= $blood_collection->staff ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Name:</td>
                                <td><?= $blood_collection->staff ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Phone:</td>
                                <td><?= $blood_collection->staff ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address:</td>
                                <td><?= $blood_collection->staff ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Designation:</td>
                                <td><?= $blood_collection->staff ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php require_once "../../component/footer.php" ?>   