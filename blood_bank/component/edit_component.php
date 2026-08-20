<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; 
  $id = $_GET['id'];
  $component = $crud->common_select("components", "*", ['id' => $id]);
  if (!$component['status'] || empty($component['data'])) {
    $_SESSION['message'] = array('danger','Error', 'Component not found.');
    echo "<script>window.location.href = '".$base_url."blood_bank/component/component.php';</script>";
    exit;
  }

  $component = $component['data'][0];
?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 style="color: #104d6f; font-size: 24px;">Add Component Information</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="component.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-arrow-left" style="font-size:11px;color:#fff;"></i></span>Back</a>
                    </div>
                </div>
                <form action="<?= $base_url; ?>blood_bank/component/store_component.php" method="POST">
                    <div class="row">
                        <div class="col-sm-5 offset-sm-2">
                            <div class="form-group">
                                <label for="bag_id">Bag ID:</label>
                                <select name="bag_id" class="form-select form-control " required>
                                    <option value="">Select Bag ID</option>
                                    <?php
                                    // Fetch all bag id for the dropdown
                                    $bag_id = $crud->common_select('screening', 'bag_id');
                                    if($bag_id['status']){
                                        foreach($bag_id['data'] as $screening) { ?>
                                        <option value="<?php echo $screening->bag_id; ?>"><?php echo htmlspecialchars($screening->bag_id); ?></option>
                                    <?php   }
                                    } else { ?>
                                    <option value="">No bags available</option>
                                    <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="component_type">Component Type:</label>
                                <select class="form-control" id="component_type" name="component_type" required>
                                    <option value="">Select Component Type</option>
                                    <option value="1" <?= $component->component_type == 1 ? 'selected' : '' ?>>Whole Blood — WB</option>
                                    <option value="2" <?= $component->component_type == 2 ? 'selected' : '' ?>>Packed Red Blood Cells (PRBC) — RBC</option>
                                    <option value="3" <?= $component->component_type == 3 ? 'selected' : '' ?>>Fresh Frozen Plasma (FFP) — FFP</option>
                                    <option value="4" <?= $component->component_type == 4 ? 'selected' : '' ?>>Platelets — PLT</option>
                                    <option value="5" <?= $component->component_type == 5 ? 'selected' : '' ?>>Plasma — PLS</option>
                                    <option value="6" <?= $component->component_type == 6 ? 'selected' : '' ?>>Cryoprecipitate — CRYO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5 offset-sm-2">
                            <div class="form-group">
                                <label for="processing_date">Processing Date:</label>
                                <input class="form-control" type="date" id="processing_date" name="processing_date" value="<?= $component->processing_date ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date:</label>
                                <input class="form-control" type="date" id="expiry_date" name="expiry_date" value="<?= $component->expiry_date ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-5 offset-sm-2">
                            <div class="form-group">
                                <label for="storage_location">Storage Location:</label>
                                <select class="form-control" id="storage_location" name="storage_location" required>
                                    <option value="">Select Storage Location</option>
                                    <option value="1" <?= $component->storage_location == 1 ? 'selected' : '' ?>>Refrigerator A Rack 01 Slot 01</option>
                                    <option value="2" <?= $component->storage_location == 2 ? 'selected' : '' ?>>Refrigerator A Rack 01 Slot 02</option>
                                    <option value="3" <?= $component->storage_location == 3 ? 'selected' : '' ?>>Refrigerator A Rack 01 Slot 03</option>
                                    <option value="4" <?= $component->storage_location == 4 ? 'selected' : '' ?>>Refrigerator A Rack 02 Slot 01</option>
                                    <option value="5" <?= $component->storage_location == 5 ? 'selected' : '' ?>>Refrigerator A Rack 02 Slot 02</option>
                                    <option value="6" <?= $component->storage_location == 6 ? 'selected' : '' ?>>Refrigerator A Rack 02 Slot 03</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="m-t-20 text-center">
                                <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 35px;background:#104d6f;color:#fff;border:1px solid #104d6f;border-radius:7px;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);"><span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-check" style="font-size:12px;"></i></span> Create Component</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<?php require_once "../../component/footer.php"; ?>
