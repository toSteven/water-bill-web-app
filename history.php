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
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js" type="text/javascript"></script>
    <script src="js/tata.js"></script>
    <link href="css/style.css" rel="stylesheet" />
    
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
                <a href="history.php" class="active" title="History">
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
        <!-- History table [INFO: I use client design for history page] -->
        <div class="client-container">
            <div style="display: flex;">
                <div class="client-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="client-text">
                    Activity Logs
                </div>
            </div>
            <div  class="client-table">
                <div style="height:500px;overflow:auto;">
                <table id="client-data-table" class="table table-striped table-bordered table-sm" cellspacing="0" >
                    <thead>
                        <tr>
                            <th class="th-sm">User</th>
                            <th class="th-sm">Logs</th>
                            <th class="th-sm">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            //Show all History Logs
                            $eventLogs = mysqli_query($conn,"SELECT * FROM logs ORDER BY datetime DESC");
                            if(mysqli_num_rows($eventLogs) > 0){
                                while($row = mysqli_fetch_assoc($eventLogs)){
                                    extract($row);
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $event_logs; ?></td>
                                <td>
                                    <!-- Display Time Ago -->
                                    <script type="text/javascript">
                                        document.write(moment("<?php echo $datetime; ?>").fromNow());
                                    </script>
                                </td>
                            </tr>
                        <?php 
                                }
                            }
                        ?>
                    </tbody>
                </table>
                </div>
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
?>