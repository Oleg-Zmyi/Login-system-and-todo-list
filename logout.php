<?php
session_start();
if (!$_SESSION['user']){
    header('Location: index.php');
};
unset($_SESSION['user']);
header('Location: login.php');