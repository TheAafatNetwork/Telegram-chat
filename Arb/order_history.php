<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Transactions - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Filters */
        .filter-btn {
            background: white; border: 1px solid #f1f5f9; border-radius: 24px;
            padding: 10px 16px; font-size: 14px; font-weight: 700; color: #475569;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            flex: 1; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .filter-btn:active { background: #f1f5f9; transform: scale(0.98); }

        .filter-dropdown {
            position: absolute; top: 115%; left: 0; width: 100%; background: white;
            border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid #f1f5f9;
            display: none; z-index: 20; overflow: hidden;
        }
        .filter-dropdown.show { display: block; animation: slideDown 0.2s ease-out; }
        .filter-option {
            padding: 14px 20px; font-size: 14px; font-weight: 600; color: #334155;
            border-bottom: 1px solid #f8fafc; cursor: pointer;
        }

        /* Transaction Card */
        .trans-card {
            background: white; border-radius: 12px; padding: 16px;
            margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;
        }

        /* Badge Logic from Screenshots */
        .badge {
            padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; 
            display: inline-block; color: white; margin-bottom: 12px;
        }
        .badge-bonus { background: #facc15; } /* Yellow (Buy-in bonus / Sell Reward) */
        .badge-buy { background: #eab308; }   /* Orange-Yellow (Buy) */
        .badge-sell { background: #22c55e; }  /* Green (Sell) - As per screenshot */

        /* Data Alignment */
        .data-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 8px; font-size: 13px;
        }
        .data-label { color: #94a3b8; font-weight: 600; }
        
        /* Amount Colors */
        .text-green { color: #22c55e; font-weight: 800; font-size: 15px; }
        .text-red { color: #ef4444; font-weight: 800; font-size: 15px; } /* Sell Amount Red */

        .font-mono-code { font-family: 'Roboto Mono', monospace; letter-spacing: -0.5px; color: #64748b; font-weight: 500; font-size: 12px; }

        /* Toast */
        .toast {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9);
            background: rgba(15, 23, 42, 0.95); color: white; padding: 12px 24px; border-radius: 30px;
            font-size: 13px; font-weight: 700; opacity: 0; pointer-events: none; z-index: 110; transition: all 0.3s;
        }
        .toast.show { opacity: 1; transform: translate(-50%, -50%) scale(1); }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex justify-center">

<div class="w-full max-w-[450px] bg-gray-50 min-h-screen flex flex-col relative">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="location.href='profile.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[18px] font-black text-gray-900">Transaction</h1>
        <div class="flex gap-3 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 cursor-pointer" onclick="location.href='profile.php'">
        </div>
    </div>

    <div class="px-5 py-4 flex gap-3 relative z-40">
        <div class="relative flex-1">
            <div onclick="toggleDropdown('type-dropdown')" class="filter-btn">
                <span id="selected-type">All Types</span>
                <img src="https://img.icons8.com/material-rounded/24/64748b/expand-arrow.png" class="w-4 h-4">
            </div>
            <div id="type-dropdown" class="filter-dropdown">
                <div onclick="filterType('All Types')" class="filter-option">All Types</div>
                <div onclick="filterType('Buy')" class="filter-option">Buy</div>
                <div onclick="filterType('Sell')" class="filter-option">Sell</div>
                <div onclick="filterType('Reward')" class="filter-option">Rewards</div>
            </div>
        </div>
        <div class="relative flex-1">
            <div onclick="toggleDropdown('time-dropdown')" class="filter-btn">
                <span id="selected-time">Choose Time</span>
                <img src="https://img.icons8.com/material-rounded/24/64748b/expand-arrow.png" class="w-4 h-4">
            </div>
            <div id="time-dropdown" class="filter-dropdown">
                <div onclick="filterTime('All Time')" class="filter-option">All Time</div>
                <div onclick="filterTime('Today')" class="filter-option">Today</div>
                <div onclick="filterTime('Yesterday')" class="filter-option">Yesterday</div>
            </div>
        </div>
    </div>

    <div id="trans-list" class="px-5 pb-20 space-y-3">
        </div>

    <div id="toast" class="toast">Copied successfully</div>

</div>

<script>
    // --- MOCK DATA MATCHING SCREENSHOT LOGIC --- //
    const transactions = [
        // 1. BUY LOGIC (Buy 3000 -> Bonus 90 [3%])
        { 
            type: 'Buy-in bonus', 
            amount: '₹90.00', 
            time: '2026-01-02 17:23:20', 
            id: 'MR2026010217213600270', 
            badge: 'badge-bonus', // Yellow
            color: 'text-green', 
            category: 'Reward', 
            date: 'Today' 
        },
        { 
            type: 'Buy', 
            amount: '₹3,000.00', 
            time: '2026-01-02 17:23:20', 
            id: 'MR2026010217213600270', 
            badge: 'badge-buy', // Orange
            color: 'text-green', 
            category: 'Buy', 
            date: 'Today' 
        },

        // 2. SELL LOGIC (Sell 3300 -> Red Amount | Sell Badge Green)
        { 
            type: 'Sell', 
            amount: '₹3,300.00', 
            time: '2026-01-02 16:58:59', 
            id: 'C2C2026010216585900709', 
            badge: 'badge-sell', // Green Badge
            color: 'text-red',   // Red Text
            category: 'Sell', 
            date: 'Today' 
        },

        // 3. SELL REWARD LOGIC (Sell Reward -> Green Amount | Yellow Badge)
        { 
            type: 'Sell Reward', 
            amount: '₹1.00', 
            time: '2026-01-02 16:58:49', 
            id: 'MC2026010216573300690', 
            badge: 'badge-bonus', // Yellow
            color: 'text-green',  // Green Text
            category: 'Reward', 
            date: 'Today' 
        },

        // 4. BUY LOGIC (Buy 100 -> Bonus 3 [3%])
        { 
            type: 'Buy-in bonus', 
            amount: '₹3.00', 
            time: '2026-01-02 17:21:19', 
            id: 'MR2026010217201706415', 
            badge: 'badge-bonus', 
            color: 'text-green', 
            category: 'Reward', 
            date: 'Today' 
        },
        { 
            type: 'Buy', 
            amount: '₹100.00', 
            time: '2026-01-02 17:21:19', 
            id: 'MR2026010217201706415', 
            badge: 'badge-buy', 
            color: 'text-green', 
            category: 'Buy', 
            date: 'Today' 
        },

        // 5. SELL LOGIC (Sell 700)
        { 
            type: 'Sell', 
            amount: '₹700.00', 
            time: '2026-01-02 16:59:11', 
            id: 'MC2026010216575604186', 
            badge: 'badge-sell', 
            color: 'text-red', 
            category: 'Sell', 
            date: 'Today' 
        }
    ];

    let currentType = 'All Types';
    let currentTime = 'All Time';

    function renderList() {
        const list = document.getElementById('trans-list');
        list.innerHTML = '';

        const filtered = transactions.filter(t => {
            const typeMatch = currentType === 'All Types' || t.category === currentType;
            const timeMatch = currentTime === 'All Time' || t.date === currentTime;
            return typeMatch && timeMatch;
        });

        if (filtered.length === 0) {
            list.innerHTML = `<div class="text-center py-10 opacity-50"><p class="font-bold text-gray-400">No records found</p></div>`;
            return;
        }

        filtered.forEach(item => {
            const html = `
                <div class="trans-card animate-fadeIn">
                    <span class="badge ${item.badge}">${item.type}</span>
                    
                    <div class="data-row">
                        <span class="data-label">Amount</span>
                        <span class="data-value ${item.color}">${item.amount}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Time</span>
                        <span class="data-value font-mono-code">${item.time}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Order Number</span>
                        <div class="flex items-center">
                            <span class="data-value font-mono-code text-[11px] truncate max-w-[150px]">${item.id}</span>
                            <div onclick="copyText('${item.id}')" class="ml-2 cursor-pointer active:scale-75 transition-transform opacity-60">
                                <img src="https://img.icons8.com/material-rounded/24/94a3b8/copy.png" class="w-3.5 h-3.5">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            list.innerHTML += html;
        });
    }

    // --- FILTERS --- //
    function toggleDropdown(id) {
        document.querySelectorAll('.filter-dropdown').forEach(el => {
            if(el.id !== id) el.classList.remove('show');
        });
        document.getElementById(id).classList.toggle('show');
    }

    function filterType(type) {
        currentType = type;
        document.getElementById('selected-type').innerText = type;
        toggleDropdown('type-dropdown');
        renderList();
    }

    function filterTime(time) {
        currentTime = time;
        document.getElementById('selected-time').innerText = time;
        toggleDropdown('time-dropdown');
        renderList();
    }

    // --- COPY --- //
    function copyText(text) {
        navigator.clipboard.writeText(text);
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        if(navigator.vibrate) navigator.vibrate(40);
        setTimeout(() => toast.classList.remove('show'), 2000);
    }

    // Click outside listener
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            document.querySelectorAll('.filter-dropdown').forEach(el => el.classList.remove('show'));
        }
    });

    window.onload = renderList;
</script>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
</style>

</body>
</html>
