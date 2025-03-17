<?php
session_start();
require_once __DIR__ . '/config.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Initialize error and success messages
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['usertype']; // Fixed here

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            'username' => $username,
            'email'    => $email
        ]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or Email already exists.';
        }
    }

    // If no errors, insert the new user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, user_type) VALUES (:username, :email, :password, :user_type)"); // Fixed syntax error
        $stmt->execute([
            'username'  => $username,
            'email'     => $email,
            'password'  => $hashedPassword,
            'user_type' => $role
        ]);
        $success = "User created successfully! They can now log in.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New User</title>
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

        .error, .success {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
        }

        .error {
            color: #ff4d4f;
            background-color: #ffecec;
            border: 1px solid #ff4d4f;
        }

        .success {
            color: #4CAF50;
            background-color: #e6f7e6;
            border: 1px solid #4CAF50;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        select {
            appearance: none;
            background-color: #fff;
            cursor: pointer;
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

        p {
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="glass-container">
    <h2>Create New User</h2>

    <!-- Display Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <?php echo htmlspecialchars($error) . "<br>"; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Success Message -->
    <?php if ($success): ?>
        <div class="success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="create_user.php" method="POST">
        <label for="username">Username</label>
        <input 
            type="text" 
            name="username" 
            id="username" 
            placeholder="Enter username" 
            required
        />

        <label for="email">Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            placeholder="Enter email" 
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

        <label for="usertype">Usertype</label>
        <select name="usertype" id="usertype" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" class="btn">Create User</button>
    </form>

    <p>
        <a href="admin_dashboard.php">Back to Admin Dashboard</a>
    </p>
</div>

</body>
</html>
