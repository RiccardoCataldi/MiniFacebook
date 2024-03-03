<?php
    include_once("../common/session.php");
    session_destroy();
    header("location: ../index.php");
?>