<?php
// Database configuration
define('DB_HOST', 'db');
define('DB_NAME', 'app_db');
define('DB_USER', 'root');
define('DB_PASS', 'root_password');

// Application configuration
define('APP_NAME', 'MyProject');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // production, development

// Paths
define('BASE_PATH', '/');
define('CSS_PATH', '/public/css/');
define('JS_PATH', '/public/js/');
define('UPLOAD_PATH', '/public/uploads/');

// Security
define('ENCRYPTION_KEY', 'your-secret-key-here');

// Smarty configuration
function getSmartyConfig() {
    return [
        'template_dir' => __DIR__ . '/../templates',
        'compile_dir' => '/var/cache/smarty/templates_c',
        'cache_dir' => '/var/cache/smarty/cache',
        'config_dir' => '/var/cache/smarty/configs',
        'caching' => APP_ENV === 'production',
        'cache_lifetime' => 3600,
        'debugging' => APP_ENV === 'development'
    ];
}

// Get template variables
function getTemplateVariables() {
    return [
        'app_name' => APP_NAME,
        'app_version' => APP_VERSION,
        'app_env' => APP_ENV,
        'css_path' => CSS_PATH . 'main.css',
        'js_path' => JS_PATH . 'main.js',
        'base_url' => BASE_PATH,
        'version' => time(), // Для кэширования
        'current_year' => date('Y')
    ];
}
?>