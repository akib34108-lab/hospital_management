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
                                <th>Test Name</th>
                                <th>Price</th>
                                <th>Test Accessor</th>
                                <th class="text-right">Actions</th>
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
                                        <td><?= $category->test_name ?></td>
                                        <td><?= $category->price ?></td>
                                        <td><?= $category->test_accessor ?></td>
                                        <td class="text-right">
                                            <a href="edit_lab.php?id=<?= $category->id ?>" class="btn btn-sm btn-primary text-right">Edit</a>
                                            <a href="delete_lab.php?id=<?= $category->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this lab category?')">Delete</a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
                <div class="pb-3 pl-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0">
                  <?php
                      $total_records = $crud->number_of_records("lab_category");
                      $records_per_page = 10;
                      $total_pages = ceil($total_records / $records_per_page);
                  ?>
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous"><i class="fa fa-chevron-left"></i></a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>lab/lab_category/lab.php?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php } ?>
                    
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next"><i class="fa fa-chevron-right"></i></a>
                    </li>
                  </ul>
              </nav>
            </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../component/footer.php";?>