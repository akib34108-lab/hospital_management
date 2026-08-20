<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; 
  $id = $_GET['id'];
  $blood_collection = $crud->common_select("blood_collection", "*", ['id' => $id]);
  if (!$blood_collection['status'] || empty($blood_collection['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Blood collection not found.');
    echo "<script>window.location.href = '".$base_url."blood_bank/blood_collection/collection.php';</script>";
    exit;
  }

  $blood_collection = $blood_collection['data'][0];

?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 style="color: #104d6f; font-size: 24px;">Edit Blood Collection Information</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="<?= $base_url; ?>blood_bank/blood_collection/collection.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 offset-sm-2">
                        <form action="<?= $base_url; ?>blood_bank/blood_collection/update_collection.php?id=<?= $blood_collection->id ?>" method="POST">
							<div class="form-group">
								<label for="donation_id">Donation ID:</label>
								<input class="form-control" type="text" id="donation_id" name="donation_id" value="<?= $blood_collection->donation_id ?>" readonly>
							</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="donor_id">Donor ID:</label>
                            <input class="form-control" type="text" id="donor_id" name="donor_id" value="<?= $blood_collection->donor_id ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="bag_id">Bag ID:</label>
                            <input class="form-control" type="text" id="bag_id" name="bag_id" value="<?= $blood_collection->bag_id ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="collection_volume">Collection Volume (mL):</label>
                            <input class="form-control" type="number" id="collection_volume" name="collection_volume" value="<?= $blood_collection->collection_volume ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="collection_location">Collection Location:</label>
                            <input class="form-control" type="text" id="collection_location" name="collection_location" value="<?= $blood_collection->collection_location ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="collection_date">Collection Date:</label>
                            <input class="form-control" type="date" id="collection_date" name="collection_date" value="<?= $blood_collection->collection_date ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3 offset-sm-2">
                        <div class="form-group">
                            <label for="staff">Phlebotomist:</label>
                            <input class="form-control" type="text" id="staff" name="staff" value="<?= $blood_collection->staff ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        
                    </div>
                    <div class="col-sm-12">
                        <div class="m-t-20 text-center">
                            <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 35px;background:#104d6f;color:#fff;border:1px solid #104d6f;border-radius:7px;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);"><span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-check" style="font-size:12px;"></i></span> Update Blood Collection</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<?php require_once "../../component/footer.php"; ?>
