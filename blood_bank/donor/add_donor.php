<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 style="color: #104d6f; font-size: 24px;">Add Donor Information</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="donor.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 offset-sm-2">
                        <form action="<?= $base_url; ?>blood_bank/donor/store_donor.php" method="POST">
							<div class="form-group">
								<label for="donor_name">Name:</label>
								<input class="form-control" type="text" id="donor_name" name="donor_name" required>
							</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input class="form-control" type="number" id="age" name="age" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input class="form-control" type="text" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="blood_group">Blood Group:</label>
                            <select class="form-control" id="blood_group" name="blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="1">A+</option>
                                <option value="2">A-</option>
                                <option value="3">B+</option>
                                <option value="4">B-</option>
                                <option value="5">AB+</option>
                                <option value="6">AB-</option>
                                <option value="7">O+</option>
                                <option value="8">O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="last_donation">Last Donate:</label>
                            <input class="form-control" type="date" id="last_donation" name="last_donation" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input class="form-control" type="text" id="address" name="address" required>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label class="display-block">Eligibility</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="donor_eligibility" id="eligible" value="1" checked>
								<label class="form-check-label" for="eligible">Eligible</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="donor_eligibility" id="not-eligible" value="2">
								<label class="form-check-label" for="not-eligible">Not Eligible</label>
							</div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="display-block">Gender:</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="gender" id="male" value="1" checked>
								<label class="form-check-label" for="male">Male</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="gender" id="female" value="2">
								<label class="form-check-label" for="female">Female</label>
							</div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        
                    </div>
                    <div class="col-sm-12">
                        <div class="m-t-20 text-center">
                            <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 35px;background:#104d6f;color:#fff;border:1px solid #104d6f;border-radius:7px;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);"><span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-check" style="font-size:12px;"></i></span> Create Donor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<?php require_once "../../component/footer.php"; ?>
