<?php
session_start();
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $email, $hashedPassword, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;

            header('Location: admin.php');
            exit();
        } else {
            $_SESSION['notification'] = 'Invalid password.';
            $_SESSION['notification_type'] = 'error';
        }
    } else {
        $_SESSION['notification'] = 'No account found with that email.';
        $_SESSION['notification_type'] = 'error';
    }

    $stmt->close();
}

mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Power Solutions - Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/osorio-wind-farm-1403824_1920.jpg') no-repeat center center/cover;
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
            color: #4CAF50;
            margin-bottom: 1rem;
        }
        .notification {
            background-color: #f44336; /* Red for error */
            color: white;
            text-align: center;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
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
            background: #FFC107;
            color: #333333;
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
        .toggle-form {
            text-align: center;
            margin-top: 1rem;
        }
        .toggle-form a {
            color: #2196F3;
            text-decoration: none;
        }
        .toggle-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($_SESSION['notification'])): ?>
            <div class="notification" style="display:block;"><?php echo $_SESSION['notification']; unset($_SESSION['notification']); ?></div>
        <?php endif; ?>
        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="toggle-form">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>
