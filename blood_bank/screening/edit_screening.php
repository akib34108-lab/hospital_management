<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; 
  $id = $_GET['id'];
  $screening = $crud->common_select("screening", "*", ['id' => $id]);
  if (!$screening['status'] || empty($screening['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Screening not found.');
    echo "<script>window.location.href = '".$base_url."blood_bank/screening/screening.php';</script>";
    exit;
  }

  $screening = $screening['data'][0];
?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 style="color: #104d6f; font-size: 24px;">Add Blood Screening</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="screening.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 offset-sm-2">
                        <form action="<?= $base_url; ?>blood_bank/screening/update_screening.php?id=<?= $screening->id ?>" method="POST">
							<div class="form-group">
								<label for="bag_id">Bag ID:</label>
								<input class="form-control" type="text" id="bag_id" name="bag_id" value="<?= $screening->bag_id ?>" required>
							</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="abo_group">ABO Group:</label>
                            <select class="form-control" id="abo_group" name="abo_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="1" <?= $screening->abo_group === 1 ? 'selected' : '' ?>>A</option>
                                <option value="2" <?= $screening->abo_group === 2 ? 'selected' : '' ?>>B</option>
                                <option value="3" <?= $screening->abo_group === 3 ? 'selected' : '' ?>>AB</option>
                                <option value="4" <?= $screening->abo_group === 4 ? 'selected' : '' ?>>O</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="rh_type">RH Type:</label>
                            <select class="form-control" id="rh_type" name="rh_type" required>
                                <option value="">Select RH Type</option>
                                <option value="1" <?= $screening->rh_type === '1' ? 'selected' : '' ?>>Positive</option>
                                <option value="2" <?= $screening->rh_type === '2' ? 'selected' : '' ?>>Negative</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="hiv">HIV:</label>
                            <select class="form-control" id="hiv" name="hiv" required>
                                <option value="">Select HIV Result</option>
                                <option value="1" <?= $screening->hiv === '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->hiv === '2' ? 'selected' : '' ?>>Non-reactive</option>
                                <option value="3" <?= $screening->hiv === '3' ? 'selected' : '' ?>>Reactive</option>
                                <option value="4" <?= $screening->hiv === '4' ? 'selected' : '' ?>>Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="hbsag">HBsAg:</label>
                            <select class="form-control" id="hbsag" name="hbsag" required>
                                <option value="">Select HBsAg Result</option>
                                <option value="1" <?= $screening->hbsag === '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->hbsag === '2' ? 'selected' : '' ?>>Non-reactive</option>
                                <option value="3" <?= $screening->hbsag === '3' ? 'selected' : '' ?>>Reactive</option>
                                <option value="4" <?= $screening->hbsag === '4' ? 'selected' : '' ?>>Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="hcv">HCV:</label>
                            <select class="form-control" id="hcv" name="hcv" required>
                                <option value="">Select HCV Result</option>
                                <option value="1" <?= $screening->hcv === '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->hcv === '2' ? 'selected' : '' ?>>Non-reactive</option>
                                <option value="3" <?= $screening->hcv === '3' ? 'selected' : '' ?>>Reactive</option>
                                <option value="4" <?= $screening->hcv === '4' ? 'selected' : '' ?>>Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="syphilis">Syphilis:</label>
                            <select class="form-control" id="syphilis" name="syphilis" required>
                                <option value="">Select Syphilis Result</option>
                                <option value="1" <?= $screening->syphilis === '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->syphilis === '2' ? 'selected' : '' ?>>Non-reactive</option>
                                <option value="3" <?= $screening->syphilis === '3' ? 'selected' : '' ?>>Reactive</option>
                                <option value="4" <?= $screening->syphilis === '4' ? 'selected' : '' ?>>Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="malaria">Malaria:</label>
                            <select class="form-control" id="malaria" name="malaria" required>
                                <option value="">Select Malaria Result</option>
                                <option value="1" <?= $screening->malaria === '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->malaria === '2' ? 'selected' : '' ?>>Non-reactive</option>
                                <option value="3" <?= $screening->malaria === '3' ? 'selected' : '' ?>>Reactive</option>
                                <option value="4" <?= $screening->malaria === '4' ? 'selected' : '' ?>>Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="other">Other Test:</label>
                            <select class="form-control" id="other" name="other" required>
                                <option value="">Select Other Test</option>
                                <option value="1" <?= $screening->other === '1' ? 'selected' : '' ?>>Positive</option>
                                <option value="2" <?= $screening->other === '2' ? 'selected' : '' ?>>Negative</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="tested_by">Tested by:</label>
                            <input class="form-control" type="text" id="tested_by" name="tested_by" value="<?= $screening->tested_by ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tested_at">Tested at:</label>
                            <input class="form-control" type="datetime-local" id="tested_at" name="tested_at" value="<?= $screening->tested_at ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="verified_by">Verified by:</label>
                            <input class="form-control" type="text" id="verified_by" name="verified_by" value="<?= $screening->verified_by ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="verified_at">Verified at:</label>
                            <input class="form-control" type="datetime-local" id="verified_at" name="verified_at" value="<?= $screening->verified_at ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" <?= $screening->status == '1' ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= $screening->status == '2' ? 'selected' : '' ?>>Passed</option>
                                <option value="3" <?= $screening->status == '3' ? 'selected' : '' ?>>Quarantined</option>
                                <option value="4" <?= $screening->status == '4' ? 'selected' : '' ?>>reactive</option>
                                <option value="5" <?= $screening->status == '5' ? 'selected' : '' ?>>Invalid</option>
                                <option value="6" <?= $screening->status == '6' ? 'selected' : '' ?>>Discarded</option>
                                <option value="7" <?= $screening->status == '7' ? 'selected' : '' ?>>Released</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <input class="form-control" type="text" id="remarks" name="remarks" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="m-t-20 text-center">
                            <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 35px;background:#104d6f;color:#fff;border:1px solid #104d6f;border-radius:7px;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);"><span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-check" style="font-size:12px;"></i></span> Create Collection</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<?php require_once "../../component/footer.php"; ?>
