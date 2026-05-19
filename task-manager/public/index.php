<?php
// Start the session so user_id is available for your tasks
session_start();

require __DIR__ . '/../vendor/autoload.php';

// This executes your switch statement in web.php immediately
require "../routes/web.php";