<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        form {
            display: none;
        }
        .active-form {
            display: block;
        }
        .form-section {
            margin-top: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Choose an action:</h2>
        <button id="registerBtn">Register</button>
        <button id="loginBtn">Login</button>

        <div class="form-section">
            <form id="loginModeForm">
                <h2>Login Mode</h2>
                <button type="button" id="loginGuestModeBtn">Guest Mode</button>
                <button type="button" id="loginUserModeBtn">User Mode</button>
            </form>

            <form id="registerModeForm">
                <h2>Register Mode</h2>
                <button type="button" id="registerGuestModeBtn">Guest Mode</button>
                <button type="button" id="registerUserModeBtn">User Mode</button>
            </form>

            <form id="loginForm" action="login.php" method="post">
                <h2>Login</h2>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
            </form>

            <form id="loginGuestForm" action="guestlogin.php" method="post">
                <h2>Guest Login</h2>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <button type="submit">Proceed as Guest</button>
            </form>

            <form id="registerForm" action="register.php" method="post">
                <h2>Register</h2>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Register</button>
            </form>

            <form id="registerGuestForm" action="guest.php" method="post">
                <h2>Guest Access</h2>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <button type="submit">Proceed as Guest</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginModeForm').classList.add('active-form');
            document.getElementById('registerModeForm').classList.remove('active-form');
            document.getElementById('loginForm').classList.remove('active-form');
            document.getElementById('loginGuestForm').classList.remove('active-form');
            document.getElementById('registerForm').classList.remove('active-form');
            document.getElementById('registerGuestForm').classList.remove('active-form');
        });

        document.getElementById('registerBtn').addEventListener('click', function() {
            document.getElementById('registerModeForm').classList.add('active-form');
            document.getElementById('loginModeForm').classList.remove('active-form');
            document.getElementById('loginForm').classList.remove('active-form');
            document.getElementById('loginGuestForm').classList.remove('active-form');
            document.getElementById('registerForm').classList.remove('active-form');
            document.getElementById('registerGuestForm').classList.remove('active-form');
        });

        document.getElementById('loginUserModeBtn').addEventListener('click', function() {
            document.getElementById('loginForm').classList.add('active-form');
            document.getElementById('loginGuestForm').classList.remove('active-form');
            document.getElementById('loginModeForm').classList.remove('active-form');
        });

        document.getElementById('loginGuestModeBtn').addEventListener('click', function() {
            document.getElementById('loginGuestForm').classList.add('active-form');
            document.getElementById('loginForm').classList.remove('active-form');
            document.getElementById('loginModeForm').classList.remove('active-form');
        });

        document.getElementById('registerUserModeBtn').addEventListener('click', function() {
            document.getElementById('registerForm').classList.add('active-form');
            document.getElementById('registerGuestForm').classList.remove('active-form');
            document.getElementById('registerModeForm').classList.remove('active-form');
        });

        document.getElementById('registerGuestModeBtn').addEventListener('click', function() {
            document.getElementById('registerGuestForm').classList.add('active-form');
            document.getElementById('registerForm').classList.remove('active-form');
            document.getElementById('registerModeForm').classList.remove('active-form');
        });
    </script>
</body>
</html>