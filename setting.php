<?php 
    session_start();
    require_once("db_connection.php");

    if(!isset($_SESSION['login'])){
        // not logged in
        header('Location: auth/login.php');
        exit();
    }

    //SESSION
    $delMessage = $_SESSION['del-message'] ?? "";
    $priceMessage = $_SESSION['price-message'] ?? "";
    $authMessage = $_SESSION['auth-message'] ?? "";
    $logMessage = $_SESSION['clearLog-message'] ?? "";
    $delConsumeMessage = $_SESSION['clearConsume-message'] ?? "";
    $delClient = $_SESSION['delAllClient-message'] ?? "";
    $username = $_SESSION['user'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link href="css/style.css" rel="stylesheet" />
    <script src="js/tata.js"></script>
    <link rel="shortcut icon" href="images/favicon.png"/>	
    
    <title>Water Billing</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <i style="color: #80cae5;" class="bx bx-droplet"></i>
                <div class="logo_name">Water Data</div>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>

        <ul class="nav_list" style="display: none;">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
            </li>
        </ul>

        <ul class="nav_list">
            <li>
                <a href="index.php" title="Dashboard">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
                </a>
            </li>
        
            <li>
                <a href="clients.php" title="Clients">
                <i class='bx bx-user'></i>
                <span class="links_name">Clients</span>
                </a>
            </li>
        
            <li>
                <a href="history.php" title="History">
                <i class='bx bx-history'></i>
                <span class="links_name">History</span>
                </a>
            </li>

            <li>
                <a href="setting.php" class="active"  title="Setting">
                <i class='bx bx-cog'></i>
                <span class="links_name">Setting</span>
                </a>
        
            </li>
        </ul>

        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <div class="name_job">
                        <div class="name"><?php echo $username; ?></div>
                        <div class="job">Administrator</div>
                    </div>
                </div>
                <form action="auth/logout.php">
                    <button type="submit"><i class='bx bx-log-out' id="log_out" title="Logout"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="home_content">
        <!-- History table [INFO: I use client design for setting page] -->
        <div class="client-container">
            <div style="display: flex;">
                <div class="client-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="client-text">
                    Settings
                </div>
            </div>
            <div class="setting-container">
                <div class="setting-one">
                    <div class="setting-holder"><i class='bx bx-money'></i><button type="button" data-toggle="modal" data-target="#editPrice">Update Cubic Price</button></div>
                    

                    <!-- CHANGE CUBIC PRICE FORM -->
                    <div class="modal fade" id="editPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Update Cubic Price</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>[<span style="color: red;">Warning</span>] : Before updating cubic price make sure all clients cubic data is reset. The computation of the client's 'To Pay' depends on the current cubic price. </p>
                                        <input type="text" class="add-input" name="price" placeholder="Enter new price" autocomplete="off">
                                        <input type="text" class="add-input" name="price-confirm" placeholder="Type 'confirm'" autocomplete="off">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button formaction="./setting/change-price.php" name="update-price" class="btn btn-info">Continue</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                //Toast notifacation for delete form
                                if($priceMessage=='false'){   
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($priceMessage=='true') {
                                    ?>
                                        <script type="text/javascript">tata.success('SUCCESS', 'Price Updated Successfully!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    
                    <!-- CHANGE PASSWORD -->
                    <div class="setting-holder"><i class='bx bx-lock-alt' ></i><button type="button" data-toggle="modal" data-target="#editPass">Change Password</button></div>
                    <div class="modal fade" id="editPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Change Password</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="">Account</label>
                                        <input type="text" class="add-input" value="<?php echo $username; ?>" autocomplete="off" disabled>
                                        <input type="password" class="add-input" name="old-pass" placeholder="Enter old password" autocomplete="off">
                                        <input type="password" class="add-input" name="new-pass" placeholder="Enter new password" autocomplete="off">
                                        <input type="password" class="add-input" name="conf-pass" placeholder="Confirm new password" autocomplete="off">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button formaction="./setting/change-password.php" name="update-pass" class="btn btn-info">Continue</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                //Toast notifacation for change password
                                if($authMessage=='false'){   
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($authMessage=='true') {
                                    ?>
                                        <script type="text/javascript">tata.success('SUCCESS', 'Password Changed!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($authMessage=='wrong') {
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', "Password Doesn't Match!", {position: 'tr', duration: 5000})</script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    
                    <!-- CLEAR LOG FORMS -->
                    <div class="setting-holder"><i class='bx bx-history' ></i><button type="button" data-toggle="modal" data-target="#clearLog">Clear Activity Logs</button></div>
                    <div class="modal fade" id="clearLog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="GET">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Clear Activity Logs</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>To confirm deletion, type <i>permanently delete</i> in the text input field.</p>
                                        <input name="log-confirm" type="text" class="add-input" placeholder="permanently delete" autocomplete="off">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button formaction="./setting/clear-log.php" name="clear-logs" class="btn btn-info">Continue</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                //Toast notifacation for change password
                                if($logMessage=='false'){   
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($logMessage=='true') {
                                    ?>
                                        <script type="text/javascript">tata.success('SUCCESS', 'Activity Logs Deleted!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>

                    <!-- Delete All Client Consume -->
                    <div class="setting-holder"><i class='bx bx-user-pin' ></i><button type="button" data-toggle="modal" data-target="#clearConsume">Delete All Cient Cubic Consume</button></div>
                    <div class="modal fade" id="clearConsume" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="GET">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Delete All Consume Data</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>[<span style="color: red;">Warning</span>] : This function will delete ALL client cubic consume. Please be carefull for your action. </p>
                                        <p>To confirm deletion, type <i>permanently delete</i> in the text input field.</p>
                                        <input name="consume-confirm" type="text" class="add-input" placeholder="permanently delete" autocomplete="off">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button formaction="./setting/delete-consume.php" name="clear-consume" class="btn btn-info">Continue</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                //Toast notifacation for change password
                                if($delConsumeMessage=='false'){   
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($delConsumeMessage=='true') {
                                    ?>
                                        <script type="text/javascript">tata.success('SUCCESS', 'Client Consume Deleted!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="setting-holder"><i class='bx bx-trash' ></i><button type="button" data-toggle="modal" data-target="#deleteAllClient">Delete All Client</button></div>
                    <div class="modal fade" id="deleteAllClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Delete All Client</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>[<span style="color: red;">Warning</span>] : This function will delete ALL CLIENT including their cubic data. Please be carefull for your action. </p>
                                        <p>To confirm deletion, type <i>permanently delete</i> in the text input field.</p>
                                        <input name="client-confirm" type="text" class="add-input" placeholder="permanently delete" autocomplete="off">
                                        <input name="delClientPass" type="password" class="add-input" placeholder="Enter Password" autocomplete="off">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button formaction="./setting/reset-client.php" name="clear-client" class="btn btn-info">Continue</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                //Toast notifacation for change password
                                if($delClient=='false'){   
                                    ?>
                                        <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }else if($delClient=='true') {
                                    ?>
                                        <script type="text/javascript">tata.success('SUCCESS', 'All Client Deleted!', {position: 'tr', duration: 5000})</script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="setting-two"></div>
            </div>
        </div>
    </div>

    <!-- custom script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/line-graph.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    unset($_SESSION["del-message"]);
    unset($_SESSION["price-message"]);
    unset($_SESSION["auth-message"]);
    unset($_SESSION["clearLog-message"]);
    unset($_SESSION["clearConsume-message"]);
    unset($_SESSION["delAllClient-message"]);
?>