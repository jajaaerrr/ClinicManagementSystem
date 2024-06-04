<?php
session_start();
include 'dbconn.php';
$pdo = $conn;
// Get username and password from form
$userID = $_POST['userID'];
$password = $_POST['password'];
$role = $_POST['role'];

// Prepare statement based on the role
if ($role === 'staff') {
    $stmt = $pdo->prepare("SELECT * FROM login l INNER JOIN usertype ut ON l.userTypeID = ut.userTypeID WHERE l.userID =? AND l.userPassword =? AND ut.userType IN ('admin', 'doctor')");
} else {
    $stmt = $pdo->prepare("SELECT * FROM login l INNER JOIN usertype ut ON l.userTypeID = ut.userTypeID WHERE l.userID =? AND l.userPassword =? AND ut.userType = 'patient'");
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
            header("Location: homePageAdmin.html?userType=admin&adminID=" . $_SESSION['adminID'] ."&userID=" . $_SESSION['userID']); // Redirect to admin dashboard
        } elseif (isset($_SESSION['doctorID'])) {
            header("Location: homePageStaff.html?userType=doctor&doctorID=" . $_SESSION['doctorID'] ."&userID=" . $_SESSION['userID']); // Redirect to staff dashboard
        }
    } else {
        header("Location: patientDashboard.html?userType=patient&patientID= " . $_SESSION['patientID']."&userID=" . $_SESSION['userID']); // Redirect to patient dashboard
    }
} else {
    // User does not exist, redirect to login page with error message
    ?>
    <script>
        alert("User does not exist");
        window.location.href = "mainPageForm.html?error=1";
    </script>
    <?php
    exit();
}
?>
