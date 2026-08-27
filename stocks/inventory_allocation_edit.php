<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

<?php
$allocation_id = $_GET['id'];
$allocation_result = $crud->common_select("inventory_transaction", "*", ['id' => $allocation_id]);

if (!$allocation_result['status'] || empty($allocation_result['data'])) {
   $_SESSION['message'] = array('danger', 'Error', 'Inventory allocation not found.');
   echo "<script>window.location.href = '" . $base_url . "stocks/inventory_transactions.php';</script>";
   exit;
}

$allocation = $allocation_result['data'][0];
$doctors = $crud->common_select("doctors", "*", [], "AND", "name", "ASC");
$users = $crud->common_select("users", "*", [], "AND", "full_name", "ASC");
$inventories = $crud->common_select("inventory_list", "*", [], "AND", "name", "ASC");
?>

<div class="page-wrapper">
   <div class="content">
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            <h4 class="page-title">Edit Inventory Allocation</h4>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            <form action="<?= $base_url; ?>stocks/update_inventory_allocation.php" method="post">
               <input type="hidden" name="id" value="<?= $allocation->id ?>">

               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Source ID</label>
                        <select class="form-control" name="source_id" id="doctor_source_id" required>
                           <?php if ($doctors['status']) {
                              foreach ($doctors['data'] as $doctor) { ?>
                                 <option value="<?= $doctor->id ?>" <?= $allocation->source_type === 'doctor' && $allocation->source_id == $doctor->id ? 'selected' : '' ?>><?= htmlspecialchars($doctor->name) ?> (ID: <?= $doctor->id ?>)</option>
                           <?php }
                           } else { ?>
                              <option value="">No doctors available</option>
                           <?php } ?>
                        </select>
                        <select class="form-control" name="source_id" id="user_source_id" required disabled style="display: none;">
                           <?php if ($users['status']) {
                              foreach ($users['data'] as $user) { ?>
                                 <option value="<?= $user->id ?>" <?= $allocation->source_type === 'user' && $allocation->source_id == $user->id ? 'selected' : '' ?>><?= htmlspecialchars($user->full_name) ?> (ID: <?= $user->id ?>)</option>
                           <?php }
                           } else { ?>
                              <option value="">No users available</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Source Type</label>
                        <select class="form-control" name="source_type" id="source_type" required>
                           <option value="doctor" <?= $allocation->source_type === 'doctor' ? 'selected' : '' ?>>Doctor</option>
                           <option value="user" <?= $allocation->source_type === 'user' ? 'selected' : '' ?>>User</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Inventory List ID</label>
                        <select class="form-control" name="inventory_list_id" id="inventory_list_id" required>
                           <option value="">Select Inventory</option>
                           <?php if ($inventories['status']) {
                              foreach ($inventories['data'] as $inventory) { ?>
                                 <option value="<?= $inventory->id ?>" data-used-type="<?= (int) $inventory->used_type ?>" <?= $allocation->inventory_list_id == $inventory->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($inventory->name) ?> (ID: <?= $inventory->id ?>)
                                 </option>
                           <?php }
                           } else { ?>
                              <option value="">No inventory available</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Quantity</label>
                        <input class="form-control" name="qty" type="number" min="1" step="1" value="<?= htmlspecialchars($allocation->qty) ?>" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Issue Date</label>
                        <input class="form-control" name="issue_date" type="date" value="<?= htmlspecialchars($allocation->issue_date) ?>" required>
                     </div>
                  </div>
                  <div class="col-sm-6" id="return_date_group" style="display: none;">
                     <div class="form-group">
                        <label>Expected Return Date</label>
                        <input class="form-control" name="return_date" type="date" value="<?= htmlspecialchars($allocation->return_date ?? '') ?>">
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Actual Return Date</label>
                        <input class="form-control" name="actual_return_date" type="date" value="<?= htmlspecialchars($allocation->actual_return_date ?? '') ?>">
                     </div>
                  </div>
               </div>
               <div class="m-t-20 text-center">
                  <button class="btn btn-primary submit-btn" type="submit">Update Allocation</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
   const sourceType = document.getElementById('source_type');
   const doctorSource = document.getElementById('doctor_source_id');
   const userSource = document.getElementById('user_source_id');
   const inventoryList = document.getElementById('inventory_list_id');
   const returnDateGroup = document.getElementById('return_date_group');

   function updateSourceOptions() {
      const isDoctor = sourceType.value === 'doctor';
      doctorSource.disabled = !isDoctor;
      doctorSource.style.display = isDoctor ? '' : 'none';
      userSource.disabled = isDoctor;
      userSource.style.display = isDoctor ? 'none' : '';
   }

   function updateReturnDateVisibility() {
      const selectedOption = inventoryList.options[inventoryList.selectedIndex];
      const isReusable = selectedOption && selectedOption.dataset.usedType === '1';
      returnDateGroup.style.display = isReusable ? '' : 'none';
   }

   sourceType.addEventListener('change', updateSourceOptions);
   inventoryList.addEventListener('change', updateReturnDateVisibility);
   updateSourceOptions();
   updateReturnDateVisibility();
</script>

   <?php require_once "../component/footer.php" ?>