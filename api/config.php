<?php
// Database connection using environment variables (Vercel) with local fallbacks
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'portal_system';
$db_port = getenv('DB_PORT') ?: 3306;

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, (int)$db_port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Use database-backed sessions for serverless deployment
require_once __DIR__ . '/session_handler.php';
initDbSessions($conn);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function isStudent() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'student');
}

function getUserDetails($username) {
    global $conn;

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getUnreadCount($username) {
    global $conn;

    $sql = "SELECT COUNT(*) as total FROM messages WHERE receiver=? AND status='unread'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

function getRequestStatus($username) {
    global $conn;

    $sql = "SELECT status FROM requests WHERE username=? AND type='certificate' ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows > 0){
        return $result->fetch_assoc()['status'];
    }
    return "none";
}
?>
