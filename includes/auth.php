<?php
// includes/auth.php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        // Use dynamic base path
        $rawDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if (preg_match('#^(.*/public)(?:/.*)?$#', $rawDir, $m)) {
            $routeBase = rtrim($m[1], '/');
        } else {
            $routeBase = rtrim($rawDir, '/');
        }
        header("Location: {$routeBase}/auth/login.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user_role'] !== $role) {
        $rawDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if (preg_match('#^(.*/public)(?:/.*)?$#', $rawDir, $m)) {
            $routeBase = rtrim($m[1], '/');
        } else {
            $routeBase = rtrim($rawDir, '/');
        }
        header("Location: {$routeBase}/?error=unauthorized");
        exit();
    }
}
?>
