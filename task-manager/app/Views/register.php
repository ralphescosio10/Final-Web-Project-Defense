<h2>Register</h2>
<?php if (isset($_GET['error'])): ?>
    <div style="color: red; margin-bottom: 10px;">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>
<form method="POST" action="/register">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Create Account</button>
</form>
<a href="/login">Already have an account? Login here.</a>