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
    <title>Real-name Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Custom Input Styling */
        .custom-input {
            width: 100%;
            background: #f8fafc; 
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            color: #334155;
            outline: none;
            transition: all 0.3s ease;
        }
        .custom-input:focus {
            background: #fff;
            border-color: #facc15;
            box-shadow: 0 4px 12px rgba(250, 204, 21, 0.05);
        }
        .custom-input::placeholder { color: #94a3b8; font-weight: 500; }

        /* Upload Button */
        .btn-primary {
            background: #facc15;
            color: #1e293b;
            font-weight: 800;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.1s;
            box-shadow: 0 4px 6px rgba(250, 204, 21, 0.2);
        }
        .btn-primary:active { transform: scale(0.98); }

        /* ID Card Placeholder Container */
        .id-card-container {
            border: 2px dashed #e2e8f0;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            transition: border-color 0.3s;
        }
        .id-card-container.uploaded { border-style: solid; border-color: #facc15; }

        /* Toast */
        .toast {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9);
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(8px); color: white; 
            padding: 14px 28px; border-radius: 30px; font-size: 13px; font-weight: 700; 
            opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100;
        }
        .toast.show { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl overflow-x-hidden pb-24">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="location.href='home.php'" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[17px] font-black text-slate-800">Real-name</h1>
        <div class="flex gap-4 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5 cursor-pointer" onclick="location.reload()">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5 cursor-pointer" onclick="location.href='home.php'">
        </div>
    </div>

    <div class="px-5 py-6 space-y-8">
        
        <p class="text-[12px] text-gray-500 leading-relaxed">
            In order to create a healthy trading environment for users, please complete real-name authentication first. Real-name authentication cannot be changed once completed, and only UPI accounts that have been real-name authenticated can be used for transactions.
        </p>

        <div class="flex flex-col items-center gap-4">
            <h3 class="text-[14px] font-bold text-slate-800">ID Document Front Photo</h3>
            
            <div id="preview-box" class="id-card-container w-[240px] h-[150px] flex items-center justify-center">
                <img id="id-preview" src="https://img.freepik.com/premium-vector/id-card-icon-vector-illustration_106065-57.jpg" class="w-full h-full object-cover opacity-80">
            </div>

            <div onclick="document.getElementById('file-upload').click()" class="btn-primary w-[240px] py-3 text-[14px]">
                Upload Image
            </div>
            <input type="file" id="file-upload" accept="image/*" class="hidden" onchange="previewImage(this)">
        </div>

        <div class="space-y-5">
            <div class="space-y-2">
                <label class="text-[14px] font-bold text-slate-800 ml-1">Real Name</label>
                <input type="text" id="realname" class="custom-input" placeholder="Please enter your real name">
            </div>

            <div class="space-y-2">
                <label class="text-[14px] font-bold text-slate-800 ml-1">ID Number</label>
                <input type="text" id="idnumber" class="custom-input" placeholder="Please enter your ID number">
            </div>
        </div>

        <div class="space-y-3 pt-2">
            <h3 class="text-[14px] font-bold text-slate-800">Warm Reminder</h3>
            <ul class="text-[12px] text-gray-500 space-y-2 leading-relaxed">
                <li>1. Please take a photo using the original government-issued identification document.</li>
                <li>2. Place the identification document on a single-colored background.</li>
                <li>3. When capturing the document, ensure the photo is clear and free of obstructions or reflections.</li>
                <li>4. Do not use black and white photos.</li>
            </ul>
        </div>

    </div>

    <div class="fixed bottom-0 w-full max-w-[450px] bg-white border-t border-gray-100 px-5 py-4 z-40">
        <button onclick="submitKYC()" class="w-full bg-[#facc15] text-slate-900 font-black py-3.5 rounded-xl shadow-lg shadow-yellow-100 active:scale-98 transition-transform text-[15px]">
            Next(1 /2)
        </button>
    </div>

    <div id="toast" class="toast">Submitted Successfully</div>

</div>

<script>
    // Logic: Image Preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('id-preview');
                const box = document.getElementById('preview-box');
                
                img.src = e.target.result;
                img.classList.remove('opacity-80');
                box.classList.add('uploaded');
                
                showToast("Image Selected");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Logic: Submit
    function submitKYC() {
        const name = document.getElementById('realname').value;
        const id = document.getElementById('idnumber').value;
        const file = document.getElementById('file-upload').files[0];

        if(!name || !id) {
            alert("Please fill all fields.");
            return;
        }
        if(!file) {
            alert("Please upload ID photo.");
            return;
        }

        // Simulate Submission
        const btn = document.querySelector('button');
        btn.innerHTML = 'Processing...';
        btn.disabled = true;
        btn.classList.add('opacity-70');

        setTimeout(() => {
            // Save local status for demo
            localStorage.setItem('kyc_status', 'pending');
            showToast("Application Submitted");
            setTimeout(() => {
                location.href = 'profile.php';
            }, 1000);
        }, 1500);
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerText = msg;
        t.classList.add('show');
        if(navigator.vibrate) navigator.vibrate(40);
        setTimeout(() => t.classList.remove('show'), 2000);
    }
</script>

</body>
</html>
