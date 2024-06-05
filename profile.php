<?php
session_start();
if (!isset($_SESSION['userID']) ||!isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

function getPatientInfo($pdo, $patientID) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE patientID =?");
        $stmt->bindParam(1, $patientID, PDO::PARAM_STR);
        $stmt->execute();
        $patient = $stmt->fetch();
        if ($patient) {
            return $patient;
        } else {
            echo "Patient with ID $patientID not found in the database!\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n";
        return false;
    }
}

function getDoctorInfo($pdo, $doctorID) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM doctor WHERE doctorID =?");
        $stmt->bindParam(1, $doctorID, PDO::PARAM_STR);
        $stmt->execute();
        $doctor = $stmt->fetch();
        if ($doctor) {
            return $doctor;
        } else {
            echo "Doctor with ID $doctorID not found in the database!\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n";
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
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Patient Profile</title>
                <style>
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #f2f2f2;
                        color: #262626;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                  .profile-card {
                        background-color: #6c63ff;
                        color: #fff;
                        border-radius: 10px;
                        padding: 20px;
                        width: 400px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                  .profile-card img {
                        width: 150px;
                        height: 150px;
                        border-radius: 50%;
                        margin-bottom: 20px;
                    }
                  .profile-card h2 {
                        margin-bottom: 20px;
                        font-size: 24px;
                        font-weight: 600;
                    }
                  .profile-card p {
                        margin: 5px 0;
                        font-size: 16px;
                    }
                  .edit-button {
                        background-color: #fff;
                        color: #6c63ff;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 20px;
                        text-decoration: none;
                        display: inline-block;
                    }
                  .edit-button:hover {
                        background-color: #f0f0f0;
                    }
                  .back-button {
                        background-color: #fff;
                        color: #6c63ff;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 20px;
                        text-decoration: none;
                        display: inline-block;
                    }
                  .back-button:hover {
                        background-color: #f0f0f0;
                    }
                </style>
            </head>
            <body>
                <div class="profile-card">
                    <img src="https://i.pinimg.com/564x/ad/74/57/ad745792a51ec9e39f90111739406f81.jpg" alt="Patient Profile Picture">
                    <h2>Patient Profile</h2>
                    <p><strong>Patient ID:</strong> <?php echo $patient['patientID'];?></p>
                    <p><strong>Name:</strong> <?php echo $patient['patientName'];?></p>
                    <p><strong>NRIC:</strong> <?php echo $patient['patientNRIC'];?></p>
                    <p><strong>Address:</strong> <?php echo $patient['patientAddress'];?></p>
                    <a href="editProfile.php?userType=patient&userID=<?php echo $userID;?>" class="edit-button">EDIT MY PROFILE</a>
                    <a href="patientDashboard.html?userType=patient&userID=<?php echo $userID;?>"" class="back-button">BACK TO DASHBOARD</a>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Patient not found!";
        }
    } elseif ($userType === 'doctor') {
        $doctor = getDoctorInfo($pdo, $userID);
        if ($doctor) {
           ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Doctor Profile</title>
                <style>
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #f2f2f2;
                        color: #262626;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                 .profile-card {
                        background-color: #6c63ff;
                        color: #fff;
                        border-radius: 10px;
                        padding: 20px;
                        width: 400px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                 .profile-card img {
                        width: 150px;
                        height: 150px;
                        border-radius: 50%;
                        margin-bottom: 20px;
                    }
                 .profile-card h2 {
                        margin-bottom: 20px;
                        font-size: 24px;
                        font-weight: 600;
                    }
                 .profile-card p {
                        margin: 5px 0;
                        font-size: 16px;
                    }
                 .edit-button {
                        background-color: #fff;
                        color: #6c63ff;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 20px;
                        text-decoration: none;
                        display: inline-block;
                    }
                 .edit-button:hover {
                        background-color: #f0f0f0;
                    }
                 .back-button {
                        background-color: #fff;
                        color: #6c63ff;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 20px;
                        text-decoration: none;
                        display: inline-block;
                    }
                 .back-button:hover {
                        background-color: #f0f0f0;
                    }
                </style>
            </head>
            <body>
                <div class="profile-card">
                    <img src="https://media.istockphoto.com/id/1495088043/vector/user-profile-icon-avatar-or-person-icon-profile-picture-portrait-symbol-default-portrait.jpg?s=612x612&w=0&k=20&c=dhV2p1JwmloBTOaGAtaA3AW1KSnjsdMt7-U_3EZElZ0=" alt="Doctor Profile Picture">
                    <h2>Doctor Profile</h2>
                    <p><strong>Doctor ID:</strong> <?php echo $doctor['doctorID'];?></p>
                    <p><strong>Name:</strong> <?php echo $doctor['doctorName'];?></p>
                    <p><strong>NRIC:</strong> <?php echo $doctor['doctorNRIC'];?></p>
                    <p><strong>Speciality:</strong> <?php echo $doctor['doctorSpeciality'];?></p>
                    <p><strong>Availability:</strong> <?php echo $doctor['availability'];?></p>
                    <a href="editProfile.php?userType=doctor&userID=<?php echo $userID;?>" class="edit-button">EDIT MY PROFILE</a>
                    <a href="homePageStaff.html?userType=doctor&userID=<?php echo $userID;?>" class="back-button">BACK TO DASHBOARD</a>
                </div>
            </body>
            </html>
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
