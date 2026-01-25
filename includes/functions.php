<?php
// includes/functions.php

/**
 * Debug logger
 */
function debug_ndjson_log($hypothesisId, $location, $message, $data = [], $runId = 'pre-fix') {
    try {
        $logDir = __DIR__ . '/../.cursor';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }
        $payload = [
            'sessionId' => 'debug-session',
            'runId' => $runId,
            'hypothesisId' => $hypothesisId,
            'location' => $location,
            'message' => $message,
            'data' => $data,
            'timestamp' => (int) round(microtime(true) * 1000),
        ];
        @file_put_contents($logDir . '/debug.log', json_encode($payload) . "\n", FILE_APPEND);
    } catch (Throwable $e) {}
}

/**
 * Escape HTML for output to prevent XSS
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF Protection
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

function csrfInput() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

/**
 * Format currency
 */
function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Redirect utility
 */
function redirect($path) {
    $location = $path;
    if (strpos($path, 'http://') !== 0 && strpos($path, 'https://') !== 0) {
        $rawDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if (preg_match('#^(.*/public)(?:/.*)?$#', $rawDir, $m)) {
            $basePath = rtrim($m[1], '/'); 
        } else {
            $basePath = rtrim($rawDir, '/');
        }
        if ($path[0] === '/') {
            $location = ($basePath === '' ? '' : $basePath) . $path;
        }
    }
    header("Location: $location");
    exit();
}

/**
 * Check if a user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}
?>
