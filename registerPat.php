<?php
include 'dbconn.php'; // Ensure this file exists and the path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientNRIC = $_POST['patientNRIC'];
    $patientName = $_POST['fullName']; 
    $patientPhoneNo = $_POST['phoneNumber']; 
    $patientAddress = $_POST['address']; 
    $userPassword = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Get the maximum patientID and increment it
        $sql = "SELECT MAX(CAST(SUBSTRING(patientID, 2) AS UNSIGNED)) AS maxID FROM patient";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxID = $result ? $result['maxID'] : 0;
        $newPatientID = 'P' . str_pad($maxID + 1, 4, '0', STR_PAD_LEFT);

        // Insert into patient table
        $sql = "INSERT INTO patient (patientID, patientNRIC, patientName, patientPhoneNo, patientAddress, registerDate) 
                VALUES (?, ?, ?, ?, ?, CURDATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID, $patientNRIC, $patientName, $patientPhoneNo, $patientAddress]);

        // Insert into usertype table
        $sql = "INSERT INTO usertype (userID, userType) VALUES (?, 'patient')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID]);

        // Insert into login table
        $sql = "INSERT INTO login (userID, userPassword) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID, $hashedPassword]);

        // Commit the transaction
        $pdo->commit();

        // Store the new patient ID in localStorage and redirect
        echo "<script>
                localStorage.setItem('patientID', '$newPatientID');
                window.location.href = 'patientRegistration.html';
              </script>";
    } catch (Exception $e) {
        // Rollback the transaction if something went wrong
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
