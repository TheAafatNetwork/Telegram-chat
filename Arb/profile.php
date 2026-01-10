<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Profile - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f4f6f8; overflow-x: hidden; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* --- 1. Ultra-Premium Asset Card --- */
        .asset-card {
            background: linear-gradient(160deg, #0f172a 0%, #1e293b 100%);
            border-radius: 26px; color: white;
            box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.5); /* Deep Shadow */
            position: relative; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
        }
        /* Subtle Noise/Grain Texture */
        .asset-card::before {
            content: ""; position: absolute; inset: 0; opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            pointer-events: none;
        }
        /* Gold Glow Accent */
        .asset-card::after {
            content: ''; position: absolute; top: -50%; right: -50%; width: 120%; height: 120%;
            background: radial-gradient(circle, rgba(250, 204, 21, 0.12) 0%, transparent 60%);
            filter: blur(50px); pointer-events: none;
        }

        /* --- 2. Grid System (Clean White) --- */
        .grid-container {
            background: white; border-radius: 24px; padding: 24px 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid white;
        }
        a.grid-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 10px; padding: 12px 4px; text-decoration: none;
            border-radius: 18px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        a.grid-item:active { background: #f8fafc; transform: scale(0.92); }
        a.grid-item img { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); }
        .grid-label { font-size: 11px; font-weight: 700; color: #475569; text-align: center; letter-spacing: -0.3px; margin-top: 4px; }

        /* --- 3. Reward Banner --- */
        .reward-banner {
            background: linear-gradient(to right, #fffbeb, #fff7ed);
            border: 1px solid #fef3c7; border-radius: 20px;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.1);
        }

        /* --- 4. Utilities --- */
        .font-mono-code { font-family: 'Roboto Mono', monospace; letter-spacing: -0.5px; }
        .blur-text { filter: blur(5px); opacity: 0.6; user-select: none; }
        
        /* Toast Notification */
        .toast {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9);
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); color: white; 
            padding: 14px 28px; border-radius: 30px; font-size: 13px; font-weight: 700; 
            opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            pointer-events: none; z-index: 100; box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            display: flex; align-items: center; gap: 10px;
        }
        .toast.show { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    </style>
</head>
<body class="flex justify-center">

<div class="w-full max-w-[450px] bg-[#f4f6f8] min-h-screen flex flex-col relative shadow-2xl overflow-x-hidden pb-28">
    
    <div class="px-6 py-5 flex items-center justify-between bg-[#f4f6f8]/90 backdrop-blur-lg sticky top-0 z-50">
        <h1 class="text-[24px] font-black tracking-tighter text-slate-900"><span class="text-[#facc15]">AR</span>Wallet</h1>
        <div class="flex gap-5 opacity-60">
            <img src="https://img.icons8.com/material-outlined/24/000000/bell.png" class="w-6 h-6 cursor-pointer hover:text-black transition-colors" onclick="showToast('No notifications')">
            <a href="settings.php">
                <img src="https://img.icons8.com/material-outlined/24/000000/settings.png" class="w-6 h-6 cursor-pointer hover:rotate-45 transition-transform duration-300">
            </a>
        </div>
    </div>

    <div class="px-5 space-y-6">
        
        <div class="flex items-center gap-4 py-2">
            <div class="relative group">
                <div class="w-[74px] h-[74px] rounded-full bg-white p-1 border border-slate-200 shadow-sm transition-transform group-active:scale-95">
                    <img id="avatar-img" src="https://arbpay.me/assets/ordinary-7f4166d8.png" class="w-full h-full object-cover rounded-full">
                </div>
                <div class="absolute bottom-1 right-1 w-4 h-4 bg-emerald-500 rounded-full border-[3px] border-[#f4f6f8]"></div>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 id="username" class="text-[22px] font-black text-slate-900 tracking-tight leading-tight mb-1.5">Loading...</h2>
                        <div class="inline-flex items-center gap-2 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-sm">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">UID</span>
                            <span id="uid-text" class="text-[12px] font-mono-code font-bold text-slate-700">-------</span>
                            <div onclick="copyToClipboard('uid-text')" class="cursor-pointer opacity-40 hover:opacity-100 pl-2 border-l border-slate-200 active:scale-90 transition-transform">
                                <img src="https://img.icons8.com/material-rounded/24/000000/copy.png" class="w-3.5 h-3.5">
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#0f172a] px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-md">
                        <img src="https://img.icons8.com/fluency/48/crown.png" class="w-3.5 h-3.5">
                        <span class="text-[#facc15] text-[10px] font-black tracking-wide">LV0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="asset-card p-7">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1 opacity-80 hover:opacity-100 transition-opacity cursor-pointer">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-300">Total Valuation</p>
                        <img id="eye-icon" onclick="toggleBalance()" src="https://img.icons8.com/material-outlined/24/94a3b8/visible.png" class="w-4 h-4">
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span id="balance-amount" class="text-[42px] font-black text-white tracking-tighter drop-shadow-lg">2.00</span>
                        <span class="text-[16px] font-bold text-[#facc15]">ARB</span>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 text-center">
                    <span class="block text-[10px] text-slate-300 font-bold">Today's PNL</span>
                    <span class="text-[12px] font-black text-emerald-400">+0.00%</span>
                </div>
            </div>

            <div class="bg-black/20 rounded-xl p-3.5 border border-white/5 flex items-center justify-between backdrop-blur-sm">
                <div class="flex flex-col overflow-hidden mr-3">
                    <span class="text-[9px] text-slate-400 font-bold uppercase mb-0.5">Deposit Address (TRC20)</span>
                    <span id="wallet-address" class="text-[11px] font-mono-code text-slate-200 truncate opacity-90 tracking-tight">Loading...</span>
                </div>
                <div onclick="copyToClipboard('wallet-address')" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center cursor-pointer hover:bg-white/20 active:scale-90 transition-all shrink-0 border border-white/5">
                    <img src="https://img.icons8.com/material-rounded/24/ffffff/copy.png" class="w-4 h-4">
                </div>
            </div>
        </div>

        <a href="rewards.php" class="reward-banner p-4 flex justify-between items-center cursor-pointer active:scale-[0.98] transition-all group">
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-2.5 rounded-full shadow-lg shadow-orange-200 text-white">
                    <img src="https://img.icons8.com/material-rounded/24/ffffff/star.png" class="w-5 h-5">
                </div>
                <div>
                    <h4 class="text-[15px] font-black text-slate-800">VIP Reward Center</h4>
                    <p class="text-[11px] text-slate-500 font-bold mt-0.5">Claim daily bonus & invite rewards</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-400 group-hover:text-yellow-500 transition-colors">
                <img src="https://img.icons8.com/material-rounded/24/cbd5e1/chevron-right.png" class="w-5 h-5">
            </div>
        </a>

        <div class="grid-container">
            <div class="grid grid-cols-3 gap-y-4">
                <a href="kyc.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/identification-documents.png" class="w-10 h-10">
                    <span class="grid-label">Real-name</span>
                </a>
                <a href="collection.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/wallet.png" class="w-10 h-10">
                    <span class="grid-label">Collection</span>
                </a>
                <a href="password.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/password.png" class="w-10 h-10">
                    <span class="grid-label">Security</span>
                </a>
                
                <a href="order_history.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/order-history.png" class="w-10 h-10">
                    <span class="grid-label">Transactions</span>
                </a>
                <a href="appeals.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/law.png" class="w-10 h-10">
                    <span class="grid-label">Appeals</span>
                </a>
                <a href="user_guidelines.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/book.png" class="w-10 h-10">
                    <span class="grid-label">User Guide</span>
                </a>
                
                <a href="buy_tutorial.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/download.png" class="w-10 h-10">
                    <span class="grid-label">Buy Tutorial</span>
                </a>
                <a href="sell_tutorial.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/upload.png" class="w-10 h-10">
                    <span class="grid-label">Sell Tutorial</span>
                </a>
                <a href="settings.php" class="grid-item">
                    <img src="https://img.icons8.com/fluency/48/settings.png" class="w-10 h-10">
                    <span class="grid-label">Settings</span>
                </a>
            </div>
        </div>
        
        <div class="text-center pt-4 pb-2 opacity-30">
            <p class="text-[10px] font-bold text-slate-400">AR Wallet v2.0.1 • Secured by 256-bit Encryption</p>
        </div>
    </div>

    <div class="fixed bottom-0 w-full max-w-[450px] bg-white border-t border-gray-100 px-8 py-4 flex justify-between items-center z-50 pb-safe">
        <a href="home.php" class="flex flex-col items-center gap-1 opacity-30 active:scale-90 transition-all"><img src="https://img.icons8.com/material-rounded/24/000000/home.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Home</span></a>
        <a href="order_history.php" class="flex flex-col items-center gap-1 opacity-30 active:scale-90 transition-all"><img src="https://img.icons8.com/material-rounded/24/000000/clipboard.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Order</span></a>
        <a href="rewards.php" class="flex flex-col items-center gap-1 opacity-30 active:scale-90 transition-all"><img src="https://img.icons8.com/material-rounded/24/000000/calendar.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Rewards</span></a>
        <a href="profile.php" class="flex flex-col items-center gap-1 text-[#facc15]"><img src="https://img.icons8.com/material-rounded/24/facc15/user.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">My</span></a>
    </div>

    <div id="toast" class="toast">
        <img src="https://img.icons8.com/material-rounded/24/ffffff/checked.png" class="w-5 h-5">
        <span id="toast-msg">Copied successfully</span>
    </div>

</div>

<script>
    // --- 1. PERSISTENT IDENTITY SYSTEM --- //
    // Data Settings.php ke saath sync rahega
    
    function initProfile() {
        let storedData = localStorage.getItem('ar_user_data');
        let currentUser;

        if (storedData) {
            currentUser = JSON.parse(storedData);
        } else {
            // New User Generator
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let name = 'S';
            for(let i=0; i<7; i++) name += chars.charAt(Math.floor(Math.random() * chars.length));
            
            currentUser = {
                uid: Math.floor(10000000 + Math.random() * 90000000),
                username: name,
                avatar: 'https://arbpay.me/assets/ordinary-7f4166d8.png',
                wallet: '1Agp' + Array(28).fill(0).map(()=>chars.charAt(Math.floor(Math.random()*chars.length))).join('') + '...'
            };
            localStorage.setItem('ar_user_data', JSON.stringify(currentUser));
        }

        // Apply Data
        document.getElementById('username').innerText = currentUser.username;
        document.getElementById('uid-text').innerText = currentUser.uid;
        document.getElementById('avatar-img').src = currentUser.avatar;
        document.getElementById('wallet-address').innerText = currentUser.wallet;
    }

    // --- 2. PRIVACY LOGIC (Eye Toggle) --- //
    let isHidden = false;
    function toggleBalance() {
        const balance = document.getElementById('balance-amount');
        const icon = document.getElementById('eye-icon');
        isHidden = !isHidden;
        
        if (isHidden) {
            balance.innerText = '****';
            balance.classList.add('blur-text');
            icon.src = "https://img.icons8.com/material-outlined/24/94a3b8/invisible.png";
        } else {
            balance.innerText = '2.00';
            balance.classList.remove('blur-text');
            icon.src = "https://img.icons8.com/material-outlined/24/94a3b8/visible.png";
        }
        
        if(navigator.vibrate) navigator.vibrate(10);
    }

    // --- 3. CLIPBOARD LOGIC --- //
    function copyToClipboard(id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text);
        showToast(id.includes('uid') ? 'UID Copied' : 'Address Copied');
        if(navigator.vibrate) navigator.vibrate(40);
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').innerText = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    }

    window.onload = initProfile;
</script>

</body>
</html>
