<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>User Guidelines - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; overflow-x: hidden; touch-action: pan-y; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Timeline Logic */
        .timeline-container { position: relative; padding-left: 20px; }
        .timeline-line {
            position: absolute; left: 9px; top: 15px; bottom: 30px; width: 2px;
            background: repeating-linear-gradient(to bottom, #facc15 0, #facc15 6px, transparent 6px, transparent 10px);
        }
        .timeline-item { position: relative; margin-bottom: 20px; display: flex; align-items: center; }
        .timeline-dot {
            width: 20px; height: 20px; border-radius: 50%; border: 4px solid #facc15; background: white;
            position: absolute; left: -20px; z-index: 10;
        }
        .timeline-content {
            background: white; padding: 12px 16px; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;
            width: 100%; margin-left: 15px;
        }
        
        /* Rule Number Styling */
        .rule-number { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 8px; display: block; }
        .rule-text { font-size: 13px; color: #475569; line-height: 1.6; font-weight: 500; text-align: justify; }
        
        /* Highlight text underline */
        .highlight-title {
            position: relative; display: inline-block; z-index: 1;
        }
        .highlight-title::after {
            content: ''; position: absolute; bottom: 2px; left: 0; width: 100%; height: 6px;
            background: #facc15; opacity: 0.4; z-index: -1;
        }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="history.back()" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-all">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[18px] font-black text-gray-900">User Guidelines</h1>
        <div class="flex gap-3">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 opacity-40 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 opacity-40 cursor-pointer" onclick="history.back()">
        </div>
    </div>

    <div class="p-6 pb-20 space-y-8">
        
        <div class="relative">
            <h2 class="text-[22px] font-black text-gray-900 leading-tight">
                User instruction<br>
                Quickly learn how to play
            </h2>
            <div class="w-8 h-1 bg-gray-900 mt-3 rounded-full"></div>
            <img src="https://img.icons8.com/fluency/96/open-book.png" class="absolute right-0 top-0 w-20 h-20 -rotate-12 drop-shadow-lg">
        </div>

        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
            <h3 class="text-[16px] font-black text-gray-900 mb-3"><span class="highlight-title">Introduction</span></h3>
            <p class="text-[13px] text-gray-600 leading-relaxed text-justify">
                AR Wallet is a trading platform developed based on the C2C principle. The trading model involves users buying and selling among each other in a hall-like environment, rather than a recharge mode.
                <br><br>
                ARB serves as the sole currency on the AR Wallet trading platform, with an exchange rate of 1:1 with legal currency. Users can use ARB, legal currency, and USDT for transaction exchanges.
            </p>
        </div>

        <div>
            <h3 class="text-[18px] font-black text-gray-900 mb-6"><span class="highlight-title">Process</span></h3>
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                <?php 
                $steps = [
                    "01. Activate AR Wallet",
                    "02. Buy ARB",
                    "03. Recharge to merchant platform",
                    "04. Withdrawal from Merchant to ARB",
                    "05. Sell ARB on AR Wallet",
                    "06. Buyer payment",
                    "07. Payment received",
                    "08. Confirmation of receipt",
                    "09. Transition complete"
                ];
                foreach($steps as $step): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span class="text-[13px] font-bold text-gray-700">
                            <span class="text-[#facc15] font-black mr-1"><?= substr($step, 0, 3) ?></span><?= substr($step, 3) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-[18px] font-black text-gray-900"><span class="highlight-title">Rules</span></h3>
            <p class="text-[13px] text-gray-500 italic bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                During the transaction process, there are also some important notes. Please make sure to read them carefully.
            </p>

            <div>
                <span class="rule-number">01.</span>
                <p class="rule-text">
                    In order to ensure the security of transactions between members, the platform adopts real-name user authentication, and the authentication information cannot be modified for lifetime. Every user must strictly adhere to the regulations and activate their own personal identity information, UPI information, etc.
                    <br><br>
                    The buyer must use their own identity information's UPI for payment. If a third-party UPI account is used for payment, the seller has the right to refuse to confirm receipt.
                    <br><br>
                    The user is responsible for any losses happen due to incorrect information provided by themselves, and the platform bears no responsibility.
                </p>
            </div>

            <div>
                <span class="rule-number">02.</span>
                <p class="rule-text">
                    To ensure the security of seller information, buyers must only confirm orders when necessary. If malicious order is found, and users lock seller quotas without making payment, the platform will automatically freeze the account.
                </p>
            </div>

            <div>
                <span class="rule-number">03.</span>
                <p class="rule-text">
                    When buying or selling ARB coins, both parties must complete the order within the specified time frame. Failure to confirm the transaction for a long time will cause the system to automatically determine the transition completed or canceled. The platform does not bear any responsibility for such cases.
                    <br><br>
                    Members must monitor transactions in real-time when buying or selling ARB. If they have any objections, they can appeal in time to avoid financial losses.
                </p>
            </div>

            <div>
                <span class="rule-number">04.</span>
                <p class="rule-text">
                    When purchasing ARB, buyers should immediately click on 'I have transferred funds' after successful payment, and upload the transfer screenshot for the seller's verification. The seller will ship the goods within the specified time frame.
                </p>
            </div>

            <div>
                <span class="rule-number">05.</span>
                <p class="rule-text">
                    When selling ARB, if a buyer places an order to purchase and uploads a transfer screenshot, please confirm whether you have received the payment. If you haven't received payment from the buyer, you can click on 'Appeal'. AR Wallet official team will investigate this transition.
                </p>
            </div>

            <div>
                <span class="rule-number">06.</span>
                <p class="rule-text">
                    If either party maliciously refuses to confirm receipt of payment during the selling process, intentionally causing inconvenience to the buyer, the platform will freeze the account.
                </p>
            </div>
        </div>

    </div>
</div>

</body>
</html>
