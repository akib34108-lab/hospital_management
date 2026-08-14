<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

/*
|--------------------------------------------------------------------------
| Demo Data
|--------------------------------------------------------------------------
| পরে database ready হলে এই অংশ SQL দিয়ে replace করা হবে।
*/

// Demo medicines
$medicines = [
    [
        "id" => 1,
        "name" => "Napa",
        "generic" => "Paracetamol",
        "strength" => "500mg",
        "form" => "Tablet"
    ],
    [
        "id" => 2,
        "name" => "Seclo",
        "generic" => "Omeprazole",
        "strength" => "20mg",
        "form" => "Capsule"
    ],
    [
        "id" => 3,
        "name" => "Napa Extra",
        "generic" => "Paracetamol + Caffeine",
        "strength" => "500mg + 65mg",
        "form" => "Tablet"
    ]
];


// Demo branches
$branches = [
    [
        "id" => 1,
        "name" => "SHIFA Main Pharmacy",
        "location" => "Dhaka"
    ],
    [
        "id" => 2,
        "name" => "SHIFA Chattogram Pharmacy",
        "location" => "Chattogram"
    ],
    [
        "id" => 3,
        "name" => "SHIFA Agrabad Pharmacy",
        "location" => "Agrabad"
    ]
];


// Demo availability
$availability = [
    [
        "medicine_id" => 1,
        "branch_id" => 1,
        "quantity" => 120
    ],
    [
        "medicine_id" => 1,
        "branch_id" => 2,
        "quantity" => 75
    ],
    [
        "medicine_id" => 1,
        "branch_id" => 3,
        "quantity" => 0
    ],

    [
        "medicine_id" => 2,
        "branch_id" => 1,
        "quantity" => 40
    ],
    [
        "medicine_id" => 2,
        "branch_id" => 2,
        "quantity" => 0
    ],
    [
        "medicine_id" => 2,
        "branch_id" => 3,
        "quantity" => 25
    ],

    [
        "medicine_id" => 3,
        "branch_id" => 1,
        "quantity" => 60
    ],
    [
        "medicine_id" => 3,
        "branch_id" => 2,
        "quantity" => 35
    ],
    [
        "medicine_id" => 3,
        "branch_id" => 3,
        "quantity" => 0
    ]
];


// Selected values
$selected_medicine = isset($_GET['medicine_id'])
    ? (int) $_GET['medicine_id']
    : 1;

$selected_branch = isset($_GET['branch_id'])
    ? (int) $_GET['branch_id']
    : 0;
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Medicine Availability
                </h4>

            </div>

            <div class="col-sm-5 col-6 text-right">

                <a href="medicines.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-medkit"></i>
                    Medicines

                </a>

                <a href="branches.php"
                   class="btn btn-info btn-rounded">

                    <i class="fa fa-hospital-o"></i>
                    Branches

                </a>

            </div>
        </div>


        <!-- Search / Filter -->
        <div class="card">

            <div class="card-header">

                <h4 class="card-title">

                    <i class="fa fa-search"></i>
                    Find Medicine Availability

                </h4>

                <p class="text-muted mb-0">

                    Find which pharmacy branch has a particular
                    medicine available.

                </p>

            </div>


            <div class="card-body">

                <form method="GET"
                      action="medicine_availability.php">

                    <div class="row">

                        <!-- Medicine -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Select Medicine
                                </label>

                                <select name="medicine_id"
                                        id="medicineSelect"
                                        class="form-control">

                                    <?php foreach ($medicines as $medicine): ?>

                                        <option
                                            value="<?= $medicine['id']; ?>"
                                            <?= $selected_medicine == $medicine['id']
                                                ? 'selected'
                                                : ''; ?>>

                                            <?= htmlspecialchars($medicine['name']); ?>
                                            -
                                            <?= htmlspecialchars($medicine['strength']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- Branch -->
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Pharmacy Branch
                                </label>

                                <select name="branch_id"
                                        class="form-control">

                                    <option value="0">
                                        All Branches
                                    </option>

                                    <?php foreach ($branches as $branch): ?>

                                        <option
                                            value="<?= $branch['id']; ?>"
                                            <?= $selected_branch == $branch['id']
                                                ? 'selected'
                                                : ''; ?>>

                                            <?= htmlspecialchars($branch['name']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- Search Button -->
                        <div class="col-md-2">

                            <div class="form-group">

                                <label>
                                    &nbsp;
                                </label>

                                <button type="submit"
                                        class="btn btn-primary btn-block">

                                    <i class="fa fa-search"></i>
                                    Search

                                </button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        <?php

        /*
        |--------------------------------------------------------------------------
        | Find Selected Medicine
        |--------------------------------------------------------------------------
        */

        $selectedMedicineData = null;

        foreach ($medicines as $medicine) {

            if ($medicine['id'] == $selected_medicine) {

                $selectedMedicineData = $medicine;
                break;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Availability
        |--------------------------------------------------------------------------
        */

        $total_quantity = 0;
        $available_branches = 0;
        $unavailable_branches = 0;

        foreach ($availability as $item) {

            if ($item['medicine_id'] != $selected_medicine) {
                continue;
            }

            if (
                $selected_branch != 0 &&
                $item['branch_id'] != $selected_branch
            ) {
                continue;
            }

            $total_quantity += $item['quantity'];

            if ($item['quantity'] > 0) {
                $available_branches++;
            } else {
                $unavailable_branches++;
            }
        }

        ?>


        <!-- Selected Medicine Overview -->
        <?php if ($selectedMedicineData): ?>

            <div class="card">

                <div class="card-body">

                    <div class="row align-items-center">

                        <!-- Icon -->
                        <div class="col-md-1 text-center">

                            <div style="
                                width:70px;
                                height:70px;
                                border-radius:12px;
                                background:#e8f7ff;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                margin:auto;
                            ">

                                <i class="fa fa-medkit"
                                   style="
                                   font-size:35px;
                                   color:#009efb;
                                   ">
                                </i>

                            </div>

                        </div>


                        <!-- Medicine Info -->
                        <div class="col-md-7">

                            <h3 class="mb-1">

                                <?= htmlspecialchars(
                                    $selectedMedicineData['name']
                                ); ?>

                            </h3>

                            <p class="text-muted mb-0">

                                <?= htmlspecialchars(
                                    $selectedMedicineData['generic']
                                ); ?>

                                &nbsp; | &nbsp;

                                <?= htmlspecialchars(
                                    $selectedMedicineData['strength']
                                ); ?>

                                &nbsp; | &nbsp;

                                <?= htmlspecialchars(
                                    $selectedMedicineData['form']
                                ); ?>

                            </p>

                        </div>


                        <!-- Total -->
                        <div class="col-md-4 text-right">

                            <small class="text-muted">
                                Total Available Quantity
                            </small>

                            <h2 class="text-primary mb-0">

                                <?= $total_quantity; ?>

                            </h2>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        <!-- Summary Cards -->
        <div class="row">

            <div class="col-md-4">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-success">

                            <i class="fa fa-check-circle"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                <?= $available_branches; ?>
                            </h3>

                            <span>
                                Available Branches
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-danger">

                            <i class="fa fa-times-circle"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                <?= $unavailable_branches; ?>
                            </h3>

                            <span>
                                Out of Stock
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-info">

                            <i class="fa fa-cubes"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                <?= $total_quantity; ?>
                            </h3>

                            <span>
                                Total Quantity
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Branch Availability Table -->
        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-md-7">

                        <h4 class="card-title mb-0">

                            <i class="fa fa-hospital-o"></i>
                            Branch-wise Availability

                        </h4>

                    </div>

                    <div class="col-md-5 text-right">

                        <span class="badge badge-info"
                              style="font-size:13px; padding:8px;">

                            <?= htmlspecialchars(
                                $selectedMedicineData['name']
                            ); ?>

                        </span>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped custom-table">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    Pharmacy Branch
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Availability
                                </th>

                                <th class="text-right">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            $counter = 1;

                            foreach ($availability as $item):

                                if (
                                    $item['medicine_id']
                                    != $selected_medicine
                                ) {
                                    continue;
                                }


                                if (
                                    $selected_branch != 0 &&
                                    $item['branch_id']
                                    != $selected_branch
                                ) {
                                    continue;
                                }


                                // Find branch
                                $currentBranch = null;

                                foreach ($branches as $branch) {

                                    if (
                                        $branch['id']
                                        == $item['branch_id']
                                    ) {

                                        $currentBranch = $branch;
                                        break;

                                    }
                                }

                                if (!$currentBranch) {
                                    continue;
                                }

                            ?>

                                <tr>

                                    <td>
                                        <?= $counter++; ?>
                                    </td>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $currentBranch['name']
                                            ); ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <i class="fa fa-map-marker text-muted"></i>

                                        <?= htmlspecialchars(
                                            $currentBranch['location']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?php if ($item['quantity'] > 0): ?>

                                            <strong>

                                                <?= $item['quantity']; ?>

                                            </strong>

                                            units

                                        <?php else: ?>

                                            <span class="text-muted">
                                                0 units
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if ($item['quantity'] > 0): ?>

                                            <span class="badge badge-success"
                                                  style="padding:7px 10px;">

                                                <i class="fa fa-check"></i>

                                                Available

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-danger"
                                                  style="padding:7px 10px;">

                                                <i class="fa fa-times"></i>

                                                Out of Stock

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td class="text-right">

                                        <a href="new_sale.php?medicine_id=<?= $selected_medicine; ?>&branch_id=<?= $item['branch_id']; ?>"
                                           class="btn btn-success btn-sm">

                                            <i class="fa fa-shopping-cart"></i>

                                            Sell

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Smart Information -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-lightbulb-o"
                           style="
                           font-size:40px;
                           color:#f5a623;
                           ">
                        </i>

                    </div>


                    <div class="col-md-8">

                        <h5>
                            Smart Branch Finder
                        </h5>

                        <p class="text-muted mb-0">

                            Instead of checking each pharmacy manually,
                            staff can search for a medicine and instantly
                            identify which SHIFA branch has stock.

                        </p>

                    </div>


                    <div class="col-md-3 text-right">

                        <a href="medicines.php"
                           class="btn btn-outline-primary">

                            <i class="fa fa-medkit"></i>
                            Browse Medicines

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