<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";


// ==================================================
// 1. TOTAL MEDICINES
// ==================================================
$total_medicine_sql = "SELECT COUNT(*) AS total FROM medicines WHERE deleted_at IS NULL";
$total_medicine_result = $crud->conn->query($total_medicine_sql);
$total_medicines = 0;

if ($total_medicine_result) {
    $row = $total_medicine_result->fetch_assoc();
    $total_medicines = (int)$row['total'];
}

// ==================================================
// 2. TOTAL ACTIVE BRANCHES
// ==================================================
$total_branch_sql = "SELECT COUNT(*) AS total FROM pharmacy_branches WHERE deleted_at IS NULL";
$total_branch_result = $crud->conn->query($total_branch_sql);
$total_branches = 0;

if ($total_branch_result) {
    $row = $total_branch_result->fetch_assoc();
    $total_branches = (int)$row['total'];
}

// ==================================================
// 3. AVAILABLE MEDICINES
// Medicine which has stock > 0
// ==================================================
$available_medicine_sql = "SELECT COUNT(DISTINCT medicine_id) AS total FROM branch_medicines WHERE quantity > 0";
$available_medicine_result = $crud->conn->query($available_medicine_sql);
$available_medicines = 0;

if ($available_medicine_result) {
    $row = $available_medicine_result->fetch_assoc();
    $available_medicines = (int)$row['total'];
}

// ==================================================
// 4. TODAY'S SALES
// ==================================================
$today_sales_sql = "SELECT COALESCE(SUM(total_amount), 0) AS total FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) = CURDATE()";
$today_sales_result = $crud->conn->query($today_sales_sql);
$today_sales = 0;

if ($today_sales_result) {
    $row = $today_sales_result->fetch_assoc();
    $today_sales = (float)$row['total'];
}

// ==================================================
// 5. TODAY'S SALE COUNT
// ==================================================
$today_sale_count_sql = "SELECT COUNT(*) AS total FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) = CURDATE()";
$today_sale_count_result = $crud->conn->query($today_sale_count_sql);
$today_sale_count = 0;

if ($today_sale_count_result) {
    $row = $today_sale_count_result->fetch_assoc();
    $today_sale_count = (int)$row['total'];
}

// ==================================================
// 6. LOW STOCK MEDICINES
// ==================================================
$low_stock_sql = "SELECT COUNT(*) AS total FROM branch_medicines bm INNER JOIN medicines m ON bm.medicine_id = m.medicine_id WHERE bm.quantity <= m.reorder_level AND m.deleted_at IS NULL";
$low_stock_result = $crud->conn->query($low_stock_sql);
$low_stock = 0;

if ($low_stock_result) {
    $row = $low_stock_result->fetch_assoc();
    $low_stock = (int)$row['total'];
}

// ==================================================
// 7. RECENT SALES
// ==================================================
$recent_sales_sql = "SELECT ps.sale_id, ps.invoice_no, ps.customer_name, ps.total_amount, ps.status, pb.branch_name FROM pharmacy_sales ps INNER JOIN pharmacy_branches pb ON ps.branch_id = pb.branch_id WHERE ps.deleted_at IS NULL ORDER BY ps.sale_id DESC LIMIT 5";
$recent_sales_result = $crud->conn->query($recent_sales_sql);

// ==================================================
// 8. TOTAL STOCK
// ==================================================
$total_stock_sql = "SELECT COALESCE(SUM(quantity), 0) AS total FROM branch_medicines";
$total_stock_result = $crud->conn->query($total_stock_sql);
$total_stock = 0;

if ($total_stock_result) {
    $row = $total_stock_result->fetch_assoc();
    $total_stock = (int)$row['total'];
}

// ==================================================
// 9. TOTAL AVAILABILITY RECORDS
// ==================================================
$availability_count_sql = "SELECT COUNT(*) AS total FROM branch_medicines";
$availability_count_result = $crud->conn->query($availability_count_sql);
$total_availability = 0;

if ($availability_count_result) {
    $row = $availability_count_result->fetch_assoc();
    $total_availability = (int)$row['total'];
}
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Pharmacy</h4>
            </div>
            <div class="col-sm-5 col-6 text-right">
                <a href="new_sale.php" class="btn btn-primary btn-rounded">
                    <i class="fa fa-plus"></i>
                    New Sale
                </a>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border-radius:10px;background:linear-gradient(135deg,#009efb,#00c6ff);color:white;">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 style="color:white;margin-bottom:10px;">
                                    <i class="fa fa-medkit"></i>
                                    Pharmacy Management
                                </h3>
                                <p style="font-size:15px;margin-bottom:0;">
                                    Manage medicines, pharmacy branches, availability and sales from one place.
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="medicine_availability.php" class="btn btn-light">
                                    <i class="fa fa-search"></i>
                                    Find Medicine
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTICS -->
        <div class="row">

            <!-- Total Medicines -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-info">
                            <i class="fa fa-medkit"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3><?php echo $total_medicines; ?></h3>
                            <span>Total Medicines</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branches -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-success">
                            <i class="fa fa-hospital-o"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3><?php echo $total_branches; ?></h3>
                            <span>Pharmacy Branches</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Medicines -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fa fa-check-circle"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3><?php echo $available_medicines; ?></h3>
                            <span>Available Medicines</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon bg-warning">
                            <i class="fa fa-money"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>৳<?php echo number_format($today_sales,2); ?></h3>
                            <span>Today's Sales</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- EXTRA STATISTICS -->
        <div class="row">

            <!-- Today's Sale Count -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Today's Transactions</h6>
                                <h3><?php echo $today_sale_count; ?></h3>
                            </div>
                            <div>
                                <span class="dash-widget-icon bg-info">
                                    <i class="fa fa-shopping-cart"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Stock -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Stock Units</h6>
                                <h3><?php echo $total_stock; ?></h3>
                            </div>
                            <div>
                                <span class="dash-widget-icon bg-success">
                                    <i class="fa fa-cubes"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Low Stock Items</h6>
                                <h3><?php echo $low_stock; ?></h3>
                            </div>
                            <div>
                                <span class="dash-widget-icon bg-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-bolt"></i>
                            Quick Actions
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <!-- Add Medicine -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="add_medicine.php" class="btn btn-outline-primary btn-block" style="padding:15px;">
                                    <i class="fa fa-plus-circle"></i>
                                    <br>
                                    <span>Add Medicine</span>
                                </a>
                            </div>

                            <!-- New Sale -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="new_sale.php" class="btn btn-outline-success btn-block" style="padding:15px;">
                                    <i class="fa fa-shopping-cart"></i>
                                    <br>
                                    <span>New Sale</span>
                                </a>
                            </div>

                            <!-- Availability -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="medicine_availability.php" class="btn btn-outline-info btn-block" style="padding:15px;">
                                    <i class="fa fa-search"></i>
                                    <br>
                                    <span>Find Medicine</span>
                                </a>
                            </div>

                            <!-- Reports -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="reports.php" class="btn btn-outline-warning btn-block" style="padding:15px;">
                                    <i class="fa fa-bar-chart"></i>
                                    <br>
                                    <span>View Reports</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MANAGEMENT CARDS -->
        <div class="row">

            <!-- Medicine Management -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div style="width:60px;height:60px;margin:0 auto 15px;border-radius:50%;background:#e8f7ff;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-medkit" style="font-size:28px;color:#009efb;"></i>
                        </div>
                        <h4>Medicine Management</h4>
                        <p class="text-muted">Add, edit and view medicine information.</p>
                        <a href="medicines.php" class="btn btn-primary btn-sm">Manage Medicines</a>
                    </div>
                </div>
            </div>

            <!-- Branch Management -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div style="width:60px;height:60px;margin:0 auto 15px;border-radius:50%;background:#e9fff3;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-hospital-o" style="font-size:28px;color:#28a745;"></i>
                        </div>
                        <h4>Branch Management</h4>
                        <p class="text-muted">Manage all pharmacy branches and locations.</p>
                        <a href="branches.php" class="btn btn-success btn-sm">Manage Branches</a>
                    </div>
                </div>
            </div>

            <!-- Availability -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div style="width:60px;height:60px;margin:0 auto 15px;border-radius:50%;background:#fff8e6;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-search" style="font-size:28px;color:#f5a623;"></i>
                        </div>
                        <h4>Medicine Availability</h4>
                        <p class="text-muted">Find which branch has a particular medicine.</p>
                        <a href="medicine_availability.php" class="btn btn-warning btn-sm">Check Availability</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- SALES OVERVIEW -->
        <div class="row">

            <!-- Recent Sales -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-shopping-cart"></i>
                            Recent Sales
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Branch</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($recent_sales_result && $recent_sales_result->num_rows > 0) {
                                        while ($sale = $recent_sales_result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td>
                                                <a href="sale_view.php?id=<?php echo (int)$sale['sale_id']; ?>">
                                                    <?php echo htmlspecialchars($sale['invoice_no']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php
                                                echo !empty($sale['customer_name'])
                                                    ? htmlspecialchars($sale['customer_name'])
                                                    : "Walk-in Customer";
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($sale['branch_name']); ?></td>
                                            <td>৳<?php echo number_format((float)$sale['total_amount'],2); ?></td>
                                            <td>
                                                <?php
                                                if ($sale['status'] == 'Completed') {
                                                ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php
                                                } elseif ($sale['status'] == 'Pending') {
                                                ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php
                                                } else {
                                                ?>
                                                    <span class="badge badge-danger">Cancelled</span>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted" style="padding:25px;">
                                                No sales data available yet.
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pharmacy Information -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pharmacy Overview</h4>
                    </div>
                    <div class="card-body">

                        <!-- Medicines -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Medicines</span>
                                <strong><?php echo $total_medicines; ?></strong>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar" style="width:<?php echo $total_medicines > 0 ? '100%' : '0%'; ?>;"></div>
                            </div>
                        </div>

                        <!-- Branches -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Branches</span>
                                <strong><?php echo $total_branches; ?></strong>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-success" style="width:<?php echo $total_branches > 0 ? '100%' : '0%'; ?>;"></div>
                            </div>
                        </div>

                        <!-- Today's Sales -->
                        <div>
                            <div class="d-flex justify-content-between">
                                <span>Today's Sales</span>
                                <strong>৳<?php echo number_format($today_sales,2); ?></strong>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-warning" style="width:<?php echo $today_sales > 0 ? '100%' : '0%'; ?>;"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Find Medicine -->
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fa fa-search" style="font-size:35px;color:#009efb;margin-bottom:10px;"></i>
                        <h4>Looking for a Medicine?</h4>
                        <p class="text-muted">
                            Quickly check which pharmacy branch has the medicine available.
                        </p>
                        <a href="medicine_availability.php" class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i>
                            Search Medicine
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- LOW STOCK ALERT -->
        <?php if ($low_stock > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>Low Stock Alert:</strong>
                        <?php echo $low_stock; ?>
                        medicine availability record(s) are at or below reorder level.
                        <a href="medicine_availability.php" class="alert-link">Check Stock</a>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <?php
    require_once "../component/footer.php";
    ?>
</div>