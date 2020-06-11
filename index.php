<?php
// create connection
$host = "localhost";
$user = "root";
$passwd = "";
$dbname = "cooldemo";
$cxn = mysqli_connect($host,$user,$passwd,$dbname)
        or die("couldn't connect to server");

// Select database
$product_sql = "SELECT * FROM product ORDER BY product_id";
$product_result = mysqli_query($cxn, $product_sql)
        or die("Invalid product");
$product_rows = mysqli_num_rows($product_result); ?> 


<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">        
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="image" content="">
        <meta name="url" content="">
        
        <title>Product</title>
        
        <!-- bootsrap 4 css -->
        <link rel="stylesheet" href="bootstrap/4.3.1/css/bootstrap.min.css">

        <!-- fontawesome css -->
        <link rel="stylesheet" href="fontawesome/5.8.1/css/all.css">

        <!-- bootsrap 4 datepicker css -->
        <link rel="stylesheet" href="bootstrap-4-datepicker/1.9.13/css/gijgo.min.css">

        <!-- jquery js -->
        <script src="js/jquery-3.3.1.min.js"></script>

        <!-- bootsrap 4 js -->
        <script src="js/popper-1.14.7.min.js"></script>
        <script src="bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!-- fontawesome js - for layering, text, and counters -->
        <script defer src="fontawesome/5.8.1/js/all.js"></script>

        <!-- bootsrap 4 datepicker js -->
        <script src="bootstrap-4-datepicker/1.9.13/js/gijgo.min.js"></script>

        <!-- bootsrap 4 datepicker js -->
        <script src="js/chain-2-dropdown-list.min.js"></script>  


        <!-- $_Get data -->   
        <?php
        @$get_uri = $_SERVER['REQUEST_URI']; ?>
    </head>
    <body>
        <!-- content -->
        <div class="container">
            <div class="row my-5">
                <div class="col-4">
                    <a href="index.php" class="btn btn-outline-secondary btn-block" role="button">Product (Demo)</a>
                </div>
                <div class="col-4">
                    <a href="favourite.php" class="btn btn-outline-info btn-block" role="button">Favourite (Demo)</a>
                </div>    
                <div class="col-4">
                    <a href="gift-organizer.php" class="btn btn-outline-success btn-block" role="button">Gift Organizer (Demo)</a>
                </div> 
            </div>    

            <div class="lead text-left mb-4"><strong>Product Page (Demo)</strong></div>

            <div class="row">
                <?php
                while ($product_display = mysqli_fetch_array($product_result)) {
                    $product_id = stripslashes($product_display["product_id"]);
                    $product_name = stripslashes($product_display["product_name"]);
                    $product_image = stripslashes($product_display["product_image"]); ?>


                    <div class="col-3 mb-4">
                        <form class="needs-validation" role="form" method="post" enctype="multipart/form-data" name="theform" novalidate>
                            <div class="card">
                                <img class="img-fluid" src="images/<?php echo $product_image ?>" alt="">
                                <div class="card-body text-center">
                                    <div class="mb-2"><?php echo $product_name ?></div>
                                    <button type="submit" class="btn btn-outline-secondary btn-sm btn-block"><small>Add to Favourite</small></button>
                                    <input type="hidden" name="add_to_favourite" value="Add to Favourite">
                                    <input type="hidden" name="product_name" value="<?php echo $product_name ?>">
                                    <input type="hidden" name="product_image" value="<?php echo $product_image ?>">                
                                </div>                                          
                            </div>
                        </form>
                    </div>        
                <?php
                } ?>               
            </div>            
        </div>
        <!-- /content -->



        <!-- transact -->
        <?php
        // submit
        if (isset($_POST['add_to_favourite'])) {
            @$product_name = addslashes($_POST["product_name"]);
            @$product_image = addslashes($_POST["product_image"]);

            // Insert into database
            $sql = "INSERT into favourite (favourite_name, favourite_image)"
                    . "VALUES ('$product_name', '$product_image')";
            $result = mysqli_query($cxn,$sql) 
                    or die("Unable to insert into favourite");

            // close connection
            $cxn->close(); ?>

            <script type="text/javascript">
                window.location = "<?php echo $get_uri ?>"
            </script>
        <?php    
        } ?>
        <!-- /transact -->    
        
    </body>
</html>