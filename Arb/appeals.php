<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Appeal - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Tabs Styling */
        .filter-tab {
            padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 700;
            color: #64748b; background: transparent; transition: all 0.3s; cursor: pointer; border: 1px solid transparent;
        }
        .filter-tab.active {
            background: #1e293b; color: #fff; border-color: #1e293b; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        }

        /* Appeal Card */
        .appeal-card {
            background: white; border-radius: 16px; padding: 16px;
            margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;
        }

        /* Badges */
        .badge-sell {
            background: #22c55e; color: white; padding: 4px 12px; border-radius: 6px;
            font-size: 12px; font-weight: 800; display: inline-block;
        }
        .status-text { font-size: 13px; font-weight: 700; }
        .status-failed { color: #ef4444; } /* Red */
        .status-process { color: #ef4444; } /* Red as per screenshot */
        
        /* Data Rows */
        .data-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 10px; font-size: 13px;
        }
        .data-label { color: #94a3b8; font-weight: 600; }
        .data-value { color: #334155; font-weight: 700; text-align: right; }
        
        /* Copy Icon */
        .copy-icon {
            width: 14px; height: 14px; margin-left: 6px; opacity: 0.5; vertical-align: middle; cursor: pointer;
        }
        .copy-icon:active { opacity: 1; transform: scale(1.2); }

        /* Toast */
        .toast {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9);
            background: rgba(15, 23, 42, 0.95); color: white; padding: 12px 24px; border-radius: 30px;
            font-size: 13px; font-weight: 700; opacity: 0; pointer-events: none; z-index: 110; transition: all 0.3s;
        }
        .toast.show { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        
        .font-mono-code { font-family: 'Roboto Mono', monospace; letter-spacing: -0.5px; }
    </style>
</head>
<body class="flex justify-center">

<div class="w-full max-w-[450px] bg-gray-50 min-h-screen flex flex-col">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="location.href='profile.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[18px] font-black text-gray-900">My Appeal</h1>
        <div class="flex gap-3 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 cursor-pointer" onclick="location.href='profile.php'">
        </div>
    </div>

    <div class="px-5 py-4 flex gap-2 overflow-x-auto no-scrollbar bg-gray-50">
        <div onclick="filterAppeals('all')" id="tab-all" class="filter-tab active">All</div>
        <div onclick="filterAppeals('process')" id="tab-process" class="filter-tab">In Appeal</div>
        <div onclick="filterAppeals('failed')" id="tab-failed" class="filter-tab">Appeal Failed</div>
    </div>

    <div id="appeal-list" class="p-5 pt-0 space-y-3 pb-20">
        </div>

    <div id="toast" class="toast">Copied successfully</div>

</div>

<script>
    // --- 1. DATA LOGIC (Same as Screenshot) --- //
    const appealsData = [
        {
            type: 'Sell',
            status: 'In appeal',
            statusCode: 'process',
            amount: '5,000.00',
            currency: '₹',
            utr: '394806940173',
            time: '2026-01-02 19:37:47',
            orderId: 'MC2026010219282504451'
        },
        {
            type: 'Sell',
            status: 'Appeal Failed',
            statusCode: 'failed',
            amount: '100.00',
            currency: '₹',
            utr: '600180918218',
            time: '2026-01-01 18:29:44',
            orderId: 'MC2026010118071600863'
        },
        {
            type: 'Sell',
            status: 'Appeal Failed',
            statusCode: 'failed',
            amount: '100.00',
            currency: '₹',
            utr: '394745883437',
            time: '2026-01-01 18:28:45',
            orderId: 'MC2026010118072101686'
        },
        {
            type: 'Sell',
            status: 'Appeal Failed',
            statusCode: 'failed',
            amount: '100.00',
            currency: '₹',
            utr: '394435468038',
            time: '2025-12-30 17:45:35',
            orderId: 'MC2025123017400019283'
        }
    ];

    // --- 2. RENDER LOGIC --- //
    function renderAppeals(filter = 'all') {
        const list = document.getElementById('appeal-list');
        list.innerHTML = '';

        const filteredData = filter === 'all' 
            ? appealsData 
            : appealsData.filter(item => item.statusCode === filter);

        if(filteredData.length === 0) {
            list.innerHTML = `<div class="text-center py-10 opacity-50"><p class="font-bold text-gray-400">No records found</p></div>`;
            return;
        }

        filteredData.forEach(item => {
            // HTML Template based on Screenshot
            const html = `
                <div class="appeal-card animate-fadeIn">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3 mb-2">
                        <span class="badge-sell">${item.type}</span>
                        <span class="status-text ${item.statusCode === 'failed' ? 'text-red-500' : 'text-red-500'} opacity-90">${item.status}</span>
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Amount</span>
                        <span class="data-value text-[15px]">${item.currency}${item.amount}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">UTR</span>
                        <div class="flex items-center">
                            <span class="data-value font-mono-code text-gray-500">${item.utr}</span>
                            <img onclick="copyText('${item.utr}')" src="https://img.icons8.com/material-rounded/24/94a3b8/copy.png" class="copy-icon">
                        </div>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Time</span>
                        <span class="data-value font-mono-code text-gray-500 text-[11px]">${item.time}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Order Time</span>
                        <div class="flex items-center">
                            <span class="data-value font-mono-code text-gray-500 text-[11px] truncate max-w-[150px]">${item.orderId}</span>
                            <img onclick="copyText('${item.orderId}')" src="https://img.icons8.com/material-rounded/24/94a3b8/copy.png" class="copy-icon">
                        </div>
                    </div>
                </div>
            `;
            list.innerHTML += html;
        });
    }

    // --- 3. FILTER LOGIC --- //
    function filterAppeals(type) {
        // Active Tab UI
        document.querySelectorAll('.filter-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + type).classList.add('active');
        
        // Haptic & Render
        if(navigator.vibrate) navigator.vibrate(20);
        renderAppeals(type);
    }

    // --- 4. UTILITIES --- //
    function copyText(text) {
        navigator.clipboard.writeText(text);
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        if(navigator.vibrate) navigator.vibrate(40);
        setTimeout(() => toast.classList.remove('show'), 2000);
    }

    // Init
    window.onload = () => renderAppeals('all');
</script>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
</style>

</body>
</html>
