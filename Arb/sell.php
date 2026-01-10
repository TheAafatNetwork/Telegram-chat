<?php
session_start();
require_once 'config.php';

// 1. Security Check
if (!isset($_SESSION['user_phone'])) {
    header("Location: index.php");
    exit();
}

// 2. User Data Fetch (UID Based)
$phone = $_SESSION['user_phone'];
$file = "data/" . $phone . ".json";
$userData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// 3. Logic Variables
$balance = $userData['balance'] ?? 0.00;
$payment_methods = $userData['payment_methods'] ?? [];
$hasUPI = !empty($payment_methods);
$orders = $userData['orders'] ?? [];

// Sort Orders (Newest First)
usort($orders, function($a, $b) {
    return strtotime($b['time']) - strtotime($a['time']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sell ARB - Glowing Edition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #fff; touch-action: pan-y; overflow-x: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* --- Glowing Banner Effects --- */
        @keyframes text-shine {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .shine-text {
            background: linear-gradient(90deg, #fff 0%, #facc15 50%, #fff 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-shine 3s linear infinite;
        }

        /* --- Hyper-Glowing Circle Logic --- */
        @keyframes glowing-aura {
            0% { box-shadow: 0 0 10px rgba(250, 204, 21, 0.4), 0 0 20px rgba(250, 204, 21, 0.2); }
            50% { box-shadow: 0 0 40px rgba(250, 204, 21, 0.8), 0 0 60px rgba(250, 204, 21, 0.4); }
            100% { box-shadow: 0 0 10px rgba(250, 204, 21, 0.4), 0 0 20px rgba(250, 204, 21, 0.2); }
        }

        .sell-circle-glow {
            width: 200px; height: 200px; border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #ffffff 0%, #facc15 50%, #eab308 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            position: relative; border: 6px solid #fff; cursor: pointer;
            animation: glowing-aura 3s ease-in-out infinite;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10;
        }
        .sell-circle-glow::after {
            content: ''; position: absolute; inset: -15px; border-radius: 50%;
            border: 3px solid transparent; border-top-color: #facc15; border-bottom-color: #facc15;
            animation: spin 2s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .sell-circle-glow:active { transform: scale(0.9) rotate(-5deg); filter: brightness(1.2); }
        
        /* Order History Cards */
        .order-card {
            background: #fff; border: 1px solid #f1f5f9; border-radius: 16px;
            padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .badge-green { background: #22c55e; color: white; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; }
        .badge-red { background: #ef4444; color: white; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; }
        .status-text { font-size: 13px; font-weight: 700; text-align: right; }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-2xl overflow-x-hidden pb-24">
    
    <div class="px-5 py-5 flex items-center justify-between border-b border-gray-50 bg-white sticky top-0 z-50">
        <div onclick="history.back()" class="p-2 bg-gray-50 rounded-full cursor-pointer active:scale-95">
            <img src="https://img.icons8.com/material-rounded/24/000000/left.png" class="w-6 h-6 opacity-70">
        </div>
        <h1 class="text-[19px] font-black text-gray-900 tracking-tight">Sell Assets</h1>
        <div class="p-2 bg-gray-50 rounded-full cursor-pointer active:scale-95" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 opacity-40">
        </div>
    </div>

    <div class="p-6 space-y-8">
        
        <div class="w-full h-[150px] bg-gradient-to-br from-[#1e293b] via-[#334155] to-[#0f172a] rounded-[35px] p-7 relative overflow-hidden shadow-2xl group">
            <div class="z-20 relative">
                <h2 class="shine-text text-[26px] font-black leading-tight">ARB Selling Rules</h2>
                <p class="text-white text-[12px] font-bold mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full animate-pulse"></span>
                    Please read the rules carefully
                </p>
            </div>
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-yellow-400/20 blur-[60px] rounded-full"></div>
            <img src="https://arbpay.me/assets/0-e04ebcda.jpg" class="absolute right-[-15px] top-4 h-[120%] object-contain opacity-40 mix-blend-screen scale-110 group-hover:rotate-6 transition-transform duration-700">
        </div>

        <div class="grid grid-cols-3 gap-3 bg-white/50 backdrop-blur-md p-5 rounded-[28px] border border-gray-100 shadow-sm">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Available</p>
                <p class="text-[16px] font-black text-gray-900"><?php echo number_format($balance, 2); ?> ARB</p>
            </div>
            <div class="text-center border-x border-gray-100">
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Sell Bal</p>
                <p class="text-[16px] font-black text-gray-900">0.00 ARB</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Progress</p>
                <p class="text-[16px] font-black text-yellow-600 tracking-tighter">0.00 ARB</p>
            </div>
        </div>

        <div class="flex flex-col items-center py-10 relative">
            <div class="absolute w-72 h-72 bg-yellow-400/20 blur-[80px] rounded-full -z-10 animate-pulse"></div>
            
            <div class="sell-circle-glow shadow-2xl" onclick="goToTimer()">
                <div class="bg-white/40 backdrop-blur-md p-3 rounded-2xl mb-2 border border-white/60 shadow-inner">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="#854d0e"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                </div>
                <span class="text-[26px] font-black text-yellow-950 tracking-tighter drop-shadow-md">SELL ARB</span>
                <div class="mt-1 bg-black/10 px-4 py-0.5 rounded-full border border-black/5">
                    <span class="text-[15px] font-black text-yellow-900">Start</span>
                </div>
            </div>
            
            <p class="mt-12 text-[11px] text-gray-400 font-bold uppercase tracking-[0.25em] opacity-60">Synchronized Selling Active</p>
        </div>

        <div class="flex gap-4">
            <button onclick="location.href='collection.php'" class="flex-1 bg-white border border-gray-100 py-5 rounded-[28px] flex flex-col items-center gap-2 active:scale-95 transition-transform shadow-sm">
                <div class="p-2 bg-gray-50 rounded-xl"><img src="https://img.icons8.com/material-rounded/24/475569/wallet.png" class="w-6 h-6"></div>
                <span class="text-[13px] font-black text-gray-600">Collection</span>
            </button>
            <button onclick="location.href='chat.php'" class="flex-1 bg-white border border-gray-100 py-5 rounded-[28px] flex flex-col items-center gap-2 active:scale-95 transition-transform shadow-sm">
                <div class="p-2 bg-gray-50 rounded-xl"><img src="https://img.icons8.com/material-rounded/24/475569/headset.png" class="w-6 h-6"></div>
                <span class="text-[13px] font-black text-gray-600">Support</span>
            </button>
        </div>

        <div class="pt-6 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                    <span class="text-[15px] font-black text-gray-800">Orders In-Progress</span>
                </div>
                <img src="https://img.icons8.com/material-outlined/24/000000/sorting-answers.png" class="w-5 h-5 opacity-20">
            </div>

            <div id="history-container">
                <?php if (empty($orders)): ?>
                    <div class="flex flex-col items-center justify-center py-10 opacity-10">
                        <img src="https://img.icons8.com/ios/100/000000/clipboard.png" class="w-16 h-16 mb-4">
                        <p class="text-[12px] font-bold uppercase tracking-widest">No Active Records Found</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): 
                        $statusColor = ($order['status'] == 'completed') ? 'text-green-500' : (($order['status'] == 'failed') ? 'text-red-500' : 'text-blue-500');
                        $badgeClass = ($order['status'] == 'completed') ? 'badge-green' : 'badge-red';
                        $statusText = ucfirst($order['status']);
                    ?>
                    <div class="order-card">
                        <div class="flex justify-between items-center mb-3">
                            <span class="<?php echo $badgeClass; ?>">Sell</span>
                            <span class="status-text <?php echo $statusColor; ?>"><?php echo $statusText; ?></span>
                        </div>
                        <div class="flex justify-between text-[13px] mb-2">
                            <span class="text-gray-400 font-bold">Amount</span>
                            <span class="font-bold text-gray-800">₹<?php echo number_format($order['amount'], 2); ?></span>
                        </div>
                        <div class="flex justify-between text-[13px] mb-2">
                            <span class="text-gray-400 font-bold">Time</span>
                            <span class="font-mono text-gray-500 text-[11px]"><?php echo $order['time']; ?></span>
                        </div>
                        <div class="flex justify-between text-[13px]">
                            <span class="text-gray-400 font-bold">Order ID</span>
                            <span class="font-mono text-gray-500 text-[11px]"><?php echo $order['id']; ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Config from PHP
    const hasUPI = <?php echo $hasUPI ? 'true' : 'false'; ?>;

    function goToTimer() {
        if (navigator.vibrate) navigator.vibrate([40, 20, 40]); // Premium Haptics
        
        // Logic: UPI Validation
        if (!hasUPI) {
            alert("Please link a UPI account first in Collection!");
            window.location.href = 'collection.php';
            return;
        }
        
        // Success -> Redirect to Timer Page
        window.location.href = 'sellout.php';
    }
</script>

</body>
</html>
