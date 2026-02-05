<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Простой сайт</title>
    <link href="{$css_path}" rel="stylesheet">
</head>
<body>
{include file="header.tpl"}

<main>
    <p>Простой контент</p>
    <button onclick="testAlert()">Тест JS</button>

    <div style="margin-top: 20px; padding: 10px; border: 1px solid #ddd;">
        <p>Время: {$current_time}</p>
        <p>SCSS скомпилирован: {$scss_compiled}</p>
        <p>Данные: {$data}</p>
    </div>
</main>

{include file="footer.tpl"}

<script>
    function testAlert() {
        alert('Работает! Версия: {$version}');
    }
</script>
</body>
</html>