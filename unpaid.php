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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
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
        <!-- client table -->
        <div class="client-container">
            <div style="display: flex;">
                <div class="client-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="client-text">
                    No Balance List
                </div>
            </div>
            <div class="client-table">
                <table id="client-data-table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="th-sm">Name</th>
                            <th class="th-sm">Address</th>
                            <th class="th-sm">Status</th>
                            <th class="th-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            //Show all Clients Data to Table
                            $client = mysqli_query($conn,"SELECT client.id, client.name, client.address, payment_status.status FROM client INNER JOIN payment_status ON client.unique_id=payment_status.unique_id WHERE payment_status.status='unpaid';");
                            if(mysqli_num_rows($client) > 0){
                                while($row = mysqli_fetch_assoc($client)){
                                    extract($row);
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td style="text-align: center;"><?php echo $address; ?></td>
                                <!-- Change color based on client status -->
                                <?php if($status=='unpaid'){ ?><td style="text-align: center;"><span class="unpaid"><?php echo $status; ?></span></td>
                                <?php } ?>

                                <!-- Action Button -->
                                <form method="GET">
                                    <td style="text-align: center;">
                                        <button formaction="client-info.php" class="btn btn-info" name="btn-client" value="<?php echo $id; ?>"><i class='bx bx-folder-open'></i></button>
                                    </td>
                                </form>
                            </tr>

                        <?php 
                                }
                            }

                            //Session Message 
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

    <!-- custom script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>

    <script>
        let btn = document.querySelector("#btn");
        let siderbar = document.querySelector(".sidebar");
        let searchBtn = document.querySelector(".bx-search");
        btn.onclick = function() {
            siderbar.classList.toggle("active");
        }
        searchBtn.onclick = function() {
            siderbar.classList.toggle("active");
        }

        $(document).ready(function () {
            $('#client-data-table').DataTable({
                scrollY: '400px',
                scrollCollapse: true,
                paging: true,
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script src="js/line-graph.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    unset($_SESSION["del-message"]);
?>