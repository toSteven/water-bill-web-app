<?php 
    // Start the PHP session
    session_start();
    
    // Include the database connection file
    require_once("db_connection.php");

    // Redirect to the login page if the user is not logged in
    if (!isset($_SESSION['login'])) {
        header('Location: auth/login.php');
        exit();
    }

    // Get session variables for displaying messages and username
    $addMessage = $_SESSION['add-message'] ?? "";
    $username = $_SESSION['user'] ?? "";

    // Query to fetch cubic price from the database
    $cubic_price = mysqli_query($conn, "SELECT * FROM cu_price");

    // Check if there are rows in the cubic price result
    if (mysqli_num_rows($cubic_price) > 0) {
        // Loop through the result and extract each row
        while ($row_price = mysqli_fetch_assoc($cubic_price)) {
            extract($row_price);
        }
    } 

    // Query to fetch cubic consumption data from the database
    $cubic_consume_query = "SELECT * FROM `cubic_consume`";
    $cubic_consume_result = mysqli_query($conn, $cubic_consume_query);

    // Initialize arrays for the line graph labels and data
    $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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

    // Loop through each row of cubic consumption data and aggregate monthly values
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
    <!-- Include external CSS libraries -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Include custom stylesheet -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- Include JavaScript library for notifications -->
    <script src="js/tata.js"></script>
    <!-- Set favicon for the website -->
    <link rel="shortcut icon" href="images/favicon.png"/>
    <title>Water Billing</title>
</head>
<body>
    <!-- Sidebar section -->
    <div class="sidebar">
        <!-- Logo and menu content -->
        <div class="logo_content">
            <div class="logo">
                <!-- Water droplet icon -->
                <i style="color: #80cae5;" class="bx bx-droplet"></i>
                <div class="logo_name">Water Data</div>
            </div>
            <!-- Menu icon -->
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <!-- Navigation list -->
        <ul class="nav_list">
            <li>
                <a href="index.php" class="active" title="Dashboard">
                    <!-- Dashboard icon -->
                    <i class="bx bx-grid-alt"></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="clients.php" title="Clients">
                    <!-- Clients icon -->
                    <i class='bx bx-user'></i>
                    <span class="links_name">Clients</span>
                </a>
            </li>
            <li>
                <a href="history.php" title="History">
                    <!-- History icon -->
                    <i class='bx bx-history'></i>
                    <span class="links_name">History</span>
                </a>
            </li>
            <li>
                <a href="setting.php" title="Setting">
                    <!-- Settings icon -->
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Setting</span>
                </a>
            </li>
        </ul>
        <!-- User profile section -->
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <div class="name_job">
                        <!-- Display the username -->
                        <div class="name"><?php echo $username; ?></div>
                        <!-- Display user role/job -->
                        <div class="job">Administrator</div>
                    </div>
                </div>
                <!-- Logout button -->
                <form action="auth/logout.php">
                    <button type="submit"><i class='bx bx-log-out' id="log_out" title="Logout"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main content section -->
    <div class="home_content">
        <!-- Search box -->
        <div class="search-box">
            <div style="display: flex;">
                <!-- Search icon -->
                <i class='bx bx-search search-icon'></i>
                <!-- Search input field -->
                <input type="text" autocomplete="off" placeholder="Search...">
                <!-- Button to add a new client -->
                <button type="button" class="btn btn-info btn-client" data-toggle="modal" data-target="#addClient">
                    <!-- Add client icon -->
                    <i class='bx bx-user-plus' title="Add Client"></i>
                </button>
                <!-- Search result display area -->
                <div class="result"></div>
            </div>
        </div>

        <!-- Modal for adding a new client -->
        <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- Modal title -->
                            <h3 class="modal-title" id="exampleModalLabel">Add Client</h3>
                            <!-- Close button -->
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
                            <!-- Close button -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- Submit button for adding a new client -->
                            <button formaction="./function/add-client.php" type="submit" name="submit-client" value="true" class="btn btn-info">Add Client</button>
                        </div>
                    </div>
                </form>
                <!-- Display success or error messages based on session variable -->
                <?php
                    if ($addMessage == 'false') {
                        ?>
                        <script type="text/javascript">tata.error('ERROR', 'Invalid Input!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } else if ($addMessage == 'true') {
                        ?>
                        <script type="text/javascript">tata.success('SUCCESS', 'Client Added!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } 
                ?>
            </div>
        </div>

        <!-- Dashboard section -->
        <div class="dashboard">
            <!-- Dashboard content for displaying various statistics -->
            <div class="dash-content">
                <div class="dash-icon">
                    <!-- User pin icon -->
                    <i class='bx bx-user-pin'></i>
                </div>
                <div class="dash-text">
                    <!-- Display the number of clients -->
                    <p>Clients</p>
                    <p>
                        <b>
                            <?php 
                                // Query to get the total number of clients
                                $sqlClient = "SELECT count('name') FROM client";
                                $totalClient = mysqli_query($conn, $sqlClient);
                                $row_client = mysqli_fetch_array($totalClient);
                                echo "$row_client[0]";
                            ?>
                        </b>
                    </p>
                </div>
            </div>
            <div class="dash-content">
                <div class="dash-icon">
                    <!-- User check icon -->
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="dash-text">
                    <!-- Display the number of clients with no balance -->
                    <p>No Balance</p>
                    <p>
                        <b>
                            <?php 
                                // Query to get the total number of clients with paid status
                                $sqlPaid = "SELECT count(*) FROM payment_status WHERE status='paid'";
                                $totalPaid = mysqli_query($conn, $sqlPaid);
                                $row_Paid = mysqli_fetch_array($totalPaid);
                                echo "$row_Paid[0]";
                            ?>
                        </b>
                        <!-- Link to show clients with no balance -->
                        [<a href="nobalance.php">Show</a>]
                    </p>
                </div>
            </div>
            <div class="dash-content">
                <div class="dash-icon">
                    <!-- User x icon -->
                    <i class='bx bx-user-x'></i>
                </div>
                <div class="dash-text">
                    <!-- Display the number of unpaid clients -->
                    <p>Unpaid</p>
                    <p>
                        <b>
                            <?php 
                                // Query to get the total number of clients with unpaid status
                                $sqlUnpaid = "SELECT count(*) FROM payment_status WHERE status='unpaid'";
                                $totalUnpaid = mysqli_query($conn, $sqlUnpaid);
                                $row_Unpaid = mysqli_fetch_array($totalUnpaid);
                                echo "$row_Unpaid[0]";
                            ?>
                        </b>
                        <!-- Link to show unpaid clients -->
                        [<a href="unpaid.php">Show</a>]
                    </p>
                </div>
            </div>
            <div class="dash-content">
                <div class="dash-icon">
                    <!-- Cube alt icon -->
                    <i class='bx bx-cube-alt'></i>
                </div>
                <div class="dash-text">
                    <!-- Display the cubic price -->
                    <p>Cubic Price</p>
                    <p>
                        <b>
                            <?php 
                                // Query to get the cubic price
                                $cubic_price = mysqli_query($conn, "SELECT * FROM cu_price");
                                if (mysqli_num_rows($cubic_price) > 0) {
                                    while ($row = mysqli_fetch_assoc($cubic_price)) {
                                        extract($row);
                                        echo "₱ " . $cu_price;
                                    }
                                }
                            ?>
                        </b>
                    </p>
                </div>
            </div>
        </div>

        <!-- Analytic container section -->
        <div class="analystic-container">
            <!-- Line graph section -->
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <!-- Line chart icon -->
                        <i class='bx bx-line-chart'></i>
                    </div>
                    <div class="analys-text">
                        <!-- Line graph heading -->
                        Line Graph
                    </div>
                </div>
                <div class="line-graph">
                    <!-- Canvas for rendering the line graph -->
                    <canvas id="myChart" ></canvas>
                </div>
            </div>
            <!-- Statement summary section -->
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <!-- Money icon -->
                        <i class='bx bx-money'></i>
                    </div>
                    <div class="analys-text">
                        <!-- Statement summary heading -->
                        Statement Summary
                    </div>
                </div>
                <div class="dash-amount">
                    <!-- Table to display monthly cubic consumption and amount -->
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <!-- Table header for month, cubic consume, and amount -->
                                <th scope="col">Month</th>
                                <th scope="col">Cubic Consume</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="overflow-y: auto;">
                            <?php
                                // Array of months for iteration
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

                                // Loop through each month to display cubic consumption and amount
                                foreach ($listMonth as $index => $value) {
                                    // Query to get the sum of cubic consumption for the current month
                                    $cuTotal = mysqli_query($conn, 'SELECT SUM(' . $listMonth[$index] . ') AS cubic_sum FROM cubic_consume'); 
                                    $row_cubic = mysqli_fetch_assoc($cuTotal); 
                                    $cubic_sum = $row_cubic['cubic_sum'];
                            ?>
                                <!-- Table row for each month -->
                                <tr>
                                    <!-- Display the month -->
                                    <th scope="row"><?php echo ucwords($listMonth[$index]); ?></th>
                                    <!-- Display the cubic consumption for the month -->
                                    <td>
                                        <?php
                                            // Display cubic consumption or '--' if it is zero
                                            if ($cubic_sum != 0) {
                                                echo $cubic_sum;
                                            } else {
                                                echo "--";
                                            }
                                        ?>
                                    </td>
                                    <!-- Display the amount based on cubic consumption and price -->
                                    <td>
                                        <?php 
                                            // Calculate the total amount for the month
                                            $collectMonthTotal = $cubic_sum * $cu_price; 
                                            // Display the amount or '--' if it is zero
                                            if ($collectMonthTotal != 0) {
                                                echo "₱ " . $collectMonthTotal;
                                            } else {
                                                echo "--";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php 
                            }
                            ?>
                            <!-- Table row for total cubic consumption and amount for the year -->
                            <tr style="border-top: 2px solid gray;">
                                <?php 
                                    // Query to get the sum of cubic consumption for the entire year
                                    $cuTotalYear = mysqli_query($conn, 'SELECT SUM(cubic_sum) AS year_sum FROM cubic_consume'); 
                                    $rowYearCubic = mysqli_fetch_assoc($cuTotalYear); 
                                    $yearCubicSum = $rowYearCubic['year_sum'];
                                ?>
                                <!-- Display 'TOTAL' for the row -->
                                <th scope="row">TOTAL </th>
                                <!-- Display the total cubic consumption for the year -->
                                <td><?php echo $yearCubicSum; ?></td>
                                <!-- Display the total amount for the year based on cubic consumption and price -->
                                <td>₱ <?php echo $yearCubicSum * $cu_price; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include JavaScript libraries for chart rendering and other functionalities -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include custom JavaScript file -->
    <script src="js/script.js"></script>

    <!-- JavaScript code for rendering the line graph using Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get the canvas element for rendering the line graph
            const ctx = document.getElementById("myChart").getContext("2d");

            // Create a new Chart instance for the line graph
            new Chart(ctx, {
                type: "line",
                data: {
                    // Set labels for X-axis (months)
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                        {
                            // Set label for the dataset
                            label: "Monthly Record",
                            // Set data points for Y-axis (cubic consumption)
                            data: <?php echo json_encode(array_values($data)); ?>,
                            // Set border width for the line
                            borderWidth: 1,
                            // Set fill to false for line graph
                            fill: false,
                        },
                    ],
                },
                options: {
                    // Configure scales for the line graph
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
    // Unset the session variable for add-message to prevent displaying messages on subsequent page loads
    unset($_SESSION["add-message"]);
?>
