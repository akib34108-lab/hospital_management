<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

// Demo data for UI testing
$medicine_id = isset($_GET['id']) ? $_GET['id'] : 1;

$medicine = [
    "medicine_name" => "Napa",
    "generic_name" => "Paracetamol",
    "brand_name" => "Beximco",
    "category" => "Painkiller",
    "dosage_form" => "Tablet",
    "strength" => "500mg",
    "unit" => "Piece",
    "description" => "Used for the temporary relief of mild to moderate pain and fever.",
    "status" => "Active"
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Edit Medicine</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="medicines.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back to Medicines
                </a>
            </div>
        </div>


        <!-- Medicine Information -->
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">
                    <i class="fa fa-pencil"></i>
                    Edit Medicine Information
                </h4>

                <p class="text-muted mb-0">
                    Update the information of this medicine.
                </p>
            </div>


            <div class="card-body">

                <form method="POST" action="">

                    <input type="hidden"
                           name="medicine_id"
                           value="<?= htmlspecialchars($medicine_id); ?>">


                    <div class="row">

                        <!-- Medicine Name -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Medicine Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="medicine_name"
                                       class="form-control"
                                       value="<?= htmlspecialchars($medicine['medicine_name']); ?>"
                                       required>

                            </div>
                        </div>


                        <!-- Generic Name -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Generic Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="generic_name"
                                       class="form-control"
                                       value="<?= htmlspecialchars($medicine['generic_name']); ?>"
                                       required>

                            </div>
                        </div>


                        <!-- Brand -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Brand / Manufacturer
                                </label>

                                <input type="text"
                                       name="brand_name"
                                       class="form-control"
                                       value="<?= htmlspecialchars($medicine['brand_name']); ?>">

                            </div>
                        </div>


                        <!-- Category -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Category
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="category"
                                        class="form-control"
                                        required>

                                    <option value="">Select Category</option>

                                    <option value="Painkiller"
                                        <?= $medicine['category'] == 'Painkiller' ? 'selected' : ''; ?>>
                                        Painkiller
                                    </option>

                                    <option value="Antibiotic"
                                        <?= $medicine['category'] == 'Antibiotic' ? 'selected' : ''; ?>>
                                        Antibiotic
                                    </option>

                                    <option value="Antacid"
                                        <?= $medicine['category'] == 'Antacid' ? 'selected' : ''; ?>>
                                        Antacid
                                    </option>

                                    <option value="Vitamin"
                                        <?= $medicine['category'] == 'Vitamin' ? 'selected' : ''; ?>>
                                        Vitamin
                                    </option>

                                    <option value="Antihistamine"
                                        <?= $medicine['category'] == 'Antihistamine' ? 'selected' : ''; ?>>
                                        Antihistamine
                                    </option>

                                    <option value="Antiviral"
                                        <?= $medicine['category'] == 'Antiviral' ? 'selected' : ''; ?>>
                                        Antiviral
                                    </option>

                                    <option value="Other"
                                        <?= $medicine['category'] == 'Other' ? 'selected' : ''; ?>>
                                        Other
                                    </option>

                                </select>

                            </div>
                        </div>


                        <!-- Dosage Form -->
                        <div class="col-md-4">
                            <div class="form-group">

                                <label>
                                    Dosage Form
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="dosage_form"
                                        class="form-control"
                                        required>

                                    <option value="">Select Form</option>

                                    <option value="Tablet"
                                        <?= $medicine['dosage_form'] == 'Tablet' ? 'selected' : ''; ?>>
                                        Tablet
                                    </option>

                                    <option value="Capsule"
                                        <?= $medicine['dosage_form'] == 'Capsule' ? 'selected' : ''; ?>>
                                        Capsule
                                    </option>

                                    <option value="Syrup"
                                        <?= $medicine['dosage_form'] == 'Syrup' ? 'selected' : ''; ?>>
                                        Syrup
                                    </option>

                                    <option value="Injection"
                                        <?= $medicine['dosage_form'] == 'Injection' ? 'selected' : ''; ?>>
                                        Injection
                                    </option>

                                    <option value="Cream"
                                        <?= $medicine['dosage_form'] == 'Cream' ? 'selected' : ''; ?>>
                                        Cream
                                    </option>

                                    <option value="Ointment"
                                        <?= $medicine['dosage_form'] == 'Ointment' ? 'selected' : ''; ?>>
                                        Ointment
                                    </option>

                                    <option value="Drops"
                                        <?= $medicine['dosage_form'] == 'Drops' ? 'selected' : ''; ?>>
                                        Drops
                                    </option>

                                    <option value="Inhaler"
                                        <?= $medicine['dosage_form'] == 'Inhaler' ? 'selected' : ''; ?>>
                                        Inhaler
                                    </option>

                                </select>

                            </div>
                        </div>


                        <!-- Strength -->
                        <div class="col-md-4">
                            <div class="form-group">

                                <label>Strength</label>

                                <input type="text"
                                       name="strength"
                                       class="form-control"
                                       value="<?= htmlspecialchars($medicine['strength']); ?>"
                                       placeholder="e.g. 500mg">

                            </div>
                        </div>


                        <!-- Unit -->
                        <div class="col-md-4">
                            <div class="form-group">

                                <label>Unit</label>

                                <select name="unit"
                                        class="form-control">

                                    <option value="Piece"
                                        <?= $medicine['unit'] == 'Piece' ? 'selected' : ''; ?>>
                                        Piece
                                    </option>

                                    <option value="Box"
                                        <?= $medicine['unit'] == 'Box' ? 'selected' : ''; ?>>
                                        Box
                                    </option>

                                    <option value="Bottle"
                                        <?= $medicine['unit'] == 'Bottle' ? 'selected' : ''; ?>>
                                        Bottle
                                    </option>

                                    <option value="Strip"
                                        <?= $medicine['unit'] == 'Strip' ? 'selected' : ''; ?>>
                                        Strip
                                    </option>

                                    <option value="Tube"
                                        <?= $medicine['unit'] == 'Tube' ? 'selected' : ''; ?>>
                                        Tube
                                    </option>

                                </select>

                            </div>
                        </div>


                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>Description</label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Write medicine details..."><?= htmlspecialchars($medicine['description']); ?></textarea>

                            </div>
                        </div>


                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>Status</label>

                                <select name="status"
                                        class="form-control">

                                    <option value="Active"
                                        <?= $medicine['status'] == 'Active' ? 'selected' : ''; ?>>
                                        Active
                                    </option>

                                    <option value="Inactive"
                                        <?= $medicine['status'] == 'Inactive' ? 'selected' : ''; ?>>
                                        Inactive
                                    </option>

                                </select>

                            </div>
                        </div>

                    </div>


                    <!-- Action Buttons -->
                    <div class="text-right">

                        <a href="medicines.php"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                name="update_medicine"
                                class="btn btn-primary">

                            <i class="fa fa-save"></i>
                            Update Medicine

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Branch Availability Shortcut -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-hospital-o"
                           style="
                           font-size:35px;
                           color:#009efb;
                           ">
                        </i>

                    </div>

                    <div class="col-md-8">

                        <h5>
                            Branch Availability
                        </h5>

                        <p class="text-muted mb-0">
                            After updating the medicine, you can check
                            which pharmacy branches have this medicine.
                        </p>

                    </div>

                    <div class="col-md-3 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-outline-info">

                            <i class="fa fa-search"></i>
                            Check Availability

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php
    require_once "../component/footer.php";
    ?>

</div>