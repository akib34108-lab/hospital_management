<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; ?>
<!-- Sidebar End -->
<div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 class="page-title" style="color: #104d6f; font-size: 24px;">Blood Collection</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="add_collection.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-plus" style="font-size:11px;color:#fff;"></i></span>Add Blood Collection</a>
            </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table text-center table-striped custom-table">
                                <thead>
                                    <tr style="background-color: #3e79b5; color: #fff;">
                                        <th>Donation ID</th>
                                        <th>Donor Name</th>
                                        <th>Collection Date</th>
                                        <th>Collection Volume (mL)</th>
                                        <th>Blood Group</th>
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
                                        $blood_collection = $crud->common_select("blood_collection",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($blood_collection['status']){
                                        foreach ($blood_collection['data'] as $collection) { ?>
                                        <td><?= $collection->donation_id ?></td>
                                        <td><?= $collection->donor_id ?></td>
                                        <td><?= $collection->collection_date ?></td>
                                        <td><?= $collection->collection_volume ?></td>
                                        <td><?= $collection->blood_group ?></td>
                                        <td>
                                            <a href="<?= $base_url ?>blood_bank/blood_collection/view_collection.php?id=<?= $collection->id ?>"><i class="fa fa-list-alt pl-2" style="font-size: 22px;"></i></a>
                                            <a href="<?= $base_url ?>blood_bank/blood_collection/edit_collection.php?id=<?= $collection->id ?>"><i class="fa fa-edit pl-2" style="font-size: 22px;"></i></a>
                                            <a onclick="return confirm('Are you sure you want to delete this collection?');" href="<?= $base_url ?>blood_bank/blood_collection/delete_collection.php?id=<?= $collection->id ?>"><i class="fa fa-trash pl-2" style="font-size: 22px;"></i></a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <?php
                      $total_records = $crud->number_of_records("blood_collection");
                      $records_per_page = 10;
                      $total_pages = ceil($total_records / $records_per_page);
                  ?>
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>blood_bank/blood_collection/collection.php?page=<?= $i ?>"><?= $i ?></a></li>
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