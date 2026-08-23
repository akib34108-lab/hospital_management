<?php
require_once "../../component/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$discharge_id = (int)$_GET['id'];

$discharge = $crud->common_query("
    SELECT d.*, p.name AS patient_name, p.gender, p.age, p.blood_group, p.phone, p.email,
    pa.admission_no, pa.room_id, pa.bed_id, pa.admission_date, pa.admission_time,
    pa.doctor_id AS admission_doctor_id, r.room_number, r.room_charge
    FROM discharges d
    LEFT JOIN patients p ON p.id = d.patient_id
    LEFT JOIN patient_admissions pa ON pa.id = d.admission_id
    LEFT JOIN rooms r ON r.id = pa.room_id
    WHERE d.discharge_id = $discharge_id
    LIMIT 1
");

if (!$discharge["status"] || empty($discharge["data"])) {
    header("Location: discharge.php");
    exit;
}

$data = $discharge["data"][0];

if (isset($_POST['add_test'])) {

    $test_id = (int)$_POST['test_id'];
    $test_price = (float)$_POST['test_price'];

    if ($test_id > 0) {

        $check = $crud->common_query("
            SELECT id FROM patient_lab_tests
            WHERE patient_id = {$data->patient_id}
            AND admission_id = {$data->admission_id}
            AND test_id = $test_id
            AND deleted_at IS NULL
            LIMIT 1
        ");

        if ($check["status"] && !empty($check["data"])) {

            $error = "This test is already added.";

        } else {

            $test_data = [
                "patient_id" => $data->patient_id,
                "admission_id" => $data->admission_id,
                "test_id" => $test_id,
                "test_price" => $test_price,
                "test_date" => date("Y-m-d"),
                "status" => "Completed"
            ];

            $result = $crud->common_insert("patient_lab_tests", $test_data);

            if ($result["status"]) {
                header("Location: discharge_view.php?id=$discharge_id");
                exit;
            } else {
                $error = $result["message"];
            }
        }
    }
}

if (isset($_GET['delete_test'])) {

    $test_record_id = (int)$_GET['delete_test'];

    $crud->common_update(
        "patient_lab_tests",
        ["deleted_at" => date("Y-m-d H:i:s")],
        ["id" => $test_record_id]
    );

    header("Location: discharge_view.php?id=$discharge_id");
    exit;
}

$lab_tests = $crud->common_query("
    SELECT id, test_name, price, test_accessor
    FROM lab_category
    ORDER BY test_name ASC
");

$patient_tests = $crud->common_query("
    SELECT plt.id, plt.test_id, plt.test_price, plt.test_date, plt.status,
    lc.test_name, lc.test_accessor
    FROM patient_lab_tests plt
    LEFT JOIN lab_category lc ON lc.id = plt.test_id
    WHERE plt.patient_id = {$data->patient_id}
    AND plt.admission_id = {$data->admission_id}
    AND plt.deleted_at IS NULL
    ORDER BY plt.id DESC
");

$test_total = 0;

if ($patient_tests["status"]) {
    foreach ($patient_tests["data"] as $test) {
        $test_total += (float)$test->test_price;
    }
}

require_once "../../component/header.php";
require_once "../../component/sidebar.php";
?>

<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Discharge Details</h4>
            </div>
            <div class="col-sm-5 col-6 text-right">
                <a href="discharge.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Patient Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Patient Name</th><td><?php echo $data->patient_name; ?></td></tr>
                    <tr><th>Patient ID</th><td><?php echo $data->patient_id; ?></td></tr>
                    <tr><th>Admission No</th><td><?php echo $data->admission_no; ?></td></tr>
                    <tr><th>Gender</th><td><?php echo $data->gender; ?></td></tr>
                    <tr><th>Age</th><td><?php echo $data->age; ?></td></tr>
                    <tr><th>Blood Group</th><td><?php echo $data->blood_group; ?></td></tr>
                    <tr><th>Phone</th><td><?php echo $data->phone; ?></td></tr>
                    <tr><th>Email</th><td><?php echo $data->email; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Admission & Room Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Doctor ID</th><td><?php echo $data->doctor_id; ?></td></tr>
                    <tr><th>Room No</th><td><?php echo $data->room_number; ?></td></tr>
                    <tr><th>Room Charge / Day</th><td>৳ <?php echo number_format((float)$data->room_charge, 2); ?></td></tr>
                    <tr><th>Bed ID</th><td><?php echo $data->bed_id; ?></td></tr>
                    <tr><th>Admission Date</th><td><?php echo $data->admission_date; ?></td></tr>
                    <tr><th>Admission Time</th><td><?php echo $data->admission_time; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Discharge Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Discharge ID</th><td><?php echo $data->discharge_id; ?></td></tr>
                    <tr><th>Discharge Date</th><td><?php echo $data->discharge_date; ?></td></tr>
                    <tr><th>Discharge Type</th><td><?php echo $data->discharge_type; ?></td></tr>
                    <tr><th>Condition</th><td><?php echo $data->discharge_condition; ?></td></tr>
                    <tr><th>Follow-up Date</th><td><?php echo $data->follow_up_date; ?></td></tr>
                    <tr><th>Diagnosis</th><td><?php echo $data->diagnosis; ?></td></tr>
                    <tr><th>Treatment Summary</th><td><?php echo $data->treatment_summary; ?></td></tr>
                    <tr><th>Advice</th><td><?php echo $data->advice; ?></td></tr>
                    <tr><th>Notes</th><td><?php echo $data->notes; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Patient Lab Tests</h4>
            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="row">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Select Lab Test</label>

                                <select name="test_id" id="test_id" class="form-control" required>
                                    <option value="">Select Test</option>

                                    <?php
                                    if ($lab_tests["status"]) {
                                        foreach ($lab_tests["data"] as $test) {
                                    ?>

                                        <option value="<?php echo $test->id; ?>" data-price="<?php echo $test->price; ?>">
                                            <?php echo $test->test_name; ?>
                                        </option>

                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Test Price</label>
                                <input type="text" name="test_price" id="test_price" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" name="add_test" class="btn btn-primary btn-block">
                                    <i class="fa fa-plus"></i> Add
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

                <div class="table-responsive mt-3">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Test Name</th>
                                <th>Test Accessor</th>
                                <th>Test Date</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if ($patient_tests["status"] && !empty($patient_tests["data"])) { ?>

                            <?php $sl = 1; ?>

                            <?php foreach ($patient_tests["data"] as $test) { ?>

                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $test->test_name; ?></td>
                                    <td><?php echo $test->test_accessor; ?></td>
                                    <td><?php echo $test->test_date; ?></td>
                                    <td>৳ <?php echo number_format((float)$test->test_price, 2); ?></td>
                                    <td>
                                        <a href="discharge_view.php?id=<?php echo $discharge_id; ?>&delete_test=<?php echo $test->id; ?>" onclick="return confirm('Are you sure you want to remove this test?');" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    No lab tests added.
                                </td>
                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="text-right">
                    <h4>Total Test Amount: ৳ <?php echo number_format($test_total, 2); ?></h4>
                </div>

            </div>
        </div>

        <div class="text-right mb-4">

            <a href="edit_discharge.php?id=<?php echo $data->discharge_id; ?>" class="btn btn-primary">
                <i class="fa fa-pencil"></i> Edit
            </a>

            <a href="discharge_invoice.php?id=<?php echo $data->discharge_id; ?>" class="btn btn-info">
                <i class="fa fa-file-text-o"></i> Invoice
            </a>

        </div>

    </div>

    <?php require_once "../../component/footer.php"; ?>

</div>

<script>
document.getElementById("test_id").addEventListener("change", function() {
    var option = this.options[this.selectedIndex];
    var price = option.getAttribute("data-price");
    document.getElementById("test_price").value = price ? price : "";
});
</script>