<?php
// Email notification function Treasurer
function sendEmailNotification($name, $email, $number) {
    // Use PHPMailer to send email
    require 'PHPMailer/PHPMailer.php';  // Correct the path based on your project folder
    require 'PHPMailer/Exception.php';  // Correct the path based on your project folder
    require 'PHPMailer/SMTP.php';       // Correct the path based on your project folder

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                         // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
        $mail->Username   = 'ressiment@gmail.com';                   // SMTP username (replace with your own)
        $mail->Password   = 'llyn fmwo nkzj kzpk';                    // SMTP password (replace with your own)
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;  // Enable implicit TLS encryption
        $mail->Port       = 465;                                      // TCP port to connect to

        // Recipients
        $mail->setFrom($email,$name);  // Sender's email
        $mail->addAddress("bhavyapatel1216@gmail.com", 'Treasurer'); // Add a recipient (Admin)

        // Content
        $mail->isHTML(true);                                          // Set email format to HTML
        $mail->Subject = 'Maintenance Payment Status Update';
        $mail->Body    = "Payment status has been updated for a maintenance record:<br>Name: $name<br>Email: $email<br>Phone Number: $number";

        // Send email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details using email passed in the URL query string
$email = isset($_GET['email']) ? $_GET['email'] : ''; // Fetch email from query parameter (or default to empty string)

// Retrieve user details based on the email
$query = "SELECT * FROM userlogin1 WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found with the provided email!";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flat_no = $user['flat'];
    $name = $user['name'];
    $phone = $user['number'];
    $email = $user['email'];
    $payment_duration = $_POST['payment-duration'];
    $s_date = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $payment_method = $_POST['payment-method'];

    // Calculate total amount based on payment duration
    $monthly_amount = 1500; // Amount per month
    $total_amount = 0;

    if (isset($payment_duration)) {
        if (in_array('1-month', $payment_duration)) {
            $total_amount += $monthly_amount * 1;
        }
        if (in_array('3-months', $payment_duration)) {
            $total_amount += $monthly_amount * 3;
        }
        if (in_array('6-months', $payment_duration)) {
            $total_amount += $monthly_amount * 6;
        }
        if (in_array('1-year', $payment_duration)) {
            $total_amount += $monthly_amount * 12;
        }
    }

    // Add additional details based on payment method
    if ($payment_method === "cash") {
        $payment_detail = "Paid in cash.";
    } elseif ($payment_method === "online") {
        if (isset($_POST['online-method'])) {
            $online_method = $_POST['online-method'];
            if ($online_method === "card") {
                $payment_detail = "Card payment, Card Name: " . $_POST['card-name'];
            } elseif ($online_method === "upi") {
                $payment_detail = "UPI Payment, UPI ID: " . $_POST['upi-id'];
            }
        }
    }

    // Insert into maintenance table
    $insert_query = "INSERT INTO maintenance (flat_no, name, phone, email, payment_duration, start_date, amount, end_date, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    // Create a string for the payment duration
    $payment_duration_str = implode(', ', $payment_duration);

    // Bind the parameters
    $stmt->bind_param(
        "sssssssss",
        $flat_no,
        $name,
        $phone,
        $email,
        $payment_duration_str, // Use the new variable here
        $s_date,
        $total_amount,
        $e_date,
        $payment_method
    );

    if ($stmt->execute()) {
        sendEmailNotification($name, $email, $phone); // Send the email notification
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Payment</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
              body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #333;
            display: flex;
            justify-content: space-between;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 30px 20px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease;
        }

        .sidebar-header h2 {
            margin: 0;
            font-weight: bold;
            text-align: center;
            font-size: 25px;
            color: #ffffff;
        }

        .sidebar-menu {
            list-style-type: none;
            margin-top: 30px;
        }

        .sidebar-menu li {
            margin: 15px 0;
        }

        .sidebar-menu li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: block;
            padding: 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar-menu li a:hover {
            background-color: #2980B9;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            flex-grow: 1;
            background-color: #ffffff;
            height: 100vh;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #a3c9f1;
            color: white;
            padding: 10px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: -15px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .logout {
            background: #e74c3c;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background: #c0392b;
        }

        .maintenance-info,
        .payment-options {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .payment-method select,
        .payment-method input {
            width: 25%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            width: 25%;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .form-group-horizontal {
            display: flex;
            gap: 20px;
        }

        .form-item {
            flex: 1;
            min-width: 200px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .checkbox-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .checkbox-group input[type="checkbox"] {
            appearance: none;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 4px;
            width: 20px;
            height: 20px;
            transition: background-color 0.3s ease;
        }

        .checkbox-group input[type="checkbox"]:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .checkbox-group label {
            font-size: 16px;
            font-weight: normal;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"]:checked + label {
            color: #4CAF50;
        }
    input[type="date"] {
        width: 150px; /* Reduce the width of the date input */
        padding: 8px; /* Adjust padding to fit the smaller size */
        font-size: 14px; /* Reduce font size */
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>💸 Payment Panel</h2>
        <ul class="sidebar-menu">
            <li><a href="u_profile.php?email=<?php echo urlencode($user['email']); ?>">👤 Profile</a></li>
            <li><a href="#">📝 Onboarding</a></li>
            <li><a href="#">📈 Dashboard</a></li>
            <li><a href="maintenance.php?email=<?php echo urlencode($user['email']); ?>">💸 Payment</a></li>
            <li><a href="#">💬 Help</a></li>
            <li><a href="loginpage.php">⬅️ Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>💸 Maintenance Payment</h1>
            <a href="loginpage.php"><button class="logout">Logout</button></a>
        </div>
        <form method="POST">
            <div class="maintenance-info">
                <h2>Maintenance Fee</h2>
                <p>The maintenance fee for the flat is set at <strong>₹1500</strong> per month.</p>
                <p id="total-amount" class="font-bold text-lg">Total Amount: ₹0</p>
            </div>
            <div class="payment-options">
                <div class="form-group-horizontal">
                    <div class="form-item">
                        <label for="flat_no">Flat Number:</label>
                        <input type="text" id="flat_no" name="flat_no" value="<?php echo htmlspecialchars($user['flat']); ?>" disabled>
                    </div>

                    <div class="form-item">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>
                    </div>

                    <div class="form-item">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['number']); ?>" disabled>
                    </div>

                    <div class="form-item">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment-duration">Choose Payment Duration:</label><br>
                    <div class="checkbox-group">
                        <input type="checkbox" id="one-month" name="payment-duration[]" value="1-month" class="mr-2" onclick="selectSingleCheckbox(this)">
                        <label for="one-month"><b>1 Month</b></label>
                        <input type="checkbox" id="three-months" name="payment-duration[]" value="3-months" class="mr-2" onclick="selectSingleCheckbox(this)">
                        <label for="three-months"><b>3 Months</b></label>
                        <input type="checkbox" id="six-months" name="payment-duration[]" value="6-months" class="mr-2" onclick="selectSingleCheckbox(this)">
                        <label for="six-months"><b>6 Months</b></label>
                        <input type="checkbox" id="one-year" name="payment-duration[]" value="1-year" class="mr-2" onclick="selectSingleCheckbox(this)">
                        <label for="one-year"><b>1 Year</b></label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="s_date">Start Date:</label>
                    <input type="date" name="s_date" required>
                    <label for="e_date">End Date:</label>
                    <input type="date" name="e_date" required>
                </div>

                <div class="payment-method">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" onchange="updatePaymentMethod()" required>
                        <option value="cash">Cash</option>
                        <option value="online">Online Payment</option>
                    </select>

                    <div id="online-methods" style="display:none;">
                        <label for="online-method">Choose Online Method:</label>
                        <select id="online-method" name="online-method">
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                        </select>

                        <div id="card-info" style="display:none;">
                            <label for="card-name">Card Name:</label>
                            <input type="text" id="card-name" name="card-name">
                        </div>
                        <div id="upi-info" style="display:none;">
                            <label for="upi-id">UPI ID:</label>
                            <input type="text" id="upi-id" name="upi-id">
                        </div>
                    </div>
                </div>

                <button type="submit">Submit Payment</button>
            </div>
        </form>
    </div>

    <script>
        // Allow only one checkbox to be selected at a time
        function selectSingleCheckbox(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="payment-duration[]"]');
            checkboxes.forEach(function(item) {
                if (item !== checkbox) {
                    item.checked = false;
                }
            });

            calculateTotal(); // Recalculate total when a checkbox is clicked
        }

        function updatePaymentMethod() {
            var paymentMethod = document.getElementById('payment-method').value;
            if (paymentMethod === 'online') {
                document.getElementById('online-methods').style.display = 'block';
            } else {
                document.getElementById('online-methods').style.display = 'none';
            }
        }

        function calculateTotal() {
            var checkboxes = document.querySelectorAll('input[name="payment-duration[]"]:checked');
            var totalAmount = 0;
            checkboxes.forEach(function(checkbox) {
                switch (checkbox.value) {
                    case '1-month':
                        totalAmount += 1500;
                        break;
                    case '3-months':
                        totalAmount += 4500;
                        break;
                    case '6-months':
                        totalAmount += 9000;
                        break;
                    case '1-year':
                        totalAmount += 18000;
                        break;
                }
            });
            document.getElementById('total-amount').textContent = 'Total Amount: ₹' + totalAmount;
        }
    </script>
</body>
</html>
