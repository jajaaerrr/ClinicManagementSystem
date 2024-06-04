<?php
session_start(); 
if (!isset($_SESSION['userID']) || !isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}
//var_dump($_SESSION);
include("dbconn.php");
$pdo = $conn;
//echo "User ID: ".$_SESSION['userID']."<br>";
//echo "User Type: ".$_SESSION['userType']."<br>";

// Function to get patient information based on patientID
function getPatientInfo($pdo, $patientID) {
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE patientID =?");
        $stmt->bindParam(1, $patientID, PDO::PARAM_STR);
        $stmt->execute();
        $patient = $stmt->fetch();
        
        if($patient){
			$patient['userType'] = 'patient';
            return $patient;
        } else {
            echo "Patient with ID $patientID not found in the database!\n"; // Debugging statement
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage()."\n"; // Debugging statement
        return false;
    }
}

// Function to get doctor information based on doctorID
function getDoctorInfo($pdo, $doctorID) {
	try{
		$stmt = $pdo->prepare("SELECT * FROM doctor WHERE doctorID =?");
		$stmt->bindParam(1, $doctorID, PDO::PARAM_STR);
		$stmt->execute();
		$doctor = $stmt->fetch();
		if($doctor){
			$doctor['userType'] = 'doctor';
			return $doctor;
		} else {
			echo "Doctor with ID $doctorID not found in the database!\n"; // Debugging statement
			return false;
		}
	}catch (PDOException $e) {
        echo "Error: ". $e->getMessage()."\n"; // Debugging statement
        return false;
	}
	
}

if (isset($_SESSION['userType'])) {
    $userType = $_SESSION['userType'];
    $userID = $_SESSION['userID'];

    if ($userType === 'patient') {
    $patient = getPatientInfo($pdo, $userID);
    if ($patient) {
       ?>
        <div class="profile-card">
            <h2>Patient Profile</h2>
            <p><strong>Patient ID:</strong> <?php echo $patient['patientID'];?></p>
            <p><strong>Patient NRIC:</strong> <?php echo $patient['patientNRIC'];?></p>
            <p><strong>Patient Name:</strong> <?php echo $patient['patientName'];?></p>
            <p><strong>Phone Number:</strong> <?php echo $patient['patientPhoneNo'];?></p>
            <p><strong>Address:</strong> <?php echo $patient['patientAddress'];?></p>
            <p><strong>Register Date:</strong> <?php echo $patient['registerDate'];?></p>
        </div>
        <?php
    } else {
        echo "Patient not found!"; // This message is only shown if the function returns false
    }
    } elseif ($userType === 'doctor') {
        $doctor = getDoctorInfo($pdo, $userID);
        if ($doctor) {
           ?>
            <div class="profile-card">
                <h2>Doctor Profile</h2>
                <p><strong>Doctor ID:</strong> <?php echo $doctor['doctorID'];?></p>
                <p><strong>Doctor Name:</strong> <?php echo $doctor['doctorName'];?></p>
                <p><strong>Doctor NRIC:</strong> <?php echo $doctor['doctorNRIC'];?></p>
                <p><strong>Doctor Speciality:</strong> <?php echo $doctor['doctorSpeciality'];?></p>
                <p><strong>Availability:</strong> <?php echo $doctor['availability'];?></p>
            </div>
            <?php
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
