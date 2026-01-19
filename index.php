<?php
require_once 'config.php';

// If already logged in, redirect to appropriate dashboard
if (isset($_SESSION['superadmin_id'])) {
    header('Location: superadmin/dashboard.php');
    exit;
} elseif (isset($_SESSION['user_id'])) {
    header('Location: user/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'user';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $conn = getDBConnection();
        
        if ($user_type === 'superadmin') {
            // Check superadmin credentials
            $stmt = $conn->prepare("SELECT id, password FROM superadmin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['superadmin_id'] = $row['id'];
                    $_SESSION['superadmin_username'] = $username;
                    header('Location: superadmin/dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid credentials';
                }
            } else {
                $error = 'Invalid credentials';
            }
            $stmt->close();
        } else {
            // Check user credentials
            $stmt = $conn->prepare("SELECT id, password, full_name FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_username'] = $username;
                    $_SESSION['user_fullname'] = $row['full_name'];
                    header('Location: user/dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid credentials';
                }
            } else {
                $error = 'Invalid credentials';
            }
            $stmt->close();
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2>Survey System</h2>
            <p class="text-muted">Please login to continue</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="user_type" id="user" value="user" checked>
                    <label class="form-check-label" for="user">
                        User
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="user_type" id="superadmin" value="superadmin">
                    <label class="form-check-label" for="superadmin">
                        Superadmin
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
