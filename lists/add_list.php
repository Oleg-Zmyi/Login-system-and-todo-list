<?php
require_once '../pdo_ini.php';
session_start();

$currentUserID = $_SESSION['user'][0]['id'];
$title = $_POST['list_name'];
try {
//    Check if exists any list with the same name
    $sql = "SELECT `id` AS count FROM lists WHERE `title` = :title AND `user_id` = :currentUserId";
    $currentUserID = $_SESSION['user'][0]['id'];
    $title = $_POST['list_name'];
    $params = [
        ':title' => $title,
        ':currentUserId' => $currentUserID
    ];
    $sth = $pdo->prepare($sql);
    $sth->execute($params);
    $count = $sth->fetch(PDO::FETCH_ASSOC);
    if ($count > 0){
        $_SESSION['wrongListName'] = 'List with this name already exist, please change the name';
        header("Location:  ../index.php");
    } else {
        //Insert new list in lists table
        $sql = "INSERT INTO `lists` (`title`, `user_id`) VALUES (:title, :currentUserId)";
        $params = [
            ':title' => $title,
            ':currentUserId' => $currentUserID
        ];
        $sth = $pdo->prepare($sql);
        $sth->execute($params);
        //Get id of new list
        $currentListId = $pdo->lastInsertId();
        header("Location:  ./list.php?id=$currentListId");
    }
} catch (PDOException $exception) {
    echo $exception->getMessage();
}