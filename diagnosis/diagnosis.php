<?php
session_start();
require_once "../component/connection.php";

$admission_id = $_GET['id'] ?? 0;

// Add Lab Test
if (isset($_POST['add_test'])) {
    $test_id = $_POST['test_id'] ?? 0;
    $test_price = $_POST['test_price'] ?? 0;

    if (!empty($admission_id) && !empty($test_id)) {
        $insert_data = [
            "admission_id" => $admission_id,
            "test_id" => $test_id,
            "test_price" => $test_price,
            "test_date" => date("Y-m-d"),
            "status" => 1,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $insert = $crud->common_insert("patient_lab_test", $insert_data);

        if ($insert["status"]) {
            header("Location: diagnosis.php?id=" . $admission_id);
            exit;
        }
    }
}

// Delete Lab Test
if (isset($_GET['delete_test'])) {
    $delete_test_id = $_GET['delete_test'];

    $delete_data = [
        "deleted_at" => date("Y-m-d H:i:s")
    ];

    $crud->common_update("patient_lab_test", $delete_data, [
        "id" => $delete_test_id,
        "admission_id" => $admission_id
    ]);

    header("Location: diagnosis.php?id=" . $admission_id);
    exit;
}

// Get All Lab Tests
$lab_tests = $crud->common_select(
    "lab_category",
    "*",
    [],
    "AND",
    "test_name",
    "ASC"
);

// Default Patient Tests
$patient_tests = [
    "status" => false,
    "data" => [],
    "message" => ""
];

$test_total = 0;

// Get Patient Lab Tests
if (!empty($admission_id)) {
    $safe_admission_id = $crud->conn->real_escape_string($admission_id);

    $sql = "SELECT plt.id, plt.admission_id, plt.test_id, plt.test_price, plt.test_date, lc.test_name, lc.test_accessor
            FROM patient_lab_test AS plt
            LEFT JOIN lab_category AS lc ON lc.id = plt.test_id
            WHERE plt.admission_id = '$safe_admission_id'
            AND plt.deleted_at IS NULL
            AND plt.status = 1
            AND lc.deleted_at IS NULL
            ORDER BY plt.id DESC";

    $patient_tests = $crud->common_query($sql);

    if ($patient_tests["status"]) {
        foreach ($patient_tests["data"] as $test) {
            $test_total += (float)$test->test_price;
        }
    }
}
?>

<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-5 col-5">
                <h4 class="page-title">Diagnosis</h4>
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
                                            <a href="diagnosis.php?id=<?php echo $admission_id; ?>&delete_test=<?php echo $test->id; ?>" onclick="return confirm('Are you sure you want to remove this test?');" class="btn btn-danger btn-sm">
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
    </div>
</div>

<script>
document.getElementById("test_id").addEventListener("change", function() {
    var selectedOption = this.options[this.selectedIndex];
    var price = selectedOption.getAttribute("data-price");
    document.getElementById("test_price").value = price ? price : "";
});
</script>

<?php require_once "../component/footer.php"; ?>
