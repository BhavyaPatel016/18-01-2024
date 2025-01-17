<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "project1";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to fetch data from userlogin1 table
//$sql = "SELECT  name, number, email,flat FROM userlogin1";
$sql = "SELECT name, number, email, flat,who FROM userlogin1 ORDER BY flat ASC";  // Sorting by name

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 05px;
        background-color: #eef2f3;
        color: #333;
        display: flex;
    }
    .sidebar {
        width: 250px;
        background: #6793AC;
        color: white;
        height: 100vh;
        padding: 9.5px;
        position: fixed;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 22px;
        margin-top:25px;
    }
    .sidebar a {
        display: block;
        padding: 15px;
        color: white;
        text-decoration: none;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.3s ease; /* Added transition for background and transform */
    }
    .sidebar a:hover {
        background: #5a7a87; /* Change background on hover */
        transform: scale(1.05); /* Slight scale up effect on hover */
    }
    .sidebar a {
        position: relative; /* Make it easier to apply animation */
    }
    .sidebar a::before {
        content: ''; /* Empty content for the pseudo-element */
        position: absolute;
        width: 100%;
        height: 2px; /* Thin line */
        background-color: #ffffff;
        bottom: 0; /* Position the line at the bottom */
        left: 0;
        transform: scaleX(0); /* Initially, the line is not visible */
        transform-origin: bottom right;
        transition: transform 0.3s ease; /* Transition for the line */
    }
    .sidebar a:hover::before {
        transform: scaleX(1); /* On hover, the line becomes visible */
        transform-origin: bottom left;
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        flex-grow: 1;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #6793AC;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
    }
    .header h1 {
        margin: 0;
        font-size: 24px;
    }
    .header .logout {
        background: #e74c3c;
        border: none;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .header .logout:hover {
        background: #c0392b;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>

    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="font-size:20px;">📊 Report Dashboard</h2>
        <a href="#">👤Profile</a>
        <a href="message.php">📩Messages</a>
        <a href="report.php">📊Reports</a>
        <a href="#">🔧Maintenances</a>
        <a href="#"> 🗝️Aminities Booking</a>
        <a href="selectcommitymember.php">👥Create Community</a>
        <a href="loginpage.php">⬅️Logout</a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>📊Report Dashboard</h1>
            <a href="loginpage.php"><button class="logout">Logout</button></a>
        </div>

        <!-- Table to display the user data -->
        <table>
            <thead>
                <tr>
                    <th>Flat Number</th>
                    <th>Owner Name</th>
                    <th>Phone Number</th>
                    <th>Owner/Rental</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any records
                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['flat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['who']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>