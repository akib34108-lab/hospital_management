<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; ?>

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
                        <form action="<?= $base_url; ?>blood_bank/screening/store_screening.php" method="POST">
							<div class="form-group">
								<label for="bag_id">Bag ID:</label>
								<input class="form-control" type="text" id="bag_id" name="bag_id" required>
							</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="abo_group">ABO Group:</label>
                            <select class="form-control" id="abo_group" name="abo_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="1">A</option>
                                <option value="2">B</option>
                                <option value="3">AB</option>
                                <option value="4">O</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="rh_type">RH Type:</label>
                            <select class="form-control" id="rh_type" name="rh_type" required>
                                <option value="">Select RH Type</option>
                                <option value="1">Positive</option>
                                <option value="2">Negative</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="hiv">HIV:</label>
                            <select class="form-control" id="hiv" name="hiv" required>
                                <option value="">Select HIV Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Non-reactive</option>
                                <option value="3">Reactive</option>
                                <option value="4">Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="hbsag">HBsAg:</label>
                            <select class="form-control" id="hbsag" name="hbsag" required>
                                <option value="">Select HBsAg Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Non-reactive</option>
                                <option value="3">Reactive</option>
                                <option value="4">Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="hcv">HCV:</label>
                            <select class="form-control" id="hcv" name="hcv" required>
                                <option value="">Select HCV Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Non-reactive</option>
                                <option value="3">Reactive</option>
                                <option value="4">Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="syphilis">Syphilis:</label>
                            <select class="form-control" id="syphilis" name="syphilis" required>
                                <option value="">Select Syphilis Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Non-reactive</option>
                                <option value="3">Reactive</option>
                                <option value="4">Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="malaria">Malaria:</label>
                            <select class="form-control" id="malaria" name="malaria" required>
                                <option value="">Select Malaria Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Non-reactive</option>
                                <option value="3">Reactive</option>
                                <option value="4">Invalid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="other">Other Test:</label>
                            <select class="form-control" id="other" name="other" required>
                                <option value="">Select Other Test</option>
                                <option value="1">Positive</option>
                                <option value="2">Negative</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="tested_by">Tested by:</label>
                            <input class="form-control" type="text" id="tested_by" name="tested_by" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tested_at">Tested at:</label>
                            <input class="form-control" type="datetime-local" id="tested_at" name="tested_at" required>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="verified_by">Verified by:</label>
                            <input class="form-control" type="text" id="verified_by" name="verified_by" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="verified_at">Verified at:</label>
                            <input class="form-control" type="datetime-local" id="verified_at" name="verified_at" required>
                        </div>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1">Pending</option>
                                <option value="2">Passed</option>
                                <option value="3">Quarantined</option>
                                <option value="4">Reactive</option>
                                <option value="5">Invalid</option>
                                <option value="6">Discarded</option>
                                <option value="7">Released</option>
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
