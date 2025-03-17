<?php 
session_start(); 
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = trim($_POST['usernameOrEmail']);
    $password        = $_POST['password'];

    // Hard-coded admin credentials:
    if ($usernameOrEmail === 'admin@gmail.com' && $password === 'admin123') {
        $_SESSION['user_id']  = 0; // Special ID for the hard-coded admin
        $_SESSION['username'] = 'admin';
        $_SESSION['usert_ype']     = 'admin';
        header('Location: admin_dashboard.php');
        exit;
    }

    // Normal login process
    if (!empty($usernameOrEmail) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :ue1 OR email = :ue2");
        $stmt->execute([
            'ue1' => $usernameOrEmail,
            'ue2' => $usernameOrEmail
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type']     = $user['user_type'];

            header('Location: index2.html');
            exit;
        } else {
            $error = 'Invalid credentials.';
        }
    } else {
        $error = 'Please enter both username/email and password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .error {
            color: #ff4d4f;
            background-color: #ffecec;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .options a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
        }

        .options a:hover {
            text-decoration: underline;
        }

        .btn {
            background-color: #4CAF50;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }
        .create-btn {
            background-color: #2196F3;
            color: #fff;
            margin-top: 10px;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            width: 100%;
    }

    .create-btn:hover {
        background-color: #1976D2;
    }
</style>
    </style>
</head>
<body>

<div class="glass-container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="usernameOrEmail">Username or Email</label>
        <input 
            type="text" 
            name="usernameOrEmail" 
            id="usernameOrEmail" 
            placeholder="Enter username or email" 
            required 
        />

        <label for="password">Password</label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Enter password" 
            required 
        />
    <button type="submit" class="btn">Login</button>

    <!-- Create Account Button -->
    <button type="button" class="btn create-btn" onclick="window.location.href='register.php'">Create Account</button>
</form>
    </form>
</div>

</body>
</html>
