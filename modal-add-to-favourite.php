<!-- modal -->
<div class="modal fade" id="addProductToFavourite<?php echo $product_id ?>">
    <div class="modal-dialog modal-xl">

        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Product To Favourite</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>                
            </div>

            <form class="needs-validation" role="form" method="post" enctype="multipart/form-data" name="theform" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-fluid mb-4" src="images/product_images/<?php echo $product_image_filename ?>" alt="">
                        </div>
                        
                        <div class="col-md-9 px-5">                                
                            <div class="lead"><strong><?php echo $product_name ?></strong></div> 

                            <div><small>
                                <?php echo "$product_volume_price_volume" . 'ml' ?>
                            </small></div> 
                            <div><small>   
                                <?php echo 'SGD$' . "$product_volume_price_sell_price" . ' ' ?>
                                <?php echo '&emsp;' . '<s>' . 'SGD$' . "$product_volume_price_usual_price" . '</s>' ?>
                            </small></div>                         

                            <!-- what it does -->
                            <div class="mt-2"><small><strong>What It Does</strong></small></div>
                            <div class="text-justify"><small><?php echo $product_description_long ?></small></div>                            
                            <!-- /what it does --> 

                            <!-- recommendation -->
                            <div class="mt-2"><small><strong>Recommendation</strong></small></div>
                            <?php
                            // Select database
                            $product_recommendation_sql = "SELECT * FROM product_recommendation WHERE (product_recommendation_product_encrypt_id = \"$product_encrypt_id\") ORDER BY product_recommendation_id";
                            $product_recommendation_result = mysqli_query($cxn, $product_recommendation_sql)
                                    or die("Invalid product_recommendation");
                            while ($product_recommendation_display = mysqli_fetch_array($product_recommendation_result)) {
                                include("admin/select-table/_product-recommendation-display.php");

                                // Select database
                                $recommendation_sql = "SELECT * FROM recommendation WHERE (recommendation_encrypt_id = \"$product_recommendation_recommendation_encrypt_id\")";
                                $recommendation_result = mysqli_query($cxn, $recommendation_sql)
                                        or die("Invalid recommendation"); 
                                $recommendation_display = mysqli_fetch_array($recommendation_result);
                                    include("admin/select-table/_recommendation-display.php"); ?>
                            
                                <span class="mr-2"><small><i class="fas fa-check text-success"></i> <?php echo $recommendation_text ?></small></span> 
                            <?php
                            } ?>                              
                            <!-- /recommendation -->   

                            <!-- ingredients -->
                            <div class="mt-2"><small><strong>Ingredients</strong></small></div>
                            <div class="text-justify"><small><?php echo $product_ingredient ?></small></div>
                            <!-- /ingredients -->

                            <!-- usage -->
                            <div class="mt-2"><small><strong>Usage</strong></small></div>
                            <div class="text-justify"><small><?php echo $product_usage ?></small></div>
                            <!-- /usage -->                            

                            <!-- add to cart --> 
                            <div class="row mt-4">
                                <div class="col-6 col-md-4">
                                    <input type="number" class="form-control" name="cart_product_quantity" min="1" step="1" value="1" placeholder="" required>
                                </div>
                                <div class="col-6 col-md-4">
                                    <?php
                                    if (!empty($_COOKIE["member_verify"])) { ?>
                                        <button type="submit" class="btn btn-success btn-block" <?php echo $cart_btn_active ?>>Add to Cart</button>
                                        <input type="hidden" name="add_to_cart" value="Add to Cart">
                                        <input type="hidden" name="member_id" value="<?php echo $member_id ?>">
                                        <input type="hidden" name="member_encrypt_id" value="<?php echo $member_encrypt_id ?>">
                                        <input type="hidden" name="member_name" value="<?php echo $member_name ?>">
                                        <input type="hidden" name="product_encrypt_id" value="<?php echo $product_encrypt_id ?>">
                                        <input type="hidden" name="product_image_filename" value="<?php echo $product_image_filename ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $product_name ?>">
                                        <input type="hidden" name="product_volume_price_volume" value="<?php echo $product_volume_price_volume ?>">
                                        <input type="hidden" name="product_volume_price_sell_price" value="<?php echo $product_volume_price_sell_price ?>">
                                    <?php
                                    }
                                    else { ?>
                                        <a href="login.php" class="btn btn-success btn-block" role="button">Add to Cart<br><small>Require login</small></a>
                                    <?php
                                    } ?>                                    
                                </div>
                            </div>    
                            <!-- /add to cart -->               
                        </div>
                    </div>                    

                </div>
            </form>
        </div>
        <!-- /modal content-->

    </div>
</div>
<!-- /modal -->



<!-- transact -->
<?php
// submit
if (isset($_POST['add_to_cart'])) {
    // Set Zone Date
    date_default_timezone_set('Asia/Singapore');
    $CurrentTimestamp = date('Y-m-d H:i:s');

    @$member_id = addslashes($_POST["member_id"]);
    @$member_encrypt_id = addslashes($_POST["member_encrypt_id"]);    
    @$member_name = addslashes($_POST["member_name"]);
    @$product_encrypt_id = addslashes($_POST["product_encrypt_id"]);
    @$product_image_filename = addslashes($_POST["product_image_filename"]);
    @$product_name = addslashes($_POST["product_name"]);
    @$product_volume_price_volume = addslashes($_POST["product_volume_price_volume"]);
    @$product_volume_price_sell_price = addslashes($_POST["product_volume_price_sell_price"]);

    @$cart_product_quantity = addslashes($_POST["cart_product_quantity"]);

    @$cart_product_sub_total = $product_volume_price_sell_price * $cart_product_quantity;
            $display_cart_product_sub_total = number_format($cart_product_sub_total, 2, '.', ',');
    
    // create connection
    include("admin/include/misc.inc");

    // Insert into database
    $sql = "INSERT into cart (cart_member_encrypt_id, cart_product_encrypt_id, cart_product_image_filename, cart_product_name, cart_product_volume, cart_product_price, cart_product_quantity, cart_product_sub_total, cart_create_by, cart_create_timestamp)"
            . "VALUES ('$member_encrypt_id', '$product_encrypt_id', '$product_image_filename', '$product_name', '$product_volume_price_volume', '$product_volume_price_sell_price', '$cart_product_quantity', '$display_cart_product_sub_total', '$member_name', '$CurrentTimestamp')";
    $result = mysqli_query($cxn,$sql) 
            or die("Unable to insert into cart");
            
    if ($result) {
        $cart_id = mysqli_insert_id($cxn);
        $cart_encrypt_id = md5("$cart_id" . "$CurrentTimestamp"); 
                
        // Update database
        $sql2 = "UPDATE cart SET cart_encrypt_id='$cart_encrypt_id' WHERE cart_id = $cart_id";
        $result2 = mysqli_query($cxn,$sql2) 
                or die("Unable to update cart");


        // close connection
        $cxn->close(); ?>

        <!-- refresh parent page once -->
        <script type="text/javascript">
            window.location = "<?php echo $get_uri ?>"
        </script>
    <?php
    }
} ?>
<!-- /transact -->