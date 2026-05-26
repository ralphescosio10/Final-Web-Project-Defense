<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f4f4f9; }
        .login-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        h2 { margin-top: 0; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .footer { text-align: center; margin-top: 15px; font-size: 0.9rem; }
        .error-message { color: red; text-align: center; font-weight: bold; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
    <?php 
    $errorMessage = '';
    
    // Check if the Controller passed a local variable error
    if (isset($error)) {
        $errorMessage = $error;
    } 
    // Check if the Controller passed a URL query key
    elseif (isset($_GET['error'])) {
        if ($_GET['error'] === 'invalid') {
            $errorMessage = "Invalid username or password";
        } else {
            $errorMessage = htmlspecialchars($_GET['error']);
        }
    }
    
    // Only display the error block if there is actually an error message
    if (!empty($errorMessage)): ?>
        <div class="error-message">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login" novalidate>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="footer">
        Don't have an account? <a href="/register">Register here</a>
    </div>
</div>

</body>
</html>