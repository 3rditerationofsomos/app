<?php
require_once 'vendor/autoload.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');

$smarty->assign('css_path', '/public/css/main.css');
$smarty->assign('js_path', '/public/js/main.js');
$smarty->assign('version', '1.0.0');
$smarty->assign('current_time', date('H:i:s'));
$smarty->assign('scss_compiled', date('Y-m-d H:i'));

// Отображаем главный шаблон
$smarty->display('index.tpl');
?>