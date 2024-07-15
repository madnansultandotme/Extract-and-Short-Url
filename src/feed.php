<?php
include 'config.php';

$sql = "SELECT content FROM posts ORDER BY id DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
?>
