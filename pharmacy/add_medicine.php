<?php
require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";

if (isset($_POST['save_medicine'])) {
    $medicine_name = $_POST['medicine_name'];
    $generic_name = $_POST['generic_name'];
    $brand_name = $_POST['brand_name'];
    $category = $_POST['category'];
    $dosage_form = $_POST['dosage_form'];
    $strength = $_POST['strength'];
    $unit = $_POST['unit'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $unit_price = $_POST['unit_price'];
    $reorder_level = $_POST['reorder_level'];
    $expiry_date = $_POST['expiry_date'];

    $data = [
        "medicine_name" => $medicine_name,
        "generic_name" => $generic_name,
        "category" => $category,
        "dosage_form" => $dosage_form,
        "strength" => $strength,
        "unit" => $unit,
        "manufacturer" => $brand_name,
        "description" => $description,
        "unit_price" => $unit_price,
        "reorder_level" => $reorder_level,
        "expiry_date" => $expiry_date,
        "status" => $status
    ];

    $result = $crud->common_insert("medicines", $data);

    if ($result["status"]) {
        echo "<script>alert('Medicine added successfully'); window.location.href='medicines.php';</script>";
    } else {
        echo "<script>alert('Error: " . $result["message"] . "');</script>";
    }
}
?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Add Medicine</h4>
            </div>
            <div class="col-sm-5 col-6 text-right">
                <a href="medicines.php" class="btn btn-secondary btn-rounded"><i class="fa fa-arrow-left"></i> Back to Medicines</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa fa-medkit"></i> Medicine Information</h4>
                <p class="text-muted mb-0">Add a new medicine to the pharmacy system.</p>
            </div>

            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" name="medicine_name" class="form-control" placeholder="e.g. Napa" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Generic Name <span class="text-danger">*</span></label>
                                <input type="text" name="generic_name" class="form-control" placeholder="e.g. Paracetamol" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brand / Manufacturer</label>
                                <input type="text" name="brand_name" class="form-control" placeholder="e.g. Beximco">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="Painkiller">Painkiller</option>
                                    <option value="Antibiotic">Antibiotic</option>
                                    <option value="Antacid">Antacid</option>
                                    <option value="Vitamin">Vitamin</option>
                                    <option value="Antihistamine">Antihistamine</option>
                                    <option value="Antiviral">Antiviral</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dosage Form <span class="text-danger">*</span></label>
                                <select name="dosage_form" class="form-control" required>
                                    <option value="">Select Form</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Cream">Cream</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Drops">Drops</option>
                                    <option value="Inhaler">Inhaler</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Strength</label>
                                <input type="text" name="strength" class="form-control" placeholder="e.g. 500mg">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit" class="form-control">
                                    <option value="Piece">Piece</option>
                                    <option value="Box">Box</option>
                                    <option value="Bottle">Bottle</option>
                                    <option value="Strip">Strip</option>
                                    <option value="Tube">Tube</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit Price <span class="text-danger">*</span></label>
                                <input type="number" name="unit_price" class="form-control" step="0.01" min="0" placeholder="e.g. 5.00" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Reorder Level</label>
                                <input type="number" name="reorder_level" class="form-control" min="0" value="10" placeholder="e.g. 10">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control" placeholder="Write medicine details..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="text-right">
                        <a href="medicines.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="save_medicine" class="btn btn-primary"><i class="fa fa-save"></i> Save Medicine</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <i class="fa fa-info-circle" style="font-size:35px; color:#009efb;"></i>
                    </div>
                    <div class="col-md-11">
                        <h5>Medicine & Branch Availability</h5>
                        <p class="text-muted mb-0">After adding the medicine, you can manage which pharmacy branches have this medicine available from the Medicine Availability section.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php require_once "../component/footer.php"; ?>
</div>