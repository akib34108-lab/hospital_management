<?php require_once "../../component/header.php";?>
<?php require_once "../../component/sidebar.php";?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Lab Category</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="add_lab.php" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Lab Category
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Code</th>
                                <th>Category Name</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                        <?php
                            // Fetch lab category from the database
                            if(isset($_GET['page']) && is_numeric($_GET['page'])){
                                $page = (int)$_GET['page'];
                                } else {
                                    $page = 1;
                                }
                                $lab_cat = $crud->common_select("lab_category",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($lab_cat['status']){
                                        foreach ($lab_cat['data'] as $category) { ?>
                                        <td><?= $category->id ?></td>
                                        <td><?= $category->cat_code ?></td>
                                        <td><?= $category->cat_name ?></td>
                                        <td class="text-right">
                                            <a href="<?= $base_url ?>lab/lab_category/edit_lab.php?id=<?= $category->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a onclick="return confirm('Are you sure you want to delete this lab category?');" href="<?= $base_url ?>lab/lab_category/delete_lab.php?id=<?= $category->id ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
                <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <?php
                      $total_records = $crud->number_of_records("lab_category");
                      $records_per_page = 10;
                      $total_pages = ceil($total_records / $records_per_page);
                  ?>
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>lab_category/lab_cat.php?page=<?= $i ?>"><?= $i ?></a></li>
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

<?php require_once "../../component/footer.php";?>