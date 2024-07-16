<?php
include 'config.php';

$sql = "SELECT content FROM posts ORDER BY id DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include click counts in the response
foreach ($posts as &$post) {
    preg_match_all('/http:\/\/localhost\/url-shortner\/public\/([a-zA-Z0-9]{6})/', $post['content'], $matches);
    $post['clicks'] = 0;
    foreach ($matches[1] as $shortUrl) {
        $clickStmt = $pdo->prepare("SELECT click_count FROM url_mappings WHERE short_url = ?");
        $clickStmt->execute([$shortUrl]);
        $clickCountRow = $clickStmt->fetch();
        $post['clicks'] += $clickCountRow['click_count'];
    }
}

echo json_encode($posts);
?>
