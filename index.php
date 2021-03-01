<?php
require_once './pdo_ini.php';
session_start();
if (!$_SESSION['user']){
    header("Location:  login.php");
}
$currentUserID = $_SESSION['user'][0]['id'];

try {
    $sql = "SELECT `id`, `name`, `pass` FROM `users` WHERE `email` = :email";
    $sth = $pdo->prepare($sql);
    $email = $_POST['email'];
    $pass = password_hash(trim($_POST['pass']), PASSWORD_DEFAULT);
    $sth->execute([':email' => $email]);
    $isTasks = $sth->fetchAll(\PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    echo $exception->getMessage();
}

//Check if there is lists of current user
try {
$sql = "SELECT COUNT(*) AS count FROM lists l WHERE l.user_id = :currentUserId ";
$sth = $pdo->prepare($sql);
$sth->execute([':currentUserId' => $currentUserID]);
$isLists = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    echo $exception->getMessage();
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
<main>
    <h1>Todo list</h1>
    <form action="./lists/add_list.php" method="post">
        <h3>You could create a new list here:</h3>
        <input type="text" placeholder="list title" name="list_name" required>
        <button type="submit">Create list</button>
        <?php if ($_SESSION['wrongListName']) : ?>
            <p class="error"> <?= $_SESSION['wrongListName'] ?></p>
            <?php  unset($_SESSION['wrongListName'])?>
        <?php endif; ?>
    </form>
    <?php try {
        $sql = "SELECT `id`, `title`, `created_at` FROM `lists` WHERE `user_id` = :currentUserId";
        $sth = $pdo->prepare($sql);
        $sth->execute([':currentUserId' => $currentUserID]);
        $lists = $sth->fetchAll(\PDO::FETCH_ASSOC);
        ?>
        <?php if (!empty($lists)) : ?>
            <h2>Your lists of tasks:</h2>
            <ul class="list">
                <?php foreach ($lists as $list): ?>
                    <li class="<?= $list['is_done'] == 0 ? 'done' : 'to_do'; ?>">
                        <a href="lists/list.php?id=<?=$list['id']?>" class="task"><?= $list['title']; ?></a>
                        <p><?= $list['created_at']; ?></p>
                        <a href="lists/delete_list.php?id=<?=$list['id']; ?>" class="">delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You don't have any lists yet!</p>
        <?php endif; ?>
    <?php } catch (Exception $exception) {
        echo $exception->getMessage();
    } ?>
</main>
</body>
</html>