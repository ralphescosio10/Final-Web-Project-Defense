<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .header-container { display: flex; justify-content: space-between; align-items: center; }
        .logout-btn { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        
        .action-links a { 
            text-decoration: underline; 
            margin-right: 5px; 
            margin-left: 5px;
            font-size: 0.9em !important; 
        }

        .modal {
            display: none; 
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); 
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 25px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .close-btn {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close-btn:hover { color: #000; }

        .modal-form input, .modal-form textarea { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ccc; 
            border-radius: 4px;
            box-sizing: border-box; 
        }
        
        .save-btn { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .open-modal-btn { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px; }

        .delete-btn-confirm { background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; border:none; cursor:pointer; }
        .cancel-btn { background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }

        .error-msg { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 10px; }
        th { background-color: #f2f2f2; }
        .status-completed { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>

<div class="header-container">
    <h1>Task Manager</h1>
    <button id="openLogoutBtn" class="logout-btn">Logout</button>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="error-msg">⚠️ <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<button id="openModalBtn" class="open-modal-btn">+ New Task</button>

<div id="addTaskModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeAddBtn">&times;</span>
        <h2 style="margin-top: 0;">Add New Task</h2>
        <form id="addTaskForm" method="POST" action="/store" class="modal-form" novalidate>
            <label>Task ID:</label>
            <input type="number" name="id" min="1" required>
            <label>Task Name:</label>
            <input type="text" name="title" required maxlength="255">
            <label>Description:</label>
            <textarea name="description" rows="4"></textarea>
            <button type="button" id="triggerConfirmAddBtn" class="save-btn">Save Task</button>
        </form>
    </div>
</div>

<div id="addConfirmModal" class="modal">
    <div class="modal-content" style="text-align: center; width: 350px;">
        <h2 style="color: #28a745; margin-top: 0;">Confirm New Task</h2>
        <p>Are you sure you want to add this task to your list?</p>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <button type="button" class="cancel-btn" id="closeAddConfirmBtn">Cancel</button>
            <button type="button" class="delete-btn-confirm" style="background-color: #28a745;" id="finalAddBtn">Yes, Add it</button>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content" style="text-align: center; width: 350px;">
        <h2 style="color: #721c24; margin-top: 0;">Are you sure?</h2>
        <p>This will permanently delete this task.</p>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <button type="button" class="cancel-btn" id="closeDeleteBtn">Cancel</button>
            <a id="confirmDeleteLink" href="#" class="delete-btn-confirm">Yes, Delete</a>
        </div>
    </div>
</div>

<div id="logoutModal" class="modal">
    <div class="modal-content" style="text-align: center; width: 300px;">
        <h2 style="margin-top: 0;">Logging out?</h2>
        <p>Are you sure you want to end your session?</p>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <button type="button" class="cancel-btn" id="closeLogoutBtn">Stay</button>
            <a href="/logout" class="delete-btn-confirm" style="background-color: #dc3545;">Logout</a>
        </div>
    </div>
</div>

<div id="editTaskModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeEditBtn">&times;</span>
        <h2 style="margin-top: 0;">Edit Task</h2>
        <form id="editTaskForm" method="POST" action="/update" class="modal-form" novalidate>
            <input type="hidden" name="id" id="editTaskId">
            
            <label>Task ID:</label>
            <input type="text" id="displayEditId" readonly style="background-color: #f0f0f0; border: 1px solid #ddd; cursor: not-allowed;">
            
            <label>Task Name:</label>
            <input type="text" name="title" id="editTaskTitle" required maxlength="255">
            
            <label>Description:</label>
            <textarea name="description" id="editTaskDesc" rows="4"></textarea>
            
            <button type="button" id="triggerConfirmUpdateBtn" class="save-btn" style="background-color: #007bff;">Update Task</button>
        </form>
    </div>
</div>

<div id="updateConfirmModal" class="modal">
    <div class="modal-content" style="text-align: center; width: 350px;">
        <h2 style="color: #004085; margin-top: 0;">Confirm Update</h2>
        <p>Are you sure you want to save these changes?</p>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <button type="button" class="cancel-btn" id="closeConfirmBtn">Cancel</button>
            <button type="button" class="delete-btn-confirm" style="background-color: #007bff;" id="finalSaveBtn">Yes, Update</button>
        </div>
    </div>
</div>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Last Edited</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><strong><?= htmlspecialchars($task['title']) ?></strong></td>
                <td><?= htmlspecialchars($task['description'] ?? 'No description') ?></td>
                <td><?= date('M d, Y H:i', strtotime($task['updated_at'])) ?></td>
                <td>
                    <span class="<?= $task['status'] === 'completed' ? 'status-completed' : 'status-pending' ?>">
                        <?= ucfirst($task['status']) ?>
                    </span>
                </td>
                <td class="action-links">
                    <a href="/complete?id=<?= $task['id'] ?>" style="color: <?= $task['status'] === 'completed' ? '#856404' : '#155724' ?>;">
                        <?= $task['status'] === 'completed' ? 'Undo' : 'Complete' ?>
                    </a>
                    <span style="color: #ccc;">|</span> 
                    
                    <a href="javascript:void(0)" 
                       style="color: #004085;" 
                       onclick="showEditModal(<?= $task['id'] ?>, '<?= addslashes(htmlspecialchars($task['title'])) ?>', '<?= addslashes(htmlspecialchars($task['description'] ?? '')) ?>')">
                       Edit
                    </a>

                    <span style="color: #ccc;">|</span>
                    
                    <a href="javascript:void(0)" 
                       style="color: #721c24;" 
                       onclick="showDeleteModal(<?= $task['id'] ?>)">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align: center;">No tasks found. Click "+ New Task" to begin!</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    const addModal = document.getElementById("addTaskModal");
    const addConfirmModal = document.getElementById("addConfirmModal");
    const deleteModal = document.getElementById("deleteModal");
    const logoutModal = document.getElementById("logoutModal");
    const editModal = document.getElementById("editTaskModal");
    const updateConfirmModal = document.getElementById("updateConfirmModal");

    const openAddBtn = document.getElementById("openModalBtn");
    const openLogoutBtn = document.getElementById("openLogoutBtn");
    const confirmDeleteLink = document.getElementById("confirmDeleteLink");
    
    const triggerConfirmAddBtn = document.getElementById("triggerConfirmAddBtn");
    const finalAddBtn = document.getElementById("finalAddBtn");
    const addForm = document.getElementById("addTaskForm");

    const triggerConfirmUpdateBtn = document.getElementById("triggerConfirmUpdateBtn");
    const finalSaveBtn = document.getElementById("finalSaveBtn");
    const editForm = document.getElementById("editTaskForm");

    openAddBtn.onclick = () => addModal.style.display = "block";
    openLogoutBtn.onclick = () => logoutModal.style.display = "block";

    function showDeleteModal(taskId) {
        confirmDeleteLink.href = "/delete?id=" + taskId;
        deleteModal.style.display = "block";
    }

    // FIX: Updates both the hidden update field and the visible static display box
    function showEditModal(id, title, description) {
        document.getElementById("editTaskId").value = id;
        document.getElementById("displayEditId").value = id;
        document.getElementById("editTaskTitle").value = title;
        document.getElementById("editTaskDesc").value = description;
        editModal.style.display = "block";
    }

    triggerConfirmAddBtn.onclick = () => {
        addConfirmModal.style.display = "block";
    };

    finalAddBtn.onclick = () => {
        addForm.submit();
    };

    triggerConfirmUpdateBtn.onclick = () => {
        updateConfirmModal.style.display = "block";
    };

    finalSaveBtn.onclick = () => {
        editForm.submit();
    };

    document.getElementById("closeAddBtn").onclick = () => addModal.style.display = "none";
    document.getElementById("closeAddConfirmBtn").onclick = () => addConfirmModal.style.display = "none";
    document.getElementById("closeDeleteBtn").onclick = () => deleteModal.style.display = "none";
    document.getElementById("closeLogoutBtn").onclick = () => logoutModal.style.display = "none";
    document.getElementById("closeEditBtn").onclick = () => editModal.style.display = "none";
    document.getElementById("closeConfirmBtn").onclick = () => updateConfirmModal.style.display = "none";

    window.onclick = function(event) {
        if (event.target == addModal) addModal.style.display = "none";
        if (event.target == addConfirmModal) addConfirmModal.style.display = "none";
        if (event.target == deleteModal) deleteModal.style.display = "none";
        if (event.target == logoutModal) logoutModal.style.display = "none";
        if (event.target == editModal) editModal.style.display = "none";
        if (event.target == updateConfirmModal) updateConfirmModal.style.display = "none";
    }
</script>

</body>
</html>