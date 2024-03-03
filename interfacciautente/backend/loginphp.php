<?php

include_once '../common/session.php'; // session start
include '../common/connection.php'; // Include la connessione al database

// Pulisci e ottieni i dati del form
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Esegui la query per ottenere la password criptata dall'utente specificato
$query = "SELECT password FROM utenti WHERE email = '$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) { // verifica se pssw inserita coincide con quella memorizzata nel db
        $_SESSION['email'] = $email; // inizializza sessione con email utente
        echo "success";
    } else {
        echo "failure";
                
    }
} else {
    echo "failure";
}
?>