<?php
include 'config.php';

$shortUrl = $_GET['url'];
$stmt = $pdo->prepare("SELECT original_url FROM url_mappings WHERE short_url = ?");
$stmt->execute([$shortUrl]);
$row = $stmt->fetch();

if ($row) {
    header("Location: " . $row['original_url']);
} else {
    echo "URL not found.";
}
?>
