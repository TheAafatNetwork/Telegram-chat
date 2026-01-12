<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Account Login</title>

    <style>
        :root {
            --brand-yellow: #ffc107;
            --bg-gray: #f8f8f8;
            --text-dark: #1a1a1a;
            --text-gray: #9e9e9e;
            --border-light: #eeeeee;
        }

        * { box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; }

        body {
            margin: 0;
            padding: 24px;
            background-color: #ffffff;
        }

        h1 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .label {
            font-size: 15px;
            margin-bottom: 12px;
            display: block;
        }

        .input-group-phone {
            display: flex;
            align-items: center;
            border: 1px solid var(--brand-yellow);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }

        .flag-box {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-right: 12px;
            border-right: 1px solid var(--border-light);
        }

        .flag-box img { width: 24px; }

        .input-group-password {
            display: flex;
            align-items: center;
            background-color: var(--bg-gray);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 45px;
        }

        input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            background: transparent;
            padding-left: 10px;
        }

        .eye-icon { cursor: pointer; }

        .btn {
            width: 100%;
            padding: 16px;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background-color: var(--brand-yellow);
            margin-bottom: 16px;
        }

        .btn-login:disabled { opacity: 0.6; }

        .btn-help { background-color: #f1f3f5; }
    </style>
</head>

<body>

<h1>Account Login</h1>

<form method="POST" action="login.php" id="loginForm">

    <label class="label">Phone Number</label>
    <div class="input-group-phone">
        <div class="flag-box">
            <img src="https://flagcdn.com/w40/in.png">
            <span>+91</span>
        </div>

        <!-- FIXED INPUT TYPE -->
        <input
            type="tel"
            name="phone"
            id="phone"
            inputmode="numeric"
            placeholder="Please enter your phone number."
            required
        >
    </div>

    <label class="label">Password</label>
    <div class="input-group-password">
        <input type="password" name="password" id="password" required>
        <div class="eye-icon" id="eyeToggle">👁</div>
    </div>

    <button type="submit" class="btn btn-login" id="loginBtn" disabled>Log In</button>
    <button type="button" class="btn btn-help">Help Center</button>
</form>

<script>
    const phone = document.getElementById('phone');
    const pass  = document.getElementById('password');
    const btn   = document.getElementById('loginBtn');
    const eye   = document.getElementById('eyeToggle');

    eye.addEventListener('click', () => {
        pass.type = pass.type === 'password' ? 'text' : 'password';
    });

    function validate() {
        const phoneVal = phone.value.replace(/\D/g, '');
        if (phoneVal.length === 10 && pass.value.trim().length > 0) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    phone.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
        validate();
    });

    pass.addEventListener('input', validate);
</script>

</body>
</html>
