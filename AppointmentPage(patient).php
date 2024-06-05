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

    <h2>appointment</h2>
    <?php
    session_start();
    include("dbconn.php");

    // Check if customerID is set in session
    if (!isset($_SESSION['patientID'])) {
        echo "Please log in to see your appointment history.";
    } else {
        $patientID = $_SESSION['patientID'];

        // Query to fetch orders for the logged-in customer
        $sql = "SELECT * FROM appointment WHERE patientID = '$patientID'";
        $query = mysqli_query($dbconn, $sql) or die ("Error: " . mysqli_error($dbconn));

        // Check if any orders are found
        $row = mysqli_num_rows($query);
        if ($row == 0) {
            echo "No orders found";
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>appointmentID</th>';
            echo '<th>patientID</th>';
            echo '<th>appointmentDate</th>';
            echo '<th>diagnosis</th>';
            echo '</tr>';
            while ($row = mysqli_fetch_array($query)) {
                echo '<tr>';
                echo '<td>' . $row["appointmentIDID"] . '</td>';
                echo '<td>' . $row["patientID"] . '</td>';
                echo '<td>' . $row["appointmentDate"] . '</td>';
                echo '<td>RM' . $row["diagnosis"] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    ?>
</div>
</body>
</html>
