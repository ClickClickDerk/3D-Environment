<?php
require_once 'db.php';

header('Content-Type: application/json');

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $hashedPassword = hashPassword($password);
        $verificationToken = bin2hex(random_bytes(16));

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, verification_token) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $hashedPassword, $email, $verificationToken])) {
            // Send verification email (implement this function)
            sendVerificationEmail($email, $verificationToken);
            echo json_encode(['success' => true, 'message' => 'Registration successful. Please check your email for verification.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed.']);
        }
    } elseif ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND verified = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && verifyPassword($password, $user['password'])) {
            echo json_encode(['success' => true, 'username' => $username]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials or unverified account.']);
        }
    }
}

function sendVerificationEmail($email, $token) {
    $to = $email;
    $subject = "Verify Your Account";
    $message = "Click the following link to verify your account: http://yourdomain.com/verify.php?token=$token";
    $headers = "From: noreply@yourdomain.com\r\n";
    mail($to, $subject, $message, $headers);
}
?>