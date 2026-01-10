<?php
session_start();
// Security
if (!isset($_SESSION['user_phone'])) {
    header("Location: index.php");
    exit();
}

$phone = $_SESSION['user_phone'];
$file = "data/" . $phone . ".json"; // Adjust path if needed

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $real_name = $_POST['real_name'] ?? '';
    $upi_id = $_POST['upi_id'] ?? '';
    
    // Validation
    if($real_name && $upi_id) {
        $currentData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        
        // Prepare new method
        $newMethod = [
            'type' => 'upi',
            'real_name' => $real_name,
            'upi_id' => $upi_id,
            'added_at' => date('Y-m-d H:i:s')
        ];

        // Append to array
        $currentData['payment_methods'][] = $newMethod;
        
        // Save
        file_put_contents($file, json_encode($currentData));
        
        // Redirect with success
        header("Location: collection.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Add UPI - AR Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f8fafc; touch-action: pan-y; }
        .custom-input {
            width: 100%; background: #fff; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 16px; font-size: 14px; color: #1e293b; outline: none;
            transition: all 0.2s;
        }
        .custom-input:focus { border-color: #facc15; box-shadow: 0 4px 12px rgba(250, 204, 21, 0.1); }
        .label { font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px; display: block; }
    </style>
</head>
<body class="flex justify-center bg-gray-50">

<div class="w-full max-w-[450px] bg-white min-h-screen flex flex-col relative shadow-xl">
    
    <div class="px-5 py-4 flex items-center justify-between bg-white sticky top-0 z-50 border-b border-gray-50">
        <div onclick="history.back()" class="p-2 -ml-2 cursor-pointer active:scale-90 transition-transform">
            <img src="https://img.icons8.com/material-rounded/24/000000/chevron-left.png" class="w-6 h-6">
        </div>
        <h1 class="text-[17px] font-black text-slate-800">Add UPI</h1>
        <div class="w-6"></div>
    </div>

    <form method="POST" class="px-6 py-8 space-y-6">
        
        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100 flex items-start gap-3">
            <img src="https://img.icons8.com/material-rounded/24/eab308/info.png" class="w-5 h-5 mt-0.5">
            <p class="text-[12px] text-yellow-800 leading-relaxed font-medium">
                Please ensure the UPI ID matches your real name. Incorrect information may lead to transaction failure.
            </p>
        </div>

        <div>
            <label class="label">Real Name</label>
            <input type="text" name="real_name" class="custom-input" placeholder="Enter your full name">
        </div>

        <div>
            <label class="label">UPI Account (VPA)</label>
            <input type="text" name="upi_id" class="custom-input" placeholder="e.g. name@okhdfcbank">
        </div>
        
        <div class="pt-4">
             <label class="label">QR Code (Optional)</label>
             <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-gray-50 transition-colors">
                 <img src="https://img.icons8.com/ios/50/cbd5e1/camera--v1.png" class="w-8 h-8 mb-2">
                 <span class="text-[12px] font-bold">Tap to upload QR</span>
             </div>
        </div>

        <div class="fixed bottom-0 left-0 w-full bg-white p-5 border-t border-gray-100 flex justify-center">
            <button type="submit" class="w-full max-w-[450px] bg-[#facc15] text-slate-900 font-black py-4 rounded-xl shadow-lg shadow-yellow-100 active:scale-98 transition-transform">
                Save Method
            </button>
        </div>

    </form>

</div>

</body>
</html>
