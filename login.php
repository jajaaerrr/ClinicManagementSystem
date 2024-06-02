<?php
session_start();
include 'dbconn.php'; // Include database connection file

// Get username and password from form
$userID = $_POST['userID'];
$password = $_POST['password'];
$role = $_POST['role'];

// Prepare statement based on the role
if ($role === 'staff') {
    $stmt = $pdo->prepare("SELECT * FROM login WHERE userID =? AND userPassword =? AND userType IN ('admin', 'doctor')");
} else {
    $stmt = $pdo->prepare("SELECT * FROM login WHERE userID =? AND userPassword =? AND userType = 'patient'");
}
$stmt->execute([$userID, $password]);

// Check if user exists
if ($stmt->rowCount() == 1) {
    // User exists, set session variable
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $userID;
    $_SESSION['userType'] = $role;

    // Set specific ID variable based on user type
    if ($role === 'staff') {
        if (strpos($userID, 'ADM') === 0) {
            $_SESSION['adminID'] = $userID;
        } elseif (strpos($userID, 'D') === 0) {
            $_SESSION['doctorID'] = $userID;
        } else {
            // Invalid staff ID, redirect back with error
            header("Location: mainPageForm.html?error=1");
            exit();
        }
    } else {
        $_SESSION['patientID'] = $userID;
    }

    // Redirect based on user type
    if ($role === 'staff') {
        if (isset($_SESSION['adminID'])) {
            header("Location: homePageAdmin.html"); // Redirect to admin dashboard
        } elseif (isset($_SESSION['doctorID'])) {
            header("Location: homePageStaff.html"); // Redirect to staff dashboard
        }
    } else {
        header("Location: patientDashboard.html"); // Redirect to patient dashboard
    }
} else {
    // User does not exist, redirect to login page with error message
    header("Location: mainPageForm.html?error=1");
}
exit();
?>
