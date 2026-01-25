<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('shelter');

// IDOR Protection: Always verify ownership
$petId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$shelterId = $_SESSION['user_id'];

// CSRF Protection for sensitive actions
if (!isset($_GET['token']) || !verifyCsrfToken($_GET['token'])) {
    redirect('/dashboard/shelter.php?error=Invalid security token.');
}

if ($petId) {
    // Verify ownership (IDOR check)
    $stmt = $pdo->prepare("SELECT id FROM pets WHERE id = ? AND shelter_id = ?");
    $stmt->execute([$petId, $shelterId]);
    if ($stmt->fetch()) {
        // SQL Injection prevented by prepared statement
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
        $stmt->execute([$petId]);
        redirect('/dashboard/shelter.php?msg=deleted');
    } else {
        redirect('/dashboard/shelter.php?error=unauthorized_access');
    }
} else {
    redirect('/dashboard/shelter.php');
}
?>
