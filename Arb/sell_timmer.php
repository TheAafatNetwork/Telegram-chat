<?php
session_start();
require_once 'config.php';

// --- 1. AJAX HANDLER (To Save Orders via JS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_order') {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_phone'])) { echo json_encode(['status'=>'error']); exit; }
    
    $phone = $_SESSION['user_phone'];
    $file = "data/" . $phone . ".json";
    $userData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    
    // New Sub-Order Data
    $newOrder = [
        'id' => $_POST['oid'],
        'amount' => (float)$_POST['amount'],
        'time' => date('Y-m-d H:i:s'),
        'status' => 'completed', // For demo purposes, we mark matched as completed or pending
        'type' => 'sell'
    ];
    
    // Append to Order History
    if (!isset($userData['orders'])) { $userData['orders'] = []; }
    $userData['orders'][] = $newOrder;
    
    // Update Balance (Simulated deduction)
    if(isset($userData['balance'])) { $userData['balance'] -= $newOrder['amount']; }
    
    file_put_contents($file, json_encode($userData));
    echo json_encode(['status'=>'ok']);
    exit;
}

// --- 2. PAGE LOAD LOGIC ---
if (!isset($_SESSION['user_phone'])) { header("Location: index.php"); exit; }

$phone = $_SESSION['user_phone'];
$file = "data/" . $phone . ".json";
$userData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Get Payment Method (UPI)
$upi_id = "Not Set";
if (!empty($userData['payment_methods'])) {
    $upi_id = $userData['payment_methods'][0]['upi_id']; // Get first UPI
} else {
    // Redirect if no UPI
    header("Location: collection.php"); 
    exit();
}

$kycStatus = "KYC Is Normal"; // Default
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Matching - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #fff; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Stepper */
        .step-line { flex: 1; height: 2px; background: #f1f5f9; margin: 0 10px; }
        .step-active { background: #22c55e; color: white; border-color: #22c55e; }
        
        /* Golden Circle Timer */
        .timer-circle {
            width: 220px; height: 220px; border-radius: 50%;
            background: radial-gradient(circle, #fef08a 0%, #facc15 40%, #eab308 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            box-shadow: 0 0 40px rgba(250, 204, 21, 0.4);
            border: 8px solid #fff; outline: 1px solid #fefce8;
            animation: pulse-gold 3s infinite;
        }
        @keyframes pulse-gold { 50% { box-shadow: 0 0 60px rgba(250, 204, 21, 0.6); } }

        /* Order List Card */
        .sub-order-card {
            background: white; border: 1px solid #f1f5f9; border-radius: 12px;
            padding: 14px; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            animation: slideDown 0.4s ease-out;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .badge-sell { background: #22c55e; color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: 800; }
        .status-wait { color: #3b82f6; font-weight: 700; font-size: 12px; }
        .status-done { color: #22c55e; font-weight: 700; font-size: 12px; }
        .mono { font-family: 'Roboto Mono', monospace; }

        /* Table Rows */
        .d-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; border-bottom: 1px solid #f8fafc; }
        .d-label { color: #94a3b8; font-weight: 600; }
        .d-val { font-weight: 700; color: #334155; }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden pb-20">
    
    <div class="px-5 py-4 flex items-center justify-between border-b border-gray-50 bg-white sticky top-0 z-50">
        <div onclick="history.back()" class="p-2 -ml-2 cursor-pointer active:scale-90"><img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6"></div>
        <h1 class="text-[18px] font-black text-gray-900">Matching</h1>
        <div onclick="location.reload()" class="p-2 cursor-pointer active:scale-90"><img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 opacity-40"></div>
    </div>

    <div class="p-6">
        
        <div class="flex items-center justify-between mb-10 px-2">
            <div class="flex flex-col items-center gap-1">
                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px] font-bold">✓</div>
                <span class="text-[10px] font-bold text-slate-800">Matching</span>
            </div>
            <div class="step-line"></div>
            <div class="flex flex-col items-center gap-1 opacity-50">
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-bold">2</div>
                <span class="text-[10px] font-bold text-slate-400">Payment</span>
            </div>
            <div class="step-line"></div>
            <div class="flex flex-col items-center gap-1 opacity-50">
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-bold">3</div>
                <span class="text-[10px] font-bold text-slate-400">Confirm</span>
            </div>
        </div>

        <div class="flex flex-col items-center mb-8">
            <div class="timer-circle mb-6">
                <img src="https://arbpay.me/assets/logo-d6f839a9.png" class="w-16 h-16 object-contain opacity-50 grayscale brightness-200 mb-2">
                <span id="countdown" class="text-[32px] font-black text-[#713f12] tracking-tighter leading-none">179:59</span>
                <span class="text-[14px] font-bold text-[#854d0e] uppercase mt-1">Matching</span>
            </div>
            <p class="text-[12px] font-bold text-yellow-700 text-center leading-tight">
                The order is currently matching. Please be patient!<br>
                <span class="text-yellow-600/70">Estimated matching time 180 minutes</span>
            </p>
            
            <button onclick="history.back()" class="mt-6 bg-white border border-gray-200 text-slate-500 font-bold py-3 px-8 rounded-xl text-[13px] shadow-sm active:scale-95 transition-transform">
                Cancel Matching
            </button>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 mb-8 border border-gray-100">
            <div class="d-row"><span class="d-label">Sell Quantity</span><span class="d-val">10000 ARB</span></div>
            <div class="d-row"><span class="d-label">Sell Amount</span><span class="d-val">₹10,000.00</span></div>
            <div class="d-row"><span class="d-label">Reward</span><span class="d-val text-green-500">100.00 ARB</span></div>
            <div class="d-row"><span class="d-label">KYC</span><span class="d-val text-[10px] bg-green-100 text-green-700 px-2 rounded"><?php echo $kycStatus; ?></span></div>
            <div class="d-row border-none"><span class="d-label">UPI ID</span><span class="d-val text-[11px]"><?php echo $upi_id; ?></span></div>
            
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Sold</p><p id="sold-qty" class="text-[14px] font-black text-slate-800">0 ARB</p></div>
                <div class="text-right"><p class="text-[10px] text-gray-400 uppercase font-bold">Remaining</p><p id="rem-qty" class="text-[14px] font-black text-red-500">10000 ARB</p></div>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <h3 class="text-[14px] font-bold text-slate-800">Matching Records</h3>
            </div>
            
            <div id="sub-orders-list" class="pb-10">
                <div id="empty-msg" class="text-center py-8 opacity-30 text-[12px] font-bold">Waiting for match...</div>
            </div>
        </div>

    </div>
</div>

<script>
    // Configuration
    const TOTAL_QTY = 10000;
    let soldQty = 0;
    let timerSeconds = 180 * 60; // 180 minutes

    // 1. Timer Logic
    setInterval(() => {
        timerSeconds--;
        const m = Math.floor(timerSeconds / 60);
        const s = timerSeconds % 60;
        document.getElementById('countdown').innerText = `${m}:${s < 10 ? '0'+s : s}`;
    }, 1000);

    // 2. Order Generation Logic (Simulates buyers found)
    function generateSubOrder() {
        if(soldQty >= TOTAL_QTY) return;

        const emptyMsg = document.getElementById('empty-msg');
        if(emptyMsg) emptyMsg.remove();

        // Random amount between 100 and 2000
        let amount = Math.floor(Math.random() * 1900) + 100;
        if(soldQty + amount > TOTAL_QTY) amount = TOTAL_QTY - soldQty;

        const oid = "MC" + Date.now() + Math.floor(Math.random() * 100);
        const time = new Date().toLocaleTimeString();

        // Add to UI
        const html = `
            <div class="sub-order-card">
                <div class="flex justify-between items-center mb-3">
                    <span class="badge-sell">Sell</span>
                    <span class="status-wait">Awaiting payment 14:59</span>
                </div>
                <div class="d-row border-none p-0 mb-1">
                    <span class="text-gray-400 font-bold text-[12px]">Amount</span>
                    <span class="font-bold text-slate-800">₹${amount}.00</span>
                </div>
                <div class="d-row border-none p-0 mb-1">
                    <span class="text-gray-400 font-bold text-[12px]">Time</span>
                    <span class="mono text-gray-500 text-[11px]">${time}</span>
                </div>
                <div class="d-row border-none p-0">
                    <span class="text-gray-400 font-bold text-[12px]">Order No</span>
                    <span class="mono text-gray-500 text-[11px]">${oid}</span>
                </div>
            </div>
        `;
        
        document.getElementById('sub-orders-list').insertAdjacentHTML('afterbegin', html);

        // Update Stats
        soldQty += amount;
        document.getElementById('sold-qty').innerText = soldQty + " ARB";
        document.getElementById('rem-qty').innerText = (TOTAL_QTY - soldQty) + " ARB";

        // 3. SAVE TO DATABASE (AJAX)
        // This ensures orders show up in Order History & Sell Page
        saveOrderToServer(oid, amount);

        // Schedule next match
        if(soldQty < TOTAL_QTY) {
            setTimeout(generateSubOrder, Math.random() * 5000 + 3000); // 3-8 seconds delay
        }
    }

    function saveOrderToServer(oid, amount) {
        const fd = new FormData();
        fd.append('action', 'save_order');
        fd.append('oid', oid);
        fd.append('amount', amount);

        fetch('sell_timmer.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'ok') {
                console.log('Order saved to history');
            }
        });
    }

    // Start Simulation after 2 seconds
    setTimeout(generateSubOrder, 2000);

</script>

</body>
</html>
