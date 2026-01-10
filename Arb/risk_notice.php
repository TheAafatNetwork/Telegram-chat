<?php
session_start();
// Security Check
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
    <title>Risk Warning - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Content Typography */
        .content-text { font-size: 14px; line-height: 1.6; color: #475569; }
        .content-title { font-weight: 700; color: #1e293b; }
        
        /* Header Icons */
        .icon-btn { opacity: 0.4; transition: opacity 0.2s; cursor: pointer; }
        .icon-btn:active { opacity: 1; transform: scale(0.95); }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="location.href='home.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[17px] font-black text-slate-800">Announcement detail</h1>
        <div class="flex gap-4">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 icon-btn" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 icon-btn" onclick="location.href='home.php'">
        </div>
    </div>

    <div class="px-5 py-6 pb-20">
        
        <h2 class="text-[22px] font-black text-slate-900 leading-tight">Risk Warning</h2>
        <p class="text-[12px] text-gray-400 font-bold mt-1.5 mb-5">2024-03-04 12:18:23</p>

        <div class="w-full h-[200px] bg-blue-50 rounded-xl overflow-hidden mb-6 shadow-sm border border-slate-100">
            <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover">
        </div>

        <div class="space-y-6">
            
            <p class="content-text">
                <span class="text-rose-500 font-bold">Phishing Scam Warning:</span> AR Wallet takes the security and privacy of our users seriously. Please be aware of phishing scams, where malicious actors attempt to deceive users into providing sensitive information such as usernames, passwords, financial details, or personal information. Phishing scams often involve fraudulent emails, text messages, or websites that mimic legitimate platforms or institutions in order to trick users into disclosing confidential information. These scams may appear authentic and convincing, but they are designed to steal your information for fraudulent purposes. To protect yourself from phishing scams, we advise you to:
            </p>

            <p class="content-text">
                Be cautious of unexpected or unsolicited communications: Exercise caution when receiving emails, text messages, or other communications requesting sensitive information, especially if they contain urgent requests, grammatical errors, or suspicious links.
            </p>

            <div class="content-text">
                <span class="content-title">1. Verify the sender's identity:</span> Before providing any information or clicking on links, verify the sender's identity by contacting AR Wallet directly using trusted contact information, such as the official website or customer support hotline.
            </div>

            <div class="content-text">
                <span class="content-title">2. Avoid clicking on suspicious links:</span> Do not click on links or download attachments from unfamiliar or suspicious sources, as they may contain malware or lead to phishing websites designed to steal your information.
            </div>

            <div class="content-text">
                <span class="content-title">3. Keep your information secure:</span> Protect your passwords and personal information by using strong, unique passwords for each online account.
            </div>

            <div class="content-text">
                <span class="content-title">4. Report suspected phishing attempts:</span> If you receive a suspicious email, text message, or communication claiming to be from AR Wallet, please report it immediately to our customer support team.
            </div>

            <div class="content-text">
                <span class="content-title">5. AR Wallet will never reach out to users through social media platforms, Whatsapp, UPI, or any other method with investment advice, offers, or requests for confirming or transferring funds.</span> If someone claims to represent AR Wallet and contacts you requesting funds or offering investment advice through any method, they are attempting to deceive or scam you.
            </div>

            <p class="content-text pt-2 border-t border-slate-50">
                Remember, AR Wallet will never request sensitive information such as passwords, financial details, or personal information via email, text message, or unsolicited communication. Stay vigilant and report any suspicious activity to help us maintain a safe and secure platform for all users.
            </p>

        </div>
    </div>

</div>

</body>
</html>
