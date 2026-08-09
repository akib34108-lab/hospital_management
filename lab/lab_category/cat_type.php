<?php require_once "../../component/header.php";?>
<?php require_once "../../component/sidebar.php";?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Category Type List</h4>
            </div>
        </div>
    <div class="row">
            <div class="col-3 text-center">
                <h4 class="page-title">Hematology</h4>
                <table class="table custom-table mb-0">
                    <thead>
                        <th>Category Code</th>
                        <th>Name</th>
                        <th>Action</th>
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
                                        <td><?= $category->cat_code ?></td>
                                        <td><?= $category->cat_name ?></td>
                                        <td><?= $category->cat_name ?></td>
                                    </tr>
                                            <?php } } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-3 text-center">
                <h4 class="page-title">Clinical Chemistry / Bioc</h4>
            </div>
            <div class="col-3 text-center">
                <h4 class="page-title">Lipid Profile</h4>
            </div>
            <div class="col-3 text-center">
                <h4 class="page-title">Endocrinology</h4>
            </div>
        </div>
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