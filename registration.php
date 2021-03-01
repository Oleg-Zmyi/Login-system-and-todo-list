<?php
session_start();
require_once './pdo_ini.php';
if ($_SESSION['user']){
    header('Location: index.php');
};

if ($_POST){
try {
    $sql = "SELECT COUNT(*) AS count FROM `users` WHERE `email` = :email ";
    $sth = $pdo->prepare($sql);
    $email = $_POST['email'];
    $sth->execute(['email' => $email]);
    $isUser = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
    if ($isUser[0]['count']){
        $message = 'User with the same name already exists';
    } else {
        try {
            $sql = "INSERT INTO `users` (name, email, pass) VALUES (:name, :email, :pass)";
            $params = [
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':pass' => password_hash(trim($_POST['pass']), PASSWORD_DEFAULT)
            ];
            $sth = $pdo->prepare($sql);
            $sth->execute($params);

            $sql = "SELECT `id`, `name` FROM `users` WHERE `email` = :email";
            $sth = $pdo->prepare($sql);
            $email = $_POST['email'];
            $sth->execute(['email' => $email]);
            $user = $sth->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
} ?>

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

<form action="" method="post">
    <p>Registration</p>
    <input type="text" name="name" placeholder="Enter your name">
    <input type="email" name="email" placeholder="Enter email">
    <input type="password" name="pass" placeholder="Enter password" autocomplete="off">
    <button type="submit">To register</button>
        <p>Already have an account? Follow the link for <a href="login.php">sign in</a></p>
    <?php if (!empty($message)) : ?>
        <p class="error"><?= $message ?></p>
        <?php unset($message); ?>
    <?php endif; ?>
</form>

</body>
</html>