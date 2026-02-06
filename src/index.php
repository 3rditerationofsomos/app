<?php

require_once 'configs/config.php';
require_once 'vendor/autoload.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');

$smarty->assign('css_path', '/public/css/main.css');
$smarty->assign('js_path', '/public/js/main.js');

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

// Collect data
$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS
);

if ($page == 'home_page.tpl') { // Home page

    $sql = "
        WITH ranked_articles AS (
            SELECT 
                r.article_type_id,
                a.id AS article_id,
                a.name AS article_name,
                a.image_link,
                a.description AS article_description,
                a.created,
                a.view_count,
                ROW_NUMBER() OVER (
                    PARTITION BY r.article_type_id 
                    ORDER BY a.created DESC
                ) AS article_rank
            FROM relations r
            JOIN articles a ON r.article_id = a.id
        )
        SELECT 
            ra.article_type_id AS type_id,
            at.name AS type_name,
            ra.article_id,
            ra.article_name,
            ra.image_link,
            ra.article_description,
            ra.created,
            ra.view_count
        FROM ranked_articles ra
        JOIN article_types at ON ra.article_type_id = at.id
        WHERE ra.article_rank <= 3
        ORDER BY ra.article_type_id, ra.created DESC";

    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $groupedArticles = [];
    foreach ($articles as $article) {
        $typeId = $article['type_id'];

        if (!isset($groupedArticles[$typeId])) {
            $groupedArticles[$typeId] = [
                'type_id' => $typeId,
                'type_name' => $article['type_name'],
                'articles' => []
            ];
        }

        $groupedArticles[$typeId]['articles'][] = [
            'article_id' => $article['article_id'],
            'image_link' => $article['image_link'],
            'article_name' => $article['article_name'],
            'article_description' => $article['article_description'],
            'created' => $article['created'],
            'view_count' => $article['view_count']
        ];
    }

    $smarty->assign('categories', $groupedArticles);

} elseif ($page == 'category_page.tpl') { // Category page

    $sql = "
        SELECT 
            :id AS type_id,
            at.name AS type_name,
            at.description AS type_description,
            a.id AS article_id,
            a.name AS article_name,
            a.image_link,
            a.description AS article_description,
            a.view_count,
            a.created
        FROM articles a
        JOIN relations r ON a.id = r.article_id
        JOIN article_types at ON at.id = r.article_type_id
        WHERE r.article_type_id = :id
        ORDER BY a.created DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $groupedArticles = [];
    foreach ($articles as $article) {
        $typeId = $article['type_id'];

        if (empty($groupedArticles)) {
            $groupedArticles = [
                'type_id' => $typeId,
                'type_name' => $article['type_name'],
                'type_description' => $article['type_description'],
                'articles' => []
            ];
        }

        $groupedArticles['articles'][] = [
            'article_id' => $article['article_id'],
            'image_link' => $article['image_link'],
            'article_name' => $article['article_name'],
            'article_description' => $article['article_description'],
            'view_count' => $article['view_count'],
            'created' => $article['created']
        ];
    }

    $smarty->assign('category', $groupedArticles);

} elseif ($page == 'article_page.tpl') { // Article page

    // Updating view count
    $sql = "
        UPDATE articles 
        SET view_count = view_count + 1
        WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Preparing article data
    $sql = "SELECT * FROM articles WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $article = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $articleFormatted = [
        'article_id' => $article[0]['id'],
        'image_link' => $article[0]['image_link'],
        'name' => $article[0]['name'],
        'description' => $article[0]['description'],
        'text' => $article[0]['text'],
        'created' => $article[0]['created'],
        'view_count' => $article[0]['view_count']
    ];

    $smarty->assign('article', $articleFormatted);

}

$smarty->assign('page', $page);
$smarty->assign('id', $id);

// Display main template
$smarty->display('index.tpl');
?>