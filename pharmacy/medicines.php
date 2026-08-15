<?php

require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";


/* =========================
   Get Medicines
   ========================= */

$medicine_result = $crud->common_select(
    "medicines",
    "*",
    [],
    "AND",
    "medicine_id",
    "DESC"
);


/* =========================
   Medicine Statistics
   ========================= */

$total_medicines = $crud->number_of_records("medicines");

$active_result = $crud->common_count(
    "medicines",
    ["status" => "Active"]
);

$inactive_result = $crud->common_count(
    "medicines",
    ["status" => "Inactive"]
);

?>

<div class="page-wrapper">

    <div class="content">


        <!-- Page Header -->
        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Medicines
                </h4>

            </div>


            <div class="col-sm-5 col-6 text-right">

                <a href="add_medicine.php"
                   class="btn btn-primary btn-rounded">

                    <i class="fa fa-plus"></i>
                    Add Medicine

                </a>

            </div>

        </div>


        <!-- Summary Cards -->
        <div class="row">


            <!-- Total Medicines -->
            <div class="col-md-4 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-info">

                            <i class="fa fa-medkit"></i>

                        </span>


                        <div class="dash-widget-info">

                            <h3>
                                <?php echo $total_medicines; ?>
                            </h3>

                            <span>
                                Total Medicines
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Active Medicines -->
            <div class="col-md-4 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-success">

                            <i class="fa fa-check-circle"></i>

                        </span>


                        <div class="dash-widget-info">

                            <h3>
                                <?php echo $active_result; ?>
                            </h3>

                            <span>
                                Active Medicines
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Inactive Medicines -->
            <div class="col-md-4 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-warning">

                            <i class="fa fa-exclamation-circle"></i>

                        </span>


                        <div class="dash-widget-info">

                            <h3>
                                <?php echo $inactive_result; ?>
                            </h3>

                            <span>
                                Inactive Medicines
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Medicine List -->
        <div class="card">


            <div class="card-header">

                <div class="row align-items-center">


                    <div class="col-md-6">

                        <h4 class="card-title mb-0">

                            <i class="fa fa-medkit"></i>
                            Medicine List

                        </h4>

                    </div>


                    <div class="col-md-6 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-outline-info btn-sm">

                            <i class="fa fa-search"></i>

                            Check Branch Availability

                        </a>

                    </div>

                </div>

            </div>


            <div class="card-body">


                <!-- Search & Filter -->
                <div class="row mb-3">


                    <!-- Search -->
                    <div class="col-md-6">

                        <div class="input-group">

                            <input type="text"
                                   id="medicineSearch"
                                   class="form-control"
                                   placeholder="Search medicine, generic or brand...">


                            <div class="input-group-append">

                                <button class="btn btn-primary">

                                    <i class="fa fa-search"></i>

                                </button>

                            </div>

                        </div>

                    </div>


                    <!-- Category -->
                    <div class="col-md-3">

                        <select id="categoryFilter"
                                class="form-control">

                            <option value="">
                                All Categories
                            </option>

                            <option value="Painkiller">
                                Painkiller
                            </option>

                            <option value="Antibiotic">
                                Antibiotic
                            </option>

                            <option value="Antacid">
                                Antacid
                            </option>

                            <option value="Vitamin">
                                Vitamin
                            </option>

                            <option value="Antihistamine">
                                Antihistamine
                            </option>

                            <option value="Antiviral">
                                Antiviral
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>


                    <!-- Status -->
                    <div class="col-md-3">

                        <select id="statusFilter"
                                class="form-control">

                            <option value="">
                                All Status
                            </option>

                            <option value="Active">
                                Active
                            </option>

                            <option value="Inactive">
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Table -->
                <div class="table-responsive">

                    <table class="table table-striped custom-table"
                           id="medicineTable">


                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    Medicine
                                </th>

                                <th>
                                    Generic Name
                                </th>

                                <th>
                                    Brand
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Strength
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-right">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php if ($medicine_result["status"]): ?>


                            <?php
                            $serial = 1;
                            ?>


                            <?php foreach ($medicine_result["data"] as $medicine): ?>


                                <tr>


                                    <!-- Serial -->
                                    <td>
                                        <?php echo $serial++; ?>
                                    </td>


                                    <!-- Medicine -->
                                    <td>

                                        <strong>

                                            <?php
                                            echo htmlspecialchars(
                                                $medicine->medicine_name
                                            );
                                            ?>

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            <?php
                                            echo htmlspecialchars(
                                                $medicine->dosage_form
                                            );
                                            ?>

                                        </small>

                                    </td>


                                    <!-- Generic Name -->
                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $medicine->generic_name
                                        );
                                        ?>

                                    </td>


                                    <!-- Brand -->
                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $medicine->manufacturer
                                        );
                                        ?>

                                    </td>


                                    <!-- Category -->
                                    <td>

                                        <span class="badge badge-info">

                                            <?php
                                            echo htmlspecialchars(
                                                $medicine->category
                                            );
                                            ?>

                                        </span>

                                    </td>


                                    <!-- Strength -->
                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $medicine->strength
                                        );
                                        ?>

                                    </td>


                                    <!-- Status -->
                                    <td>


                                        <?php if ($medicine->status == "Active"): ?>


                                            <span class="badge badge-success">

                                                Active

                                            </span>


                                        <?php else: ?>


                                            <span class="badge badge-warning">

                                                Inactive

                                            </span>


                                        <?php endif; ?>


                                    </td>


                                    <!-- Actions -->
                                    <td class="text-right">


                                        <div class="dropdown dropdown-action">


                                            <a href="#"
                                               class="action-icon dropdown-toggle"
                                               data-toggle="dropdown">

                                                <i class="fa fa-ellipsis-v"></i>

                                            </a>


                                            <div class="dropdown-menu dropdown-menu-right">


                                                <!-- View -->
                                                <a class="dropdown-item"
                                                   href="medicine_view.php?id=<?php echo $medicine->medicine_id; ?>">

                                                    <i class="fa fa-eye m-r-5"></i>

                                                    View

                                                </a>


                                                <!-- Edit -->
                                                <a class="dropdown-item"
                                                   href="edit_medicine.php?id=<?php echo $medicine->medicine_id; ?>">

                                                    <i class="fa fa-pencil m-r-5"></i>

                                                    Edit

                                                </a>


                                            </div>

                                        </div>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php else: ?>


                            <!-- No Medicine -->
                            <tr id="noMedicineMessage">

                                <td colspan="8"
                                    class="text-center text-muted"
                                    style="padding:30px;">

                                    <i class="fa fa-medkit"
                                       style="font-size:30px;">
                                    </i>

                                    <br><br>

                                    No medicine found.

                                </td>

                            </tr>


                        <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Information Card -->
        <div class="card">

            <div class="card-body">


                <div class="row align-items-center">


                    <div class="col-md-8">

                        <h5>

                            <i class="fa fa-info-circle text-info"></i>

                            Medicine Availability

                        </h5>


                        <p class="text-muted mb-0">

                            Want to know which SHIFA pharmacy branch
                            has a particular medicine?

                        </p>

                    </div>


                    <div class="col-md-4 text-right">


                        <a href="medicine_availability.php"
                           class="btn btn-info">

                            <i class="fa fa-search"></i>

                            Find Medicine

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


<!-- Search & Filter -->
<script>

$(document).ready(function () {


    function filterMedicines() {


        var searchValue =
            $("#medicineSearch").val().toLowerCase();


        var categoryValue =
            $("#categoryFilter").val().toLowerCase();


        var statusValue =
            $("#statusFilter").val().toLowerCase();


        var visibleRows = 0;


        $("#medicineTable tbody tr").each(function () {


            if ($(this).attr("id") === "noMedicineMessage") {

                return;

            }


            var rowText =
                $(this).text().toLowerCase();


            var categoryText =
                $(this)
                .find("td:eq(4)")
                .text()
                .trim()
                .toLowerCase();


            var statusText =
                $(this)
                .find("td:eq(6)")
                .text()
                .trim()
                .toLowerCase();


            var searchMatch =
                rowText.includes(searchValue);


            var categoryMatch =
                categoryValue === "" ||
                categoryText === categoryValue;


            var statusMatch =
                statusValue === "" ||
                statusText === statusValue;


            if (
                searchMatch &&
                categoryMatch &&
                statusMatch
            ) {

                $(this).show();

                visibleRows++;

            } else {

                $(this).hide();

            }

        });


        if (visibleRows === 0) {

            $("#noMedicineMessage").show();

        } else {

            $("#noMedicineMessage").hide();

        }

    }


    $("#medicineSearch").on(
        "keyup",
        function () {
            filterMedicines();
        }
    );


    $("#categoryFilter").on(
        "change",
        function () {
            filterMedicines();
        }
    );


    $("#statusFilter").on(
        "change",
        function () {
            filterMedicines();
        }
    );


});

</script>