<?php
require_once '../pdo_ini.php';
if ($_GET['id']) {
    $id = $_GET['id'];
    // delete all tasks from this list
    try {
        $sql = "DELETE FROM `tasks` WHERE `list_id` = ?";
        $params = [$id];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
};
    // delete list
    try {
        $sql = "DELETE FROM `lists` WHERE `id` = ?";
        $params = [$id];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } catch (Exception $exception){
        echo $exception->getMessage();
    };

header("Location:  ../index.php");