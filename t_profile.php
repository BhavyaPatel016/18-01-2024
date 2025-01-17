<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #eef2f3, #e3f2fd); /* Light gradient background */
            color: #333;
            display: flex;
            height: 100vh;
            overflow-x: hidden;
            transition: background 0.3s ease;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 270px;
            background: #2C3E50; /* Dark shade for sidebar */
            color: white;
            height: 100%;
            padding: 40px 30px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            border-radius: 0 20px 20px 0; /* Rounded corners for the sidebar */
            z-index: 10;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 60px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #FFDC00; /* Bright color for the title */
            text-transform: uppercase;
        }

        /* Sidebar Links */
        .sidebar a {
            display: block;
            padding: 15px 20px;
            margin: 10px 0;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: #FFDC00;
            border-radius: 5px;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar a:hover {
            background: #34495E; 
            transform: translateX(10px);
        }

        .sidebar a:hover::before {
            opacity: 1;
        }

        .sidebar a.active {
            background: #2980B9; /* Highlight active link */
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 270px;
            padding: 30px;
            flex-grow: 1;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            transition: margin-left 0.3s ease;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2C3E50;
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header .logout {
            background: #E74C3C;
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
        }

        .header .logout:hover {
            background: #C0392B;
            transform: scale(1.1);
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }

            .sidebar h2 {
                font-size: 22px;
            }

            .sidebar a {
                font-size: 16px;
                padding: 12px 15px;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .header h1 {
                font-size: 28px;
            }

            .header .logout {
                font-size: 14px;
                padding: 10px 15px;
            }
        }

        /* Smooth Transitions */
        * {
            box-sizing: border-box;
        }

        a:focus, button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.5);
        }

        .sidebar a:hover, .header .logout:hover {
            background-color: #7D9EB4;
        }

        .sidebar a.active {
            background-color: #8FA9C4; /* Adjusted for active state */
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .main-content {
            animation: fadeIn 0.5s ease-out;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>💰 Treasurer Dashboard</h2>
        <a href="#" class="active">👤 Profile</a>
        <a href="check.php">📩 Check Payment</a>
        <a href="m_report.php">📊 Maintenance Reports</a>
        <a href="loginpage.php">⬅️ Logout</a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Profile Page</h1>
            <a href="loginpage.php"><button class="logout">Logout</button></a>
        </div>
        <!-- Content goes here -->
    </div>
</body>
</html>
