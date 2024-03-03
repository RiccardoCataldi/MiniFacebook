<?php

include_once '../common/session.php'; // session start
include_once '../common/connection.php';

$password = $_POST["password"];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // password passata viene cifrata con algoritmo PASSWORD_DEFAULT

$firstName = $_POST["name"];

$lastName = $_POST["surname"];
$gender = $_POST["gender"];
$birthCity = $_POST["birthCity"];
$birthCountry = $_POST["birthCountry"];
$birthProvince = $_POST["birthProvince"];
$birthDate = $_POST["birthDate"];
$residenceCity = $_POST["residenceCity"];
$residenceCountry = $_POST["residenceCountry"];
$residenceProvince = $_POST["residenceProvince"];
$hobby = $_POST["hobby"];
$email = $_SESSION['email'];

// Inizializzare la query di aggiornamento
$updateQuery = "UPDATE utenti SET";

$countUpdatesUtente = 0;

// Verificare ogni attributo e aggiungerlo alla query solo se non è vuoto
if (!empty($password) || $password == "0") {
    
    $updateQuery .= " password = '$hashedPassword',";
    $countUpdatesUtente +=1;
}

if (!empty($firstName)) {
    $updateQuery .= " nome = '$firstName',";
    $countUpdatesUtente +=1;
}

if (!empty($lastName)) {
    $updateQuery .= " cognome = '$lastName',";
    $countUpdatesUtente +=1;
}

if (!empty($gender)) {
    $updateQuery .= " sesso = '$gender',";
    $countUpdatesUtente +=1;
}

if (!empty($birthCity)) {
    $updateQuery .= " cittàNascita = '$birthCity',";
    $countUpdatesUtente +=1;
}

if (!empty($birthCountry)) {
    $updateQuery .= " nazioneNascita = '$birthCountry',";
    $countUpdatesUtente +=1;
}

if (!empty($birthProvince)) {
    $updateQuery .= " provinciaNascita = '$birthProvince',";
    $countUpdatesUtente +=1;
}

if (!empty($birthDate)) {
    $updateQuery .= " dataNascita = '$birthDate',";
    $countUpdatesUtente +=1;
}

if (!empty($residenceCity)) {
    $updateQuery .= " cittàResidenza = '$residenceCity',";
    $countUpdatesUtente +=1;
}

if (!empty($residenceCountry)) {
    $updateQuery .= " nazioneResidenza = '$residenceCountry',";
    $countUpdatesUtente +=1;
}

if (!empty($residenceProvince)) {
    $updateQuery .= " provinciaResidenza = '$residenceProvince',";
    $countUpdatesUtente +=1;
}
if (!empty($hobby)) {
    // aggiungi gli hobby
    foreach ($hobby as $selectedOption) {

        // check se utente ha già quell'hobby
        $checkQuery = "SELECT * FROM Praticano WHERE email = '$email' AND tipo = '$selectedOption'";
        $resultCheck = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($resultCheck) == 0) { // se utente non ha già quell'hobby, puoi aggiungerlo
            $insertQueryHobby = "INSERT INTO Praticano (email,tipo) VALUES ('$email', '$selectedOption')";
            $resultHobby = mysqli_query($conn, $insertQueryHobby);
        }
    }

}

if ($countUpdatesUtente > 0) {
    // Rimuovere l'ultima virgola dalla query
    $updateQuery = rtrim($updateQuery, ',');

    // Aggiungere la clausola WHERE per identificare l'utente
    $updateQuery .= " WHERE email = '$email' ";

    // Eseguire la query di aggiornamento
    $result = mysqli_query($conn, $updateQuery);
}

// redirect a profilo con dati aggiornati
header("Location: ../frontend/profile.php");
