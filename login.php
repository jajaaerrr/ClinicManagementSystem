<?php
session_start();
include 'dbconn.php'; // Include database connection file

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare statement
$stmt = $pdo->prepare("SELECT * FROM admin WHERE adminID = ? AND adminPassword = ?");
$stmt->execute([$username, $password]);

// Check if user exists
if ($stmt->rowCount() == 1) {
    // User exists, set session variable and redirect
    $_SESSION['loggedin'] = true;
    header("Location: adminDashboard.php"); // Redirect to admin dashboard
} else {
    // User does not exist, redirect to login page with error message
    header("Location: mainPageForm.html?error=1"); // Redirect with error code
}
?>
