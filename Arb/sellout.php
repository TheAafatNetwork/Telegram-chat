<?php
session_start();
require_once 'config.php';

// 1. Security Check
if (!isset($_SESSION['user_phone'])) { header("Location: index.php"); exit(); }

$phone = $_SESSION['user_phone'];
$file = "data/" . $phone . ".json";
$userData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// 2. Fetch User Data
$balance = $userData['balance'] ?? 0.00;
$payment_methods = $userData['payment_methods'] ?? [];
$upi_id = !empty($payment_methods) ? $payment_methods[0]['upi_id'] : 'Select Method >';
$hasUPI = !empty($payment_methods);

// 3. Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = floatval($_POST['amount']);
    
    // Server-Side Validation
    if(!$hasUPI) { $error = "Please add a payment method first."; }
    elseif($qty < 100) { $error = "Minimum limit is 100 ARB."; }
    elseif($qty > $balance) { $error = "Insufficient balance."; }
    else {
        // Success: Data session me save karke Timer page par bhejo
        $_SESSION['sell_quantity'] = $qty;
        header("Location: sell_timmer.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sell ARB - Professional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        
        /* Glass Input Box */
        .input-box {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 20px; position: relative; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-box:focus-within {
            border-color: #facc15; box-shadow: 0 4px 20px rgba(250, 204, 21, 0.15); transform: translateY(-2px);
        }
        
        .huge-input {
            font-size: 32px; font-weight: 800; color: #1e293b; width: 100%; outline: none; background: transparent;
            font-family: 'Roboto Mono', monospace; letter-spacing: -1px;
        }
        .huge-input::placeholder { color: #cbd5e1; }

        /* Percentage Pills */
        .percent-pill {
            background: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700;
            padding: 6px 12px; border-radius: 8px; cursor: pointer; transition: all 0.2s;
        }
        .percent-pill:active, .percent-pill.active {
            background: #fef08a; color: #854d0e; transform: scale(0.95);
        }

        /* Payment Method Card */
        .pay-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; active: scale(0.98);
        }

        /* Submit Button */
        .btn-sell {
            background: #facc15; color: #1e293b; font-size: 16px; font-weight: 800;
            width: 100%; py-4; border-radius: 16px;
            box-shadow: 0 4px 15px rgba(250, 204, 21, 0.3);
            transition: all 0.2s;
        }
        .btn-sell:active { transform: scale(0.98); box-shadow: none; }
        .btn-sell:disabled { background: #e2e8f0; color: #94a3b8; box-shadow: none; cursor: not-allowed; }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-2xl overflow-x-hidden">
    
    <div class="px-5 py-4 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur-md z-50 border-b border-gray-50">
        <div onclick="history.back()" class="p-2 -ml-2 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
            <img src="https://img.icons8.com/material-rounded/24/1e293b/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[17px] font-black text-slate-800 tracking-tight">Sell ARB</h1>
        <div class="w-6"></div> </div>

    <form method="POST" class="p-6 flex flex-col h-full" onsubmit="return validateForm()">
        
        <div class="flex justify-between items-center mb-6 px-1">
            <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Available Balance</span>
            <div class="flex items-center gap-1.5">
                <img src="https://img.icons8.com/material-rounded/24/facc15/wallet.png" class="w-4 h-4">
                <span class="text-[14px] font-black text-slate-800"><?php echo number_format($balance, 2); ?> ARB</span>
            </div>
        </div>

        <div class="input-box mb-6">
            <div class="flex justify-between items-baseline">
                <input type="number" name="amount" id="amount" class="huge-input" placeholder="0.00" step="any" inputmode="decimal">
                <span class="text-[16px] font-black text-slate-400 ml-2">ARB</span>
            </div>
            <div class="flex justify-between items-center mt-3 border-t border-gray-100 pt-3">
                <p id="inrValue" class="text-[13px] font-bold text-gray-400">≈ ₹0.00</p>
                <div class="flex gap-2">
                    <div onclick="setPercent(0.25)" class="percent-pill">25%</div>
                    <div onclick="setPercent(0.50)" class="percent-pill">50%</div>
                    <div onclick="setPercent(1.00)" class="percent-pill">MAX</div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide mb-3 px-1">Receive Account</p>
            <div onclick="location.href='collection.php'" class="pay-card group">
                <div class="flex items-center gap-3">
                    <div class="bg-yellow-50 p-2.5 rounded-xl text-yellow-600">
                        <img src="https://img.icons8.com/ios-filled/50/eab308/qr-code.png" class="w-5 h-5">
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-800">UPI Transfer</p>
                        <p class="text-[11px] font-bold text-gray-400 font-mono"><?php echo $upi_id; ?></p>
                    </div>
                </div>
                <img src="https://img.icons8.com/material-rounded/24/cbd5e1/chevron-right.png" class="w-5 h-5 group-active:translate-x-1 transition-transform">
            </div>
        </div>

        <div class="bg-gray-50 rounded-2xl p-5 mb-8 space-y-3 border border-gray-100">
            <div class="flex justify-between text-[12px]">
                <span class="text-gray-500 font-medium">Price</span>
                <span class="font-bold text-slate-800">1 ARB ≈ ₹1.00</span>
            </div>
            <div class="flex justify-between text-[12px]">
                <span class="text-gray-500 font-medium">Fee (0%)</span>
                <span class="font-bold text-green-500">0.00 ARB</span>
            </div>
            <div class="h-px bg-gray-200 my-1"></div>
            <div class="flex justify-between items-center">
                <span class="text-[12px] font-bold text-gray-500">Total Receive</span>
                <span id="totalRec" class="text-[18px] font-black text-slate-800">₹0.00</span>
            </div>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-500 text-[12px] font-bold p-3 rounded-xl mb-4 text-center border border-red-100">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="mt-auto">
            <button type="submit" id="sellBtn" disabled class="w-full bg-[#facc15] text-slate-900 font-black py-4 rounded-xl shadow-lg shadow-yellow-100 active:scale-[0.98] transition-all flex items-center justify-center gap-2 opacity-50 cursor-not-allowed">
                <span>Sell ARB</span>
                <img src="https://img.icons8.com/material-rounded/24/1e293b/arrow.png" class="w-4 h-4 rotate-180">
            </button>
            <p class="text-center text-[10px] text-gray-400 font-medium mt-4">
                Estimated arrival: <span class="text-green-500 font-bold">1-3 Hours</span>
            </p>
        </div>

    </form>

</div>

<script>
    // Configuration
    const balance = <?php echo $balance; ?>;
    const hasUPI = <?php echo $hasUPI ? 'true' : 'false'; ?>;
    const input = document.getElementById('amount');
    const inrDisplay = document.getElementById('inrValue');
    const totalRec = document.getElementById('totalRec');
    const btn = document.getElementById('sellBtn');

    // 1. Live Calculation Logic
    input.addEventListener('input', function() {
        let val = parseFloat(this.value);
        
        if (isNaN(val) || val <= 0) {
            resetUI();
            return;
        }

        // Logic: 1 ARB = 1 INR (Demo Rate)
        let inr = val * 1; 
        
        inrDisplay.innerText = "≈ ₹" + inr.toLocaleString('en-IN', {minimumFractionDigits: 2});
        totalRec.innerText = "₹" + inr.toLocaleString('en-IN', {minimumFractionDigits: 2});
        
        // Button Validation
        validateInput(val);
    });

    // 2. Percentage Logic
    function setPercent(pct) {
        if(navigator.vibrate) navigator.vibrate(15);
        let val = (balance * pct).toFixed(2);
        input.value = val;
        // Trigger input event manually to update UI
        input.dispatchEvent(new Event('input'));
    }

    // 3. Validation Logic
    function validateInput(val) {
        let isValid = true;

        if (val < 100) isValid = false; // Min limit
        if (val > balance) isValid = false; // Max limit
        if (!hasUPI) isValid = false; // UPI check

        if (isValid) {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // 4. Form Submit Guard
    function validateForm() {
        if(!hasUPI) {
            alert("Please add a Payment Method first!");
            location.href = 'collection.php';
            return false;
        }
        return true;
    }

    function resetUI() {
        inrDisplay.innerText = "≈ ₹0.00";
        totalRec.innerText = "₹0.00";
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
</script>

</body>
</html>
