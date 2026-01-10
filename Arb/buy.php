<?php
$extra_images = [
    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfkW_S5Ix8u_U5bvXC478VRWfYPu0fSfiCLFraIQCksQMh32PFU3lq8no&s=10",
    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSKy4JnTV9kODvaEHqlCbCAr3Q6-lBmQkt3VoZTJSnt36zFs45r6iUmBoVk&s=10",
    "https://5.imimg.com/data5/SELLER/Default/2023/5/311924711/FA/JZ/CU/141123255/bbt-jpg-500x500.jpg",
    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVqRBvWVkDoZV48eBdZh4xLIClPxH79gIMc0PxH1UZYQ&s=10"
];
$main_logo = "https://arbpay.me/assets/0-e04ebcda.jpg";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Trading Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #fff; overflow: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .active-tab-line { height: 3px; width: 24px; background: #000; border-radius: 10px; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); }
        .nav-tab { cursor: pointer; position: relative; padding: 12px 0; color: #9CA3AF; flex: 1; text-align: center; font-size: 14px; font-weight: 600; }
        .nav-tab.active { color: #000; }
        
        /* Layout Fixes */
        .main-scroll-area { height: calc(100vh - 180px); overflow-y: auto; }
        input:focus { border-color: #FFD700; outline: none; }
        .btn-active:active { transform: scale(0.97); transition: 0.1s; }
        
        /* Input Styling */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="flex justify-center">

<div class="w-full max-w-[450px] bg-white h-screen flex flex-col relative border-x border-gray-100 shadow-xl">
    <div class="bg-white px-4 py-4 flex items-center justify-between border-b border-gray-50">
        <div class="p-1 cursor-pointer"><img src="https://img.icons8.com/material-rounded/24/000000/left.png" class="w-5 h-5 opacity-60"></div>
        <h1 class="text-[18px] font-bold flex-1 text-center ml-6">Buy</h1>
        <div class="flex gap-4 opacity-40">
            <img id="refresh-icon" src="https://img.icons8.com/ios/50/refresh--v1.png" class="w-5 h-5 cursor-pointer">
            <img src="https://img.icons8.com/ios/50/multiply.png" class="w-5 h-5 cursor-pointer">
        </div>
    </div>

    <div class="bg-white shadow-sm">
        <div class="flex items-center justify-center gap-3 py-2 text-[11px] text-gray-400 font-medium">
            <span>1 INR = 1 ARB</span><span>1 U = 97.00 INR</span><span class="bg-orange-500 text-white px-1.5 py-0.5 rounded text-[9px] font-bold">+5%</span>
        </div>
        <div class="flex border-b border-gray-50">
            <div onclick="switchTab('Quick', this)" class="nav-tab">Quick</div>
            <div onclick="switchTab('UPI', this)" class="nav-tab">UPI</div>
            <div onclick="switchTab('OTP-UPI', this)" class="nav-tab active">OTP-UPI<div class="active-tab-line"></div></div>
            <div onclick="switchTab('BANK', this)" class="nav-tab">BANK</div>
            <div onclick="switchTab('USDT', this)" class="nav-tab">USDT</div>
        </div>
    </div>

    <div id="content-area" class="flex-1 main-scroll-area no-scrollbar">
        
        <div id="list-view" class="p-4 space-y-4">
            <div class="bg-orange-50 border border-orange-100 p-3 rounded-2xl">
                <p class="text-orange-500 text-[12px] font-bold text-center italic">Tips: Requires KYC connection to purchase.</p>
            </div>
            <div class="flex gap-3 px-1">
                <button onclick="setFilter('default', this)" class="filter-btn active bg-gray-100 px-6 py-2 rounded-full text-[13px] font-bold">Default</button>
                <button onclick="setFilter('large', this)" class="filter-btn text-gray-400 px-6 py-2 rounded-full text-[13px]">Large</button>
                <button onclick="setFilter('small', this)" class="filter-btn text-gray-400 px-6 py-2 rounded-full text-[13px]">Small</button>
            </div>
            <div id="offers-container" class="space-y-3 pb-10">
                <?php for($i=0; $i<15; $i++): ?>
                <div class="offer-card bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <img src="<?= $main_logo ?>" class="w-10 h-10 rounded-full profile-dp border-2 border-white shadow-sm">
                        <div>
                            <div class="price-text text-[18px] font-black tracking-tight text-gray-800">₹0</div>
                            <div class="flex gap-1.5 mt-1 opacity-70">
                                <img src="https://img.icons8.com/color/48/paytm.png" class="w-3.5 h-3.5">
                                <img src="https://img.icons8.com/color/48/google-pay.png" class="w-3.5 h-3.5">
                                <img src="https://img.icons8.com/color/48/phonepe.png" class="w-3.5 h-3.5">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 items-end">
                        <div class="reward-text bg-orange-500 text-white text-[10px] font-black px-2 py-0.5 rounded-lg">🎁 Reward +0</div>
                        <div class="limit-text text-gray-400 text-[9px] font-medium tracking-tighter">Limit 0-0</div>
                    </div>
                    <button onclick="handlePurchase(this)" class="bg-yellow-400 text-black px-7 py-2.5 rounded-full font-bold text-[13px] btn-active shadow-sm">Buy</button>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <div id="quick-view" class="hidden p-5 flex flex-col space-y-6">
            <div class="space-y-2">
                <p class="font-bold text-gray-700 text-[13px] ml-1">You will pay</p>
                <div class="border-2 border-gray-100 rounded-2xl p-4 flex justify-between items-center bg-white shadow-sm focus-within:border-yellow-400">
                    <input id="q-amt" type="number" placeholder="Enter amount 100-30000" class="w-full text-lg font-bold bg-transparent">
                    <div class="text-purple-600 font-bold flex items-center gap-1 bg-purple-50 px-2 py-1 rounded-lg text-[12px]"><img src="https://img.icons8.com/color/48/rupee.png" class="w-4 h-4"> INR</div>
                </div>
            </div>
            <div class="space-y-2">
                <p class="font-bold text-gray-700 text-[13px] ml-1">You will receive</p>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex justify-between items-center">
                    <div id="q-res" class="text-2xl font-black text-gray-300">0.00</div>
                    <div class="text-yellow-600 font-bold flex gap-1.5 items-center text-[12px] bg-yellow-50 px-2 py-1 rounded-lg"><img src="<?= $main_logo ?>" class="w-5 h-5 rounded-full"> ARB</div>
                </div>
            </div>
            <button onclick="handlePurchase()" class="w-full bg-yellow-400 py-4 rounded-2xl font-black text-lg shadow-lg btn-active">Buy Now</button>
            
            <div class="space-y-4 pt-4">
                <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                    <p class="font-bold text-[13px] text-blue-800 mb-1 flex items-center gap-2">✨ Quick Buy Benefits</p>
                    <p class="text-[11px] text-blue-600 leading-relaxed font-medium">Our smart matching algorithm connects you to verified merchants instantly. Minimum transaction starts at ₹100 with zero slippage.</p>
                </div>
            </div>
        </div>

        <div id="usdt-view" class="hidden p-5 flex flex-col space-y-6 pb-20">
            <div class="flex justify-between items-center text-[11px] font-black px-1">
                <span class="text-gray-400 uppercase tracking-widest">Network: TRC-20</span>
                <span class="text-blue-500 bg-blue-50 px-2 py-0.5 rounded">1 USDT = 97 ARB</span>
            </div>
            <div class="bg-[#27A17C] rounded-[24px] p-6 text-white relative shadow-lg">
                <p class="text-[11px] mb-2 font-bold opacity-80 uppercase tracking-tighter">Deposit amount (minimum 5 USDT)</p>
                <div class="flex justify-between items-center">
                    <input id="u-amt" type="number" placeholder="0.00" class="bg-transparent outline-none w-full text-2xl font-black placeholder-white/40">
                    <span class="flex items-center gap-1 font-bold bg-white/20 px-3 py-1.5 rounded-xl backdrop-blur-sm text-[13px]"><img src="https://img.icons8.com/color/48/tether.png" class="w-5 h-5 bg-white rounded-full"> USDT</span>
                </div>
            </div>
            <div class="bg-[#FFB800] rounded-[24px] p-6 text-white shadow-lg">
                <p class="text-[11px] mb-2 font-bold opacity-80 uppercase tracking-tighter">You will get</p>
                <div class="flex justify-between items-center">
                    <div id="u-res" class="text-2xl font-black opacity-60">0.00</div>
                    <span class="flex items-center gap-1 font-bold bg-black/10 px-3 py-1.5 rounded-xl backdrop-blur-sm text-[13px]"><img src="<?= $main_logo ?>" class="w-5 h-5 rounded-full"> ARB</span>
                </div>
            </div>
            <button onclick="handlePurchase()" class="w-full bg-yellow-400 py-4 rounded-[20px] font-black text-lg shadow-xl btn-active">Recharge Now</button>
        </div>

    </div>
</div>

<script>
    let cRan = { min: 2000, max: 15000 }; 
    let tid = null; 
    let cTab = 'OTP-UPI';
    let refreshSpeed = 300; 

    const eImg = <?= json_encode($extra_images) ?>; 
    const mLog = "<?= $main_logo ?>";

    function switchTab(n, el) {
        cTab = n;
        document.querySelectorAll('.nav-tab').forEach(t => { 
            t.classList.remove('active'); 
            const line = t.querySelector('.active-tab-line'); if(line) line.remove(); 
        });
        el.classList.add('active'); el.innerHTML += '<div class="active-tab-line"></div>';

        const isList = ['UPI','OTP-UPI','BANK'].includes(n);
        document.getElementById('list-view').style.display = isList ? 'block' : 'none';
        document.getElementById('quick-view').style.display = (n==='Quick') ? 'flex' : 'none';
        document.getElementById('usdt-view').style.display = (n==='USDT') ? 'flex' : 'none';

        if(isList) { startR(); upd(true); } else if(tid) clearInterval(tid);
    }

    // Purchase Logic Fix
    function handlePurchase(btnElement = null) {
        let amount = 0;
        
        if(cTab === 'Quick') {
            amount = document.getElementById('q-amt').value;
        } else if (cTab === 'USDT') {
            let usdt = document.getElementById('u-amt').value;
            amount = usdt * 97; // Converting to INR
        } else if (btnElement) {
            // Finding amount from the specific card
            let card = btnElement.closest('.offer-card');
            amount = card.querySelector('.price-text').innerText.replace('₹', '').replace(',', '');
        }

        if(!amount || amount <= 0) {
            alert("Please enter a valid amount");
            return;
        }

        // Passing both amount and tab method to order.php
        window.location.href = `order.php?amount=${amount}&method=${cTab}`;
    }

    function setFilter(type, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('active', 'bg-gray-100', 'font-bold');
            b.classList.add('text-gray-400');
        });
        btn.classList.add('active', 'bg-gray-100', 'font-bold');
        btn.classList.remove('text-gray-400');
        
        if(type === 'large') {
            cRan = { min: 15001, max: 50000 };
            refreshSpeed = 400; 
        } else if(type === 'small') {
            cRan = { min: 100, max: 1999 };
            refreshSpeed = 250; 
        } else {
            cRan = { min: 2000, max: 15000 };
            refreshSpeed = 300; 
        }
        
        startR(); 
        upd(true);
    }

    document.getElementById('q-amt').addEventListener('input', (e) => {
        let val = parseFloat(e.target.value) || 0;
        document.getElementById('q-res').innerText = val.toFixed(2);
        document.getElementById('q-res').style.color = val > 0 ? "#000" : "#D1D5DB";
    });

    document.getElementById('u-amt').addEventListener('input', (e) => {
        let val = parseFloat(e.target.value) || 0;
        document.getElementById('u-res').innerText = (val * 97).toFixed(2);
        document.getElementById('u-res').style.opacity = val > 0 ? "1" : "0.6";
    });

    function upd(all=false) {
        if(!['UPI','OTP-UPI','BANK'].includes(cTab)) return;
        const cards = document.querySelectorAll('.offer-card');
        const toU = all ? cards : Array.from({length: 5}, () => cards[Math.floor(Math.random()*cards.length)]);
        toU.forEach(c => {
            const p = Math.floor(Math.random()*(cRan.max-cRan.min+1))+cRan.min;
            const r = Math.floor(p*0.03); 
            let img = (Math.random() > 0.5) ? eImg[Math.floor(Math.random()*4)] : mLog;
            c.querySelector('.price-text').innerText = '₹'+p.toLocaleString();
            c.querySelector('.reward-text').innerText = '🎁 Reward +'+r;
            c.querySelector('.profile-dp').src = img;
            c.querySelector('.limit-text').innerText = `Limit ${p.toLocaleString()}-${p.toLocaleString()}`;
        });
    }

    function startR() { 
        if(tid) clearInterval(tid); 
        tid = setInterval(()=>upd(false), refreshSpeed); 
    }
    
    window.onload = () => { switchTab('OTP-UPI', document.querySelector('.nav-tab.active')); };
</script>
</body>
</html>
