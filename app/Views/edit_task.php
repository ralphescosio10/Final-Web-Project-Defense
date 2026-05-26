<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .edit-container { max-width: 400px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        input[readonly] { background-color: #f9f9f9; cursor: not-allowed; color: #666; }
        button { background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .cancel-link { color: #666; text-decoration: none; margin-left: 10px; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Task</h2>

    <?php if ($task): ?>
        <form method="POST" action="/update">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">

            <div class="form-group">
                <label>Task ID (Read-only):</label>
                <input type="text" value="<?= $task['id'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Task Name:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
            </div>

            <button type="submit">Update Task</button>
            <a href="/" class="cancel-link">Cancel</a>
        </form>
    <?php else: ?>
        <p>Task not found.</p>
        <a href="/">Back to Dashboard</a>
    <?php endif; ?>
</div>

</body>
</html>