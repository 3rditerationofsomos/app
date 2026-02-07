<?php

require_once 'configs/config.php';
require_once 'vendor/autoload.php';
require_once 'functions.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');

$smarty->assign('css_path', '/public/css/main.css');

// Assign content template and id
$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);

$page = 'home_page.tpl';
$id = 0;
if (preg_match('/^\/home\/(\d+)/', $path, $matches)) {
    $page = 'home_page.tpl';
} elseif (preg_match('/^\/category\/(\d+)/', $path, $matches)) {
    $page = 'category_page.tpl';
    $id = $matches[1];
} elseif (preg_match('/^\/article\/(\d+)/', $path, $matches)) {
    $page = 'article_page.tpl';
    $id = $matches[1];
}

$smarty->assign('page', $page);
$smarty->assign('id', $id);

// Collect data
$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS
);

// Determine which content page to load
if ($page == 'home_page.tpl') { // Home page
    $groupedArticles = getHomePageData($pdo);
    $smarty->assign('categories', $groupedArticles);
} elseif ($page == 'category_page.tpl') { // Category page
    $groupedArticles = getCategoryPageData($pdo, $id, $path);
    $smarty->assign('category', $groupedArticles);
} elseif ($page == 'article_page.tpl') { // Article page
    updateViewCount($pdo, $id);
    $articleFormatted = getArticlePageData($pdo, $id);
    $smarty->assign('article', $articleFormatted);
}

// Display main template
$smarty->display('index.tpl');

?>