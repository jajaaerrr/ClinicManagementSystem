<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Include your database connection file
    include_once 'db_connect.php';

    // Get the form data
    $doctorName = $_POST['doctorName'];
    $specialization = $_POST['specialization'];

    // Insert the doctor details into the database
    $sql = "INSERT INTO doctors (doctor_name, specialization) VALUES ('$doctorName', '$specialization')";
    if (mysqli_query($conn, $sql)) {
        echo "Doctor registered successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
