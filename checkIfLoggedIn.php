<?php

if (!isset($_SESSION['login'])) {
    sendTo("login.php");
}

?>