<?php
session_start();


// Database configuration
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "tsubaki_floral";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

// Handle login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // $result = password_verify($password, $user['password']);
            // echo "Aa: ".$result;
            // echo $user['password'];
            // echo $password;
            // print password_hash("admin123", PASSWORD_DEFAULT);

            // echo "/n";
            // echo "/n";

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($user['role'] == 'admin') {
                    echo "Login Successful";
                    header("Location: admin.php");
                    $_SESSION['admin_logged_in'] = true;
                    exit;
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error_message = "Invalid email or password!";
            }
        } else {
            $error_message = "Invalid email or password!";
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all fields!";
    }
}

// Handle registration
if (isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['reg_email']);
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user'; // Default role
                
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
                
                if ($stmt->execute()) {
                    $success_message = "Registration successful! You can now login.";
                } else {
                    $error_message = "Registration failed. Please try again.";
                }
            } else {
                $error_message = "Email already exists!";
            }
            $stmt->close();
        } else {
            $error_message = "Passwords do not match!";
        }
    } else {
        $error_message = "Please fill in all fields!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsubaki Floral - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #d4546a;
            --secondary-color: #f8d7da;
            --accent-color: #86c5a6;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --muted-color: #6c757d;
            --success-color: #86c5a6;
            --border-radius: 12px;
            --transition: all 0.3s ease;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fef7f7 0%, #fff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c44569 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .login-subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 30px;
        }

        .form-tabs {
            display: flex;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            background: var(--light-color);
            padding: 5px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: calc(var(--border-radius) - 3px);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            color: var(--muted-color);
        }

        .tab-btn.active {
            background: white;
            color: var(--primary-color);
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(212, 84, 106, 0.25);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c44569 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(212, 84, 106, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 84, 106, 0.4);
        }

        .alert {
            padding: 12px 16px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d1eddb;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-link a:hover {
            color: #c44569;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .login-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1 class="login-title">Tsubaki Floral</h1>
            <p class="login-subtitle">Beautiful flowers for every occasion</p>
        </div>
        
        <div class="login-body">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <div class="form-tabs">
                <button type="button" class="tab-btn active" onclick="showLogin()">Login</button>
                <button type="button" class="tab-btn" onclick="showRegister()">Register</button>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="form-section active">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="form-section">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="reg_username" class="form-label">Username</label>
                        <input type="text" id="reg_username" name="reg_username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_email" class="form-label">Email Address</label>
                        <input type="email" id="reg_email" name="reg_email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_password" class="form-label">Password</label>
                        <input type="password" id="reg_password" name="reg_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </form>
            </div>

            <div class="back-link">
                <a href="index.php">← Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('loginForm').classList.add('active');
            document.getElementById('registerForm').classList.remove('active');
            document.querySelectorAll('.tab-btn')[0].classList.add('active');
            document.querySelectorAll('.tab-btn')[1].classList.remove('active');
        }

        function showRegister() {
            document.getElementById('registerForm').classList.add('active');
            document.getElementById('loginForm').classList.remove('active');
            document.querySelectorAll('.tab-btn')[1].classList.add('active');
            document.querySelectorAll('.tab-btn')[0].classList.remove('active');
        }
    </script>
</body>
</html>