<?php

require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";


/* =========================
   Get Medicine ID
   ========================= */

$medicine_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


/* =========================
   Check Medicine ID
   ========================= */

if ($medicine_id <= 0) {

    echo "<script>
            alert('Invalid medicine ID');
            window.location.href='medicines.php';
          </script>";

    exit;
}


/* =========================
   Get Medicine Data
   ========================= */

$result = $crud->common_select(
    "medicines",
    "*",
    ["medicine_id" => $medicine_id]
);


if (!$result["status"]) {

    echo "<script>
            alert('Medicine not found');
            window.location.href='medicines.php';
          </script>";

    exit;
}


/* Get first medicine */

$medicine = $result["data"][0];


/* =========================
   Update Medicine
   ========================= */

if (isset($_POST['update_medicine'])) {

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


    /* Data to update */

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


    /* Update database */

    $update_result = $crud->common_update(
        "medicines",
        $data,
        ["medicine_id" => $medicine_id]
    );


    /* Check result */

    if ($update_result["status"]) {

        echo "<script>
                alert('Medicine updated successfully');
                window.location.href='medicines.php';
              </script>";

        exit;

    } else {

        echo "<script>
                alert('Error: " . $update_result["message"] . "');
              </script>";
    }


    /* Keep updated values in form */

    $medicine->medicine_name = $medicine_name;
    $medicine->generic_name = $generic_name;
    $medicine->manufacturer = $brand_name;
    $medicine->category = $category;
    $medicine->dosage_form = $dosage_form;
    $medicine->strength = $strength;
    $medicine->unit = $unit;
    $medicine->description = $description;
    $medicine->status = $status;
    $medicine->unit_price = $unit_price;
    $medicine->reorder_level = $reorder_level;
    $medicine->expiry_date = $expiry_date;
}

?>


<div class="page-wrapper">

    <div class="content">


        <!-- Page Header -->
        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Edit Medicine
                </h4>

            </div>


            <div class="col-sm-5 col-6 text-right">

                <a href="medicines.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-arrow-left"></i>

                    Back to Medicines

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
                           value="<?php echo $medicine_id; ?>">


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
                                       value="<?php echo htmlspecialchars($medicine->medicine_name); ?>"
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
                                       value="<?php echo htmlspecialchars($medicine->generic_name); ?>"
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
                                       value="<?php echo htmlspecialchars($medicine->manufacturer); ?>">

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


                                    <option value="">
                                        Select Category
                                    </option>


                                    <option value="Painkiller"
                                        <?php echo ($medicine->category == 'Painkiller') ? 'selected' : ''; ?>>

                                        Painkiller

                                    </option>


                                    <option value="Antibiotic"
                                        <?php echo ($medicine->category == 'Antibiotic') ? 'selected' : ''; ?>>

                                        Antibiotic

                                    </option>


                                    <option value="Antacid"
                                        <?php echo ($medicine->category == 'Antacid') ? 'selected' : ''; ?>>

                                        Antacid

                                    </option>


                                    <option value="Vitamin"
                                        <?php echo ($medicine->category == 'Vitamin') ? 'selected' : ''; ?>>

                                        Vitamin

                                    </option>


                                    <option value="Antihistamine"
                                        <?php echo ($medicine->category == 'Antihistamine') ? 'selected' : ''; ?>>

                                        Antihistamine

                                    </option>


                                    <option value="Antiviral"
                                        <?php echo ($medicine->category == 'Antiviral') ? 'selected' : ''; ?>>

                                        Antiviral

                                    </option>


                                    <option value="Other"
                                        <?php echo ($medicine->category == 'Other') ? 'selected' : ''; ?>>

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


                                    <option value="">
                                        Select Form
                                    </option>


                                    <option value="Tablet"
                                        <?php echo ($medicine->dosage_form == 'Tablet') ? 'selected' : ''; ?>>

                                        Tablet

                                    </option>


                                    <option value="Capsule"
                                        <?php echo ($medicine->dosage_form == 'Capsule') ? 'selected' : ''; ?>>

                                        Capsule

                                    </option>


                                    <option value="Syrup"
                                        <?php echo ($medicine->dosage_form == 'Syrup') ? 'selected' : ''; ?>>

                                        Syrup

                                    </option>


                                    <option value="Injection"
                                        <?php echo ($medicine->dosage_form == 'Injection') ? 'selected' : ''; ?>>

                                        Injection

                                    </option>


                                    <option value="Cream"
                                        <?php echo ($medicine->dosage_form == 'Cream') ? 'selected' : ''; ?>>

                                        Cream

                                    </option>


                                    <option value="Ointment"
                                        <?php echo ($medicine->dosage_form == 'Ointment') ? 'selected' : ''; ?>>

                                        Ointment

                                    </option>


                                    <option value="Drops"
                                        <?php echo ($medicine->dosage_form == 'Drops') ? 'selected' : ''; ?>>

                                        Drops

                                    </option>


                                    <option value="Inhaler"
                                        <?php echo ($medicine->dosage_form == 'Inhaler') ? 'selected' : ''; ?>>

                                        Inhaler

                                    </option>


                                </select>

                            </div>

                        </div>


                        <!-- Strength -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Strength
                                </label>


                                <input type="text"
                                       name="strength"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($medicine->strength); ?>"
                                       placeholder="e.g. 500mg">

                            </div>

                        </div>


                        <!-- Unit -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Unit
                                </label>


                                <select name="unit"
                                        class="form-control">


                                    <option value="Piece"
                                        <?php echo ($medicine->unit == 'Piece') ? 'selected' : ''; ?>>

                                        Piece

                                    </option>


                                    <option value="Box"
                                        <?php echo ($medicine->unit == 'Box') ? 'selected' : ''; ?>>

                                        Box

                                    </option>


                                    <option value="Bottle"
                                        <?php echo ($medicine->unit == 'Bottle') ? 'selected' : ''; ?>>

                                        Bottle

                                    </option>


                                    <option value="Strip"
                                        <?php echo ($medicine->unit == 'Strip') ? 'selected' : ''; ?>>

                                        Strip

                                    </option>


                                    <option value="Tube"
                                        <?php echo ($medicine->unit == 'Tube') ? 'selected' : ''; ?>>

                                        Tube

                                    </option>


                                </select>

                            </div>

                        </div>


                        <!-- Unit Price -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Unit Price

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="number"
                                       name="unit_price"
                                       class="form-control"
                                       step="0.01"
                                       min="0"
                                       value="<?php echo htmlspecialchars($medicine->unit_price); ?>"
                                       required>

                            </div>

                        </div>


                        <!-- Reorder Level -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Reorder Level
                                </label>


                                <input type="number"
                                       name="reorder_level"
                                       class="form-control"
                                       min="0"
                                       value="<?php echo htmlspecialchars($medicine->reorder_level); ?>">

                            </div>

                        </div>


                        <!-- Expiry Date -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Expiry Date
                                </label>


                                <input type="date"
                                       name="expiry_date"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($medicine->expiry_date); ?>">

                            </div>

                        </div>


                        <!-- Description -->
                        <div class="col-md-12">

                            <div class="form-group">

                                <label>
                                    Description
                                </label>


                                <textarea name="description"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Write medicine details..."><?php echo htmlspecialchars($medicine->description); ?></textarea>

                            </div>

                        </div>


                        <!-- Status -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Status
                                </label>


                                <select name="status"
                                        class="form-control">


                                    <option value="Active"
                                        <?php echo ($medicine->status == 'Active') ? 'selected' : ''; ?>>

                                        Active

                                    </option>


                                    <option value="Inactive"
                                        <?php echo ($medicine->status == 'Inactive') ? 'selected' : ''; ?>>

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