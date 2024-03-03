<?php 
include_once "../common/connection.php";
include_once "../common/function.php";
include_once "../common/session.php";

$loggedInUserEmail = $_SESSION['email'];
$friendEmail = isset($_GET['email']) ? urldecode($_GET['email']) : '';

$result = removeFriend($conn,$loggedInUserEmail, $friendEmail);

if ($result) { // se non ci sono errori
    
    header("Location: ../frontend/notAccessibleProfile.php?email=$friendEmail");

}
?>