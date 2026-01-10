<?php
/**
 * GLOBAL AUTH GUARD
 * Used by: chat.php, buy.php, admin pages
 */

session_start();

/*
|--------------------------------------------------------------------------
| SESSION KEY CHECK
|--------------------------------------------------------------------------
| Tumhare project me login.php ye session set karta hai:
| $_SESSION['user_phone']
| Isliye auth bhi wahi check karega.
*/
if (!isset($_SESSION['user_phone']) || empty($_SESSION['user_phone'])) {
    // Not logged in → login page
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| OPTIONAL: USER DATA LOAD (SAFE)
|--------------------------------------------------------------------------
| Agar future me user data chahiye ho to yahin milega
*/
$userPhone = $_SESSION['user_phone'];
