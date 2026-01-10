<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Rewards Center - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #fff; overflow-x: hidden; touch-action: pan-y; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* --- Glowing Gold Card --- */
        .gold-card {
            background: radial-gradient(circle at 10% 20%, #fffbeb 0%, #fcd34d 90%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative; z-index: 1;
        }
        /* Glowing Pulse Animation */
        @keyframes gold-pulse {
            0% { box-shadow: 0 10px 30px -5px rgba(251, 191, 36, 0.4); }
            50% { box-shadow: 0 10px 50px -5px rgba(251, 191, 36, 0.8), 0 0 20px rgba(250, 204, 21, 0.4); }
            100% { box-shadow: 0 10px 30px -5px rgba(251, 191, 36, 0.4); }
        }
        .glow-effect { animation: gold-pulse 3s infinite ease-in-out; }

        /* Task Card */
        .task-card {
            background: #fff; border: 1px solid #f3f4f6; border-radius: 20px; padding: 16px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.2s;
        }
        .task-card:active { transform: scale(0.98); }

        /* Smooth Progress Bar */
        .progress-track { background: rgba(0,0,0,0.06); height: 6px; border-radius: 10px; overflow: hidden; width: 100%; }
        .progress-fill { background: #111827; height: 100%; border-radius: 10px; width: 0%; transition: width 1.5s cubic-bezier(0.25, 1, 0.5, 1); }

        /* Tab Switcher */
        .tab-pill {
            position: relative; background: #f8fafc; border-radius: 16px; padding: 4px; display: flex;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.04);
        }
        .tab-item { flex: 1; text-align: center; padding: 12px; z-index: 10; font-weight: 800; font-size: 14px; color: #94a3b8; transition: 0.3s; cursor: pointer; }
        .tab-item.active { color: #0f172a; }
        .tab-bg {
            position: absolute; height: 82%; top: 9%; width: 48%; background: #fff;
            border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Invite Button Glow */
        @keyframes btn-glow {
            0% { box-shadow: 0 4px 15px rgba(250, 204, 21, 0.4); }
            50% { box-shadow: 0 4px 25px rgba(250, 204, 21, 0.8); }
            100% { box-shadow: 0 4px 15px rgba(250, 204, 21, 0.4); }
        }
        .btn-glowing { animation: btn-glow 2s infinite; }

        /* Level Icons */
        .level-icon { filter: grayscale(1); opacity: 0.5; transition: 0.3s; transform: scale(0.9); }
        .level-icon.active { filter: grayscale(0); opacity: 1; transform: scale(1.15); filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1)); }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden pb-24">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white/90 backdrop-blur-md sticky top-0 z-50">
        <h1 class="text-[22px] font-black tracking-tighter"><span class="text-[#facc15]">AR</span>Wallet</h1>
        <div onclick="location.href='rules.php'" class="bg-gray-50 border border-gray-100 px-3 py-1.5 rounded-full text-[11px] font-bold text-gray-500 cursor-pointer active:scale-95 transition-all flex items-center gap-1">
            <img src="https://img.icons8.com/material-outlined/24/6b7280/info.png" class="w-3 h-3"> VIP Rules
        </div>
    </div>

    <div class="px-5 pt-2 pb-6">
        <div class="flex items-center gap-2 mb-4 px-1">
            <img src="https://img.icons8.com/fluency/48/star.png" class="w-5 h-5">
            <span class="text-[13px] font-bold text-gray-600">Rewards Claimed</span>
            <span class="text-[18px] font-black text-gray-900 ml-auto">0.00 ARB</span>
        </div>

        <div id="hero-card" class="gold-card glow-effect p-6 rounded-[32px] relative overflow-hidden transition-all duration-500">
            <img id="bg-watermark" src="https://arbpay.me/assets/ordinary-7f4166d8.png" class="absolute -right-5 -bottom-5 w-48 h-48 opacity-10 rotate-12 pointer-events-none">
            
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <span class="bg-white/40 backdrop-blur-md px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase text-yellow-900 mb-2 inline-block shadow-sm">Current Level</span>
                    <h2 id="level-title" class="text-[42px] font-black text-gray-900 leading-none mb-1">LV0</h2>
                    <p id="level-desc" class="text-[11px] font-bold text-gray-700">Ordinary Member</p>
                </div>
                <img id="level-img" src="https://arbpay.me/assets/ordinary-7f4166d8.png" class="w-24 h-24 object-contain animate-float drop-shadow-lg">
            </div>

            <div class="mt-6 relative z-10">
                <div class="flex justify-between text-[10px] font-black text-gray-800 mb-1.5">
                    <span>Next Level Progress</span>
                    <span id="progress-text">0/5000</span>
                </div>
                <div class="progress-track"><div id="progress-fill" class="progress-fill"></div></div>
                <p class="text-[10px] text-gray-600 font-bold mt-2">Trade <span class="text-black">5000 ARB</span> to reach Bronze!</p>
            </div>
        </div>
    </div>

    <div class="pl-6 mb-6">
        <p class="text-[11px] font-black text-gray-400 mb-3 uppercase tracking-wide">Privilege Tiers</p>
        <div class="flex items-center gap-5 overflow-x-auto no-scrollbar pr-6 pb-4">
            <div onclick="setLevel(0)" class="level-icon active flex flex-col items-center min-w-[60px]" id="lvl-0">
                <img src="https://arbpay.me/assets/ordinary-7f4166d8.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV0</span>
            </div>
            <div onclick="setLevel(1)" class="level-icon flex flex-col items-center min-w-[60px]" id="lvl-1">
                <img src="https://arbpay.me/assets/bronze-ec4729f3.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV1</span>
            </div>
            <div onclick="setLevel(2)" class="level-icon flex flex-col items-center min-w-[60px]" id="lvl-2">
                <img src="https://arbpay.me/assets/silver-f265ca44.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV2</span>
            </div>
            <div onclick="setLevel(3)" class="level-icon flex flex-col items-center min-w-[60px]" id="lvl-3">
                <img src="https://arbpay.me/assets/gold-c4109368.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV3</span>
            </div>
            <div onclick="setLevel(4)" class="level-icon flex flex-col items-center min-w-[60px]" id="lvl-4">
                <img src="https://arbpay.me/assets/platinum-ab226ef0.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV4</span>
            </div>
            <div onclick="setLevel(5)" class="level-icon flex flex-col items-center min-w-[60px]" id="lvl-5">
                <img src="https://arbpay.me/assets/diamond-6a0db287.png" class="w-14 h-14 object-contain mb-2"><span class="text-[10px] font-black">LV5</span>
            </div>
        </div>
    </div>

    <div class="px-6 mb-6">
        <div class="tab-pill">
            <div id="tab-bg" class="tab-bg" style="transform: translateX(4%)"></div>
            <div onclick="switchTab('task')" class="tab-item active">Daily Tasks</div>
            <div onclick="switchTab('invite')" class="tab-item">Invite</div>
        </div>
    </div>

    <div id="content-area" class="px-5 space-y-4 animate-fadeIn pb-10">
        <div class="task-list space-y-4">
            
            <div class="task-card">
                <div class="flex gap-4 items-center">
                    <div class="bg-yellow-50 p-3 rounded-full"><img src="https://img.icons8.com/fluency/48/shopping-cart-loaded.png" class="w-6 h-6"></div>
                    <div>
                        <h4 class="text-[14px] font-black text-gray-900">First Deposit</h4>
                        <p class="text-[11px] text-gray-500 font-bold">Buy at least 50 ARB</p>
                    </div>
                </div>
                <button onclick="location.href='buy.php'" class="bg-[#1e293b] text-white px-5 py-2 rounded-xl text-[12px] font-bold shadow-lg active:scale-95 transition-all">Go</button>
            </div>

            <div class="task-card">
                <div class="flex gap-4 items-center">
                    <div class="bg-blue-50 p-3 rounded-full"><img src="https://img.icons8.com/fluency/48/bar-chart.png" class="w-6 h-6"></div>
                    <div>
                        <h4 class="text-[14px] font-black text-gray-900">Trading Pro</h4>
                        <p class="text-[11px] text-gray-500 font-bold">Reach <span class="text-blue-600">2000 ARB</span> Volume</p>
                        <div class="w-24 bg-gray-100 h-1.5 rounded-full mt-2"><div class="bg-blue-500 h-1.5 rounded-full" style="width: 15%"></div></div>
                    </div>
                </div>
                <button class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl text-[12px] font-bold cursor-not-allowed">0/2000</button>
            </div>

            <div class="task-card">
                <div class="flex gap-4 items-center">
                    <div class="bg-green-50 p-3 rounded-full"><img src="https://img.icons8.com/fluency/48/add-user-group-man-man.png" class="w-6 h-6"></div>
                    <div>
                        <h4 class="text-[14px] font-black text-gray-900">Refer a Friend</h4>
                        <p class="text-[11px] text-gray-500 font-bold">Get 5% on First Deposit</p>
                    </div>
                </div>
                <button onclick="switchTab('invite')" class="bg-[#facc15] text-gray-900 px-4 py-2 rounded-xl text-[12px] font-black shadow-md active:scale-95 transition-all">Invite</button>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 w-full max-w-[450px] bg-white border-t border-gray-100 px-8 py-4 flex justify-between items-center z-50">
        <div onclick="location.href='home.php'" class="flex flex-col items-center gap-1 opacity-25 cursor-pointer"><img src="https://img.icons8.com/material-rounded/24/000000/home.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Home</span></div>
        <div onclick="location.href='order_history.php'" class="flex flex-col items-center gap-1 opacity-25 cursor-pointer"><img src="https://img.icons8.com/material-rounded/24/000000/clipboard.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Order</span></div>
        <div class="flex flex-col items-center gap-1 text-[#facc15]"><img src="https://img.icons8.com/material-rounded/24/facc15/calendar.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">Rewards</span></div>
        <div onclick="location.href='profile.php'" class="flex flex-col items-center gap-1 opacity-25 cursor-pointer"><img src="https://img.icons8.com/material-rounded/24/000000/user.png" class="w-6 h-6"><span class="text-[10px] font-bold uppercase">My</span></div>
    </div>
</div>

<script>
    // Data Logic
    const levelData = [
        { name: 'LV0', desc: 'Ordinary Member', img: 'https://arbpay.me/assets/ordinary-7f4166d8.png' },
        { name: 'LV1', desc: 'Bronze Member', img: 'https://arbpay.me/assets/bronze-ec4729f3.png' },
        { name: 'LV2', desc: 'Silver Member', img: 'https://arbpay.me/assets/silver-f265ca44.png' },
        { name: 'LV3', desc: 'Gold Member', img: 'https://arbpay.me/assets/gold-c4109368.png' },
        { name: 'LV4', desc: 'Platinum Member', img: 'https://arbpay.me/assets/platinum-ab226ef0.png' },
        { name: 'LV5', desc: 'Diamond Member', img: 'https://arbpay.me/assets/diamond-6a0db287.png' }
    ];

    function setLevel(idx) {
        document.getElementById('level-title').innerText = levelData[idx].name;
        document.getElementById('level-desc').innerText = levelData[idx].desc;
        
        const img = document.getElementById('level-img');
        const bgImg = document.getElementById('bg-watermark');
        
        // Update Images
        img.src = levelData[idx].img;
        bgImg.src = levelData[idx].img;
        
        // Progress Logic
        document.getElementById('progress-fill').style.width = ((idx+1) * 15) + '%';
        document.getElementById('progress-text').innerText = (idx * 1000) + '/5000';

        // Badge Active State
        document.querySelectorAll('.level-icon').forEach(el => el.classList.remove('active'));
        document.getElementById('lvl-'+idx).classList.add('active');
        
        if(navigator.vibrate) navigator.vibrate(10);
    }

    function switchTab(type) {
        const bg = document.getElementById('tab-bg');
        const container = document.getElementById('content-area');
        const items = document.querySelectorAll('.tab-item');

        if(type === 'task') {
            bg.style.transform = "translateX(4%)";
            items[0].classList.add('active'); items[1].classList.remove('active');
            location.reload(); 
        } else {
            bg.style.transform = "translateX(108%)";
            items[1].classList.add('active'); items[0].classList.remove('active');
            
            // Invite Section with 5% Logic
            container.innerHTML = `
                <div class="animate-fadeIn space-y-6">
                     <div class="relative rounded-[24px] overflow-hidden shadow-lg group">
                        <img src="https://arbpay.me/assets/invite_banner.jpg" class="w-full h-[150px] object-cover" onerror="this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR6A7p8P8N4N5L6F_D9A9p9M0O7K0R0T0R0&s'">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex items-center px-6">
                            <div>
                                <h3 class="text-white text-[20px] font-black leading-tight">Friend's First Deposit</h3>
                                <p class="text-[#facc15] text-[14px] font-black mt-1">You Get 5% Commission</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-[24px] p-6 text-center shadow-xl">
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider mb-2">My Referral Code</p>
                        <div onclick="alert('Copied!')" class="bg-gray-800 rounded-xl p-3 flex justify-center items-center gap-3 cursor-pointer active:scale-95 transition-all">
                            <span class="text-[24px] font-mono font-black text-[#facc15] tracking-widest">AR8829</span>
                            <img src="https://img.icons8.com/material-rounded/24/ffffff/copy.png" class="w-4 h-4 opacity-50">
                        </div>
                        <button class="w-full bg-[#facc15] mt-5 py-3 rounded-xl font-black text-gray-900 btn-glowing">Invite & Earn 5%</button>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-[28px] p-6 shadow-sm space-y-6">
                        <h3 class="text-[16px] font-black text-gray-900">How it works?</h3>
                        <div class="space-y-5">
                            <div class="flex gap-4">
                                <div class="bg-yellow-50 p-2.5 rounded-xl"><img src="https://img.icons8.com/fluency/48/share.png" class="w-6 h-6"></div>
                                <div>
                                    <h4 class="text-[13px] font-black text-gray-800">1. Share Code</h4>
                                    <p class="text-[11px] text-gray-500 font-medium">Friend registers using your link.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="bg-green-50 p-2.5 rounded-xl"><img src="https://img.icons8.com/fluency/48/money-bag.png" class="w-6 h-6"></div>
                                <div>
                                    <h4 class="text-[13px] font-black text-gray-800">2. Friend Deposits</h4>
                                    <p class="text-[11px] text-gray-500 font-medium">They complete their first deposit.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="bg-blue-50 p-2.5 rounded-xl"><img src="https://img.icons8.com/fluency/48/confetti.png" class="w-6 h-6"></div>
                                <div>
                                    <h4 class="text-[13px] font-black text-gray-800">3. You Earn 5%</h4>
                                    <p class="text-[11px] text-gray-500 font-medium">Commission added to your wallet instantly.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }
    }

    // Init
    setTimeout(() => { document.getElementById('progress-fill').style.width = '5%'; }, 500);
</script>

</body>
</html>
