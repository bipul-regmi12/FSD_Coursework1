<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// Sanitize and validate inputs
$species = trim($_GET['species'] ?? '');
$breed   = trim($_GET['breed'] ?? '');
$age     = trim($_GET['age'] ?? '');
$gender  = trim($_GET['gender'] ?? '');
$size    = trim($_GET['size'] ?? '');
$city    = trim($_GET['city'] ?? '');
$fee_max = isset($_GET['fee_max']) && $_GET['fee_max'] !== '' ? (float)$_GET['fee_max'] : null;

$query = "SELECT id, name, species, breed, age_range, gender, size, location_city, location_state, adoption_fee, status FROM pets WHERE status = 'available' ";
$params = [];

if ($species) {
    $query .= " AND species = ? ";
    $params[] = $species;
}
if ($breed) {
    $query .= " AND breed LIKE ? ";
    $params[] = "%$breed%";
}
if ($age) {
    $query .= " AND age_range = ? ";
    $params[] = $age;
}
if ($gender) {
    $query .= " AND gender = ? ";
    $params[] = $gender;
}
if ($size) {
    $query .= " AND size = ? ";
    $params[] = $size;
}
if ($city) {
    $query .= " AND location_city LIKE ? ";
    $params[] = "%$city%";
}
if ($fee_max !== null) {
    $query .= " AND adoption_fee <= ? ";
    $params[] = $fee_max;
}

$query .= " ORDER BY created_at DESC";

// SQL Injection prevented by using prepared statements
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$pets = $stmt->fetchAll();

// Compute route base for image URLs
$rawDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if (preg_match('#^(.*/public)(?:/.*)?$#', $rawDir, $m)) {
    $routeBase = rtrim($m[1], '/');
} else {
    $routeBase = rtrim($rawDir, '/');
}

// Add formatting and image URL
foreach ($pets as &$pet) {
    $pet['formatted_fee'] = formatPrice($pet['adoption_fee']);
    $pet['main_image'] = $routeBase . '/image.php?type=pet&id=' . (int)$pet['id'];
}

echo json_encode($pets);
?>
