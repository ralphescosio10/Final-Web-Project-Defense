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
        
        /* --- MODAL STYLES --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Dim backgrounds */
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 999;
        }

        /* Displays the modal */
        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 260px;
            text-align: center;
        }

        .modal-content p {
            font-size: 1rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-yes {
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            width: 100%;
            display: block;
            font-size: 0.9rem;
            font-weight: bold;
            box-sizing: border-box;
        }
        .btn-yes:hover { background-color: #218838; }

        .btn-no {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .btn-no:hover { background-color: #c82333; }
        
        /* Style for trigger pointer hook */
        .open-modal-trigger {
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }
        .open-modal-trigger:hover { text-decoration: underline; }
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
        Don't have an account? <span class="open-modal-trigger" id="openModal">Register here</span>
    </div>
</div>

<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <p>Would you like to register an account?</p>
        <div class="modal-buttons">
            <a href="/register" class="btn-yes">Yes</a>
            <button class="btn-no" id="closeModal">No</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('confirmModal');
    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');

    // Display overlay
    openBtn.addEventListener('click', () => {
        modal.classList.add('active');
    });

    // Remove overlay via 'No' button selection
    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    // Close window if clicking anywhere on the outer dark backdrop
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
</script>

</body>
</html>