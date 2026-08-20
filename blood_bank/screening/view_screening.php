<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; 

  $id = $_GET['id'];
  $blood_screening = $crud->common_select("screening", "*", ['id' => $id]);
  if (!$blood_screening['status'] || empty($blood_screening['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Blood screening not found.');
    echo "<script>window.location.href = '".$base_url."blood_bank/screening/screening.php';</script>";
    exit;
  }

  $blood_screening = $blood_screening['data'][0];

?>
<!-- Sidebar End -->
 <div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-5 col-5">
                <h4 style="color: #104d6f; font-size: 24px;">Blood Screening Information</h4>
            </div>
            <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="screening.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5 offset-sm-1">
                <div class="table-responsive">
                    <table class="table custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th colspan="2" style="font-size: 20px; background-color: #3e79b5; color: #fff;">Blood Bag Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php if (!empty($blood_screening)) { ?>
                                <td class="font-weight-bold">Bag ID:</td>
                                <td><?= $blood_screening->bag_id ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tested by:</td>
                                <td><?= $blood_screening->tested_by ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tested at:</td>
                                <td><?= $blood_screening->tested_at ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Doctor ID:</td>
                                <td><?= $blood_screening->doctor_id ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Verified at:</td>
                                <td><?= $blood_screening->verified_at ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                <div class="col-sm-5">
                    <div class="table-responsive">
                        <table class="table custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th colspan="2" style="font-size: 20px; background-color: #3e79b5; color: #fff;">Bag Test Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">ABO Group:</td>
                                    <td><?php $aboGroup = [1=>'A', 2=>'B', 3=>'AB', 4=>'O' ]; ?>
                                        <?= htmlspecialchars($aboGroup[(int)$blood_screening->abo_group] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">RH Type:</td>
                                    <td><?php $rhType = [1=>'Positive', 2=>'Negative']; ?>
                                        <?= htmlspecialchars($rhType[(int)$blood_screening->rh_type] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">HIV:</td>
                                    <td><?php $hivStatus = [1=>'Pending', 2=>'Non-Reactive', 3=>'Reactive', 4=>'Invalid']; ?>
                                        <?= htmlspecialchars($hivStatus[(int)$blood_screening->hiv] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">HBsAG:</td>
                                    <td><?php $hbsagStatus = [1=>'Pending', 2=>'Non-Reactive', 3=>'Reactive', 4=>'Invalid']; ?>
                                        <?= htmlspecialchars($hbsagStatus[(int)$blood_screening->hbsag] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">HCV:</td>
                                        <td><?php $hcvStatus = [1=>'Pending', 2=>'Non-Reactive', 3=>'Reactive', 4=>'Invalid']; ?>
                                        <?= htmlspecialchars($hcvStatus[(int)$blood_screening->hcv] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Syphilis:</td>
                                    <td><?php $syphilisStatus = [1=>'Pending', 2=>'Non-Reactive', 3=>'Reactive', 4=>'Invalid']; ?>
                                        <?= htmlspecialchars($syphilisStatus[(int)$blood_screening->syphilis] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Malaria:</td>
                                    <td><?php $malariaStatus = [1=>'Pending', 2=>'Non-Reactive', 3=>'Reactive', 4=>'Invalid']; ?>
                                        <?= htmlspecialchars($malariaStatus[(int)$blood_screening->malaria] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Other:</td>
                                    <td><?php $otherStatus = [1=>'Positive', 2=>'Negative']; ?>
                                        <?= htmlspecialchars($otherStatus[(int)$blood_screening->other] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Status:</td>
                                    <td><?php $status = [1=>'Pending', 2=>'Passed', 3=>'Quarantined', 4=>'Reactive', 5=>'Invalid', 6=>'Discarded', 7=>'Released']; ?>
                                        <?= htmlspecialchars($status[(int)$blood_screening->status] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Remarks:</td>
                                    <td><?= $blood_screening->remarks ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<?php require_once "../../component/footer.php" ?>   