<?php
// create connection
$host = "localhost";
$user = "root";
$passwd = "";
$dbname = "cooldemo";
$cxn = mysqli_connect($host,$user,$passwd,$dbname)
        or die("couldn't connect to server");

// Select database
$favourite_sql = "SELECT * FROM favourite ORDER BY favourite_id";
$favourite_result = mysqli_query($cxn, $favourite_sql)
        or die("Invalid favourite");
$favourite_rows = mysqli_num_rows($favourite_result); ?> 

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
        
        <title>Gift Organizer</title>
        
        <!-- bootsrap 4 css -->
        <link rel="stylesheet" href="bootstrap/4.3.1/css/bootstrap.min.css">

        <!-- fontawesome css -->
        <link rel="stylesheet" href="fontawesome/5.8.1/css/all.css">

        <!-- Gift organize css -->
        <link rel="stylesheet" href="vendor/toastr/css/toastr.css">
        <link rel="stylesheet" href="css/gift-organize.css">

        
        <!-- jquery js -->
        <script src="js/jquery.min.js"></script>

        <!-- bootsrap 4 js -->
        
        <script src="bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!-- fontawesome js - for layering, text, and counters -->
        <script defer src="fontawesome/5.8.1/js/all.js"></script>
        <script defer src="vendor/toastr/js/toastr.js"></script>
        <script defer src="vendor/bootbox/bootbox.all.min.js"></script>
        <script defer src="js/gift-organizer.js"></script>

        
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

            <div class="lead text-left mb-4"><strong>Gift Organizer (Demo)</strong></div>

            <div class="row main-container">
               <div class="col-3">
                  <div class="partial-container">
                    <label class="descriptor">Gift Organizer</label>
                    <div class="file-explorer">
                        
                    </div>
                    <div class="row">
                       <div class="col-12 btn-container">
                       <button type="button"  class="btn btn-primary btn-select">Select</button>
                       <button type="button" class="btn btn-danger btn-folder-add">Add</button>
                       </div>
                    </div>
                  </div>
               </div>
               <div class="col-6">
                  <div class="partial-container">
                    <label class="descriptor">Gift Organizer</label>
                    <div class="product-list">
                      
                    </div>
                    <div class="row">
                       <div class="col-12 btn-container">
                       <button type="button" class="btn btn-warning btn-clear-whole">Clear Whole List</button>
                       <label class="pull-right cost-info">Total Costs:<b>493.6$</b></label>
                       <button type="button" class="btn btn-success pull-right btn-add-cart">Add to Cart</button>
                       </div>
                    </div>
                  </div>
               </div>   
               <div class="col-3">
                 <div class="partial-container">
                    <label class="descriptor">Your Favourites</label>
                    <div class="favour-list">
                         <div>
                         <?php
                            while ($favourite_display = mysqli_fetch_array($favourite_result)) {
                                $favourite_id = stripslashes($favourite_display["favourite_id"]);
                                $favourite_name = stripcslashes($favourite_display["favourite_name"]);
                                $favourite_image = stripslashes($favourite_display["favourite_image"]); ?>
                                
                                    <div class="card">
                                      <div class="row">   
                                        <div class="col-4">
                                            <img class="img-fluid" src="images/<?php echo $favourite_image ?>" alt="">
                                        </div>
                                        <div class="col-8">
                                            <label class="product_name"><?php echo $favourite_name; ?></label>
                                        </div>
                                     </div>
                                     <div class="col-12">
                                         <button type="button" class="btn btn-sm btn-primary btn-add-to-list">Add to LIST</button>
                                     </div>
                                   </div>      
                            <?php
                            } ?>        
                           </div>  
                    </div>

                 </div>
               </div>
            </div>                        
        </div>
        <!-- /content -->


        <div class="modal fade" id="addModal">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Folder/List</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/action_page.php">
                        <input type="hidden" class="add-to-folder"/>  
                        <input type="hidden" class="add-to-folder-level"/>  
                        <div class="form-group row">
                            <label class="col-3 text-right form-label">Append To:</label>
                            <label class="col-9 form-label append-to-label"></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 text-right form-label">Type:</label>
                            <div class="col-9">
                                <select class="form-control folder-type">
                                     <option value=1>Folder</option>
                                     <option value=2>List</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-3 text-right form-label">Name:</label>
                            <div class="col-9">
                                <input class="form-control folder-name" />
                            </div>
                        </div>
                       
                    </form>
                   
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-save-folder">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>

                </div>
            </div>
        </div> 

        <div class="lock">
             <img src="css/icons/Loading.gif" />
        </div>
        <!-- transact -->
        <?php
        // submit
        if (isset($_POST['remove_from_favourite'])) {
            @$favourite_id = addslashes($_POST["favourite_id"]);

            // Delete from database
            $sql = "DELETE FROM favourite WHERE (favourite_id = $favourite_id)";
            $result = mysqli_query($cxn,$sql) 
                    or die("Unable to delete from favourite");

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