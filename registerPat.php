<?php
include 'dbconn.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $patientNRIC = $_POST['patientNRIC'];
    $fullName = $_POST['fullName'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate passwords
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    try {
        // Fetch the last patient ID from the database
        $stmt = $pdo->query("SELECT patientID FROM patient ORDER BY patientID DESC LIMIT 1");
        $row = $stmt->fetch();
        $lastPatientID = $row ? $row['patientID'] : 'P0000';

        // Increment the patient ID
        $numericPart = (int)substr($lastPatientID, 1);
        $newPatientID = 'P' . str_pad($numericPart + 1, 4, '0', STR_PAD_LEFT);

        // Check if newPatientID is already in use (in case of deleted records)
        $stmt = $pdo->prepare("SELECT patientID FROM patient WHERE patientID = ?");
        $stmt->execute([$newPatientID]);
        while ($stmt->fetch()) {
            $numericPart++;
            $newPatientID = 'P' . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
            $stmt->execute([$newPatientID]);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Begin a transaction
        $pdo->beginTransaction();

        // Insert data into the patient table
        $sql = "INSERT INTO patient (patientID, patientNRIC, patientName, patientPhoneNo, patientAddress, registerDate) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID, $patientNRIC, $fullName, $phoneNumber, $address]);

        // Insert data into the usertype table
        $sql = "INSERT INTO usertype (userID, userType) VALUES (?, 'patient')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID]);

        // Insert data into the login table
        $sql = "INSERT INTO login (userID, userPassword, userTypeID) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPatientID, $hashedPassword, $newPatientID]);

        // Commit the transaction
        $pdo->commit();

        // Store the patient ID in localStorage for the alert
        echo "<script>
                localStorage.setItem('patientID', '$newPatientID');
                window.location.href = 'patientRegistration.html';
              </script>";
    } catch (PDOException $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
