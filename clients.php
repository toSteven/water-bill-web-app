<?php 
    // Start the session and include the database connection file
    session_start();
    require_once("db_connection.php");

    // Redirect to login page if user is not logged in
    if(!isset($_SESSION['login'])){
        header('Location: auth/login.php');
        exit();
    }

    // Retrieve session variables for display
    $delMessage = $_SESSION['del-message'] ?? "";
    $username = $_SESSION['user'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- External CSS and JS libraries -->
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
    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <i style="color: #80cae5;" class="bx bx-droplet"></i>
                <div class="logo_name">Water Data</div>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>

        <!-- Navigation List (Initially Hidden) -->
        <ul class="nav_list" style="display: none;">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
            </li>
        </ul>

        <!-- Main Navigation List -->
        <ul class="nav_list">
            <li>
                <a href="index.php" title="Dashboard">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
                </a>
            </li>
        
            <li>
                <a href="clients.php" class="active" title="Clients">
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
                <a href="setting.php" title="Setting">
                <i class='bx bx-cog'></i>
                <span class="links_name">Setting</span>
                </a>
            </li>
        </ul>

        <!-- User Profile Section -->
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <div class="name_job">
                        <!-- Display username and role -->
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

    <!-- Main Content Section -->
    <div class="home_content">
        <div class="client-container">
            <div style="display: flex;">
                <div class="client-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="client-text">
                    Client List
                </div>
            </div>
            <!-- Client Table Section -->
            <div class="client-table">
                <!-- Display client data in a table -->
                <table id="client-data-table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="th-sm">Name</th>
                            <th class="th-sm">Address</th>
                            <th class="th-sm">Cubic</th>
                            <th class="th-sm">Status</th>
                            <th class="th-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            // Fetch and display client data in table rows
                            $client = mysqli_query($conn,"SELECT * FROM client");
                            if(mysqli_num_rows($client) > 0){
                                while($row = mysqli_fetch_assoc($client)){
                                    extract($row);
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td style="text-align: center;"><?php echo $address; ?></td>
                                <td style="text-align: center;">
                                    <?php
                                        // Retrieve and display cubic consumption
                                        $consume = mysqli_query($conn,"SELECT cubic_sum FROM cubic_consume WHERE unique_id = '$unique_id'");
                                        if ($consume) {
                                            $row_consume = mysqli_fetch_row($consume);
                                            echo $row_consume[0] ?? null;
                                        }else{
                                            exit;
                                        }
                                    ?>
                                </td>
                                <?php if($status=='UNPAID'){ ?><td style="text-align: center;"><span class="unpaid"><?php echo $status; ?></span></td>
                                <?php }else if($status=='PAID'){ ?><td style="text-align: center;"><span class="paid"><?php echo $status; ?></span></td>
                                <?php }else{  ?><td style="text-align: center;"><span><?php echo "not available"; } ?>
                                <!-- Action Button (View Client Details) -->
                                <form method="GET">
                                    <td style="text-align: center;">
                                        <button formaction="client-info.php" class="btn btn-info" name="btn-client" value="<?php echo $id; ?>"><i class='bx bx-folder-open'></i></button>
                                    </td>
                                </form>
                            </tr>
                        <?php 
                                }
                            }

                            // Display success message if client is deleted
                            if($delMessage=='true'){   
                                ?>
                                    <script type="text/javascript">tata.success('SUCCESS', 'Client Deleted!', {position: 'tr', duration: 5000})</script>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script src="js/line-graph.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    // Unset the session variable for delete message
    unset($_SESSION["del-message"]);
?>
