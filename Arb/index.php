<?php
session_start();

if (isset($_SESSION['user_phone'])) {
    header("Location: /home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Account Login</title>
    <style>
        /* Exact Theme Colors */
        :root {
            --brand-yellow: #ffc107; /* The exact Log In button yellow */
            --bg-gray: #f8f8f8;      /* Password field background */
            --text-dark: #1a1a1a;
            --text-gray: #9e9e9e;
            --border-light: #eeeeee;
        }

        * { box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; }
        
        body { 
            margin: 0; 
            padding: 24px; 
            background-color: #ffffff; 
            -webkit-font-smoothing: antialiased;
        }

        /* Language Selector Top Right */
        .lang-selector {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
            color: #444;
            font-size: 15px;
            margin-bottom: 40px;
        }
        .lang-selector svg { width: 18px; height: 18px; color: #666; }

        /* Typography */
        h1 {
            font-size: 34px;
            font-weight: 700;
            margin: 0 0 35px 0;
            color: #212529;
            letter-spacing: -0.5px;
        }

        .label {
            display: block;
            font-size: 15px;
            font-weight: 500;
            color: #444;
            margin-bottom: 12px;
        }

        /* Input Group - Phone (Yellow Border) */
        .input-group-phone {
            display: flex;
            align-items: center;
            border: 1px solid var(--brand-yellow);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }
        .flag-box { display: flex; align-items: center; gap: 8px; padding-right: 12px; border-right: 1px solid var(--border-light); }
        .flag-box img { width: 24px; border-radius: 2px; }
        .country-code { font-size: 16px; color: var(--text-dark); font-weight: 500; }

        /* Input Group - Password (Gray BG) */
        .pw-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .forgot-link { color: var(--brand-yellow); text-decoration: none; font-size: 15px; font-weight: 500; }

        .input-group-password {
            display: flex;
            align-items: center;
            background-color: var(--bg-gray);
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 45px;
        }

        input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 16px;
            color: var(--text-dark);
            padding-left: 10px;
        }
        input::placeholder { color: #cccccc; }

        .eye-icon { color: #b0b0b0; cursor: pointer; display: flex; align-items: center; }

        /* Buttons */
        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            text-align: center;
            border: none;
            cursor: pointer;
            margin-bottom: 16px;
            transition: opacity 0.2s;
        }
        .btn-login { background-color: var(--brand-yellow); color: #000; }
        .btn-login:disabled { opacity: 0.6; }
        .btn-help { background-color: #f1f3f5; color: #495057; }

        /* Footer */
        .footer {
            margin-top: 30px;
            font-size: 15px;
            color: var(--text-dark);
        }
        .footer a { color: var(--brand-yellow); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

    <div class="lang-selector">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
        <span>English</span>
    </div>

    <h1>Account Login</h1>

    <form method="POST" action="login.php" id="loginForm">
        <label class="label">Phone Number</label>
        <div class="input-group-phone">
            <div class="flag-box">
                <img src="https://flagcdn.com/w40/in.png" alt="IN">
                <span class="country-code">+91</span>
            </div>
            <input type="number" name="phone" id="phone" placeholder="Please enter your phone number." required>
        </div>

        <div class="pw-header">
            <label class="label">Password</label>
            <a href="#" class="forgot-link">Forgot Password?</a>
        </div>
        <div class="input-group-password">
            <input type="password" name="password" id="password" placeholder="Please enter your login password." required>
            <div class="eye-icon" id="eyeToggle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>
        </div>

        <button type="submit" class="btn btn-login" id="loginBtn" disabled>Log In</button>
        <button type="button" class="btn btn-help">Help Center</button>
    </form>

    <div class="footer">
        No Account? <a href="#">Register Now»</a>
    </div>

    <script>
        const phone = document.getElementById('phone');
        const pass = document.getElementById('password');
        const btn = document.getElementById('loginBtn');
        const eye = document.getElementById('eyeToggle');

        // Toggle Password Visibility
        eye.addEventListener('click', () => {
            const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
            pass.setAttribute('type', type);
        });

        // Enable Login Button only when Phone is 10 digits
        function validate() {
            if (phone.value.length === 10 && pass.value.length > 0) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }

        phone.addEventListener('input', (e) => {
            if (e.target.value.length > 10) e.target.value = e.target.value.slice(0, 10);
            validate();
        });
        pass.addEventListener('input', validate);
    </script>
</body>
</html>
