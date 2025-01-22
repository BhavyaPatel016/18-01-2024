<?php

$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "project1"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch already booked flats based on the floor
if (isset($_GET['floor'])) {
    $floor = $_GET['floor'];
    // Query to get the booked flats for the selected floor
    $query = "SELECT flat FROM building WHERE floor = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $floor);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookedFlats = [];
    while ($row = $result->fetch_assoc()) {
        $bookedFlats[] = $row['flat'];
    }
    
    echo json_encode($bookedFlats);
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $floor = isset($_POST['floor']) ? $_POST['floor'] : '';
    $flat = isset($_POST['flat']) ? $_POST['flat'] : '';
    $who = isset($_POST['who']) ? $_POST['who'] : '';
    $purchaseDate = isset($_POST['purdate']) ? $_POST['purdate'] : ''; // Capture purchase date
    $rentalDate = isset($_POST['rentdate']) ? $_POST['rentdate'] : null; // Capture rental date

    // Insert building data into the 'building' table
    $insertBuildingQuery = "INSERT INTO building (floor, flat, who, purchaseDate, rentalDate) 
                            VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($insertBuildingQuery)) {
        // Bind parameters to the query
        $stmt->bind_param("sssss", $floor, $flat, $who, $purchaseDate, $rentalDate);

        if ($stmt->execute()) {
            echo "Building data inserted successfully!<br>";
            // Redirect to homepage or any other page after successful insertion
            header("Location: u_registerpage.php");
            exit;
        } else {
            echo "Error executing building query: " . $stmt->error . "<br>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        echo "Error preparing building query: " . $conn->error . "<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Registration</title>
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
            padding-left: 150px;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 30px;
            padding: 55px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }
        h1 {
            margin-bottom: 20px;
            color: green;
            text-align: center;
        }
        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }
        select, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .date {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        select:focus, button:focus {
            outline: none;
        }
        select {
            transition: border-color 0.3s;
        }
        select:focus {
            border-color: #0099CC;
        }
        button {
            width: 200px;
            padding: 12px;
            margin-top: 5px;
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
            background-color: #45a049;
            transform: scale(1.05);
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .back-button {
            background-color: green; 
            width: 30%;
        }
        .back-button:hover {
            background-color: #45a049;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<!-- Form for building registration -->
<div class="container">
    <form action="registerpage.php" method="POST" onsubmit="return handleSubmit(event)">
        <h1>REGISTER</h1>

        <!-- Who are you? -->
        <label for="who">Who are you?</label><br>
        <input type="radio" id="owner" name="who" value="Owner" onchange="toggleDateFields()" required>
        <label for="owner" style="display: inline-block; margin-right: 20px;">Owner</label>
        <input type="radio" id="rental" name="who" value="Rental" onchange="toggleDateFields()" required>
        <label for="rental" style="display: inline-block;">Rental</label><br><br>

        <!-- Floor Selection -->
        <label for="floor">Floor*</label>
        <select id="floor" name="floor" required onchange="updateFlats()" required>
            <option value="0">Select The Floor</option>
            <option value="1">1st Floor</option>
            <option value="2">2nd Floor</option>
            <option value="3">3rd Floor</option>
            <option value="4">4th Floor</option>
            <option value="5">5th Floor</option>
        </select>

        <!-- Flat Selection -->
        <label for="flat">Flat*</label>
        <select id="flat" name="flat" required>
            <option value="0">Select The Flat</option>
        </select>

        <!-- Purchase and Rental Date Fields -->
        <div id="purchaseDateSection" class="hidden">
            <label for="purdate">Purchase Date:</label>
            <input class="date" type="date" name="purdate" id="purdate" min="2022-01-01" max="" required>
        </div>

        <div id="rentalDateSection" class="hidden">
            <label for="rentdate">Rental Date:</label>
            <input class="date" type="date" name="rentdate" id="rentdate" min="2022-01-01" max="" required>
        </div>

        <!-- Buttons -->
        <div class="button-container">
            <button type="submit">Next</button>  
            <button type="button" class="back-button" onclick="history.back()">Back</button>  
        </div>
    </form>
</div>

<script>
    let selectedOwnerFlat = null; // Store the selected flat for "Owner"

    function updateFlats() {
        const floorSelect = document.getElementById('floor');
        const flatSelect = document.getElementById('flat');
        const selectedFloor = floorSelect.value;
        const ownerRadio = document.getElementById('owner').checked;

        // Clear previous flat options
        flatSelect.innerHTML = '<option value="0">Select The Flat</option>';

        if (selectedFloor === '0') {
            flatSelect.disabled = true; // Disable flat dropdown if no floor is selected
            return; // Don't populate flats if no floor is selected
        }

        flatSelect.disabled = false; // Enable flat dropdown once a floor is selected

        // Make an AJAX request to get the booked flats for the selected floor
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'registerpage.php?floor=' + selectedFloor, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const bookedFlats = JSON.parse(xhr.responseText);
                let flats = [];

                // Define available flats per floor
                if (selectedFloor === '1') {
                    flats = ['101', '102', '103', '104'];
                } else if (selectedFloor === '2') {
                    flats = ['201', '202', '203', '204'];
                } else if (selectedFloor === '3') {
                    flats = ['301', '302', '303', '304'];
                } else if (selectedFloor === '4') {
                    flats = ['401', '402', '403', '404'];
                } else if (selectedFloor === '5') {
                    flats = ['501', '502', '503', '504'];
                }

                // Populate the flat options based on selected floor
                flats.forEach(flat => {
                    const option = document.createElement('option');
                    option.value = flat;
                    option.textContent = flat;

                    // If the user is an owner, disable the flat if it's already booked
                    if (ownerRadio) {
                        if (bookedFlats.includes(flat)) {
                            option.disabled = true;
                            option.textContent = flat + ' (Booked)';
                        }
                    }

                    flatSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }

    function toggleDateFields() {
        const ownerRadio = document.getElementById('owner');
        const rentalRadio = document.getElementById('rental');
        const purchaseDateSection = document.getElementById('purchaseDateSection');
        const rentalDateSection = document.getElementById('rentalDateSection');
        const purdate = document.getElementById('purdate');
        const rentdate = document.getElementById('rentdate');
        const floorSelect = document.getElementById('floor');
        const flatSelect = document.getElementById('flat');

        // If "Owner" is selected
        if (ownerRadio.checked) {
            purchaseDateSection.classList.remove('hidden');
            purdate.disabled = false;
            rentalDateSection.classList.add('hidden');
            rentdate.disabled = true;

            // Clear the floor and flat selection when switching to "Owner" mode
            floorSelect.value = '0';
            flatSelect.innerHTML = '<option value="0">Select The Flat</option>';
        } 
        // If "Rental" is selected
        else if (rentalRadio.checked) {
            rentalDateSection.classList.remove('hidden');
            rentdate.disabled = false;
            purchaseDateSection.classList.add('hidden');
            purdate.disabled = true;

            // Clear the floor and flat selection when switching to "Rental" mode
            floorSelect.value = '0';
            flatSelect.innerHTML = '<option value="0">Select The Flat</option>';
        } else {
            purchaseDateSection.classList.add('hidden');
            rentalDateSection.classList.add('hidden');
            purdate.disabled = true;
            rentdate.disabled = true;
        }
    }

    // Set today's date as the max date for the date fields
    window.onload = function() {
        setDateRange();
    }

    function setDateRange() {
        const today = new Date().toISOString().split("T")[0];
        document.getElementById("purdate").max = today;
        document.getElementById("rentdate").max = today;
    }

    function handleSubmit(event) {
        // Prevent form submission
        event.preventDefault();

        // Get form elements
        const floorSelect = document.getElementById('floor');
        const flatSelect = document.getElementById('flat');
        const ownerRadio = document.getElementById('owner');
        const rentalRadio = document.getElementById('rental');
        const purchaseDate = document.getElementById('purdate');
        const rentalDate = document.getElementById('rentdate');

        // Check if floor is selected
        if (floorSelect.value === '0') {
            alert("Please select a floor.");
            return false;
        }

        // Check if flat is selected
        if (flatSelect.value === '0') {
            alert("Please select a flat.");
            return false;
        }

        // Check if either owner or rental is selected
        if (!ownerRadio.checked && !rentalRadio.checked) {
            alert("Please select who you are (Owner or Rental).");
            return false;
        }

        // Check if purchase date is required and filled
        if (ownerRadio.checked && purchaseDate.value === '') {
            alert("Please select a purchase date.");
            return false;
        }

        // Check if rental date is required and filled
        if (rentalRadio.checked && rentalDate.value === '') {
            alert("Please select a rental date.");
            return false;
        }

        // If all validations pass, submit the form
        event.target.submit();
    }
</script>

</body>
</html>
