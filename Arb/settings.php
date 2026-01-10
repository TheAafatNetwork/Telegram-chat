<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Settings - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Block Design from Screenshot */
        .setting-block {
            background: white;
            border-radius: 12px; /* Smooth rounded corners */
            padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px; /* Spacing between blocks */
            box-shadow: 0 1px 2px rgba(0,0,0,0.02); /* Subtle shadow */
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .setting-block:active { 
            transform: scale(0.98); 
            background: #fcfcfc;
        }

        /* Avatar styling */
        .avatar-preview {
            width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
            border: 1px solid #f1f5f9;
        }

        /* Modal Logic */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            display: none; justify-content: center; align-items: center; z-index: 100; opacity: 0; transition: opacity 0.3s;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-box {
            background: white; width: 85%; max-width: 320px; border-radius: 24px; padding: 24px;
            transform: scale(0.9); transition: transform 0.3s; box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-overlay.show .modal-box { transform: scale(1); }

        /* Custom Input */
        .custom-input {
            width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px;
            font-size: 16px; font-weight: 600; outline: none; transition: border-color 0.3s;
            color: #334155;
        }
        .custom-input:focus { border-color: #facc15; }
        .error-msg { color: #ef4444; font-size: 12px; font-weight: 600; margin-top: 6px; display: none; }

        /* Delete Block */
        .delete-block {
            background: white; border-radius: 12px; padding: 16px; text-align: center;
            color: #ef4444; font-weight: 700; font-size: 14px; margin-top: 30px;
            cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .delete-block:active { background: #fef2f2; transform: scale(0.98); }

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

<div class="w-full max-w-[450px] bg-gray-50 min-h-screen flex flex-col relative shadow-xl">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-100">
        <div onclick="location.href='profile.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[18px] font-black text-gray-900">Settings</h1>
        <div class="flex gap-3 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 cursor-pointer" onclick="location.href='profile.php'">
        </div>
    </div>

    <div class="p-5">
        
        <h2 class="text-[14px] font-black text-gray-900 mb-4 ml-1">Basic Information</h2>

        <div class="setting-block" onclick="document.getElementById('file-upload').click()">
            <span class="text-[14px] font-bold text-gray-700">Avatar</span>
            <div class="flex items-center gap-3">
                <img id="display-avatar" class="avatar-preview" src="https://arbpay.me/assets/ordinary-7f4166d8.png">
                <img src="https://img.icons8.com/material-rounded/24/cbd5e1/chevron-right.png" class="w-5 h-5">
            </div>
            <input type="file" id="file-upload" accept="image/*" style="display: none;" onchange="updateAvatar(this)">
        </div>

        <div class="setting-block" onclick="openModal()">
            <span class="text-[14px] font-bold text-gray-700">Nickname</span>
            <div class="flex items-center gap-3">
                <span id="display-name" class="text-[13px] font-bold text-gray-500">Loading...</span>
                <img src="https://img.icons8.com/material-rounded/24/cbd5e1/chevron-right.png" class="w-5 h-5">
            </div>
        </div>

        <div class="setting-block" onclick="copyUID()">
            <span class="text-[14px] font-bold text-gray-700">UID</span>
            <div class="flex items-center gap-3">
                <span id="display-uid" class="text-[13px] font-mono-code font-bold text-gray-500">-------</span>
                <img src="https://img.icons8.com/material-outlined/24/94a3b8/copy.png" class="w-4 h-4">
            </div>
        </div>

        <div class="delete-block" onclick="deleteAccount()">
            Delete Account
        </div>

        <div class="text-center mt-6">
            <p class="text-[10px] font-bold text-gray-300">AR Wallet v2.0.1</p>
        </div>

    </div>

    <div id="edit-modal" class="modal-overlay">
        <div class="modal-box">
            <h3 class="text-[18px] font-black text-gray-900 mb-4 text-center">Change Nickname</h3>
            <input type="text" id="input-name" class="custom-input" placeholder="Enter unique nickname" maxlength="12">
            <p id="name-error" class="error-msg">Username already exists</p>
            
            <div class="flex gap-3 mt-6">
                <button onclick="closeModal()" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 active:scale-95">Cancel</button>
                <button onclick="saveName()" class="flex-1 py-3 rounded-xl font-bold text-gray-900 bg-[#facc15] shadow-lg shadow-yellow-200 active:scale-95">Save</button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">Updated Successfully</div>

</div>

<script>
    // --- SMART SYNC SYSTEM (Same logic as Profile) --- //
    
    let currentUser = {};

    function initSettings() {
        // Load global user data
        const storedData = localStorage.getItem('ar_user_data');
        
        if (storedData) {
            currentUser = JSON.parse(storedData);
        } else {
            // Generate New User if data missing
            const randomUID = Math.floor(10000000 + Math.random() * 90000000);
            currentUser = {
                uid: randomUID,
                username: generateRandomName(),
                avatar: 'https://arbpay.me/assets/ordinary-7f4166d8.png',
                wallet: generateWallet()
            };
            saveUser();
        }

        // Bind Data to UI
        document.getElementById('display-avatar').src = currentUser.avatar;
        document.getElementById('display-name').innerText = currentUser.username;
        document.getElementById('display-uid').innerText = currentUser.uid;
    }

    // --- AVATAR LOGIC --- //
    function updateAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentUser.avatar = e.target.result; // Update Object
                saveUser(); // Save to LocalStorage
                document.getElementById('display-avatar').src = e.target.result; // Update UI
                showToast('Avatar Updated');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- NICKNAME LOGIC --- //
    const takenNames = ['admin', 'system', 'root', 'support', 'arwallet'];

    function openModal() {
        document.getElementById('input-name').value = currentUser.username;
        document.getElementById('edit-modal').classList.add('show');
        setTimeout(() => document.getElementById('input-name').focus(), 100);
    }

    function closeModal() {
        document.getElementById('edit-modal').classList.remove('show');
        document.getElementById('name-error').style.display = 'none';
    }

    function saveName() {
        const newName = document.getElementById('input-name').value.trim();
        const errorEl = document.getElementById('name-error');

        if (newName.length < 3) {
            showError("Minimum 3 characters"); return;
        }
        // Simulation: Prevent duplicate usernames
        if (takenNames.includes(newName.toLowerCase())) {
            showError("Username already exists"); return;
        }

        currentUser.username = newName; // Update Object
        saveUser(); // Sync to Profile
        
        document.getElementById('display-name').innerText = newName;
        closeModal();
        showToast("Nickname Changed");
    }

    function showError(msg) {
        const err = document.getElementById('name-error');
        err.innerText = msg;
        err.style.display = 'block';
        if(navigator.vibrate) navigator.vibrate(50);
    }

    // --- UTILS --- //
    function saveUser() {
        localStorage.setItem('ar_user_data', JSON.stringify(currentUser));
    }

    function deleteAccount() {
        if(confirm("Permanently delete account and reset all data?")) {
            localStorage.removeItem('ar_user_data');
            showToast("Account Deleted");
            setTimeout(() => location.href = 'profile.php', 1000);
        }
    }

    function copyUID() {
        navigator.clipboard.writeText(currentUser.uid);
        showToast("UID Copied");
        if(navigator.vibrate) navigator.vibrate(40);
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerText = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2000);
    }

    // Generators
    function generateRandomName() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let name = 'S';
        for(let i=0; i<7; i++) name += chars.charAt(Math.floor(Math.random() * chars.length));
        return name;
    }
    
    function generateWallet() {
        const str = '1AgpWLyeQC9HkfPciYi3rNXB4mGzoTFATs'.split('').sort(() => 0.5 - Math.random()).join('');
        return '1Agp' + str.substring(0, 28) + '...';
    }

    window.onload = initSettings;
</script>

</body>
</html>
