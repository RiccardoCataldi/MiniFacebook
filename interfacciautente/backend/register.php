<?php

include_once '../common/session.php'; // session start
include_once '../common/connection.php';

// Ottieni i dati del form e pulisci
$email = $_POST["email"];
$password = $_POST["password"];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // password passata viene cifrata con algoritmo PASSWORD_DEFAULT

$firstName = empty($_POST["firstName"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["firstName"]) . "'";
$lastName = empty($_POST["lastName"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["lastName"]) . "'";
$gender = empty($_POST["gender"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["gender"]) . "'";
$birthCity = empty($_POST["birthCity"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["birthCity"]) . "'";
$birthCountry = empty($_POST["birthCountry"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["birthCountry"]) . "'";
$birthProvince = empty($_POST["birthProvince"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["birthProvince"]) . "'";
$birthDate = empty($_POST["birthDate"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["birthDate"]) . "'";
$residenceCity = empty($_POST["residenceCity"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["residenceCity"]) . "'";
$residenceCountry = empty($_POST["residenceCountry"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["residenceCountry"]) . "'";
$residenceProvince = empty($_POST["residenceProvince"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["residenceProvince"]) . "'";
$hobby = empty($_POST['hobby']) ? "NULL" : $_POST['hobby'];

// Esegui la query per verificare se l'utente esiste già
$checkQuery = "SELECT * FROM Utenti WHERE email = '$email'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) == 0) {
    // Utente non esiste, procedi con la registrazione
    if ($email == 'luca.giussani2@studenti.unimi.it' or $email == 'riccardo.cataldi@studenti.unimi.it') {
// solo noi 2 siamo gli amministratori
        $amministratore = "amministratore";
        $insertQuery = "INSERT INTO Utenti (email, password, amministratore, nome, cognome, sesso, cittàNascita, nazioneNascita, provinciaNascita, dataNascita, cittàResidenza, nazioneResidenza, provinciaResidenza) VALUES ('$email', '$hashedPassword', '$amministratore', $firstName, $lastName, $gender, $birthCity, $birthCountry, $birthProvince, $birthDate, $residenceCity, $residenceCountry, $residenceProvince)";
        echo $insertQuery;
    } else {
// altrimenti viene inserito come utente base
        $insertQuery = "INSERT INTO Utenti (email, password, nome, cognome, sesso, cittàNascita, nazioneNascita, provinciaNascita, dataNascita, cittàResidenza, nazioneResidenza, provinciaResidenza) VALUES ('$email', '$hashedPassword', $firstName, $lastName, $gender, $birthCity, $birthCountry, $birthProvince, $birthDate, $residenceCity, $residenceCountry, $residenceProvince)";
        echo $insertQuery;
    }

    if (mysqli_query($conn, $insertQuery)) {
        // Registrazione avvenuta con successo, imposta la sessione utente
        $_SESSION['email'] = $email;

        // imposta gli hobby
        foreach ($hobby as $selectedOption) {
            $insertQueryHobby = "INSERT INTO Praticano (email,tipo) VALUES ('$email', '$selectedOption')";
            $resultHobby = mysqli_query($conn, $insertQueryHobby);
        }

// Reindirizza al feed dell'utente
        header('Location: ../frontend/feed.php');

    } else {
        // Errore durante la registrazione
        echo "Errore durante la registrazione: " . mysqli_error($conn);
    }

} else {
    // Utente già registrato, imposta la variabile di sessione e reindirizza a index.php
    $_SESSION['email_gia_esistente'] = true;
    header('Location: ../frontend/sign-up.php');
}

?>