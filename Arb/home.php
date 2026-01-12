<?php
session_start();

if (!isset($_SESSION['user_phone'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Wallet - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #fff; touch-action: pan-y; overflow-x: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Premium Slider Animation */
        .slider-wrapper { display: flex; transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1); width: 100%; height: 100%; }
        .banner-slide { min-width: 100%; height: 100%; }
        
        /* Bottom Nav Active State */
        .tab-active { color: #facc15; border-top: 2px solid #facc15; margin-top: -2px; }
        .nav-item { transition: transform 0.2s ease; }
        .nav-item:active { transform: scale(0.9); }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden pb-24">
    
    <div class="px-5 py-5 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <h1 class="text-[24px] font-black tracking-tighter"><span class="text-[#facc15]">AR</span>Wallet</h1>
        <div class="flex gap-4 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-6 h-6 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-6 h-6">
        </div>
    </div>

    <div class="px-6 py-4">
        <p class="text-[12px] text-gray-400 font-bold uppercase tracking-widest">My total assets</p>
        <div class="flex items-end gap-1.5 mt-1">
            <span class="text-[34px] font-black leading-none text-gray-900">0.00</span>
            <span class="text-[14px] font-black text-gray-800 pb-1 uppercase">ARB</span>
        </div>
    </div>

    <div class="px-5 py-4 w-full h-[170px] relative overflow-hidden">
        <div id="slider" class="slider-wrapper">
            <div onclick="location.href='risk_notice.php'" class="banner-slide bg-gradient-to-br from-emerald-500 to-teal-700 rounded-[30px] p-6 flex items-center relative overflow-hidden shadow-lg cursor-pointer">
                <div class="relative z-10 flex items-center gap-4 w-full text-white">
                    <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-md border border-white/30">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-[20px] font-black leading-none tracking-tight">Risk Warning</h2>
                        <p class="text-[11px] opacity-90 mt-1.5 font-medium leading-tight">Trading involves capital risk. Analyze before you trade.</p>
                        <span class="text-[12px] font-bold mt-2 flex items-center gap-1">View Details <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg></span>
                    </div>
                </div>
            </div>
            <div onclick="location.href='rewards.php'" class="banner-slide bg-gradient-to-br from-indigo-500 to-blue-700 rounded-[30px] p-6 flex items-center relative overflow-hidden shadow-lg ml-2 cursor-pointer">
                <div class="relative z-10 flex items-center gap-4 w-full text-white">
                    <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-md">
                        <img src="https://img.icons8.com/fluency/48/gift.png" class="w-7 h-7">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-[20px] font-black leading-none tracking-tight">Earn Rewards</h2>
                        <p class="text-[11px] opacity-90 mt-1.5 font-medium leading-tight">Get up to +6% Bonus on every successful transaction.</p>
                        <span class="text-[12px] font-bold mt-2">Check Bonus ></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 right-10 flex gap-1.5 z-20">
            <div class="dot w-4 h-1.5 rounded-full bg-white transition-all"></div>
            <div class="dot w-1.5 h-1.5 rounded-full bg-white/40 transition-all"></div>
        </div>
    </div>

    <div class="grid grid-cols-4 px-6 py-6 gap-y-6">
        <div class="flex flex-col items-center gap-2 cursor-pointer active:scale-90 transition-transform">
            <div class="bg-yellow-50 p-3 rounded-2xl"><img src="https://img.icons8.com/fluency-systems-filled/48/facc15/download.png" class="w-6 h-6"></div>
            <span class="text-[12px] font-bold text-gray-700">Buy rules</span>
        </div>
        <div class="flex flex-col items-center gap-2 cursor-pointer active:scale-90 transition-transform">
            <div class="bg-yellow-50 p-3 rounded-2xl"><img src="https://img.icons8.com/fluency-systems-filled/48/facc15/upload.png" class="w-6 h-6"></div>
            <span class="text-[12px] font-bold text-gray-700">Sell rules</span>
        </div>
        <div onclick="location.href='chat.php'" class="flex flex-col items-center gap-2 cursor-pointer active:scale-90 transition-transform">
            <div class="bg-yellow-50 p-3 rounded-2xl"><img src="https://img.icons8.com/material-rounded/48/facc15/headset.png" class="w-6 h-6"></div>
            <span class="text-[12px] font-bold text-gray-700">Help Center</span>
        </div>
        <div class="flex flex-col items-center gap-2 cursor-pointer active:scale-90 transition-transform">
            <div class="bg-yellow-50 p-3 rounded-2xl"><img src="https://img.icons8.com/material-rounded/48/facc15/user.png" class="w-6 h-6"></div>
            <span class="text-[12px] font-bold text-gray-700">Account</span>
        </div>
    </div>

    <div class="px-5 py-2 flex gap-4">
        <div onclick="location.href='buy.php'" class="flex-1 bg-yellow-400/10 border-2 border-yellow-400/20 rounded-[24px] p-5 flex justify-between items-center cursor-pointer active:scale-95 transition-all">
            <div>
                <p class="text-yellow-700 font-black text-[16px]">Buy ARB</p>
                <p class="text-[10px] text-yellow-600 font-bold opacity-80">Fast Purchase</p>
            </div>
            <img src="https://img.icons8.com/material-rounded/24/facc15/download.png" class="w-6 h-6">
        </div>
        <div onclick="location.href='sell.php'" class="flex-1 bg-green-50 border border-green-100 rounded-[24px] p-5 flex justify-between items-center cursor-pointer active:scale-95 transition-all">
            <div>
                <p class="text-green-600 font-black text-[16px]">Sell ARB</p>
                <p class="text-[10px] text-green-500 font-bold">Fast Withdraw</p>
            </div>
            <img src="https://img.icons8.com/material-rounded/24/000000/upload.png" class="w-6 h-6 opacity-30">
        </div>
    </div>

    <div class="px-6 py-10 flex flex-col items-center justify-center">
        <div class="flex items-center gap-2 mb-8 w-full opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/sorting-answers.png" class="w-5 h-5">
            <span class="text-[14px] font-black text-gray-800">0 orders in progress</span>
        </div>
        <div class="flex flex-col items-center opacity-10">
            <img src="https://img.icons8.com/ios/100/000000/empty-box.png" class="w-16 h-16 mb-2">
            <p class="text-[13px] font-bold">You have no active orders yet</p>
        </div>
    </div>

    <div class="fixed bottom-0 w-full max-w-[450px] bg-white border-t border-gray-100 px-8 py-4 flex justify-between items-center z-50 shadow-2xl">
        <div class="nav-item flex flex-col items-center gap-1 tab-active">
            <img src="https://img.icons8.com/material-rounded/24/facc15/home.png" class="w-6 h-6">
            <span class="text-[11px] font-bold uppercase tracking-tight">Home</span>
        </div>
        <div onclick="location.href='order_history.php'" class="nav-item flex flex-col items-center gap-1 opacity-25 cursor-pointer">
            <img src="https://img.icons8.com/material-rounded/24/000000/clipboard.png" class="w-6 h-6">
            <span class="text-[11px] font-bold uppercase tracking-tight">Orders</span>
        </div>
        <div onclick="location.href='rewards.php'" class="nav-item flex flex-col items-center gap-1 opacity-25 cursor-pointer">
            <img src="https://img.icons8.com/material-rounded/24/000000/calendar.png" class="w-6 h-6">
            <span class="text-[11px] font-bold uppercase tracking-tight">Rewards</span>
        </div>
        <div onclick="location.href='profile.php'" class="nav-item flex flex-col items-center gap-1 opacity-25 cursor-pointer">
            <img src="https://img.icons8.com/material-rounded/24/000000/user.png" class="w-6 h-6">
            <span class="text-[11px] font-bold uppercase tracking-tight">Account</span>
        </div>
    </div>

</div>

<script>
    // Advanced Slider Logic
    let index = 0;
    const slider = document.getElementById('slider');
    const dots = document.querySelectorAll('.dot');
    const total = 2;

    function updateSlider() {
        slider.style.transform = `translateX(-${index * 102}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('w-4', i === index);
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('w-1.5', i !== index);
            dot.classList.toggle('bg-white/40', i !== index);
        });
    }

    // Auto-swipe every 4s
    setInterval(() => { index = (index + 1) % total; updateSlider(); }, 4000);

    // Precise Touch Support
    let startX = 0;
    slider.addEventListener('touchstart', e => startX = e.touches[0].clientX);
    slider.addEventListener('touchend', e => {
        let diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            index = diff > 0 ? Math.min(index + 1, total - 1) : Math.max(index - 1, 0);
            updateSlider();
        }
    });
</script>

</body>
</html>
