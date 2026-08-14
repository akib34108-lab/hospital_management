<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

// Demo branch data
$branches = [
    [
        "id" => 1,
        "name" => "SHIFA Main Pharmacy",
        "code" => "SHP-001",
        "location" => "Dhaka",
        "phone" => "01700000001",
        "manager" => "Pharmacy Manager",
        "status" => "Active"
    ],
    [
        "id" => 2,
        "name" => "SHIFA Chattogram Pharmacy",
        "code" => "SHP-002",
        "location" => "Chattogram",
        "phone" => "01700000002",
        "manager" => "Branch Manager",
        "status" => "Active"
    ],
    [
        "id" => 3,
        "name" => "SHIFA Agrabad Pharmacy",
        "code" => "SHP-003",
        "location" => "Agrabad",
        "phone" => "01700000003",
        "manager" => "Branch Manager",
        "status" => "Inactive"
    ]
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Pharmacy Branches</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="add_branch.php"
                   class="btn btn-primary btn-rounded">
                    <i class="fa fa-plus"></i>
                    Add Branch
                </a>
            </div>
        </div>


        <!-- Summary Cards -->
        <div class="row">

            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-info">
                            <i class="fa fa-hospital-o"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>3</h3>
                            <span>Total Branches</span>
                        </div>

                    </div>

                </div>
            </div>


            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-success">
                            <i class="fa fa-check-circle"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>2</h3>
                            <span>Active Branches</span>
                        </div>

                    </div>

                </div>
            </div>


            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-danger">
                            <i class="fa fa-times-circle"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>1</h3>
                            <span>Inactive Branches</span>
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <!-- Branch List -->
        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-md-6">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-hospital-o"></i>
                            Branch List
                        </h4>
                    </div>

                    <div class="col-md-6 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-outline-info btn-sm">

                            <i class="fa fa-search"></i>
                            Medicine Availability

                        </a>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <!-- Search & Filter -->
                <div class="row mb-3">

                    <div class="col-md-7">

                        <div class="input-group">

                            <input type="text"
                                   id="branchSearch"
                                   class="form-control"
                                   placeholder="Search branch, code, location or manager...">

                            <div class="input-group-append">

                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-5">

                        <select id="branchStatusFilter"
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
                           id="branchTable">

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Branch</th>
                                <th>Code</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Manager</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($branches as $branch): ?>

                                <tr>

                                    <td>
                                        <?= $branch['id']; ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?= htmlspecialchars($branch['name']); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <span class="badge badge-light">
                                            <?= htmlspecialchars($branch['code']); ?>
                                        </span>

                                    </td>


                                    <td>

                                        <i class="fa fa-map-marker text-muted"></i>

                                        <?= htmlspecialchars($branch['location']); ?>

                                    </td>


                                    <td>

                                        <i class="fa fa-phone text-muted"></i>

                                        <?= htmlspecialchars($branch['phone']); ?>

                                    </td>


                                    <td>
                                        <?= htmlspecialchars($branch['manager']); ?>
                                    </td>


                                    <td>

                                        <?php if ($branch['status'] == "Active"): ?>

                                            <span class="badge badge-success">

                                                <i class="fa fa-check"></i>
                                                Active

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-danger">

                                                <i class="fa fa-times"></i>
                                                Inactive

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td class="text-right">

                                        <div class="dropdown dropdown-action">

                                            <a href="#"
                                               class="action-icon dropdown-toggle"
                                               data-toggle="dropdown">

                                                <i class="fa fa-ellipsis-v"></i>

                                            </a>


                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a class="dropdown-item"
                                                   href="medicine_availability.php?branch_id=<?= $branch['id']; ?>">

                                                    <i class="fa fa-search m-r-5"></i>
                                                    View Medicines

                                                </a>


                                                <a class="dropdown-item"
                                                   href="edit_branch.php?id=<?= $branch['id']; ?>">

                                                    <i class="fa fa-pencil m-r-5"></i>
                                                    Edit

                                                </a>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <!-- No Result -->
                            <tr id="noBranchMessage"
                                style="display:none;">

                                <td colspan="8"
                                    class="text-center text-muted"
                                    style="padding:30px;">

                                    <i class="fa fa-hospital-o"
                                       style="font-size:30px;">
                                    </i>

                                    <br><br>

                                    No branch found.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Branch & Medicine Connection -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-link"
                           style="
                           font-size:38px;
                           color:#009efb;
                           ">
                        </i>

                    </div>


                    <div class="col-md-8">

                        <h5>
                            Branch-wise Medicine Management
                        </h5>

                        <p class="text-muted mb-0">

                            Manage and track which medicines are
                            available at each pharmacy branch.

                        </p>

                    </div>


                    <div class="col-md-3 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-primary">

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


<script>
$(document).ready(function () {

    function filterBranches() {

        var searchValue =
            $("#branchSearch").val().toLowerCase();

        var statusValue =
            $("#branchStatusFilter").val().toLowerCase();

        var visibleRows = 0;


        $("#branchTable tbody tr").each(function () {

            if ($(this).attr("id") === "noBranchMessage") {
                return;
            }


            var rowText =
                $(this).text().toLowerCase();


            var statusText =
                $(this)
                .find("td:eq(6)")
                .text()
                .trim()
                .toLowerCase();


            var searchMatch =
                rowText.includes(searchValue);


            var statusMatch =
                statusValue === "" ||
                statusText === statusValue;


            if (searchMatch && statusMatch) {

                $(this).show();

                visibleRows++;

            } else {

                $(this).hide();

            }

        });


        if (visibleRows === 0) {

            $("#noBranchMessage").show();

        } else {

            $("#noBranchMessage").hide();

        }
    }


    $("#branchSearch").on("keyup", function () {
        filterBranches();
    });


    $("#branchStatusFilter").on("change", function () {
        filterBranches();
    });

});
</script>