<?php
include 'config.php';

$shortUrl = $_GET['url'];
$stmt = $pdo->prepare("SELECT original_url, click_count FROM url_mappings WHERE short_url = ?");
$stmt->execute([$shortUrl]);
$row = $stmt->fetch();

if ($row) {
    // Increment click count
    $newClickCount = $row['click_count'] + 1;
    $updateStmt = $pdo->prepare("UPDATE url_mappings SET click_count = ? WHERE short_url = ?");
    $updateStmt->execute([$newClickCount, $shortUrl]);

    // Redirect to original URL
    header("Location: " . $row['original_url']);
} else {
    echo "URL not found.";
}
?>
