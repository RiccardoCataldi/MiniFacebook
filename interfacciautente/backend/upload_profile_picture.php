<?php
include '../common/session.php';
include '../common/connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDirectory = '../img/profile/';
    $profilePicturePath = $uploadDirectory . basename($_FILES['profilePicture']['name']);
    $uploadSuccess = move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profilePicturePath);

    if ($uploadSuccess) {
        // Update the database with the new profile picture path
        $sql = "UPDATE utenti SET profilePicturePath = '$profilePicturePath' WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // Redirect to the profile page
            header('Location: ../frontend/profile.php');
        } else {
            // Handle database error
            echo "Failed to update the database.";
        }


        
    } else {
        // Handle file upload failure
        echo "Failed to upload the file.";
    }
} else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>