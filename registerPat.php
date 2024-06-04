<?php
include 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientNRIC = $_POST['patientNRIC'];
    $patientName = $_POST['fullName']; 
    $patientPhoneNo = $_POST['phoneNumber']; 
    $patientAddress = $_POST['address'];
    $userPassword = $_POST['password'];

    // Hash the password
    //$hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    try {
        // Begin a transaction
        $conn->beginTransaction();

        // Get the maximum patientID and increment it
        $sql = "SELECT MAX(CAST(SUBSTRING(patientID, 2) AS UNSIGNED)) AS maxID FROM patient";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxID = $result? $result['maxID'] : 0;
        $newPatientID = 'P'. str_pad($maxID + 1, 4, '0', STR_PAD_LEFT);

        // Insert into patient table
        $sql = "INSERT INTO patient (patientID, patientNRIC, patientName, patientPhoneNo, patientAddress, registerDate)  VALUES (?,?,?,?,?, CURDATE())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newPatientID, $patientNRIC, $patientName, $patientPhoneNo, $patientAddress]);

        // Insert into usertype table
        $sql = "INSERT INTO usertype (userTypeID, userType) VALUES (?, 'patient')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newPatientID]);

        // Insert into login table
        $sql = "INSERT INTO login (userID, userPassword, userTypeID) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newPatientID, $userPassword, $newPatientID]);

        // Commit the transaction
        $conn->commit();

        // Display the new patient ID in an alert box and redirect
        echo "<script>
                alert('Your patient ID is: $newPatientID. Use this to login.');
                window.location.href = 'mainPageForm.html';
              </script>";
    } catch (Exception $e) {
        // Rollback the transaction if something went wrong
        $conn->rollBack();
        die("Error: ". $e->getMessage());
    }
}
?>
