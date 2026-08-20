<?php
require_once "../../component/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: discharge.php");
    exit;
}

$discharge_id = (int)$_GET['id'];

$discharge = $crud->common_query("
    SELECT
        d.*,
        p.name AS patient_name,
        p.gender,
        p.age,
        p.blood_group,
        p.phone,
        p.email,
        pa.admission_no,
        pa.room_id,
        pa.bed_id,
        pa.admission_date,
        pa.admission_time,
        r.room_number,
        r.room_charge
    FROM discharges d
    LEFT JOIN patients p ON p.id = d.patient_id
    LEFT JOIN patient_admissions pa ON pa.id = d.admission_id
    LEFT JOIN rooms r ON r.id = pa.room_id
    WHERE d.discharge_id = $discharge_id
    LIMIT 1
");

$lab_result = $crud->common_select("lab_category", "*");
$lab_tests = ($lab_result["status"] && !empty($lab_result["data"])) ? $lab_result["data"] : [];

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
                <a href="discharge.php" class="btn btn-secondary btn-rounded"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>

        <?php if ($discharge["status"] && !empty($discharge["data"])) {
            $data = $discharge["data"][0];
        ?>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Patient Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Patient Name</th><td><?= htmlspecialchars($data->patient_name ?? 'N/A') ?></td></tr>
                    <tr><th>Patient ID</th><td><?= htmlspecialchars($data->patient_id ?? 'N/A') ?></td></tr>
                    <tr><th>Admission No</th><td><?= htmlspecialchars($data->admission_no ?? 'N/A') ?></td></tr>
                    <tr><th>Gender</th><td><?= htmlspecialchars($data->gender ?? 'N/A') ?></td></tr>
                    <tr><th>Age</th><td><?= htmlspecialchars($data->age ?? 'N/A') ?></td></tr>
                    <tr><th>Blood Group</th><td><?= htmlspecialchars($data->blood_group ?? 'N/A') ?></td></tr>
                    <tr><th>Phone</th><td><?= htmlspecialchars($data->phone ?? 'N/A') ?></td></tr>
                    <tr><th>Email</th><td><?= htmlspecialchars($data->email ?? 'N/A') ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Admission & Room Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Room ID</th><td><?= htmlspecialchars($data->room_id ?? 'N/A') ?></td></tr>
                    <tr><th>Room No</th><td><?= htmlspecialchars($data->room_number ?? 'N/A') ?></td></tr>
                    <tr><th>Room Charge / Day</th><td>৳ <?= number_format((float)($data->room_charge ?? 0), 2) ?></td></tr>
                    <tr><th>Bed ID</th><td><?= htmlspecialchars($data->bed_id ?? 'N/A') ?></td></tr>
                    <tr><th>Admission Date</th><td><?= !empty($data->admission_date) ? date("d M Y", strtotime($data->admission_date)) : 'N/A' ?></td></tr>
                    <tr><th>Admission Time</th><td><?= !empty($data->admission_time) ? date("h:i A", strtotime($data->admission_time)) : 'N/A' ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Discharge Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="220">Discharge ID</th><td><?= htmlspecialchars($data->discharge_id) ?></td></tr>
                    <tr><th>Doctor ID</th><td><?= $data->doctor_id ? htmlspecialchars($data->doctor_id) : 'N/A' ?></td></tr>
                    <tr><th>Discharge Date</th><td><?= !empty($data->discharge_date) ? date("d M Y, h:i A", strtotime($data->discharge_date)) : 'N/A' ?></td></tr>
                    <tr><th>Discharge Type</th><td><?= htmlspecialchars($data->discharge_type ?? 'N/A') ?></td></tr>
                    <tr><th>Discharge Condition</th><td><?= htmlspecialchars($data->discharge_condition ?? 'N/A') ?></td></tr>
                    <tr><th>Follow-up Date</th><td><?= !empty($data->follow_up_date) ? date("d M Y", strtotime($data->follow_up_date)) : 'N/A' ?></td></tr>
                    <tr><th>Diagnosis</th><td><?= nl2br(htmlspecialchars($data->diagnosis ?? 'N/A')) ?></td></tr>
                    <tr><th>Treatment Summary</th><td><?= nl2br(htmlspecialchars($data->treatment_summary ?? 'N/A')) ?></td></tr>
                    <tr><th>Discharge Advice</th><td><?= nl2br(htmlspecialchars($data->advice ?? 'N/A')) ?></td></tr>
                    <tr><th>Notes</th><td><?= nl2br(htmlspecialchars($data->notes ?? 'N/A')) ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lab Tests</h4>
                <p class="text-muted mb-0">Select the tests performed for this patient.</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Select Lab Test</label>
                            <select id="lab_test" class="form-control">
                                <option value="">-- Select Test --</option>
                                <?php foreach ($lab_tests as $test) { ?>
                                    <option value="<?= htmlspecialchars($test->test_name) ?>" data-price="<?= htmlspecialchars($test->price) ?>" data-accessor="<?= htmlspecialchars($test->test_accessor) ?>">
                                        <?= htmlspecialchars($test->test_name) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" id="lab_price" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="addLabTest" class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="labTestTable">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Test Name</th>
                                <th>Test Accessor</th>
                                <th width="150">Price</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="text-right mt-3">
                    <h4>Total Test Amount: <strong>৳ <span id="labTotal">0.00</span></strong></h4>
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <a href="edit_discharge.php?id=<?= $data->discharge_id ?>" class="btn btn-primary">
                <i class="fa fa-pencil"></i> Edit
            </a>
            <a href="discharge_invoice.php?id=<?= $data->discharge_id ?>" class="btn btn-info">
                <i class="fa fa-file-text-o"></i> Invoice
            </a>
        </div>

        <?php } else { ?>

        <div class="alert alert-warning">
            Discharge record not found.
        </div>

        <?php } ?>
    </div>

    <?php require_once "../../component/footer.php"; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const testSelect = document.getElementById("lab_test");
    const priceInput = document.getElementById("lab_price");
    const addButton = document.getElementById("addLabTest");
    const tableBody = document.querySelector("#labTestTable tbody");
    const totalDisplay = document.getElementById("labTotal");

    testSelect.addEventListener("change", function() {
        const option = this.options[this.selectedIndex];
        const price = option.getAttribute("data-price") || "";
        priceInput.value = price ? "৳ " + price : "";
    });

    addButton.addEventListener("click", function() {
        const option = testSelect.options[testSelect.selectedIndex];

        if (!testSelect.value) {
            alert("Please select a lab test.");
            return;
        }

        const testName = testSelect.value;
        const price = parseFloat(option.getAttribute("data-price")) || 0;
        const accessor = option.getAttribute("data-accessor") || "N/A";

        let exists = false;

        tableBody.querySelectorAll("tr").forEach(function(row) {
            if (row.querySelector(".test-name").textContent.trim() === testName) {
                exists = true;
            }
        });

        if (exists) {
            alert("This test is already added.");
            return;
        }

        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="serial"></td>
            <td class="test-name">${testName}</td>
            <td>${accessor}</td>
            <td class="test-price">${price.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-test"><i class="fa fa-trash"></i></button></td>
        `;

        tableBody.appendChild(row);

        updateSerial();
        calculateTotal();

        testSelect.value = "";
        priceInput.value = "";
    });

    tableBody.addEventListener("click", function(e) {
        const button = e.target.closest(".remove-test");

        if (button) {
            button.closest("tr").remove();
            updateSerial();
            calculateTotal();
        }
    });

    function updateSerial() {
        tableBody.querySelectorAll("tr").forEach(function(row, index) {
            row.querySelector(".serial").textContent = index + 1;
        });
    }

    function calculateTotal() {
        let total = 0;

        tableBody.querySelectorAll(".test-price").forEach(function(price) {
            total += parseFloat(price.textContent) || 0;
        });

        totalDisplay.textContent = total.toFixed(2);
    }
});
</script>