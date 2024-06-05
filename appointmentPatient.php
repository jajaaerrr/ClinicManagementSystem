<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<title>Welcome To MedicHub</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
    }

    body {
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }

    .header {
        background: linear-gradient(-135deg, #c850c0, #4158d0);
        color: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header h1 {
        font-size: 55px;
        display: inline;
    }

    .menu {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .menu-button {
        background-color: #fae6d2;
        color: #b69b7c;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
    }

    .menu-button:hover {
        background-color: #b69b7c;
        color: #fae6d2;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #b69b7c;
    }

    .order p {
        margin: 0;
        color: #b69b7c;
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Welcome To MedicHub</h1>
    </div>

    <h2>Appointment</h2>
    <?php
    session_start();
    echo "Session ID: " . session_id() . "<br>"; // Debugging session ID
    include("dbconn.php");

    // Debugging: Check if session variable is set
    if (!isset($_SESSION['userID'])) {
        echo "Session userID is not set.";
    } else {
        echo "Session userID is set to: " . $_SESSION['userID'];
        $patientID = $_SESSION['userID'];

        // Query to fetch appointments for the logged-in patient
        $sql = "SELECT * FROM appointment WHERE patientID = '$patientID'";
        $query = mysqli_query($dbconn, $sql) or die("Error: " . mysqli_error($dbconn));

        // Check if any appointments are found
        $row = mysqli_num_rows($query);
        if ($row == 0) {
            echo "No appointments found";
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>Appointment ID</th>';
            echo '<th>Appointment Date</th>';
      echo '<th>Time Slot</th>';
            echo '<th>Diagnosis</th>';
      echo '<th>Doctor Name</th>';
            echo '<th>Medicine Name</th>';
      echo '<th>Appointment Status</th>';
            echo '<th>mcID</th>';
            echo '</tr>';
            while ($row = mysqli_fetch_array($query)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row["appointmentID"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["appointmentDate"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["timeSlot"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["diagnosis"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["doctorName"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["medName"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["appointmentStatus"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["mcSerialNumber"]) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    ?>
</div>
</body>
</html>