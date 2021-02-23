<?php

session_start()
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/main.css">
    <title>Document</title>
</head>
<body>
<header>
    <?php
    if ($_SESSION['user'][0]['name']) : ?>
        <p>Hi, <?= $_SESSION['user'][0]['name']; ?></p>
        <p><a href="logout.php">sign out</a></p>
    <?php else: ?>
        <p>Hi, Guest</p>
    <?php endif; ?>
</header>
<main>
    <h1>404</h1>
    <p>Page is not found or removed</p>
    <a href="/">Back to home page</a>
</main>
</body>
</html>