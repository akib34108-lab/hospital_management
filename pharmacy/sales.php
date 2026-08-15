<?php

require_once "../component/header.php";
require_once "../component/sidebar.php";
require_once "../crud/crud_class.php";

$crud = new crud_class();


// ==========================================
// SALES DATA
// ==========================================

$sql = "
    SELECT
        ps.sale_id,
        ps.invoice_no,
        ps.branch_id,
        ps.customer_name,
        ps.customer_phone,
        ps.sale_date,
        ps.total_amount,
        ps.payment_method,
        ps.status,
        pb.branch_name

    FROM pharmacy_sales ps

    LEFT JOIN pharmacy_branches pb
        ON ps.branch_id = pb.branch_id

    WHERE ps.deleted_at IS NULL

    ORDER BY ps.sale_id DESC
";

$sales = $crud->common_query($sql);


// ==========================================
// SUMMARY
// ==========================================

$total_sales = 0;
$completed_sales = 0;
$pending_sales = 0;
$total_amount = 0;


if ($sales['status'] && !empty($sales['data'])) {

    $total_sales = count($sales['data']);

    foreach ($sales['data'] as $row) {

        if ($row->status == "Completed") {
            $completed_sales++;
        }

        if ($row->status == "Pending") {
            $pending_sales++;
        }

        $total_amount += $row->total_amount;
    }
}

?>

<div class="page-wrapper">

    <div class="content">


        <!-- ==========================================
             PAGE HEADER
        =========================================== -->

        <div class="page-header">

            <div class="page-title">

                <h4>Pharmacy Sales</h4>

                <h6>
                    Manage pharmacy sales and invoices
                </h6>

            </div>


            <div class="page-btn">

                <a href="new_sale.php" class="btn btn-primary">

                    <i class="fa fa-plus me-1"></i>

                    New Sale

                </a>

            </div>

        </div>



        <!-- ==========================================
             SUMMARY CARDS
        =========================================== -->

        <div class="row">


            <!-- Total Sales -->

            <div class="col-lg-3 col-sm-6 col-12">

                <div class="dash-widget">

                    <div class="dash-widgetcontent">

                        <h5>
                            <?php echo $total_sales; ?>
                        </h5>

                        <h6>
                            Total Sales
                        </h6>

                    </div>


                    <div class="dash-widgeticon">

                        <span class="dash-widget-icon bg-primary">

                            <i class="fa fa-shopping-cart"></i>

                        </span>

                    </div>

                </div>

            </div>



            <!-- Completed Sales -->

            <div class="col-lg-3 col-sm-6 col-12">

                <div class="dash-widget">

                    <div class="dash-widgetcontent">

                        <h5>
                            <?php echo $completed_sales; ?>
                        </h5>

                        <h6>
                            Completed Sales
                        </h6>

                    </div>


                    <div class="dash-widgeticon">

                        <span class="dash-widget-icon bg-success">

                            <i class="fa fa-check-circle"></i>

                        </span>

                    </div>

                </div>

            </div>



            <!-- Pending Sales -->

            <div class="col-lg-3 col-sm-6 col-12">

                <div class="dash-widget">

                    <div class="dash-widgetcontent">

                        <h5>
                            <?php echo $pending_sales; ?>
                        </h5>

                        <h6>
                            Pending Sales
                        </h6>

                    </div>


                    <div class="dash-widgeticon">

                        <span class="dash-widget-icon bg-warning">

                            <i class="fa fa-clock-o"></i>

                        </span>

                    </div>

                </div>

            </div>



            <!-- Total Revenue -->

            <div class="col-lg-3 col-sm-6 col-12">

                <div class="dash-widget">

                    <div class="dash-widgetcontent">

                        <h5>

                            ৳ <?php echo number_format($total_amount, 2); ?>

                        </h5>

                        <h6>
                            Total Revenue
                        </h6>

                    </div>


                    <div class="dash-widgeticon">

                        <span class="dash-widget-icon bg-info">

                            <i class="fa fa-money"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>



        <!-- ==========================================
             SALES TABLE
        =========================================== -->

        <div class="card">


            <!-- Card Header -->

            <div class="card-header">

                <div class="card-title">

                    <h4>
                        Sales List
                    </h4>

                </div>

            </div>



            <!-- Card Body -->

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table datanew">


                        <!-- Table Header -->

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Invoice No</th>

                                <th>Branch</th>

                                <th>Customer</th>

                                <th>Sale Date</th>

                                <th>Total</th>

                                <th>Payment</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>



                        <!-- Table Body -->

                        <tbody>


                        <?php

                        if (
                            $sales['status']
                            &&
                            !empty($sales['data'])
                        ) {

                            $sl = 1;

                            foreach ($sales['data'] as $row) {

                        ?>



                            <tr>


                                <!-- Serial -->

                                <td>
                                    <?php echo $sl++; ?>
                                </td>



                                <!-- Invoice -->

                                <td>

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $row->invoice_no
                                        );

                                        ?>

                                    </strong>

                                </td>



                                <!-- Branch -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row->branch_name
                                        ?? "N/A"
                                    );

                                    ?>

                                </td>



                                <!-- Customer -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row->customer_name
                                        ?? "Walk-in Customer"
                                    );

                                    ?>

                                    <?php

                                    if (!empty($row->customer_phone)) {

                                    ?>

                                        <br>

                                        <small class="text-muted">

                                            <?php

                                            echo htmlspecialchars(
                                                $row->customer_phone
                                            );

                                            ?>

                                        </small>

                                    <?php

                                    }

                                    ?>

                                </td>



                                <!-- Sale Date -->

                                <td>

                                    <?php

                                    echo date(
                                        "d M Y, h:i A",
                                        strtotime($row->sale_date)
                                    );

                                    ?>

                                </td>



                                <!-- Total -->

                                <td>

                                    <strong>

                                        ৳

                                        <?php

                                        echo number_format(
                                            $row->total_amount,
                                            2
                                        );

                                        ?>

                                    </strong>

                                </td>



                                <!-- Payment -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row->payment_method
                                    );

                                    ?>

                                </td>



                                <!-- Status -->

                                <td>


                                    <?php

                                    if ($row->status == "Completed") {

                                        echo '

                                        <span class="badge bg-success">

                                            Completed

                                        </span>

                                        ';

                                    }

                                    elseif ($row->status == "Pending") {

                                        echo '

                                        <span class="badge bg-warning">

                                            Pending

                                        </span>

                                        ';

                                    }

                                    else {

                                        echo '

                                        <span class="badge bg-danger">

                                            Cancelled

                                        </span>

                                        ';

                                    }

                                    ?>

                                </td>



                                <!-- Action -->

                                <td>

                                    <a
                                        href="sale_view.php?id=<?php echo $row->sale_id; ?>"
                                        class="btn btn-sm btn-info"
                                        title="View Sale"
                                    >

                                        <i class="fa fa-eye"></i>

                                    </a>

                                </td>


                            </tr>



                        <?php

                            }

                        }

                        else {

                        ?>



                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center"
                                >

                                    <div class="py-4">

                                        <i
                                            class="fa fa-shopping-cart fa-2x text-muted"
                                        ></i>

                                        <h6 class="mt-2">

                                            No sales found

                                        </h6>

                                        <a
                                            href="new_sale.php"
                                            class="btn btn-primary mt-2"
                                        >

                                            <i class="fa fa-plus me-1"></i>

                                            Create New Sale

                                        </a>

                                    </div>

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

</div>



<?php

require_once "../component/footer.php";

?>