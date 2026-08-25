<?php

require_once "../component/header.php";
require_once "../component/sidebar.php";

$branch_result = $crud->common_select("pharmacy_branches", "*", [], "AND", "branch_id", "DESC");
$total_branches = $crud->number_of_records("pharmacy_branches");
$active_branches = $crud->common_count("pharmacy_branches", ["status" => "Active"]);
$inactive_branches = $crud->common_count("pharmacy_branches", ["status" => "Inactive"]);
?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Pharmacy Branches</h4>
            </div>
            <div class="col-sm-5 col-6 text-right">
                <a href="add_branch.php" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add Branch</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-info"><i class="fa fa-hospital-o"></i></span>
                        <div class="dash-widget-info">
                            <h3><?php echo $total_branches; ?></h3>
                            <span>Total Branches</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-success"><i class="fa fa-check-circle"></i></span>
                        <div class="dash-widget-info">
                            <h3><?php echo $active_branches; ?></h3>
                            <span>Active Branches</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-warning"><i class="fa fa-exclamation-circle"></i></span>
                        <div class="dash-widget-info">
                            <h3><?php echo $inactive_branches; ?></h3>
                            <span>Inactive Branches</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title mb-0"><i class="fa fa-hospital-o"></i> Pharmacy Branch List</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="medicine_availability.php" class="btn btn-outline-info btn-sm"><i class="fa fa-search"></i> Medicine Availability</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="branchSearch" class="form-control" placeholder="Search branch...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped custom-table" id="branchTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Branch Name</th>
                                <th>Branch Code</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php if ($branch_result["status"]): ?>
                            <?php $serial = 1; ?>
                            <?php foreach ($branch_result["data"] as $branch): ?>
                                <tr>
                                    <td><?php echo $serial++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($branch->branch_name ?? ''); ?></strong></td>

                                    <td>
                                        <?php
                                        if (isset($branch->branch_code) && $branch->branch_code !== null && $branch->branch_code !== '') {
                                            echo htmlspecialchars($branch->branch_code);
                                        } else {
                                            echo '<span class="text-muted">-</span>';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if (isset($branch->location) && $branch->location !== null && $branch->location !== '') {
                                            echo htmlspecialchars($branch->location);
                                        } else {
                                            echo '<span class="text-muted">-</span>';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if (isset($branch->address) && $branch->address !== null && $branch->address !== '') {
                                            echo htmlspecialchars($branch->address);
                                        } else {
                                            echo '<span class="text-muted">-</span>';
                                        }
                                        ?>
                                    </td>

                                    <td><?php echo htmlspecialchars($branch->phone ?? ''); ?></td>

                                    <td>
                                        <?php $status = $branch->status ?? 'Inactive'; ?>
                                        <?php if ($status == "Active"): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="branch_view.php?id=<?php echo $branch->branch_id; ?>"><i class="fa fa-eye m-r-5"></i> View</a>
                                                <a class="dropdown-item" href="edit_branch.php?id=<?php echo $branch->branch_id; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="noBranchMessage">
                                <td colspan="8" class="text-center text-muted" style="padding:30px;">
                                    <i class="fa fa-hospital-o" style="font-size:30px;"></i><br><br>
                                    No pharmacy branch found.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5><i class="fa fa-info-circle text-info"></i> Medicine Availability</h5>
                        <p class="text-muted mb-0">Check which pharmacy branch has a particular medicine.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="medicine_availability.php" class="btn btn-info"><i class="fa fa-search"></i> Find Medicine</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php require_once "../component/footer.php"; ?>
</div>

<script>
$(document).ready(function () {
    $("#branchSearch").on("keyup", function () {
        var searchValue = $(this).val().toLowerCase();
        var visibleRows = 0;

        $("#branchTable tbody tr").each(function () {
            if ($(this).attr("id") === "noBranchMessage") return;

            var rowText = $(this).text().toLowerCase();

            if (rowText.includes(searchValue)) {
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
    });
});
</script>