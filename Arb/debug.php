<?php
/* ================= DEBUG MODE ================= */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🧪 PHP DEBUG REPORT</h2>";

/* 1️⃣ PHP BASIC INFO */
echo "<h3>1. PHP Info</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";

/* 2️⃣ HEADERS STATUS */
echo "<h3>2. Headers Status</h3>";
if (headers_sent($file, $line)) {
    echo "❌ Headers already sent in <b>$file</b> at line <b>$line</b><br>";
} else {
    echo "✅ Headers NOT sent yet (redirect possible)<br>";
}

/* 3️⃣ SESSION TEST */
echo "<h3>3. Session Test</h3>";
session_start();
$_SESSION['debug_test'] = 'OK';

echo "Session ID: " . session_id() . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
echo "Session Data:<br><pre>";
print_r($_SESSION);
echo "</pre>";

/* 4️⃣ COOKIE TEST */
echo "<h3>4. Cookie Test</h3>";
setcookie("debug_cookie", "alive", time() + 60, "/");
if (isset($_COOKIE['debug_cookie'])) {
    echo "✅ Cookie already present<br>";
} else {
    echo "⚠️ Cookie set now, refresh page once to verify<br>";
}

/* 5️⃣ CONFIG FILE CHECK */
echo "<h3>5. config.php Check</h3>";
ob_start();
@include 'config.php';
$output = ob_get_clean();

if ($output !== '') {
    echo "❌ config.php OUTPUT DETECTED:<br><pre>$output</pre>";
} else {
    echo "✅ config.php produces NO output<br>";
}

/* 6️⃣ REDIRECT TEST */
echo "<h3>6. Redirect Test</h3>";
if (!headers_sent()) {
    echo "Trying redirect in 3 seconds...<br>";
    header("Refresh:3; url=home.php");
} else {
    echo "❌ Redirect blocked due to headers sent<br>";
}

echo "<hr><b>END OF DEBUG REPORT</b>";
