<?php
session_start();
require_once './pdo_ini.php';
if ($_SESSION['user_id']){
header('Location: index.php');
}
if ($_POST["email"] && $_POST["pass"]){
    try {
        $sql = "SELECT `id`, `name`, `pass` FROM `users` WHERE `email` = :email";
        $sth = $pdo->prepare($sql);
        $email = $_POST['email'];
        $pass = password_hash(trim($_POST['pass']), PASSWORD_DEFAULT);
        $sth->execute([':email' => $email]);
        $isUser = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if (password_verify($_POST["pass"], $isUser[0]['pass'] )){
            $_SESSION['user'] = $isUser;
            unset($_SESSION['user'][0]['pass']);
            header('Location: index.php');
        } else {
            $message = 'Email or password is incorrect';
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
}
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
<form action="" method="post">
    <input type="text" name="email" placeholder="Enter your e-mail" required>
    <input type="password" name="pass" placeholder="Enter the password" required>
    <button type="submit">Sign in</button>
    <p>Not registered yet? Follow the link for <a href="registration.php">registration</a></p>
    <?php
    if (isset($message)) :?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>
</form>
</body>
</html>