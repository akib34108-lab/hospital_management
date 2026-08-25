<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

<?php
$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$allocation_id = $is_edit ? (int) $_GET['id'] : '';
$doctors = $crud->common_select("doctors", "*", [], "AND", "name", "ASC");
$users = $crud->common_select("users", "*", [], "AND", "full_name", "ASC");
$inventories = $crud->common_select("inventory_list", "*", [], "AND", "name", "ASC");
?>

<div class="page-wrapper">
   <div class="content">
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            <h4 class="page-title"><?= $is_edit ? 'Edit' : 'Add' ?> Inventory Allocation</h4>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            <form action="<?= $base_url; ?>stocks/store_inventory_allocation.php" method="post">
               <?php if ($is_edit) { ?>
                  <input type="hidden" name="id" value="<?= $allocation_id ?>">
               <?php } ?>
               <input type="hidden" name="deleted_at" value="">

               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Source ID</label>
                        <select class="form-control" name="source_id" id="doctor_source_id" required>
                           <?php if ($doctors['status']) {
                              foreach ($doctors['data'] as $doctor) { ?>
                                 <option value="<?= $doctor->id ?>"><?= htmlspecialchars($doctor->name) ?> (ID: <?= $doctor->id ?>)</option>
                           <?php }
                           } else { ?>
                              <option value="">No doctors available</option>
                           <?php } ?>
                        </select>
                        <select class="form-control" name="source_id" id="user_source_id" required disabled style="display: none;">
                           <?php if ($users['status']) {
                              foreach ($users['data'] as $user) { ?>
                                 <option value="<?= $user->id ?>"><?= htmlspecialchars($user->full_name) ?> (ID: <?= $user->id ?>)</option>
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
                           <option value="doctor">Doctor</option>
                           <option value="user">User</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Inventory Item</label>
                        <select class="form-control" name="inventory_list_id" id="inventory_list_id" required>
                           <option value="">Select Inventory</option>
                           <?php if ($inventories['status']) {
                              foreach ($inventories['data'] as $inventory) { ?>
                                 <option value="<?= $inventory->id ?>" data-used-type="<?= (int) $inventory->used_type ?>">
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
                        <input class="form-control" name="qty" type="number" min="1" step="1" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Issue Date</label>
                        <input class="form-control" name="issue_date" type="date" required>
                     </div>
                  </div>
                  <div class="col-sm-6" id="return_date_group" style="display: none;">
                     <div class="form-group">
                        <label>Expected Return Date</label>
                        <input class="form-control" name="return_date" type="date">
                     </div>
                  </div>
                  
               </div>
               <div class="m-t-20 text-center">
                  <button class="btn btn-primary submit-btn" type="submit"><?= $is_edit ? 'Update' : 'Add' ?> Allocation</button>
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