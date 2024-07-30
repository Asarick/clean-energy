<?php
session_start();
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password != $confirmPassword) {
        $_SESSION['notification'] = 'Passwords do not match.';
        $_SESSION['notification_type'] = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['notification'] = 'Invalid email format.';
        $_SESSION['notification_type'] = 'error';
    } else {
        // Check email format for admin and insurer
        if (preg_match("/@agriconnect\.admin$/", $email)) {
            $role = 'admin';
        } elseif (preg_match("/@agriconnect\.insurer$/", $email)) {
            $role = 'insurer';
        } else {
            $role = 'user';
        }

        // Check if an admin account already exists
        if ($role == 'admin') {
            $checkAdminQuery = "SELECT COUNT(*) AS admin_count FROM users WHERE role = 'admin'";
            $result = mysqli_query($con, $checkAdminQuery);
            $row = mysqli_fetch_assoc($result);
            if ($row['admin_count'] > 0) {
                $_SESSION['notification'] = 'Admin account already exists and cannot be created again.';
                $_SESSION['notification_type'] = 'error';
                header('Location: signup.php');
                exit();
            }
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 'active')";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashedPassword, $role);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['notification'] = 'Signup successful! Please login.';
                $_SESSION['notification_type'] = 'success';
                header('Location: login.php');
                exit();
            } else {
                $_SESSION['notification'] = 'Error: ' . mysqli_error($con);
                $_SESSION['notification_type'] = 'error';
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['notification'] = 'Error: Could not prepare statement.';
            $_SESSION['notification_type'] = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Power Solutions - Signup</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --accent-color: #FFC107;
            --background-color: #FFFFFF;
            --text-color: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: url('images/pinwheels-6535595_1920.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            background: var(--accent-color);
            color: var(--text-color);
            padding: 0.8rem 1.5rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 30px;
            text-decoration: none;
            transition: opacity 0.2s ease-in;
            width: 100%;
            text-align: center;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .notification {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Eco Power Solutions</h1>
        <p class="welcome-message">Welcome to a greener future! To start making a difference, please create an account.</p>

        <?php if (isset($_SESSION['notification'])): ?>
        <div class="notification alert alert-<?php echo $_SESSION['notification_type']; ?>">
            <?php echo $_SESSION['notification']; unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
        </div>
        <?php endif; ?>

        <form id="signup-form" method="POST">
            <div class="form-group">
                <label for="signup-name">Full Name:</label>
                <input type="text" id="signup-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email:</label>
                <input type="email" id="signup-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Password:</label>
                <input type="password" id="signup-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
    </div>
</body>
</html>
