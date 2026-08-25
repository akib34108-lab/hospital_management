<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

// ==================================================
// DATE FILTER
// ==================================================
$from_date = $_GET['from_date'] ?? date('Y-m-01');
$to_date = $_GET['to_date'] ?? date('Y-m-d');

// ==================================================
// ESCAPE DATE
// ==================================================
$from_date_safe = $crud->conn->real_escape_string($from_date);
$to_date_safe = $crud->conn->real_escape_string($to_date);

// ==================================================
// 1. TOTAL SALES
// ==================================================
$total_sales_sql = "SELECT COUNT(*) AS total_sales FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe'";
$total_sales_result = $crud->conn->query($total_sales_sql);
$total_sales = 0;

if ($total_sales_result) {
    $row = $total_sales_result->fetch_assoc();
    $total_sales = (int)$row['total_sales'];
}

// ==================================================
// 2. TOTAL REVENUE
// ==================================================
$total_revenue_sql = "SELECT COALESCE(SUM(total_amount), 0) AS total_revenue FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe'";
$total_revenue_result = $crud->conn->query($total_revenue_sql);
$total_revenue = 0;

if ($total_revenue_result) {
    $row = $total_revenue_result->fetch_assoc();
    $total_revenue = (float)$row['total_revenue'];
}

// ==================================================
// 3. PENDING SALES
// ==================================================
$pending_sql = "SELECT COUNT(*) AS total_pending FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Pending' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe'";
$pending_result = $crud->conn->query($pending_sql);
$total_pending = 0;

if ($pending_result) {
    $row = $pending_result->fetch_assoc();
    $total_pending = (int)$row['total_pending'];
}

// ==================================================
// 4. CANCELLED SALES
// ==================================================
$cancelled_sql = "SELECT COUNT(*) AS total_cancelled FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Cancelled' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe'";
$cancelled_result = $crud->conn->query($cancelled_sql);
$total_cancelled = 0;

if ($cancelled_result) {
    $row = $cancelled_result->fetch_assoc();
    $total_cancelled = (int)$row['total_cancelled'];
}

// ==================================================
// 5. BRANCH-WISE SALES
// ==================================================
$branch_sales_sql = "SELECT pb.branch_name, COUNT(ps.sale_id) AS total_sales, COALESCE(SUM(ps.total_amount), 0) AS total_amount FROM pharmacy_sales ps INNER JOIN pharmacy_branches pb ON ps.branch_id = pb.branch_id WHERE ps.deleted_at IS NULL AND ps.status = 'Completed' AND DATE(ps.sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe' GROUP BY ps.branch_id, pb.branch_name ORDER BY total_amount DESC";
$branch_sales_result = $crud->conn->query($branch_sales_sql);

// ==================================================
// 6. PAYMENT METHOD SALES
// ==================================================
$payment_sql = "SELECT payment_method, COUNT(*) AS total_sales, COALESCE(SUM(total_amount), 0) AS total_amount FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe' GROUP BY payment_method ORDER BY total_amount DESC";
$payment_result = $crud->conn->query($payment_sql);

// ==================================================
// 7. TOP SELLING MEDICINES
// ==================================================
$top_medicine_sql = "SELECT m.medicine_name, m.generic_name, SUM(psi.quantity) AS total_quantity, COALESCE(SUM(psi.subtotal), 0) AS total_amount FROM pharmacy_sale_items psi INNER JOIN pharmacy_sales ps ON psi.sale_id = ps.sale_id INNER JOIN medicines m ON psi.medicine_id = m.medicine_id WHERE psi.deleted_at IS NULL AND ps.deleted_at IS NULL AND ps.status = 'Completed' AND DATE(ps.sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe' GROUP BY psi.medicine_id, m.medicine_name, m.generic_name ORDER BY total_quantity DESC LIMIT 10";
$top_medicine_result = $crud->conn->query($top_medicine_sql);

// ==================================================
// 8. DAILY SALES
// ==================================================
$daily_sales_sql = "SELECT DATE(sale_date) AS sale_day, COUNT(*) AS total_sales, COALESCE(SUM(total_amount), 0) AS total_amount FROM pharmacy_sales WHERE deleted_at IS NULL AND status = 'Completed' AND DATE(sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe' GROUP BY DATE(sale_date) ORDER BY sale_day DESC";
$daily_sales_result = $crud->conn->query($daily_sales_sql);
?>

<style>
.report-card {
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
}
.report-card h3 {
    font-size: 26px;
    margin-bottom: 5px;
}
.report-card p {
    margin-bottom: 0;
    color: #777;
}
.report-title {
    font-weight: 600;
    margin-bottom: 20px;
}
.report-table th {
    background: #f5f5f5;
}
.filter-card {
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
}
@media print {
    .sidebar,
    .page-header,
    .filter-card,
    .no-print,
    footer {
        display: none !important;
    }
    .page-wrapper {
        margin-left: 0 !important;
    }
}
</style>

<div class="page-wrapper">
    <div class="content">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div class="page-title">
                <h4>Pharmacy Reports</h4>
                <h6>Sales and pharmacy performance report</h6>
            </div>

            <div class="page-btn no-print">
                <button type="button" onclick="window.print()" class="btn btn-primary">
                    <i class="fa fa-print"></i>
                    Print Report
                </button>
            </div>
        </div>

        <!-- DATE FILTER -->
        <div class="filter-card no-print">
            <form method="GET">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($from_date); ?>">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($to_date); ?>">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i>
                                Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row">

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="report-card">
                    <p>Total Completed Sales</p>
                    <h3><?php echo $total_sales; ?></h3>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="report-card">
                    <p>Total Revenue</p>
                    <h3>৳<?php echo number_format($total_revenue, 2); ?></h3>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="report-card">
                    <p>Pending Sales</p>
                    <h3><?php echo $total_pending; ?></h3>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="report-card">
                    <p>Cancelled Sales</p>
                    <h3><?php echo $total_cancelled; ?></h3>
                </div>
            </div>

        </div>

        <!-- BRANCH-WISE SALES -->
        <div class="card">
            <div class="card-header">
                <h4 class="report-title">Branch-wise Sales</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Branch</th>
                                <th>Total Sales</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $counter = 1;

                            if ($branch_sales_result && $branch_sales_result->num_rows > 0) {
                                while ($row = $branch_sales_result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                                    <td><?php echo $row['total_sales']; ?></td>
                                    <td>৳<?php echo number_format($row['total_amount'], 2); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center">No branch sales found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PAYMENT METHOD -->
        <div class="card">
            <div class="card-header">
                <h4 class="report-title">Payment Method Summary</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Method</th>
                                <th>Total Sales</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $counter = 1;

                            if ($payment_result && $payment_result->num_rows > 0) {
                                while ($row = $payment_result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                    <td><?php echo $row['total_sales']; ?></td>
                                    <td>৳<?php echo number_format($row['total_amount'], 2); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center">No payment data found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TOP SELLING MEDICINES -->
        <div class="card">
            <div class="card-header">
                <h4 class="report-title">Top Selling Medicines</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Generic Name</th>
                                <th>Quantity Sold</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $counter = 1;

                            if ($top_medicine_result && $top_medicine_result->num_rows > 0) {
                                while ($row = $top_medicine_result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                                    <td>
                                        <?php
                                        echo !empty($row['generic_name'])
                                            ? htmlspecialchars($row['generic_name'])
                                            : "N/A";
                                        ?>
                                    </td>
                                    <td><?php echo $row['total_quantity']; ?></td>
                                    <td>৳<?php echo number_format($row['total_amount'], 2); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center">No medicine sales found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DAILY SALES -->
        <div class="card">
            <div class="card-header">
                <h4 class="report-title">Daily Sales</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Total Sales</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $counter = 1;

                            if ($daily_sales_result && $daily_sales_result->num_rows > 0) {
                                while ($row = $daily_sales_result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo date("d M Y", strtotime($row['sale_day'])); ?></td>
                                    <td><?php echo $row['total_sales']; ?></td>
                                    <td>৳<?php echo number_format($row['total_amount'], 2); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center">No daily sales found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
require_once "../component/footer.php";
?>