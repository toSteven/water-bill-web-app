<?php 
    // Start a new session and include the database connection file
    session_start();
    require_once("db_connection.php");

    // Redirect to the login page if the user is not logged in
    if(!isset($_SESSION['login'])){
        header('Location: auth/login.php');
        exit();
    }

    // Initialize session variables to store messages and user information
    $editMessage = $_SESSION['edit-message'] ?? "";
    $consumeMessage = $_SESSION['consume-message'] ?? "";
    $delMessage = $_SESSION["del-message"] ?? "";
    $resetMessage = $_SESSION["reset-message"] ?? "";
    $paymentMessage = $_SESSION["payment-message"] ?? "";
    $username = $_SESSION['user'] ?? "";

    // Get the client ID from the GET parameters and store it in the session
    $client_id = $_GET['btn-client'] ?? "";
    $_SESSION['passID'] = $client_id;

    // Retrieve client information from the database based on the client ID
    $profile = mysqli_query($conn,"SELECT * FROM client WHERE id='$client_id'");
    if(mysqli_num_rows($profile) > 0){
        while($row_id = mysqli_fetch_assoc($profile)){
            extract($row_id);
        }
    }

    // Retrieve the cubic price from the database
    $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price");
    if(mysqli_num_rows($cubic_price) > 0){
        while($row_price = mysqli_fetch_assoc($cubic_price)){
            extract($row_price);
        }
    }  

    // Retrieve the payment status for the client per month
    $pay_status = mysqli_query($conn,"SELECT * FROM payment_status WHERE unique_id='$unique_id'");
    if(mysqli_num_rows($pay_status) > 0){
        while($row_status = mysqli_fetch_assoc($pay_status)){
            extract($row_status);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link href="css/style.css" rel="stylesheet" />
    <script src="js/tata.js"></script>
    <link rel="shortcut icon" href="images/favicon.png"/>	
    <title>Water Billing</title>
</head>
<body>
    <div class="sidebar"> 
        <!-- Logo and Menu Button Section -->
        <div class="logo_content">
                <!-- Logo -->
                <div class="logo">
                    <i class="bx bx-droplet"></i>
                    <div class="logo_name">Water Data</div>
                </div>
                <!-- Menu Button -->
                <i class="bx bx-menu" id="btn"></i>
            </div>

            <!-- Navigation Links -->
            <ul class="nav_list">
                <!-- Dashboard Link -->
                <li>
                    <a href="index.php">
                        <i class="bx bx-grid-alt"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                </li>

                <!-- Clients Link -->
                <li>
                    <a href="clients.php" class="active">
                        <i class='bx bx-user'></i>
                        <span class="links_name">User</span>
                    </a>
                </li>

                <!-- History Link -->
                <li>
                    <a href="history.php">
                        <i class='bx bx-history'></i>
                        <span class="links_name">History</span>
                    </a>
                </li>

                <!-- Setting Link -->
                <li>
                    <a href="setting.php">
                        <i class='bx bx-cog'></i>
                        <span class="links_name">Setting</span>
                    </a>
                </li>
            </ul>

            <!-- User Profile Section -->
            <div class="profile_content">
                <div class="profile">
                    <!-- User Details -->
                    <div class="profile_details">
                        <div class="name_job">
                            <div class="name"><?php echo $username; ?></div>
                            <div class="job">Administrator</div>
                        </div>
                    </div>
                    <!-- Logout Button -->
                    <form action="auth/logout.php">
                        <button type="submit"><i class='bx bx-log-out' id="log_out" title="Logout"></i></button>
                    </form>
                </div>
            </div>
    </div>

    <div class="home_content">
        <div class="info-container">
            <div style="display: flex; height: 100%;">
                <!-- Client profile container -->
                <div class="client-profile">

                    <!-- Search box section -->
                    <div style="width: 85%; margin: 15px;" class="search-box">
                        <div style="display: flex;">
                            <!-- Search icon -->
                            <i class='bx bx-search search-icon'></i>
                            
                            <!-- Search input -->
                            <input type="text" autocomplete="off" placeholder="Search Profile">
                            
                            <!-- Search result display area -->
                            <div style="width: 100%;" class="result"></div>
                        </div>
                    </div>

                    <!-- Profile form -->
                    <form method="GET">

                        <!-- Profile image display area -->
                        <div class="profile-img"></div>
                        <br>

                        <!-- Displaying client name -->
                        <p><b><?php echo $name ?></b></p>

                        <!-- Displaying client contact number -->
                        <p><i class='bx bxs-phone'></i>
                            <?php
                            // Check if contact number is available
                            if(empty($contact)) {
                                echo "Not Available";
                            } else {
                                echo $contact;
                            }
                            ?>
                        </p>

                        <!-- Displaying client address -->
                        <p><i class='bx bxs-location-plus' ></i>
                            <?php
                            // Check if address is available
                            if(empty($address)) {
                                echo "Not Available";
                            } else {
                                echo $address;
                            }
                            ?>
                        </p>

                        <!-- Button to edit client information -->
                        <button style="background-color: #b3b3ff;" type="button" class="btn btn-info" data-toggle="modal" data-target="#editClient" title="Edit"><i class='bx bx-edit'></i></button>

                        <!-- Button to delete client information -->
                        <button style="background-color: #ff8080;" type="button" class="btn btn-info" data-toggle="modal" data-target="#deleteClient" title="Delete"><i class='bx bx-trash' ></i></button>

                        <!-- Button to initiate payment -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#payConsume" title="Pay">Pay</button>
                    </form>
                </div>

                <!-- MODAL FOR PAYMENT -->
                <div class="modal fade" id="payConsume" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Payment</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>* Please check cubic consume carefully before marking them as paid.</p>
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr> 
                                                <th scope="col"><input type="checkbox" id="select-all" 
                                                    <?php 
                                                        if(
                                                            $stat_january=='paid' &&
                                                            $stat_february=='paid' &&
                                                            $stat_march=='paid' &&
                                                            $stat_april=='paid' &&
                                                            $stat_may=='paid' &&
                                                            $stat_june=='paid' &&
                                                            $stat_july=='paid' &&
                                                            $stat_august=='paid' &&
                                                            $stat_september=='paid' &&
                                                            $stat_october=='paid' &&
                                                            $stat_november=='paid' &&
                                                            $stat_december=='paid' 
                                                        ) {
                                                            echo "disabled";
                                                        }
                                                    ?>
                                                >&nbsp;&nbsp;Months</th>
                                                <th scope="col">Total Consume</th>
                                                <th scope="col">To Pay</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                //Get the cubic consume per month
                                                $cuMonthData = mysqli_query($conn,"SELECT * FROM cubic_consume WHERE unique_id='$unique_id'");
                                                if(mysqli_num_rows($cuMonthData ) > 0){
                                                    while($row = mysqli_fetch_assoc($cuMonthData )){
                                                        extract($row);
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="jan" <?php if(empty($january) || $stat_january=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;January</label>
                                                </td>
                                                <?php 
                                                    if(empty($january)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $january; ?></td>
                                                            <td>₱ <?php if($january <= 10) { echo "130";}else{ echo $january * $cu_price; }  ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="feb" <?php if(empty($february) || $stat_february=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;February</label>
                                                </td>
                                                <?php 
                                                    if(empty($february)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $february; ?></td>
                                                            <td>₱ <?php if($february <= 10) { echo "130"; }else{ echo $february * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="mar" <?php if(empty($march) || $stat_march=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;March</label>
                                                </td>
                                                <?php 
                                                    if(empty($march)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $march; ?></td>
                                                            <td>₱ <?php if($march <= 10) { echo "130"; }else{ echo $march * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="apr" <?php if(empty($april) || $stat_april=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;April</label>
                                                </td>
                                                <?php 
                                                    if(empty($april)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $april; ?></td>
                                                            <td>₱ <?php if($april <= 10) { echo "130"; }else{ echo $april * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="may" <?php if(empty($may) || $stat_may=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;May</label>
                                                </td>
                                                <?php 
                                                    if(empty($may)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $may; ?></td>
                                                            <td>₱ <?php if($may <= 10) { echo "130"; }else{ echo $may * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="jun" <?php if(empty($june) || $stat_june=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;June</label>
                                                </td>
                                                <?php 
                                                    if(empty($june)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $june; ?></td>
                                                            <td>₱ <?php if($june <= 10) { echo "130"; }else{ echo $june * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="jul" <?php if(empty($july)|| $stat_july=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;July</label>
                                                </td>
                                                <?php 
                                                    if(empty($july)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $july; ?></td>
                                                            <td>₱ <?php if($july <= 10) { echo "130"; }else{ echo $july * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="aug" <?php if(empty($august) || $stat_august=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;August</label>
                                                </td>
                                                <?php 
                                                    if(empty($august)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $august; ?></td>
                                                            <td>₱ <?php if($august <= 10) { echo "130"; }else{ echo $august * $cu_price; }; ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="sept" <?php if(empty($september) || $stat_september=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;September</label>
                                                </td>
                                                <?php 
                                                    if(empty($september)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $september; ?></td>
                                                            <td>₱ <?php if($september <= 10) { echo "130"; }else{ echo $september * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="oct" <?php if(empty($october) || $stat_october=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;October</label>
                                                </td>
                                                <?php 
                                                    if(empty($october)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $october; ?></td>
                                                            <td>₱ <?php if($october <= 10) { echo "130"; }else{ echo $october * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="nov" <?php if(empty($november) || $stat_november=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;November</label>
                                                </td>
                                                <?php 
                                                    if(empty($november)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $november; ?></td>
                                                            <td>₱ <?php if($november <= 10) { echo "130"; }else{ echo $november * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="payment[]" value="dec" <?php if(empty($december) || $stat_december=='paid') { echo "disabled"; } ?>>
                                                    <label for="vehicle3"> &nbsp;&nbsp;December</label>
                                                </td>
                                                <?php 
                                                    if(empty($december)){
                                                        ?>
                                                            <td>Not Available</td>
                                                            <td>Not Available</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <td><?php echo $december; ?></td>
                                                            <td>₱ <?php if($december <= 10) { echo "130"; }else{ echo $december * $cu_price; } ?></td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button formaction="./function/payment.php" name="payment_btn" value="<?php echo $unique_id ?>" class="btn btn-info">Pay</button>
                                </div>
                            </div>
                        </form>
                        <?php
                            //Toast notifacation for delete form
                            if($paymentMessage=='true'){   
                                ?>
                                    <script type="text/javascript">tata.success('SUCCESS', 'Payment Successful!', {position: 'tr', duration: 5000})</script>
                                <?php
                            }else if($paymentMessage=='false') {
                                ?>
                                    <script type="text/javascript">tata.error('ERROR', 'Payment Unsuccessful!', {position: 'tr', duration: 5000})</script>
                                <?php
                            }
                        ?>
                    </div>
                </div>

                <!-- MODAL FOR DELETE FORM -->
                <div class="modal fade" id="deleteClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="GET">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Account Deletion</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>To confirm deletion, type <i>permanently delete</i> in the text input field.</p>
                                    <input type="text" class="add-input" name="confirm-delete" placeholder="permanently delete" autocomplete="off">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button formaction="./function/delete-client.php" name="delete-client" style="background-color: #ff8080;" value="<?php echo $unique_id ?>" class="btn btn-info">Delete</button>
                                </div>
                            </div>
                        </form>
                        <?php
                            //Toast notifacation for delete form
                            if($delMessage=='false'){   
                                ?>
                                    <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                <?php
                            } 
                        ?>
                    </div>
                </div>

                <!-- MODAL FOR EDIT FORM -->
                <div class="modal fade" id="editClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="GET">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Edit Client</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label for="address">Name</label>
                                    <input type="text" class="add-input" name="name" placeholder="Client name.." value="<?php echo $name ?>">

                                    <label for="address">Address</label>
                                    <input type="text" class="add-input" name="address" placeholder="Client address.." value="<?php echo $address ?>">

                                    <label for="address">Contact No.</label>
                                    <input type="text" class="add-input" name="contact" placeholder="Client Number.." value="<?php echo $contact ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button formaction="./function/update-client.php" name="update-client" value="<?php echo $client_id ?>" class="btn btn-info">Save changes</button>
                                </div>
                            </div>
                        </form>
                        <?php
                            //Toast notifacation for edit form
                            if($editMessage=='false'){   
                                ?>
                                    <script type="text/javascript">tata.error('ERROR', 'Something Wrong!', {position: 'tr', duration: 5000})</script>
                                <?php
                            } else if($editMessage=='true'){
                                ?>
                                    <script type="text/javascript">tata.success('SUCCESS', 'Edited Successfully!', {position: 'tr', duration: 5000})</script>
                                <?php
                            } 
                        ?>
                    </div>
                </div>

                <div class="client-info">
                    <p><b>Account Balance</b><span style="float: right;"><script>document.write(new Date().toDateString()); </script></span></p>
                    <hr>
                    <div style="display: flex; height: 88%;">
                        <div class="data-one">
                            <div class="data-content">
                                <div style="display: flex; height: 100%;">
                                    <div  iv class="data-icon">
                                        <i class='bx bx-money'></i>
                                    </div>
                                    <div class="data-text">
                                        Status
                                        <br>
                                        <?php 
                                            //Array
                                            $statMonth = array(
                                                $stat_january, 
                                                $stat_february, 
                                                $stat_march, 
                                                $stat_april, 
                                                $stat_may, 
                                                $stat_june, 
                                                $stat_july, 
                                                $stat_august,
                                                $stat_september, 
                                                $stat_october, 
                                                $stat_november, 
                                                $stat_december
                                            );

                                            $listMonth = array(
                                                $january, 
                                                $february, 
                                                $march, 
                                                $april, 
                                                $may, 
                                                $june, 
                                                $july, 
                                                $august,
                                                $september, 
                                                $october, 
                                                $november, 
                                                $december
                                            );

                                            // Display Status
                                            if($cubic_sum==0 && $total==0){
                                                ?>
                                                <h6 class="nobalance">no balance</h6>
                                                <?php
                                            }else if(
                                                $stat_january=='paid' &&
                                                $stat_february=='paid' &&
                                                $stat_march=='paid' &&
                                                $stat_april=='paid' &&
                                                $stat_may=='paid' &&
                                                $stat_june=='paid' &&
                                                $stat_july=='paid' &&
                                                $stat_august=='paid' &&
                                                $stat_september=='paid' &&
                                                $stat_october=='paid' &&
                                                $stat_november=='paid' &&
                                                $stat_december=='paid'
                                            ) {
                                                ?>
                                                <p class="paid">PAID</p>
                                                <?php
                                            }else{
                                                // Count unpaid month per cient
                                                $count_unpaid = 0;
                                                foreach($statMonth as $unpaid) {
                                                    if($unpaid=='unpaid'){
                                                        $count_unpaid +=1;
                                                    }
                                                }
                                                
                                                if(empty($count_unpaid)){
                                                    ?>
                                                    <h6 class="nobalance">no balance</h6>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <h6 class="unpaid"><?php echo $count_unpaid; ?> unpaid</h6>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="data-content">
                                <div style="display: flex; height: 100%;">
                                    <div  iv class="data-icon">
                                        <i class='bx bx-droplet' ></i>
                                    </div>
                                    <div class="data-text">
                                        Consume
                                        <br>
                                        <b> <!--Display Total Amount of cubic consume -->
                                            <?php 
                                                $consume = mysqli_query($conn,"SELECT cubic_sum FROM cubic_consume WHERE unique_id = '$unique_id'");
                                                if ($consume) {
                                                    $row_consume = mysqli_fetch_row($consume);
                                                    echo $row_consume[0];
                                                }else{
                                                    exit;
                                                }
                                            ?>
                                        </b>
                                    </div>
                                </div>
                            </div>

                            <div class="data-content">
                                <div style="display: flex; height: 100%;">
                                    <div  iv class="data-icon">
                                        <i class='bx bx-wallet'></i>
                                    </div>
                                    <div class="data-text">
                                        To Pay
                                        <br>
                                        <b>₱<!--Display Total payment -->
                                        <?php  

                                            if(empty($listMonth)) {
                                                echo "0";
                                            }else{
                                                $toPayCount = 0;
                                                $countMinRate = 0; //to count cubic meter less than equal to 10
                                                foreach($listMonth as $index => $value) {
                                            
                                                    if($listMonth[$index] > 0 && $listMonth[$index] <=10 ){
                                                        if($statMonth[$index]=='unpaid'){
                                                            $countMinRate +=1;
                                                        }
                                                    }else if($listMonth[$index] > 10){ //Count/Compute all cubic greater than 11
                                                        if($statMonth[$index]=='unpaid'){
                                                            $toPayCount += $listMonth[$index];
                                                        }
                                                    }
                                                }
                                                $computeTotalRate = 130 * $countMinRate; //Total of cubic meter with less than 10 
                                                echo ($toPayCount * 13) + $computeTotalRate; //Total To Pay 
                                            }
                                        ?>
                                        </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="data-two">
                            <!-- Display Bar Graph -->
                            <canvas id="bar-chart" width="100%" height="75%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table to Add/Edit the Data of Cubic Consume -->
            <div class="consume-container">
                <span><i style="color: #80ffc1;" class='bx bxs-circle'></i></span> Paid &nbsp;<span><i style="color: #ffb3b3;" class='bx bxs-circle'></i></span> Unpaid
                <table class="table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Jan</th>
                            <th class="text-center">Feb</th>
                            <th class="text-center">Mar</th>
                            <th class="text-center">Apr</th>
                            <th class="text-center">May</th>
                            <th class="text-center">Jun</th>
                            <th class="text-center">Jul</th>
                            <th class="text-center">Aug</th>
                            <th class="text-center">Sept</th>
                            <th class="text-center">Oct</th>
                            <th class="text-center">Nov</th>
                            <th class="text-center">Dec</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST">
                            <tr>
                                <?php 
                                    //Get the cubic consume per month
                                    $cuMonthData = mysqli_query($conn,"SELECT * FROM cubic_consume WHERE unique_id='$unique_id'");
                                    if(mysqli_num_rows($cuMonthData ) > 0){
                                        while($row = mysqli_fetch_assoc($cuMonthData )){
                                            extract($row);
                                        }
                                    }
                                ?>

                                <!-- Display the consume to user table ([INFO]: User can ADD and EDIT the data from this table) -->
                                <td><input name="jan" class="td-consume" type="number" value="<?php echo $january; ?>" <?php if($stat_january=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="feb" class="td-consume" type="number" value="<?php echo $february; ?>" <?php if($stat_february=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="mar" class="td-consume" type="number" value="<?php echo $march; ?>" <?php if($stat_march=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="apr" class="td-consume" type="number" value="<?php echo $april; ?>" <?php if($stat_april=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="may" class="td-consume" type="number" value="<?php echo $may; ?>" <?php if($stat_may=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="jun" class="td-consume" type="number" value="<?php echo $june; ?>" <?php if($stat_june=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="jul" class="td-consume" type="number" value="<?php echo $july; ?>" <?php if($stat_july=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="aug" class="td-consume" type="number" value="<?php echo $august; ?>" <?php if($stat_august=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="sept"class="td-consume" type="number" value="<?php echo $september; ?>" <?php if($stat_september=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="oct" class="td-consume" type="number" value="<?php echo $october; ?>" <?php if($stat_october=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="nov" class="td-consume" type="number" value="<?php echo $november; ?>" <?php if($stat_november=='paid') { echo "readonly"; } ?>></td>
                                <td><input name="dec" class="td-consume" type="number" value="<?php echo $december; ?>" <?php if($stat_december=='paid') { echo "readonly"; } ?>></td>
                                <td>
                                <span class="table-success"><button name="update-consume" value="<?php echo $unique_id; ?>" formaction="./function/update-consume.php" class="btn btn-success btn-rounded btn-sm my-0" onclick="return confirm('Are you sure you want to update cubic consume?')">Save</button></span>
                                </td>
                            </tr>
                        </form>
                        <tr>
                            <?php 
                            ?>
                            <!-- Display the To Pay ammount of client per month -->
                            <!-- January -->
                            <?php 
                                if($stat_january=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($january <=10){ echo "130";  }else{ echo  $january * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_january=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($january <=10){ echo "130";  }else{ echo  $january * $cu_price; }?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $january * $cu_price?></td>
                                    <?php
                                }
                            ?>
                            
                            <!-- February -->
                            <?php 
                                if($stat_february=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($february <=10){ echo "130";  }else{ echo  $february * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_february=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($february <=10){ echo "130";  }else{ echo  $february * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $february* $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- March -->
                            <?php 
                                if($stat_march=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($march <=10){ echo "130";  }else{ echo  $march * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_march=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($march <=10){ echo "130";  }else{ echo  $march * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $march * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- April -->
                            <?php 
                                if($stat_april=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($april <=10){ echo "130";  }else{ echo  $april * $cu_price; }?></td>
                                    <?php
                                }else if($stat_april=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($april <=10){ echo "130";  }else{ echo  $april * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $april * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- May -->
                            <?php 
                                if($stat_may=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($may <=10){ echo "130";  }else{ echo  $may * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_may=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($may <=10){ echo "130";  }else{ echo  $may * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $may * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- June -->
                            <?php 
                                if($stat_june=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($june <=10){ echo "130";  }else{ echo  $june * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_june=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($june <=10){ echo "130";  }else{ echo  $june * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $june * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- July -->
                            <?php 
                                if($stat_july=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($july <=10){ echo "130";  }else{ echo  $july * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_july=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($july <=10){ echo "130";  }else{ echo  $july * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $july * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- August -->
                            <?php 
                                if($stat_august=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($august <=10){ echo "130";  }else{ echo  $august * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_august=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($august <=10){ echo "130";  }else{ echo  $august * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $august * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- September -->
                            <?php 
                                if($stat_september=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($september <=10){ echo "130";  }else{ echo  $september * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_september=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($september <=10){ echo "130";  }else{ echo  $september * $cu_price; }?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $september * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- October -->
                            <?php 
                                if($stat_october=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($october <=10){ echo "130";  }else{ echo  $october * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_october=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($october <=10){ echo "130";  }else{ echo  $october * $cu_price; } ?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $october * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- November -->
                            <?php 
                                if($stat_november=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($november<=10){ echo "130";  }else{ echo  $november * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_november=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($november <=10){ echo "130";  }else{ echo  $november * $cu_price; }?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $november * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- December -->
                            <?php 
                                if($stat_december=='paid') {
                                    ?>
                                    <td class="marked-paid">₱ <?php if($december <=10){ echo "130";  }else{ echo  $december * $cu_price; } ?></td>
                                    <?php
                                }else if($stat_december=='unpaid'){
                                    ?>
                                    <td class="marked-unpaid">₱ <?php if($december <=10){ echo "130";  }else{ echo  $december * $cu_price; }?></td>
                                    <?php
                                }else {
                                    ?>
                                    <td>₱ <?php echo $december * $cu_price?></td>
                                    <?php
                                }
                            ?>

                            <!-- Reset Button -->
                            <td>
                                <span class="table-success"><button name="update-consume" class="btn btn-danger btn-rounded btn-sm my-0" data-toggle="modal" data-target="#resetConsume">reset</button></span>
                            </td>
                            
                            <!-- CONFIRM RESET FORM-->
                            <div class="modal fade" id="resetConsume" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="GET">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalLabel">Reset Confirmation</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>To confirm reset, type <i>reset permanently</i> in the text input field.</p>
                                                <input type="text" class="add-input" name="confirm-resetInput" placeholder="reset permanently" autocomplete="off">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button formaction="./function/reset-consume.php" name="confirm-reset" style="background-color: #ff8080;" value="<?php echo $unique_id ?>" class="btn btn-info">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                        //Toast notifacation for delete form
                                        if($resetMessage=='false') {   
                                            ?>
                                                <script type="text/javascript">tata.error('ERROR', 'Something Wrong!, Try again!', {position: 'tr', duration: 5000})</script>
                                            <?php
                                        }else if($resetMessage=='true') {
                                            ?>
                                                <script type="text/javascript">tata.success('SUCCESS', 'Reset Successfully!', {position: 'tr', duration: 5000})</script>
                                            <?php
                                        } 
                                    ?>
                                </div>
                            </div>
                        </tr>

                        <?php
                            // Toast notifacation for ADD/EDITING the cubic consume data
                            if($consumeMessage=='false'){   
                                ?>
                                    <script type="text/javascript">tata.error('ERROR', 'Something Wrong!', {position: 'tr', duration: 5000})</script>
                                <?php
                            } else if($consumeMessage=='true'){
                                ?>
                                    <script type="text/javascript">tata.success('SUCCESS', 'Cubic Edited Successfully!', {position: 'tr', duration: 5000})</script>
                                <?php
                            } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <!-- custom script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>

    <script>
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'],
                datasets: [
                    {
                    label: "Cubic Meter",
                    backgroundColor: "#3e95cd",
                    borderColor: "#3e95cd",
                    data: [
                            <?php echo $january; ?>,
                            <?php echo $february; ?>,
                            <?php echo $march; ?>,
                            <?php echo $april; ?>,
                            <?php echo $may; ?>,
                            <?php echo $june; ?>,
                            <?php echo $july; ?>,
                            <?php echo $august; ?>,
                            <?php echo $september; ?>,
                            <?php echo $october; ?>,
                            <?php echo $november; ?>,
                            <?php echo $december; ?>
                        ]
                    }
                ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Cubic Consume Per Month'
                }
            }
        });

        $('#select-all').click(function(event) {
            $(':checkbox').prop('checked', this.checked);
        });
    </script>
</body>
</html>
<?php
    unset($_SESSION["edit-message"]);
    unset($_SESSION["consume-message"]);
    unset($_SESSION["del-message"]);
    unset($_SESSION["reset-message"]);
    unset($_SESSION["payment-message"]);
?>