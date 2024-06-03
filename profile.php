<?php
session_start(); 
var_dump($_SESSION);
include("dbconn.php");
$pdo = $conn;
echo "User ID: ".$_GET['userID']."<br>";
echo "User Type: ".$_GET['userType']."<br>";

// Function to get patient information based on patientID
function getPatientInfo($pdo, $patientID) {
	try{
		$stmt = $pdo->prepare("SELECT * FROM patient WHERE patientID = :patientID");
		$stmt->bindParam(':patientID', $patientID, PDO::PARAM_STR);
		$stmt->execute();
		$patient = $stmt->fetch();
		
		if($patient){
		return $patient;
		} else{
			return false;
		}
	} catch (PDOException $e) {
		echo "Error: ". $e -> getMessage();
		return false;
	}
}

// Function to get doctor information based on doctorID
function getDoctorInfo($pdo, $doctorID) {
    $stmt = $pdo->prepare("SELECT * FROM doctor WHERE doctorID = :doctorID");
    $stmt->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
    $stmt->execute();
    $doctor = $stmt->fetch();

    return $doctor;
}

if (isset($_GET['userType'])) {
    $userType = $_GET['userType'];
    $userID = $_GET['userID'];

    if ($userType === 'patient') {
        $patient = getPatientInfo($pdo, $userID);
        if ($patient) {
            echo "Patient ID: ". $patient['patientID']. "<br>";
			echo "Patient NRIC: ". $patient['patientNRIC']. "<br>";
            echo "Patient Name: ". $patient['patientName']. "<br>";
			echo "Phone Number: ". $patient['phoneNumber']. "<br>";
            echo "Address: ". $patient['address']. "<br>";
            echo "Register Date: ". $patient['registerDate']. "<br>";
        } else {
            echo "Patient not found!";
        }
    } elseif ($userType === 'doctor') {
        $doctor = getDoctorInfo($pdo, $userID);
        if ($doctor) {
            echo "Doctor ID: ". $doctor['doctorID']. "<br>";
            echo "Doctor Name: ". $doctor['doctorName']. "<br>";
            echo "Doctor NRIC: ". $doctor['doctorNRIC']. "<br>";
            echo "Doctor Speciality: ". $doctor['doctorSpeciality']. "<br>";
            echo "Availability: ". $doctor['availability']. "<br>";
        } else {
            echo "Doctor not found!";
        }
    } else {
        echo "Invalid user type!";
    }
} else {
    echo "User type or ID not provided!";
}

$pdo = null;
?>
