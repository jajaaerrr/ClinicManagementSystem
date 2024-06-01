<?php
session_start(); // Start the session
include("dbconn.php");

// Function to get patient information based on patientID
function getPatientInfo($patientID, $conn) {
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patientID = ?");
    $stmt->bind_param("s", $patientID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get admin information based on adminID
function getAdminInfo($adminID, $conn) {
    $stmt = $conn->prepare("SELECT * FROM admin WHERE adminID = ?");
    $stmt->bind_param("s", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get doctor information based on doctorID
function getDoctorInfo($doctorID, $conn) {
    $stmt = $conn->prepare("SELECT * FROM doctor WHERE doctorID = ?");
    $stmt->bind_param("s", $doctorID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Check if userType is provided in the URL
if(isset($_GET['userType'])) {
    $userType = $_GET['userType'];
    // Check if connection is established
    if(isset($conn)) {
        if ($userType === 'patient') {
            if(isset($_GET['patientID'])) {
                $patientID = $_GET['patientID'];
                $patient = getPatientInfo($patientID, $conn);
                if ($patient) {
                    echo "Patient ID: " . $patient['patientID'] . "<br>";
                    echo "Patient Name: " . $patient['patientName'] . "<br>";
                    echo "Patient NRIC: " . $patient['patientNRIC'] . "<br>";
                    echo "Patient Phone No: " . $patient['patientPhoneNo'] . "<br>";
                    echo "Patient Address: " . $patient['patientAddress'] . "<br>";
                    echo "Register Date: " . $patient['registerDate'] . "<br>";
                } else {
                    echo "Patient not found!";
                }
            } else {
                echo "Patient ID not provided!";
            }
        } elseif ($userType === 'admin') {
            if(isset($_GET['adminID'])) {
                $adminID = $_GET['adminID'];
                $admin = getAdminInfo($adminID, $conn);
                if ($admin) {
                    echo "Admin ID: " . $admin['adminID'] . "<br>";
                    // Additional admin information can be displayed here
                } else {
                    echo "Admin not found!";
                }
            } else {
                echo "Admin ID not provided!";
            }
        } elseif ($userType === 'doctor') {
            if(isset($_GET['doctorID'])) {
                $doctorID = $_GET['doctorID'];
                $doctor = getDoctorInfo($doctorID, $conn);
                if ($doctor) {
                    echo "Doctor ID: " . $doctor['doctorID'] . "<br>";
                    echo "Doctor Name: " . $doctor['doctorName'] . "<br>";
                    echo "Doctor NRIC: " . $doctor['doctorNRIC'] . "<br>";
                    echo "Doctor Speciality: " . $doctor['doctorSpeciality'] . "<br>";
                    echo "Availability: " . $doctor['availability'] . "<br>";
                } else {
                    echo "Doctor not found!";
                }
            } else {
                echo "Doctor ID not provided!";
            }
        } else {
            echo "Invalid user type!";
        }
    } else {
        echo "Connection not established!";
    }
} else {
    echo "User type not provided!";
}

// Close the database connection if it exists
if(isset($conn)) {
    $conn->close();
}
?>
