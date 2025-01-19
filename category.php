 <?php 
 $cid= $_GET['id'] ?? "";
 if(empty($cid)){
    throw new ErrorException("Error: This page requires a category ID.");
 }
 $category_qry = $conn->query("SELECT * FROM `category_list` where `id` = '{$cid}'");
 if($category_qry->num_rows > 0){
    $data = $category_qry->fetch_assoc();

 }else{
    throw new ErrorException("Error: This page requires a category ID.");
 }
 
 ?>
 <!-- Masthead-->
 <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-center mb-4 page-title">
                    	<h1 class="text-white"><?= $data['name'] ?? "" ?></h1>
                        <hr class="divider my-4 bg-dark" />

                    </div>
                    
                </div>
            </div>
        </header>
	<section class="page-section" id="menu">
        <h1 class="text-center text-cursive" style="font-size:3em"><b><?= $data['name'] ?? "" ?>'s Menu</b></h1>
        <div class="d-flex justify-content-center">
            <hr class="border-dark" width="5%">
        </div>
        <div class="row mx-0">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="menu-field" class="card-deck px-2 py-3 justify-content-center">
                        <?php 
                            include 'admin/db_connect.php';
                            $limit = 10;
                            $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0 ;
                            $offset = $page > 0 ? $page * $limit : 0;
                            $all_menu =$conn->query("SELECT id FROM  product_list")->num_rows;
                            $page_btn_count = ceil($all_menu / $limit);
                            $qry = $conn->query("SELECT * FROM  product_list where `category_id` = '{$cid}' order by `name` asc Limit $limit OFFSET $offset ");
                            while($row = $qry->fetch_assoc()):
                            ?>
                            <div class="col-lg-3 mb-3">
                            <div class="card menu-item  rounded-0">
                                <div class="position-relative overflow-hidden" id="item-img-holder">
                                    <img src="assets/img/<?php echo $row['img_path'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body rounded-0">
                                <h5 class="card-title"><?php echo $row['name'] ?></h5>
                                <p class="card-text truncate"><?php echo $row['description'] ?></p>
                                <div class="text-center">
                                    <button class="btn btn-sm btn-outline-dark view_prod btn-block" data-id=<?php echo $row['id'] ?>><i class="fa fa-eye"></i> View</button>
                                    
                                </div>
                                </div>
                                
                            </div>
                            </div>
                            <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php //$page_btn_count = 10;exit; ?>
        <!-- Pagination Buttons Block -->
        <div class="row mx-0">
            <div class="w-100 mx-4 d-flex justify-content-center">
                <div class="btn-group paginate-btns">
                    <!-- Previous Page Button -->
                    <a class="btn btn-default border border-dark" <?php echo ($page == 0)? 'disabled' :'' ?> href="./?_page=<?php echo ($page) ?>">Prev.</a>
                    <!-- End of Previous Page Button -->
                    <!-- Pages Page Button -->
            
                    <!-- looping page buttons  -->
                    <?php for($i = 1; $i <= $page_btn_count; $i++): ?>
                    <!-- Display button blocks  -->
            
                    <!-- Limiting Page Buttons  -->
                    <?php if($page_btn_count > 10): ?>
                    <!-- Show ellipisis button before the last Page Button  -->
                        <?php if($i = $page_btn_count && !in_array($i, range( ($page - 3), ($page + 3) ) )): ?>
                            <a class="btn btn-default border border-dark ellipsis">...</a>
                        <?php endif; ?>
                
                        <!-- Show ellipisis button after the First Page Button  -->
                        <?php if($i == 1 || $i == $page_btn_count || (in_array($i, range( ($page - 3), ($page + 3) ) )) ): ?>
                            <a class="btn btn-default border border-dark <?php echo ($i == ($page + 1)) ? 'active' : '';  ?>" href = "./?_page=<?php echo $i ?>"><?php echo $i; ?></a>
                            <?php if($i == 1 && !in_array($i, range( ($page - 3), ($page + 3) ) )): ?>
                                <a class="btn btn-default border border-dark ellipsis">...</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="btn btn-default border border-dark <?php echo ($i == ($page + 1)) ? 'active' : '';  ?>" href = "./?_page=<?php echo $i ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                    <!-- Display button blocks  -->
                    <?php endfor; ?>
                    <!-- End of looping page buttons  -->
            
                    <!-- End of Pages Page Button -->
                    <!-- Next Page Button -->
                    <a class="btn btn-default border border-dark" <?php echo (($page+1) == $page_btn_count)? 'disabled' :'' ?> href="./?_page=<?php echo ($page+2) ?>">Next</a>
                    <!-- End of Next Page Button -->
                </div>
            </div>
        </div>
        <!-- End Pagination Buttons Block -->
    </section>
    <script>
        
        $('.view_prod').click(function(){
            uni_modal_right('Product Details','view_prod.php?id='+$(this).attr('data-id'))
        })
    </script>
    <?php if(isset($_GET['_page'])): ?>
        <script>
            $(function(){
                document.querySelector('html').scrollTop = $('#menu').offset().top - 100
            })
        </script>
    <?php endif; ?>
	
