<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Secure Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #ffffff; color: #1f2937; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Button States Fix */
        .btn-disabled { background-color: #f3f4f6 !important; color: #9ca3af !important; cursor: not-allowed !important; pointer-events: none !important; border: none; }
        .btn-active { background-color: #22c55e !important; color: #ffffff !important; font-weight: 700 !important; cursor: pointer !important; pointer-events: auto !important; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3); }
    </style>
</head>
<body class="flex justify-center">

<?php 
    // URL se amount aur method fetch karne ka logic
    $amount = isset($_GET['amount']) ? $_GET['amount'] : "200.00"; 
    $method = isset($_GET['method']) ? $_GET['method'] : "Phonepe";
?>

<div class="w-full max-w-[450px] bg-white h-screen flex flex-col relative border-x border-gray-100 overflow-hidden shadow-sm">
    
    <div class="px-4 py-4 flex items-center justify-between border-b border-gray-50">
        <div onclick="history.back()" class="p-1 cursor-pointer opacity-60">
            <img src="https://img.icons8.com/material-rounded/24/000000/left.png" class="w-6 h-6">
        </div>
        <div class="flex items-center gap-2">
            <img src="https://img.icons8.com/ios-filled/50/22c55e/shield.png" class="w-4 h-4">
            <span class="font-bold text-gray-700">Secure Checkout</span>
        </div>
        <div class="opacity-0 w-6"></div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar p-6 space-y-8 pb-32">
        
        <div class="flex justify-between items-end">
            <div>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Payable Amount</p>
                <h1 class="text-[32px] font-extrabold text-gray-900 leading-none">₹<?= number_format($amount, 2) ?></h1>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 font-medium mb-1">Session Closes In</p>
                <span id="timer" class="text-gray-700 font-mono font-bold text-[15px]">15:00</span>
            </div>
        </div>

        <div class="text-center">
            <div onclick="window.location.href='upi://pay?pa=arwallet@upi&am=<?= $amount ?>'" class="inline-block p-6 border border-gray-100 rounded-[32px] bg-white shadow-sm cursor-pointer active:scale-95 transition-transform">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=arwallet@upi&am=<?= $amount ?>" class="w-48 h-48 rounded-lg">
                <p class="mt-4 text-[12px] font-bold text-gray-400 uppercase tracking-widest">Tap to pay with <?= $method ?></p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="space-y-2">
                <label class="text-[12px] font-bold text-gray-500 ml-1">12-Digit UTR / Reference No.</label>
                <input type="number" id="utr-input" oninput="validate()" placeholder="Enter 12 digit UTR" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl p-4 text-[15px] font-semibold outline-none focus:border-blue-500 transition-colors">
            </div>
            
            <div class="space-y-2">
                <label class="text-[12px] font-bold text-gray-500 ml-1">Payment Mobile Number</label>
                <div class="flex gap-2">
                    <input type="number" id="phone-input" oninput="validate()" placeholder="10-digit number" class="flex-1 bg-gray-50/50 border border-gray-200 rounded-xl p-4 text-[15px] font-semibold outline-none focus:border-blue-500 transition-colors">
                    <button id="verify-btn" disabled onclick="showOtpModal()" class="bg-gray-100 text-gray-400 px-5 rounded-xl font-bold text-[12px] transition-all">Verify</button>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5 border-t border-gray-50 bg-white absolute bottom-0 w-full">
        <button id="submit-btn" disabled onclick="window.location.href='history_confirm.php'" class="btn-disabled w-full py-4 rounded-xl font-bold text-[15px] uppercase tracking-wider transition-all">
            Complete Payment
        </button>
    </div>
</div>

<div id="otp-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-6 backdrop-blur-sm">
    <div class="bg-white w-full max-w-[340px] rounded-3xl p-8 text-center space-y-6">
        <div>
            <h3 class="font-bold text-gray-800 text-[18px]">Mobile Verification</h3>
            <p class="text-[12px] text-gray-400 mt-1">Enter otp code sent to your mobile</p>
        </div>
        <input type="number" id="otp-input" placeholder="0000" class="w-full text-center tracking-[12px] text-[24px] font-bold border-b-2 border-gray-100 outline-none pb-2 focus:border-blue-500">
        <button onclick="closeOtpModal()" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold">Verify & Unlock</button>
    </div>
</div>

<script>
    let isPhoneVerified = false;

    // Timer Logic
    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        const display = document.querySelector('#timer');
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;
            if (--timer < 0) { timer = 0; }
        }, 1000);
    }

    // Fixed Validation Logic
    function validate() {
        const utr = document.getElementById('utr-input').value;
        const phone = document.getElementById('phone-input').value;
        const vBtn = document.getElementById('verify-btn');
        const sBtn = document.getElementById('submit-btn');

        // Verify button enable
        if(phone.length === 10) {
            vBtn.disabled = false;
            vBtn.style.backgroundColor = "#3b82f6";
            vBtn.style.color = "#ffffff";
        } else {
            vBtn.disabled = true;
            vBtn.style.backgroundColor = "#f3f4f6";
            vBtn.style.color = "#9ca3af";
        }

        // Complete Payment button unlock logic
        if(utr.length === 12 && isPhoneVerified === true) {
            sBtn.disabled = false;
            sBtn.className = "w-full py-4 rounded-xl font-bold text-[15px] uppercase tracking-wider transition-all btn-active";
        } else {
            sBtn.disabled = true;
            sBtn.className = "w-full py-4 rounded-xl font-bold text-[15px] uppercase tracking-wider transition-all btn-disabled";
        }
    }

    function showOtpModal() { document.getElementById('otp-modal').classList.remove('hidden'); }
    
    function closeOtpModal() {
        const otpVal = document.getElementById('otp-input').value;
        if(otpVal.length >= 4) {
            isPhoneVerified = true; // Status updated
            document.getElementById('otp-modal').classList.add('hidden');
            const vBtn = document.getElementById('verify-btn');
            vBtn.innerText = "Verified ✅";
            vBtn.style.backgroundColor = "#22c55e";
            vBtn.disabled = true;
            validate(); // validation check wapas chalana
        } else {
            alert("Please enter 4 digit OTP");
        }
    }

    window.onload = function () { startTimer(60 * 15); };
</script>

</body>
</html>
