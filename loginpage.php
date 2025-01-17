<?php
$admin_email = 'admin123@gmail.com';
$admin_password = 'password123';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    if ($email === $admin_email && $password === $admin_password) {
        // Set session variable and redirect to admin page
        $_SESSION['admin_logged_in'] = true;
        header('Location: adminpage.php');
        exit();
    } else {
        
        $error_message = 'Invalid email or password.';
    }
}
?>

<?php
session_start(); // Start the session to manage user login state

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "project1"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ''; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to check credentials
    $stmt = $conn->prepare("SELECT * FROM userlogin1 WHERE email = ? AND password = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        // User found, set session variable and redirect
        $_SESSION['user_logged_in'] = true;
        header("Location: u_profile.php?email=" . urlencode($email));
        exit();
    } else {
        $error_message = 'Invalid email or password.';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: url('./image1.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: flex-start; 
            align-items: center;
            height: 100vh;
            padding-left: 200px; 
            color: #333;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 55px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 299px;
            text-align: left; 
        }
        h1 {
            margin-bottom: 20px;
            color: #4CAF50;
            text-align: center; 
        }
        input {
            width: calc(100% - 24px);
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #4CAF50;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px; 
            border: none;
            border-radius: 30px;
            background-color: green; 
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 15px;
        }
        button:hover {
            background-color: #4CAF50; 
            transform: scale(1.05);
        }
        .error-message {
            color: red; 
            text-align: center; 
            margin-top: 10px;
        }
        .links {
            margin-top: 20px; 
            text-align: center; 
        }
        .links a {
            text-decoration: none;
            color: #4CAF50; 
            margin: 0 10px; 
        }
        .links a:hover {
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign In</h1>
        <form action="loginpage.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <div class="links">
            <a href="#">Change Password</a>
            <span>|</span>
            <a href="registerpage.php">Sign Up</a>
        </div>
    </div>
</body>
</html>