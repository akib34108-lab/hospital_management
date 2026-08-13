<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->
 <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 style="color: #009efb; font-size: 24px;">Doctors</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="add_doctor.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#009efb;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-plus" style="font-size:11px;color:#fff;"></i></span>Add Doctor</a>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $doctors = $crud->common_query("SELECT doctors.*,departments.department_name,designation.designation_name,shift.shift_name FROM doctors LEFT JOIN departments ON doctors.department_id = departments.id LEFT JOIN designation ON doctors.designation_id = designation.id LEFT JOIN shift ON doctors.shift_id = shift.id");
                    if ($doctors['status']) {
                        foreach ($doctors['data'] as $doctor) {
                            ?>
                <div class="col-sm-6 col-md-3 mb-3">
                    <table class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <tr>
                            <td colspan="2" class="text-right pr-2"><?php if ($doctor->status == '1') { ?><span style="display:inline-block;width:8px;height:8px;background:#28a745;border-radius:50%;"></span> Active
                            <?php } else { ?><span style="display:inline-block;width:8px;height:8px;background:#dc3545;border-radius:50%;"></span> Inactive<?php } ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" colspan="2" style="font-size: 18px; color: #009efb;"><?= htmlspecialchars($doctor->name) ?><br>
                                            <span style="font-size: 12px; color: gray;"><?= htmlspecialchars($doctor->department_name) ?></span>
                                            <span style="font-size: 12px; color: gray;">(<?= htmlspecialchars($doctor->designation_name) ?>)</span>
                            </th>
                        </tr>
                    </table>
                    <table border="1" class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <tr class="text-center">
                            <td>ID</td>
                            <td><?= htmlspecialchars($doctor->id) ?></td>
                        </tr>
                        <tr class="text-center">
                            <td>Shift</td>
                            <td><?= htmlspecialchars($doctor->shift_name) ?></td>
                        </tr>
                    </table>
                    <table class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <tr>
                            <td colspan="2" class="text-center"><i class="fa fa-user"></i> <a href="<?= $base_url ?>doctors/view_doctor.php?id=<?= $doctor->id ?>">View Profile</a></td>
                        </tr>
                    </table>
                </div>
                <?php }} ?>
            </div>
                <!--<div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Department Name</th>
                                        <th>Designation Name</th>
                                        <th>Shift</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        // Fetch department from the database
                                        if(isset($_GET['page']) && is_numeric($_GET['page'])){
                                            $page = (int)$_GET['page'];
                                        } else {
                                            $page = 1;
                                        }
                                        $doctors = $crud->common_query("select doctors.*, departments.department_name, designation.designation_name, shift.shift_name from doctors left join departments on doctors.department_id=departments.id left join designation on doctors.designation_id=designation.id left join shift on doctors.shift_id=shift.id",10,($page-1)*10);
                                        
                                        if($doctors['status']){
                                        foreach ($doctors['data'] as $doctor) { ?>
                                        <td><?= $doctor->id ?></td>
                                        <td><?= $doctor->name ?></td>
                                        <td><?= $doctor->department_name ?></td>
                                        <td><?= $doctor->designation_name ?></td>
                                        <td><?= $doctor->shift_name ?></td>
                                        <td>
                                            <?php if ($doctor->status == '1') { ?>
                                            <span class="badge bg-success">Active</span>
                                            <?php } else { ?>
                                            <span class="badge bg-danger">Inactive</span>
                                            <?php } ?>
                                        </td>

                                        
                                        <td class="text-right">
                                            <a href="<?= $base_url ?>doctors/view_doctor.php?id=<?= $doctor->id ?>">View Profile</a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <?php
                      $total_records = $crud->number_of_records("doctors");
                      $records_per_page = 10;
                      $total_pages = ceil($total_records / $records_per_page);
                  ?>
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>doctors/doctors.php?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php } ?>
                    
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next">Next</a>
                    </li>
                  </ul>
              </nav>
            </div>
        </div>
    </div>
</div> -->
<?php require_once "../component/footer.php" ?>      