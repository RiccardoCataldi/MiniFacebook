<?php

include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

$emailComment = $_SESSION['email'];
$emailPost = $_POST['emailPost'];
$dataPubblicazione = $_POST['dataPubblicazione'];
$rating = $_POST['ratingValue'];


if (ratingPost($conn, $emailComment, $emailPost, $dataPubblicazione, $rating)) {
    echo "success";
} else {
    echo "failure";
}
