<?php
session_start();
require_once 'config.php'; // Assuming config exists for constants

// Security Check
if (!isset($_SESSION['user_phone'])) {
    header("Location: index.php");
    exit();
}

// Logic: Fetch User Payment Data
$phone = $_SESSION['user_phone'];
$file = "data/" . $phone . ".json"; // Adjust path if needed
$payment_methods = [];

if (file_exists($file)) {
    $userData = json_decode(file_get_contents($file), true);
    if (isset($userData['payment_methods'])) {
        $payment_methods = $userData['payment_methods'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Collection Method</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #fff; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* The specific "Add" button from screenshot */
        .btn-add-method {
            background: #f8fafc; border: 1px solid #f1f5f9;
            color: #334155; font-weight: 700; font-size: 14px;
            padding: 14px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; transition: all 0.2s;
        }
        .btn-add-method:active { background: #e2e8f0; transform: scale(0.98); }

        /* Premium UPI Card (When data exists) */
        .upi-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white; border-radius: 20px; padding: 20px;
            position: relative; overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
            margin-bottom: 15px;
        }
        .upi-card::after {
            content: ''; position: absolute; top: -50%; right: -20%; width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="location.href='home.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[17px] font-black text-slate-800">Collection</h1>
        <div class="flex gap-4">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 cursor-pointer opacity-40 hover:opacity-100" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 cursor-pointer opacity-40 hover:opacity-100" onclick="location.href='home.php'">
        </div>
    </div>

    <div class="px-5 py-8 flex flex-col items-center">
        
        <?php if (empty($payment_methods)): ?>
            <div class="mt-20 mb-8 flex flex-col items-center opacity-80">
                <div class="w-32 h-32 mb-4 relative">
                   <img src="https://img.icons8.com/pastel-glyph/128/e2e8f0/box--v1.png" class="w-full h-full object-contain opacity-50">
                </div>
                <p class="text-[13px] text-gray-400 font-medium">No data for now~</p>
            </div>

            <button onclick="location.href='add_payment.php'" class="btn-add-method">
                <img src="https://img.icons8.com/material-rounded/24/94a3b8/plus-math.png" class="w-4 h-4">
                Add payment UPI
            </button>
        <?php else: ?>
            
            <div class="w-full space-y-4">
                <?php foreach($payment_methods as $pm): ?>
                <div class="upi-card">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                                <img src="https://img.icons8.com/ios-filled/50/ffffff/qr-code.png" class="w-6 h-6">
                            </div>
                            <div>
                                <p class="text-[14px] font-bold opacity-90">UPI Transfer</p>
                                <p class="text-[10px] opacity-60">Verified</p>
                            </div>
                        </div>
                        <img src="https://img.icons8.com/fluency/48/checked.png" class="w-5 h-5">
                    </div>
                    <div>
                        <p class="text-[10px] opacity-50 uppercase tracking-widest mb-1">UPI ID</p>
                        <p class="text-[18px] font-mono font-bold tracking-wide text-[#facc15]"><?php echo htmlspecialchars($pm['upi_id']); ?></p>
                        <p class="text-[12px] opacity-70 mt-1"><?php echo htmlspecialchars($pm['real_name']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <button onclick="location.href='add_payment.php'" class="btn-add-method mt-6">
                    <img src="https://img.icons8.com/material-rounded/24/94a3b8/plus-math.png" class="w-4 h-4">
                    Add another method
                </button>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
