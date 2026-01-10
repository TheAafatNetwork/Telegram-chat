<?php
// Telegram Settings
define('TG_BOT_TOKEN', '8192173343:AAEhYU6zSaqv-SwrdkgZunFeqepiF96ksKM');
define('TG_CHAT_ID', '6576475603');
define('DATA_DIR', __DIR__ . '/data/');

// Create data directory if not exists
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}

function sendTelegram($message) {
    $url = "https://api.telegram.org/bot" . TG_BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => TG_CHAT_ID,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    return @file_get_contents($url, false, $context);
}
?>
