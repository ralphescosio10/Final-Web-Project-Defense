Task Manager (PHP MVC Project)
Description
This is a web-based Task Management system built using the Model-View-Controller (MVC) architecture. The application allows users to create, read, update, and delete tasks while managing their own accounts through a secure login system.

MVC Architecture
The project is divided into three main components to ensure clean code:

Model: Manages the data and database logic (e.g., Task.php, User.php).

View: Handles the user interface and what the user sees (e.g., tasks.php, login.php).

Controller: Processes user requests and connects the Model and View (e.g., TaskController.php).

Features
User Registration and Login.

Create Tasks with Manual ID input.

Edit Task titles and descriptions.

Delete tasks from the database.

Toggle task status between Pending and Completed.

Setup Instructions
Database: Import task_manager.sql into MySQL Workbench.

Server: Run the project using a local PHP server (e.g., php -S localhost:8000).

Access: Open your browser and go to localhost:8000.

Technologies Used
PHP (Backend Logic).

MySQL (Database).

HTML/CSS (Frontend UI).

JavaScript (Modals and Interactions).