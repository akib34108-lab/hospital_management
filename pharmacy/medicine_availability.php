<?php

require_once "../component/header.php";
require_once "../component/sidebar.php";
require_once "../crud/crud_class.php";

$crud = new crud_class();


// ==================================================
// MESSAGE
// ==================================================

$message = "";
$message_type = "";


// ==================================================
// ADD / UPDATE AVAILABILITY
// ==================================================

if (isset($_POST['save_availability'])) {

    $branch_id =
        (int)($_POST['branch_id'] ?? 0);

    $medicine_id =
        (int)($_POST['medicine_id'] ?? 0);

    $quantity =
        (int)($_POST['quantity'] ?? 0);

    $selling_price =
        (float)($_POST['selling_price'] ?? 0);


    // ==============================================
    // VALIDATION
    // ==============================================

    if ($branch_id <= 0) {

        $message = "Please select a branch.";
        $message_type = "danger";

    } elseif ($medicine_id <= 0) {

        $message = "Please select a medicine.";
        $message_type = "danger";

    } elseif ($quantity < 0) {

        $message = "Quantity cannot be negative.";
        $message_type = "danger";

    } elseif ($selling_price <= 0) {

        $message = "Selling price must be greater than 0.";
        $message_type = "danger";

    } else {


        // ==========================================
        // CHECK DUPLICATE
        // ==========================================

        $check_sql = "
            SELECT
                branch_medicine_id
            FROM branch_medicines
            WHERE branch_id = '$branch_id'
            AND medicine_id = '$medicine_id'
            LIMIT 1
        ";

        $check_result =
            $crud->conn->query($check_sql);


        if (
            $check_result &&
            $check_result->num_rows > 0
        ) {

            // ======================================
            // UPDATE EXISTING RECORD
            // ======================================

            $existing =
                $check_result->fetch_assoc();

            $branch_medicine_id =
                (int)$existing[
                    'branch_medicine_id'
                ];


            $update_sql = "
                UPDATE branch_medicines
                SET
                    quantity = '$quantity',
                    selling_price = '$selling_price'
                WHERE
                    branch_medicine_id =
                    '$branch_medicine_id'
            ";


            if (
                $crud->conn->query(
                    $update_sql
                )
            ) {

                $message =
                    "Medicine availability updated successfully.";

                $message_type =
                    "success";

            } else {

                $message =
                    "Update failed: "
                    . $crud->conn->error;

                $message_type =
                    "danger";
            }


        } else {


            // ======================================
            // INSERT NEW RECORD
            // ======================================

            $insert_sql = "
                INSERT INTO branch_medicines
                (
                    branch_id,
                    medicine_id,
                    quantity,
                    selling_price
                )
                VALUES
                (
                    '$branch_id',
                    '$medicine_id',
                    '$quantity',
                    '$selling_price'
                )
            ";


            if (
                $crud->conn->query(
                    $insert_sql
                )
            ) {

                $message =
                    "Medicine added to branch successfully.";

                $message_type =
                    "success";

            } else {

                $message =
                    "Insert failed: "
                    . $crud->conn->error;

                $message_type =
                    "danger";
            }
        }
    }
}


// ==================================================
// DELETE AVAILABILITY
// ==================================================

if (
    isset($_GET['delete'])
) {

    $delete_id =
        (int)$_GET['delete'];


    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM branch_medicines
            WHERE branch_medicine_id =
            '$delete_id'
        ";


        if (
            $crud->conn->query(
                $delete_sql
            )
        ) {

            $message =
                "Medicine availability removed successfully.";

            $message_type =
                "success";

        } else {

            $message =
                "Delete failed: "
                . $crud->conn->error;

            $message_type =
                "danger";
        }
    }
}


// ==================================================
// GET BRANCHES
// ==================================================

$branch_sql = "
    SELECT
        branch_id,
        branch_name
    FROM pharmacy_branches
    WHERE deleted_at IS NULL
    AND status = 'Active'
    ORDER BY branch_name ASC
";

$branches =
    $crud->common_query(
        $branch_sql
    );


// ==================================================
// GET MEDICINES
// ==================================================

$medicine_sql = "
    SELECT
        medicine_id,
        medicine_name,
        generic_name,
        unit_price
    FROM medicines
    WHERE deleted_at IS NULL
    ORDER BY medicine_name ASC
";

$medicines =
    $crud->common_query(
        $medicine_sql
    );


// ==================================================
// GET AVAILABILITY LIST
// ==================================================

$availability_sql = "

    SELECT

        bm.branch_medicine_id,

        bm.branch_id,

        bm.medicine_id,

        bm.quantity,

        bm.selling_price,

        pb.branch_name,

        m.medicine_name,

        m.generic_name

    FROM branch_medicines bm

    INNER JOIN pharmacy_branches pb

        ON bm.branch_id =
           pb.branch_id

    INNER JOIN medicines m

        ON bm.medicine_id =
           m.medicine_id

    WHERE pb.deleted_at IS NULL

    AND m.deleted_at IS NULL

    ORDER BY
        bm.branch_medicine_id DESC

";

$availability =
    $crud->common_query(
        $availability_sql
    );

?>


<style>

.availability-page {
    position: relative;
}

.availability-page
input,
.availability-page
select,
.availability-page
button {

    pointer-events: auto !important;

    position: relative;

    z-index: 20;
}

.availability-card {

    background: #fff;

    border: 1px solid #ddd;

    border-radius: 6px;

    padding: 20px;

    margin-bottom: 20px;
}

.stock-low {

    color: #dc3545;

    font-weight: 600;
}

.stock-good {

    color: #198754;

    font-weight: 600;
}

</style>


<div class="page-wrapper availability-page">

    <div class="content">


        <!-- ==========================================
             PAGE HEADER
        =========================================== -->

        <div class="page-header">

            <div class="page-title">

                <h4>
                    Medicine Availability
                </h4>

                <h6>
                    Manage medicine availability by branch
                </h6>

            </div>

        </div>



        <!-- ==========================================
             MESSAGE
        =========================================== -->

        <?php if (!empty($message)) { ?>

            <div
                class="alert alert-<?php
                echo $message_type;
                ?>"
            >

                <?php
                echo htmlspecialchars(
                    $message
                );
                ?>

            </div>

        <?php } ?>



        <!-- ==========================================
             ADD MEDICINE TO BRANCH
        =========================================== -->

        <div class="card">

            <div class="card-header">

                <h4>
                    Add Medicine Availability
                </h4>

            </div>


            <div class="card-body">

                <form
                    method="POST"
                >

                    <div class="row">


                        <!-- Branch -->

                        <div
                            class="col-lg-3 col-md-6 col-sm-6"
                        >

                            <div class="form-group">

                                <label>

                                    Branch
                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select
                                    name="branch_id"
                                    class="form-control"
                                    required
                                >

                                    <option value="">
                                        Select Branch
                                    </option>


                                    <?php

                                    if (
                                        isset(
                                            $branches['status']
                                        )
                                        &&
                                        $branches['status']
                                        === true
                                    ) {

                                        foreach (
                                            $branches['data']
                                            as $branch
                                        ) {

                                    ?>

                                        <option
                                            value="<?php
                                            echo (int)
                                                $branch->branch_id;
                                            ?>"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $branch->branch_name
                                            );
                                            ?>

                                        </option>

                                    <?php

                                        }
                                    }

                                    ?>

                                </select>

                            </div>

                        </div>



                        <!-- Medicine -->

                        <div
                            class="col-lg-3 col-md-6 col-sm-6"
                        >

                            <div class="form-group">

                                <label>

                                    Medicine
                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select
                                    name="medicine_id"
                                    id="medicine_id"
                                    class="form-control"
                                    required
                                >

                                    <option value="">
                                        Select Medicine
                                    </option>


                                    <?php

                                    if (
                                        isset(
                                            $medicines['status']
                                        )
                                        &&
                                        $medicines['status']
                                        === true
                                    ) {

                                        foreach (
                                            $medicines['data']
                                            as $medicine
                                        ) {

                                    ?>

                                        <option
                                            value="<?php
                                            echo (int)
                                                $medicine->medicine_id;
                                            ?>"
                                            data-price="<?php
                                            echo htmlspecialchars(
                                                $medicine->unit_price
                                            );
                                            ?>"
                                        >

                                            <?php

                                            echo htmlspecialchars(
                                                $medicine->medicine_name
                                            );

                                            if (
                                                !empty(
                                                    $medicine
                                                    ->generic_name
                                                )
                                            ) {

                                                echo " - "
                                                    .
                                                    htmlspecialchars(
                                                        $medicine
                                                        ->generic_name
                                                    );

                                            }

                                            ?>

                                        </option>

                                    <?php

                                        }
                                    }

                                    ?>

                                </select>

                            </div>

                        </div>



                        <!-- Quantity -->

                        <div
                            class="col-lg-2 col-md-6 col-sm-6"
                        >

                            <div class="form-group">

                                <label>
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    class="form-control"
                                    min="0"
                                    value="0"
                                    required
                                >

                            </div>

                        </div>



                        <!-- Selling Price -->

                        <div
                            class="col-lg-2 col-md-6 col-sm-6"
                        >

                            <div class="form-group">

                                <label>
                                    Selling Price
                                </label>

                                <input
                                    type="number"
                                    name="selling_price"
                                    id="selling_price"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                            </div>

                        </div>



                        <!-- Save -->

                        <div
                            class="col-lg-2 col-md-6 col-sm-6"
                        >

                            <div class="form-group">

                                <label>
                                    &nbsp;
                                </label>

                                <button
                                    type="submit"
                                    name="save_availability"
                                    class="btn btn-primary w-100"
                                >

                                    <i class="fa fa-plus"></i>

                                    Add

                                </button>

                            </div>

                        </div>


                    </div>

                </form>

            </div>

        </div>



        <!-- ==========================================
             AVAILABILITY LIST
        =========================================== -->

        <div class="card">

            <div class="card-header">

                <h4>
                    Medicine Availability List
                </h4>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Medicine
                                </th>

                                <th>
                                    Branch
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Selling Price
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            $counter = 1;


                            if (
                                isset(
                                    $availability['status']
                                )
                                &&
                                $availability['status']
                                === true
                            ) {

                                foreach (
                                    $availability['data']
                                    as $row
                                ) {

                            ?>

                                <tr>

                                    <!-- Number -->

                                    <td>

                                        <?php
                                        echo $counter++;
                                        ?>

                                    </td>


                                    <!-- Medicine -->

                                    <td>

                                        <strong>

                                            <?php
                                            echo htmlspecialchars(
                                                $row
                                                ->medicine_name
                                            );
                                            ?>

                                        </strong>


                                        <?php

                                        if (
                                            !empty(
                                                $row
                                                ->generic_name
                                            )
                                        ) {

                                        ?>

                                            <br>

                                            <small
                                                class="text-muted"
                                            >

                                                <?php
                                                echo htmlspecialchars(
                                                    $row
                                                    ->generic_name
                                                );
                                                ?>

                                            </small>

                                        <?php

                                        }

                                        ?>

                                    </td>


                                    <!-- Branch -->

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row
                                            ->branch_name
                                        );
                                        ?>

                                    </td>


                                    <!-- Quantity -->

                                    <td>

                                        <?php

                                        if (
                                            (int)
                                            $row->quantity
                                            <= 10
                                        ) {

                                        ?>

                                            <span
                                                class="stock-low"
                                            >

                                                <?php
                                                echo (int)
                                                    $row->quantity;
                                                ?>

                                            </span>

                                        <?php

                                        } else {

                                        ?>

                                            <span
                                                class="stock-good"
                                            >

                                                <?php
                                                echo (int)
                                                    $row->quantity;
                                                ?>

                                            </span>

                                        <?php

                                        }

                                        ?>

                                    </td>


                                    <!-- Selling Price -->

                                    <td>

                                        ৳

                                        <?php

                                        echo number_format(
                                            $row
                                                ->selling_price,
                                            2
                                        );

                                        ?>

                                    </td>


                                    <!-- Status -->

                                    <td>

                                        <?php

                                        if (
                                            (int)
                                            $row->quantity
                                            > 0
                                        ) {

                                        ?>

                                            <span
                                                class="badge badge-success"
                                            >

                                                Available

                                            </span>

                                        <?php

                                        } else {

                                        ?>

                                            <span
                                                class="badge badge-danger"
                                            >

                                                Out of Stock

                                            </span>

                                        <?php

                                        }

                                        ?>

                                    </td>


                                    <!-- Action -->

                                    <td>

                                        <a
                                            href="medicine_availability.php?delete=<?php
                                            echo (int)
                                                $row
                                                ->branch_medicine_id;
                                            ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to remove this medicine from this branch?');"
                                        >

                                            <i
                                                class="fa fa-trash"
                                            ></i>

                                        </a>

                                    </td>

                                </tr>

                            <?php

                                }

                            } else {

                            ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted"
                                    >

                                        No medicine availability found.

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


<script>

// ==================================================
// AUTO FILL SELLING PRICE
// ==================================================

document
    .getElementById("medicine_id")
    .addEventListener(
        "change",
        function () {

            const option =
                this.options[
                    this.selectedIndex
                ];

            const price =
                option.getAttribute(
                    "data-price"
                );


            document
                .getElementById(
                    "selling_price"
                )
                .value =
                price || "";

        }
    );

</script>


<?php

require_once "../component/footer.php";

?>