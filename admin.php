<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch users
$usersQuery = "SELECT id, name, email, role, status, created_at FROM users";
$usersResult = mysqli_query($con, $usersQuery);
$users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);

// Fetch feedbacks
$feedbackQuery = "SELECT id, name, email, feedback_type, message, reply, created_at FROM feedback";
$feedbackResult = mysqli_query($con, $feedbackQuery);
$feedbacks = mysqli_fetch_all($feedbackResult, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_feedback_id'])) {
    $feedbackId = $_POST['reply_feedback_id'];
    $reply = $_POST['reply'];

    $stmt = $con->prepare("UPDATE feedback SET reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $feedbackId);

    if ($stmt->execute()) {
        $_SESSION['notification'] = 'Reply sent successfully!';
        $_SESSION['notification_type'] = 'success';
    } else {
        $_SESSION['notification'] = 'Error: ' . $stmt->error;
        $_SESSION['notification_type'] = 'error';
    }

    $stmt->close();
    header('Location: admin_dashboard.php');
    exit();
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Eco Power Solutions</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --accent-color: #FFC107;
            --background-color: #FFFFFF;
            --text-color: #333333;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }

        h1, h2 {
            color: var(--primary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        .notification {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
            display: none;
        }

        .notification.success {
            background-color: var(--primary-color);
            color: white;
        }

        .notification.error {
            background-color: #f44336;
            color: white;
        }

        .btn {
            display: inline-block;
            background: var(--accent-color);
            color: var(--text-color);
            padding: 0.5rem 1rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 30px;
            text-decoration: none;
            transition: opacity 0.2s ease-in;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <?php if (isset($_SESSION['notification'])): ?>
            <div class="notification <?php echo $_SESSION['notification_type']; ?>">
                <?php echo $_SESSION['notification']; unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
            </div>
        <?php endif; ?>

        <h2>Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['status']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Feedbacks</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Feedback Type</th>
                    <th>Message</th>
                    <th>Reply</th>
                    <th>Actions</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo $feedback['id']; ?></td>
                        <td><?php echo $feedback['name']; ?></td>
                        <td><?php echo $feedback['email']; ?></td>
                        <td><?php echo $feedback['feedback_type']; ?></td>
                        <td><?php echo $feedback['message']; ?></td>
                        <td><?php echo $feedback['reply']; ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <textarea name="reply" placeholder="Reply here..." required></textarea>
                                <input type="hidden" name="reply_feedback_id" value="<?php echo $feedback['id']; ?>">
                                <button type="submit" class="btn">Reply</button>
                            </form>
                        </td>
                        <td><?php echo $feedback['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
