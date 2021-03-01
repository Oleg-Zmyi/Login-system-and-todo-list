<?php
require_once '../pdo_ini.php';
if (isset($_GET['id']) && isset($_GET['list_id']) && isset($_GET['done'])) {
    try {
        $id = $_GET['id'];
        $done = $_GET['done'] == 0 ? 1 : 0;
        $query = "UPDATE `tasks` SET `is_done` = :done WHERE `id` = :id";
        $params = [
            ':id' => $id,
            ':done' => $done
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $listId = $_GET['list_id'];
        header("Location:  ./list.php?id=$listId");
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
} else {
    header("Location:  ./list.php");
}
