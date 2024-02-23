<?php 
    session_start(); // Start or resume a session
    require_once("db_connection.php"); // Include the database connection file

    // CHECK IF ALREADY LOGGED IN
    if(!isset($_SESSION['login'])){
        // If not logged in, redirect to the login page
        header('Location: auth/login.php');
        exit();
    }

    // SESSION VARIABLES
    $addMessage = $_SESSION['add-message'] ?? ""; // Get the add-message session variable or set to empty string
    $username = $_SESSION['user'] ?? ""; // Get the user session variable or set to empty string

    // GET CUBIC PRICE
    $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price"); // Query to get cubic price from the database
    if(mysqli_num_rows($cubic_price) > 0){
        while($row_price = mysqli_fetch_assoc($cubic_price)){
            extract($row_price); // Extract the result row to individual variables
        }
    } 

    // Query to fetch all data from the 'cubic_consume' table
    $cubic_consume_query = "SELECT * FROM `cubic_consume`";
    // Execute the query
    $cubic_consume_result = mysqli_query($conn, $cubic_consume_query);

    // Array to store month labels for the line graph
    $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // Array to store monthly cubic consumption data
    $data = [
        'january' => 0,
        'february' => 0,
        'march' => 0,
        'april' => 0,
        'may' => 0,
        'june' => 0,
        'july' => 0,
        'august' => 0,
        'september' => 0,
        'october' => 0,
        'november' => 0,
        'december' => 0,
    ];

    // Loop through each row of the result set and aggregate monthly data
    while ($row = mysqli_fetch_assoc($cubic_consume_result)) {
        $data['january'] += $row['january'];
        $data['february'] += $row['february'];
        $data['march'] += $row['march'];
        $data['april'] += $row['april'];
        $data['may'] += $row['may'];
        $data['june'] += $row['june'];
        $data['july'] += $row['july'];
        $data['august'] += $row['august'];
        $data['september'] += $row['september'];
        $data['october'] += $row['october'];
        $data['november'] += $row['november'];
        $data['december'] += $row['december'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" /> <!-- Boxicons CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link href="css/style.css" rel="stylesheet" /> <!-- Custom CSS -->
    <script src="js/tata.js"></script> <!-- Tata JS library -->
    <link rel="shortcut icon" href="images/favicon.png"/> <!-- Favicon -->
    
    <title>Water Billing</title>
</head>
<body>
    <!-- Sidebar Section -->
    <div class="sidebar">
        <!-- Logo and Menu Toggle -->
        <div class="logo_content">
            <div class="logo">
                <i style="color: #80cae5;" class="bx bx-droplet"></i>
                <div class="logo_name">Water Data</div>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>

        <!-- Navigation Links -->
        <ul class="nav_list">
            <!-- Dashboard Link -->
            <li>
                <a href="index.php" class="active" title="Dashboard">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
                </a>
            </li>
        
            <!-- Clients Link -->
            <li>
                <a href="clients.php" title="Clients">
                <i class='bx bx-user'></i>
                <span class="links_name">Clients</span>
                </a>
            </li>
        
            <!-- History Link -->
            <li>
                <a href="history.php" title="History">
                <i class='bx bx-history'></i>
                <span class="links_name">History</span>
                </a>
            </li>

            <!-- Setting Link -->
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
        <!-- Live Search Bar -->
        <div class="search-box">
            <div style="display: flex;">
                <i class='bx bx-search search-icon'></i>
                <input type="text" autocomplete="off" placeholder="Search...">
                <!-- Button to Add New Client -->
                <button type="button" class="btn btn-info btn-client" data-toggle="modal" data-target="#addClient">
                    <i class='bx bx-user-plus' title="Add Client"></i>
                </button>
                <div class="result"></div>
            </div>
        </div>

        <!-- Modal For Adding New Client -->
        <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <!-- Form for Adding New Client -->
                <form method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Add Client</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Input fields for client information -->
                            <label for="name">Name</label>
                            <input type="text" class="add-input" name="name" placeholder="Client name..">

                            <label for="address">Address</label>
                            <input type="text" class="add-input" name="address" placeholder="Client address..">

                            <label for="contact">Contact No.</label>
                            <input type="text" class="add-input" name="contact" placeholder="Contact Number..">
                        </div>
                        <div class="modal-footer">
                            <!-- Close and Add Client Buttons -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button formaction="./function/add-client.php" type="submit" name="submit-client" value="true" class="btn btn-info">Add Client</button>
                        </div>
                    </div>
                </form>

                <!-- Display Success or Error Message -->
                <?php
                    if($addMessage=='false'){   
                        ?>
                            <script type="text/javascript">tata.error('ERROR', 'Invalid Input!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } else if($addMessage=='true'){
                        ?>
                            <script type="text/javascript">tata.success('SUCCESS', 'Client Added!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } 
                ?>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard">
            <!-- Client Count -->
            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-pin'></i>
                </div>
                <div class="dash-text">
                    <p>Clients</p>
                    <p>
                        <b>
                            <!-- Count Total Clients -->
                            <?php 
                                $sqlClient = "SELECT count('name') FROM client";
                                $totalClient = mysqli_query($conn,$sqlClient);
                                $row_client = mysqli_fetch_array($totalClient);
                                echo "$row_client[0]";
                            ?>
                        </b>
                    </p>
                </div>
            </div>

            <!-- No Balance Clients Count -->
            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="dash-text">
                    <p>No Balance</p>
                    <p>
                        <b>
                            <!-- Count Paid Clients -->
                            <?php 
                                $sqlPaid = "SELECT count(*) FROM payment_status WHERE status='paid'";
                                $totalPaid = mysqli_query($conn,$sqlPaid);
                                $row_Paid = mysqli_fetch_array($totalPaid);
                                echo "$row_Paid[0]";
                            ?>
                        </b>
                        [<a href="nobalance.php">Show</a>]
                    </p>
                </div>
            </div>

            <!-- Unpaid Clients Count -->
            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-x'></i>
                </div>
                <div class="dash-text">
                    <p>Unpaid</p>
                    <p>
                        <b>
                            <!-- Count Unpaid Clients -->
                            <?php 
                                $sqlUnpaid = "SELECT count(*) FROM payment_status WHERE status='unpaid'";
                                $totalUnpaid = mysqli_query($conn,$sqlUnpaid);
                                $row_Unpaid = mysqli_fetch_array($totalUnpaid);
                                echo "$row_Unpaid[0]";
                            ?>
                        </b>
                        [<a href="unpaid.php">Show</a>]
                    </p>
                </div>
            </div>

            <!-- Cubic Price Display -->
            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-cube-alt'></i>
                </div>
                <div class="dash-text">
                    <p>Cubic Price</p>
                    <p>
                        <b>
                            <!-- Show Cubic Price -->
                            <?php 
                                $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price");
                                if(mysqli_num_rows($cubic_price) > 0){
                                    while($row = mysqli_fetch_assoc($cubic_price)){
                                        extract($row);
                                        echo "₱ ". $cu_price;
                                    }
                                }
                            ?>
                        </b>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Analytics Container Section -->
        <div class="analystic-container">
            <!-- Line Graph Section -->
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <i class='bx bx-line-chart'></i>
                    </div>
                    <div class="analys-text">
                        Line Graph
                    </div>
                </div>
                <!-- Line Graph Canvas -->
                <div class="line-graph">
                    <canvas id="myChart" ></canvas>
                </div>
            </div>

            <!-- Statement Summary Section -->
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <i class='bx bx-money'></i>
                    </div>
                    <div class="analys-text">
                        Statement Summary
                    </div>
                </div>
                <!-- Dash Amount Table -->
                <div class="dash-amount">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Month</th>
                                <th scope="col">Cubic Consume</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="overflow-y: auto;">
                            <!-- Get the total Collectibles Per Month-->
                            <?php
                                $listMonth = array(
                                    'january',
                                    'february',
                                    'march',
                                    'april',
                                    'may',
                                    'june',
                                    'july',
                                    'august',
                                    'september',
                                    'october',
                                    'november',
                                    'december'
                                );

                                foreach($listMonth as $index => $value) {
                                    $cuTotal = mysqli_query($conn, 'SELECT SUM('.$listMonth[$index].') AS cubic_sum FROM cubic_consume'); 
                                    $row_cubic = mysqli_fetch_assoc($cuTotal); 
                                    $cubic_sum = $row_cubic['cubic_sum'];
                            ?>
                                    <tr>
                                        <th scope="row"><?php echo ucwords($listMonth[$index]); ?></th>
                                        <td>
                                            <?php
                                                if($cubic_sum!=0) {
                                                    echo $cubic_sum;
                                                } else {
                                                    echo "--";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $collectMonthTotal = $cubic_sum * $cu_price; 
                                                if($collectMonthTotal!=0) {
                                                    echo "₱ ". $collectMonthTotal;
                                                } else {
                                                    echo "--";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            ?>

                            <!-- Total Year Consume -->
                            <tr style="border-top: 2px solid gray;">
                                <?php 
                                    $cuTotalYear = mysqli_query($conn, 'SELECT SUM(cubic_sum) AS year_sum FROM cubic_consume'); 
                                    $rowYearCubic = mysqli_fetch_assoc($cuTotalYear); 
                                    $yearCubicSum = $rowYearCubic['year_sum'];
                                ?>
                                <th scope="row">TOTAL </th>
                                <td><?php echo $yearCubicSum; ?></td>
                                <td>₱ <?php echo $yearCubicSum * $cu_price; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script> <!-- Chart.js library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- jQuery library -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> <!-- Bootstrap JS library -->
    <script src="js/script.js"></script> <!-- Custom JS -->
    <!-- <script src="js/line-graph.js"></script> -->

    <!-- Line Chart Initialization Script -->
    <script>
        // Initialize Line Chart on page load
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("myChart").getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                        {
                            label: "Monthly Record",
                            data: <?php echo json_encode(array_values($data)); ?>,
                            borderWidth: 1,
                            fill: false,
                            borderColor: 'rgba(75, 192, 192, 1)',
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 10,
                        },
                    },
                },
            });
        });
    </script>
</body>
</html>
<?php
    unset($_SESSION["add-message"]); // Unset the add-message session variable
?>
