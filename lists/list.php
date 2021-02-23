<?php
require_once '../pdo_ini.php';
session_start();
if(!$_GET['id'] || !$_SESSION['user'] ) {
    header("Location:  .././index.php");
}
$listId = $_GET['id'];
if ($_GET['id']) {
    try {
        $sql = "SELECT COUNT(*) AS id FROM lists WHERE `id` = :currentListId";
        $params = [':currentListId' => $listId];
        $sth = $pdo->prepare($sql);
        $sth->execute($params);
        $count = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$count['id']) {
            header("Location:  .././404.php");
        } else {
        }
    } catch (Exception $exception){
        $exception->getMessage();
    }
}

$currentUserID = $_SESSION['user'][0]['id'];
$listTitle['title'] = '';
//Get title to current list
try {
    $sql = "SELECT DISTINCT `title` from lists WHERE `id` = :listId";
    $sth = $pdo->prepare($sql);
    $sth->execute([':listId' => $listId]);
    $listTitle = $sth->fetch(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    $exception->getMessage();
}

//get all tasks from this list
try {
    $sql = "SELECT `id`, `task`, `created_at`, `is_done` FROM `tasks` WHERE `list_id` = :currentListId ORDER BY `id` DESC ";
    $currentListID = $_GET['id'];
    $sth = $pdo->prepare($sql);
    $sth->execute([':currentListId' => $currentListID]);
    $lists = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    $exception->getMessage();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>List</title>
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
    <h1><?= $listTitle['title'] ?></h1>
    <a href="<?=$_SERVER["PHP_SELF"]?>">back to your lists</a>
    <form action="./add_task.php" method="post">
        <label for="list_name">Add new task to current list</label>
        <input type="hidden" value="<?= $_GET['id'] ?>" name="list_id">
        <input type="text" placeholder="task" name="task" required>
        <button type="submit">Create task</button>
        <?php
        if ($_SESSION['wrongTaskName']) : ?>
        <p class="error"><?= $_SESSION['wrongTaskName']; ?></p>
        <?php unset($_SESSION['wrongTaskName']); ?>
        <?php endif; ?>
    </form>
    <?php if (!empty($lists)) : ?>
    <ul class="list">
        <?php foreach ($lists as $list): ?>
           <li class="<?= $list['is_done'] == 0 ? 'done' : 'to_do'; ?>">
               <p class="task"><?= $list['task']; ?></p>
               <p><?= $list['created_at'] ?></p>
               <a href="delete_task.php?<?= http_build_query(['id' => $list['id'], 'list_id' => $listId]) ?>" class="">delete</a>
               <a href="done.php?<?= http_build_query(['id' => $list['id'], 'list_id' => $listId, 'done'=> $list['is_done']]) ?>" class=""><?= $list['is_done'] == 0 ? 'done' : 'to_do' ?></a>
           </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <p>There isn't any tasks</p>
    <?php endif; ?>
</main>
</body>
</html>

