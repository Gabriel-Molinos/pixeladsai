<?php

function dd($data = null){
    echo "<pre>";
    print_r($data);
    die();
}

function base_path($path) {

    return __DIR__ . "/../" . $path;

}

function autoload(){
    spl_autoload_register(function($class) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        require base_path("{$class}.php");
    });
}

function abort(){
    http_response_code(404);
    return header('location: /');
}

function view($view, $data = [], $layout = 'app')
{
    extract($data);

    ob_start();
    require BASE_PATH . "/Views/{$view}.view.php";
    $content = ob_get_clean();

    require BASE_PATH . "/Views/templates/{$layout}.php";
}

function auth() {

    if(! isset($_SESSION['user'])) {

        return null;

    }

    return $_SESSION['user'];

}

function partial($name, $data = [])
{
    extract($data);

    $path = BASE_PATH . "/Views/partials/{$name}.view.php";

    if (!file_exists($path)) {
        die("Partial não encontrada: {$path}");
    }

    require $path;
}