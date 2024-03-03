<?php

include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

$email = $_SESSION['email'];
$msg = $_POST['message'];
$città = $_POST['città'];
$separateCityData = ['null', 'null', 'null']; // imposta città, provincia, nazione a null

if ($città != 'null') {
    $separateCityData = explode(",", $città); // ricava città, provincia, nazione dalla stringa separando dalle virgole
}

if (!empty($_FILES["image"]["name"])) {
    $nomeFile = $_FILES["image"]["name"];
    $folder = "../img/post/" . $email . "-" . date('Y-m-d') . "-" . $nomeFile;
    $posizioneFileSystem = $folder;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $folder)) {
        $dataPubblicazione = date("Y-m-d H:i:s");
        $tipo = "foto";
        if ($città != 'null') { // se è stata passata una città
            if ($msg) {
                $descrizione = $msg;
                $query = "INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem,descrizione,nomeCittà,provincia,nazione) VALUES ('$email','$dataPubblicazione','$tipo','$nomeFile','$posizioneFileSystem','$descrizione','$separateCityData[0]', '$separateCityData[1]','$separateCityData[2]')";
            } else {
                $query = "INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem,nomeCittà,provincia,nazione) VALUES ('$email','$dataPubblicazione','$tipo','$nomeFile','$posizioneFileSystem','$separateCityData[0]','$separateCityData[1]','$separateCityData[2]')";
            }
        } else { // se non c'è nessuna città associata

            if ($msg) {
                $descrizione = $msg;
                $query = "INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem,descrizione) VALUES ('$email','$dataPubblicazione','$tipo','$nomeFile','$posizioneFileSystem','$descrizione')";
            } else {
                $query = "INSERT INTO Messaggi (email,dataPubblicazione,tipo,nomeFile,posizioneFileSystem) VALUES ('$email','$dataPubblicazione','$tipo','$nomeFile','$posizioneFileSystem')";
            }
        }

        $result = mysqli_query($conn, $query);


        if ($result) {
            echo "success";
        } else {
            echo "failure";
        }
    } else {
        echo "failure";
    }
} else {
    
    if (publishMsg($conn, $email, $msg, $separateCityData)) {
        echo "success";
    } else {
        echo "failure";
    }
}

