<?php
require_once '../pdo_ini.php';
if ($_GET['id'] && $_GET['list_id'] ) {
    try {
        $id = $_GET['id'];
        $sql = "DELETE FROM `tasks` WHERE `id` = ?";
        $params = [$id];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $listId = $_GET['list_id'];
        header("Location:  ./list.php?id=$listId");
    } catch (Exception $exception) {
        $exception->getMessage();
    }
} else {
    header("Location:  ./list.php");
}
