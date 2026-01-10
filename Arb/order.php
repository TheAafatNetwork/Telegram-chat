<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Select Method Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #fff; overflow-x: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        .payment-card { 
            transition: all 0.2s ease; 
            cursor: pointer; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .payment-card:active { transform: scale(0.96); opacity: 0.9; }
        
        /* Premium Gradients */
        .bg-phonepe { background: linear-gradient(90deg, #5f259f 0%, #7b39cc 100%); }
        .bg-paytm { background: #ffffff; border: 1.5px solid #e2e8f0; }
        .bg-mobikwik { background: linear-gradient(90deg, #004ba0 0%, #0075be 100%); }
        .bg-airtel { background: linear-gradient(90deg, #e11900 0%, #ff3b30 100%); }
        .bg-supermoney { background: linear-gradient(90deg, #4d41ff 0%, #6358ff 100%); }

        .blend-logo-dark { 
            mix-blend-mode: screen; 
            filter: brightness(1.1) contrast(1.1); 
            max-height: 35px;
            width: auto;
            object-fit: contain;
        }
        .blend-logo-light { 
            mix-blend-mode: multiply; 
            max-height: 35px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>
<body class="flex justify-center">

<?php 
    // buy.php se amount pakadne ka logic
    $amount = isset($_GET['amount']) ? $_GET['amount'] : "200"; 
?>

<div class="w-full max-w-[450px] bg-white h-screen flex flex-col relative border-x border-gray-100 shadow-2xl overflow-hidden">
    
    <div class="px-4 py-4 flex items-center justify-between border-b border-gray-100 bg-white sticky top-0 z-10">
        <div onclick="history.back()" class="p-1 cursor-pointer">
            <img src="https://img.icons8.com/material-rounded/24/000000/left.png" class="w-6 h-6 opacity-70">
        </div>
        <h1 class="text-[19px] font-bold flex-1 text-center">Select Method Payment</h1>
        <div class="flex gap-4 opacity-40">
            <img src="https://img.icons8.com/material-outlined/24/000000/refresh.png" class="w-5 h-5">
            <img src="https://img.icons8.com/material-outlined/24/000000/multiply.png" class="w-5 h-5">
        </div>
    </div>

    <div class="p-5 flex-1 overflow-y-auto no-scrollbar pb-10">
        <div class="mb-6 p-4 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
            <span class="text-gray-500 font-bold text-[14px]">Selected Amount:</span>
            <span class="text-black font-black text-[20px]">₹<?= number_format($amount, 2) ?></span>
        </div>

        <h2 class="text-[17px] font-bold text-gray-800 mb-1">Please select payment account:</h2>
        <p class="text-[11px] text-red-500 leading-tight mb-6 font-semibold uppercase tracking-tight">
            Please be sure to use the selected platform for payment, otherwise the payment will fail 100%.
        </p>

        <div class="space-y-4">
            
            <div onclick="goToPayment('Phonepe')" class="payment-card bg-phonepe rounded-2xl p-5 flex justify-between items-center text-white">
                <span class="text-[16px] font-bold tracking-wide">PhonePe</span>
                <div class="flex items-center">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUSsvOvs6Wu-pfQgW1u9LX1xeUURE-4cWZE0hfb67xgg&s=10" class="blend-logo-dark scale-125">
                </div>
            </div>

            <div onclick="goToPayment('Paytm')" class="payment-card bg-paytm rounded-2xl p-5 flex justify-between items-center shadow-sm">
                <span class="text-[16px] font-bold text-gray-800 tracking-wide">Paytm</span>
                <div class="flex items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/42/Paytm_logo.png" class="blend-logo-light">
                </div>
            </div>

            <div onclick="goToPayment('Mobikwik')" class="payment-card bg-mobikwik rounded-2xl p-5 flex justify-between items-center text-white">
                <span class="text-[16px] font-bold tracking-wide">MobiKwik</span>
                <div class="flex items-center">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQD_PUY42RsrX5RnLBhIwA6-TNbR-WZqYEJ5q-zyBcBfnIAiruukLjceA&s=10" class="blend-logo-dark brightness-150">
                </div>
            </div>

            <div onclick="goToPayment('Airtel')" class="payment-card bg-airtel rounded-2xl p-5 flex justify-between items-center text-white">
                <span class="text-[16px] font-bold tracking-wide">Airtel Bank</span>
                <div class="flex items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Airtel_payments_bank_logo.jpg" class="blend-logo-dark">
                </div>
            </div>

            <div onclick="goToPayment('Supermoney')" class="payment-card bg-supermoney rounded-2xl p-5 flex justify-between items-center text-white">
                <span class="text-[16px] font-bold tracking-wide">Super.money</span>
                <div class="flex items-center">
                    <img src="https://img-cdn.publive.online/fit-in/1200x675/entrackr/media/post_attachments/wp-content/uploads/2024/06/Super-Money-.png" class="blend-logo-dark scale-110">
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // URL se amount fetch karke aage bhej raha hai
    const currentAmount = "<?= $amount ?>";

    function goToPayment(method) {
        // Amount aur Method dono payment.php ko pass honge
        window.location.href = "payment.php?method=" + method + "&amount=" + currentAmount;
    }
</script>

</body>
</html>
