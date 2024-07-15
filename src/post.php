<?php
include 'config.php';

function generateShortUrl($length = 6) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function getShortUrl($originalUrl) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT short_url FROM url_mappings WHERE original_url = ?");
    $stmt->execute([$originalUrl]);
    $row = $stmt->fetch();
    if ($row) {
        return $row['short_url'];
    }

    do {
        $shortUrl = generateShortUrl();
        $stmt = $pdo->prepare("SELECT id FROM url_mappings WHERE short_url = ?");
        $stmt->execute([$shortUrl]);
        $row = $stmt->fetch();
    } while ($row);

    $stmt = $pdo->prepare("INSERT INTO url_mappings (original_url, short_url) VALUES (?, ?)");
    $stmt->execute([$originalUrl, $shortUrl]);

    return $shortUrl;
}

function extractAndShortenUrls($content) {
    $pattern = '/https?:\/\/[^\s]+/';
    return preg_replace_callback($pattern, function($matches) {
        $shortUrl = getShortUrl($matches[0]);
    //   here we can add any custom domain name
        return "http:http://localhost/url-shortner/public/$shortUrl";
    }, $content);
}

$data = json_decode(file_get_contents('php://input'), true);
$content = $data['content'];

$shortenedContent = extractAndShortenUrls($content);

$sql = "INSERT INTO posts (content) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$shortenedContent]);

$response = [
    "success" => true,
    "post" => [
        "content" => $shortenedContent
    ]
];
echo json_encode($response);
?>
