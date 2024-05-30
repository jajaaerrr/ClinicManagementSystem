<?php
session_start();
include 'dbconn.php'; // Include database connection file

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Prepare statement based on the role
if ($role === 'staff') {
    $stmt = $pdo->prepare("SELECT * FROM login WHERE userID = ? AND userPassword = ? AND userType IN ('admin', 'doctor')");
} else {
    $stmt = $pdo->prepare("SELECT * FROM login WHERE userID = ? AND userPassword = ? AND userType = 'patient'");
}
$stmt->execute([$username, $password]);

// Check if user exists
if ($stmt->rowCount() == 1) {
    // User exists, set session variable
    $_SESSION['loggedin'] = true;

    if ($role === 'staff') {
        // Redirect based on userID prefix
        if (strpos($username, 'ADM') === 0) {
            header("Location: homePageAdmin.html"); // Redirect to admin dashboard
        } elseif (strpos($username, 'D') === 0) {
            header("Location: homePageStaff.html"); // Redirect to staff dashboard
        } else {
            // Invalid staff ID, redirect back with error
            header("Location: mainPageForm.html?error=1");
        }
    } else {
        // Patient role, redirect to patient dashboard
        header("Location: patientDashboard.html");
    }
} else {
    // User does not exist, redirect to login page with error message
    header("Location: mainPageForm.html?error=1");
}
exit();
?>
