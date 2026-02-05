<?php

require_once 'configs/config.php';
require_once 'vendor/autoload.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');

$smarty->assign('css_path', '/public/css/main.css');
$smarty->assign('js_path', '/public/js/main.js');
$smarty->assign('version', '1.0.0');
$smarty->assign('current_time', date('H:i:s'));
$smarty->assign('scss_compiled', date('Y-m-d H:i'));

$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS
);

$stmt = $pdo->query("SELECT * FROM articles");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = '</br>';
foreach ($result as $article) {
    $data .= 'Название статьи: ' . $article['name'] . '</br>';
}
$smarty->assign('data', $data);

// Отображаем главный шаблон
$smarty->display('index.tpl');
?>