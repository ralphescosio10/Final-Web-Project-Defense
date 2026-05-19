<?php

namespace Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);

        $file = dirname(__DIR__) . "/app/Views/{$view}.php";

        if (file_exists($file)) {
            require $file;
        } else {
            die("View file not found: " . $file);
        }
    }
}