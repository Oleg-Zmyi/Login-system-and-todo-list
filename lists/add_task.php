<?php
require_once '../pdo_ini.php';
session_start();
if (!empty($_POST['list_id']) && !empty($_POST['task'])) {
    $task = $_POST['task'];
    $currentListId = $_POST['list_id'];
//    Check if this list id exists
    try {
        $sql = "SELECT COUNT(*) AS id FROM lists WHERE `id` = :currentListId";
        $params = [':currentListId' => $currentListId];
        $sth = $pdo->prepare($sql);
        $sth->execute($params);
        $count = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$count['id']) {
            header("Location:  .././index.php");
        } else {
            //    Check if exists any task with the same name
            $sql = "SELECT COUNT(*) AS id FROM tasks WHERE `task` = :task AND `list_id` = :currentListId";
            $params = [
                ':task' => $task,
                ':currentListId' => $currentListId
            ];
            $sth = $pdo->prepare($sql);
            $sth->execute($params);
            $count = $sth->fetch(PDO::FETCH_ASSOC);
            if ($count["id"]){
                $_SESSION['wrongTaskName'] = 'task with the same value already exists';
            } else {
                // add new task to 'tasks' table
                $sql = "INSERT INTO `tasks` (`list_id`, `task`) VALUES (:list_id, :task)";
                $params = [
                    ':list_id' => $currentListId,
                    ':task' => $task
                ];
                $sth = $pdo->prepare($sql);
                $sth->execute($params);
            }
            header("Location:  ./list.php?id=$currentListId");
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
}

