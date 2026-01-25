<?php
require_once __DIR__ . '/../config/db.php';

// Simple script to output image blob
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = $_GET['type'] ?? 'pet';

if ($type === 'pet' && $id) {
    $stmt = $pdo->prepare("SELECT main_image FROM pets WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row && $row['main_image']) {
        // Simple detection of mime type (assume jpeg/png mostly works or generic octet-stream)
        // For better mime detection, finfo is good, but let's assume valid image.
        // Or output everything as jpeg/png, browsers often auto-detect.
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($row['main_image']);
        
        if (!$mimeType) $mimeType = 'image/jpeg';
        
        header("Content-Type: $mimeType");
        
        // Output headers to help caching if needed, but for now simple
        echo $row['main_image'];
        exit;
    }
}

// Fallback to placeholder (redirect or read file)
// We need to know assetBase to redirect correctly?
// Just read the file directly
header("Content-Type: image/png");
readfile(__DIR__ . '/../assets/img/placeholder-pet.png');
?>
