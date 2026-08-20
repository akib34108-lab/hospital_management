<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; ?>
<!-- Sidebar End -->
<div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 class="page-title" style="color: #104d6f; font-size: 24px;">Blood Screening</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="add_screening.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-plus" style="font-size:11px;color:#fff;"></i></span>Add Blood Screening</a>
            </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table text-center table-striped custom-table">
                                <thead>
                                    <tr style="background-color: #3e79b5; color: #fff;">
                                        <th>Bag ID</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Tested by</th>
                                        <th>Verified by</th>
                                        <th>Action</th>
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
                                        $screening = $crud->common_query("SELECT screening.*,doctors.name from screening join doctors on screening.doctor_id = doctors.id LIMIT 10 OFFSET " . ($page - 1) * 10);
                                        if($screening['status']){
                                        foreach ($screening['data'] as $screening) { ?>
                                        <td><?= $screening->bag_id ?></td>
                                        <td><?php $screeningStatuses = [1 => 'Pending', 2 => 'Passed', 3 => 'Quarantined', 4 => 'Reactive', 5 => 'Invalid', 6 => 'Discarded', 7 => 'Released'];?>
                                            <?= htmlspecialchars($screeningStatuses[(int)$screening->status] ?? 'N/A') ?></td>
                                        <td><?= $screening->remarks ?></td>
                                        <td><?= $screening->tested_by ?></td>
                                        <td><?= $screening->name ?></td>
                                        <td>
                                            <a href="<?= $base_url ?>blood_bank/screening/view_screening.php?id=<?= $screening->id ?>"><i class="fa fa-list-alt pl-2" style="color: #d6bf11; font-size: 24px;"></i></a>
                                            <a href="<?= $base_url ?>blood_bank/screening/edit_screening.php?id=<?= $screening->id ?>"><i class="fa fa-edit pl-2" style="color: #20865f; font-size: 24px;"></i></a>
                                            <a onclick="return confirm('Are you sure you want to delete this screening?');" href="<?= $base_url ?>blood_bank/screening/delete_screening.php?id=<?= $screening->id ?>"><i class="fa fa-trash pl-2" style="color: #dc3545; font-size: 24px;"></i></a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <?php
                      $total_records = $crud->number_of_records("screening");
                      $records_per_page = 10;
                      $total_pages = ceil($total_records / $records_per_page);
                  ?>
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>blood_bank/screening/screening.php?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php } ?>
                    
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next">Next</a>
                    </li>
                  </ul>
              </nav>
            </div>
        </div>
    </div>
</div>
</div>
<?php require_once "../../component/footer.php" ?>      