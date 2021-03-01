<?php
$config = [
    'host' => '127.0.0.1',
    'dbname' => 'todo',
    'user' => 'orisil-dev',
    'pass' => 'w2j9Ly6qdICls49i',
];

try {
    $pdo = new \PDO(
        sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['dbname']),
        $config['user'],
        $config['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $exception) {
    echo $exception->getMessage();
}

$sth = $pdo->prepare("CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL auto_increment,       
  `name` varchar(50) NOT NULL,     
  `email`  varchar(100) NOT NULL,     
  `pass` varchar(255) NOT NULL,    
   PRIMARY KEY  (`id`)
);");
$sth->execute();

$sth = $pdo->prepare("CREATE TABLE IF NOT EXISTS `lists` (
  `id` int(10) NOT NULL auto_increment,
  `title` Varchar(255) NOT NULL,
  `user_id`  int(10) NOT NULL,        
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
   PRIMARY KEY  (`id`),
   FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);");
$sth->execute();

$sth = $pdo->prepare("CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) NOT NULL auto_increment,       
  `list_id` int(10) NOT NULL,          
  `task`  varchar(255) NOT NULL, 
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  `is_done`  tinyint(1) DEFAULT '0',
   PRIMARY KEY  (`id`),
   FOREIGN KEY (`list_id`) REFERENCES `lists`(`id`)
);");
$sth->execute();


