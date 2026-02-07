<?php

// Main functions
function getHomePageData(PDO $pdo): array
{
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

    return $groupedArticles;
}

function getCategoryPageData(PDO $pdo, $id, $path): array
{
    // Sorting column
    if (preg_match('/\/by_views$/', $path)) {
        $sortColumn = 'view_count';
    } else {
        $sortColumn = 'created';
    }

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
        ORDER BY a." . $sortColumn . " DESC";

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

    return $groupedArticles;
}

function updateViewCount(PDO $pdo, $id): void
{
    // Updating view count
    $sql = "
        UPDATE articles 
        SET view_count = view_count + 1
        WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

function getArticlePageData(PDO $pdo, $id): array
{
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
        'view_count' => $article[0]['view_count'],
        'similar' => [],
    ];

    // Preparing similar articles
    $sql = "
        SELECT 
            a.id,
            a.name,
            a.image_link,
            a.description,
            a.view_count,
            a.created
        FROM articles a
        INNER JOIN relations r ON a.id = r.article_id
        WHERE a.id != :id AND 
            r.article_type_id IN (
                SELECT article_type_id 
                FROM relations 
                WHERE article_id = :id
            )
        GROUP BY a.id
        ORDER BY RAND()
        LIMIT 3;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $articleList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($articleList as $article) {
        $articleFormatted['similar'][] = [
            'article_id' => $article['id'],
            'image_link' => $article['image_link'],
            'article_name' => $article['name'],
            'article_description' => $article['description'],
            'created' => $article['created'],
            'view_count' => $article['view_count']
        ];
    }

    return $articleFormatted;
}

// Seeding functions
function fillArticleTypes(PDO $pdo): void
{
    $sql = "INSERT INTO article_types (name, description) 
                VALUES(:name, :description)";

    $dataList = [
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.']
    ];

    foreach ($dataList as $data) {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':name' => $data[0],
            ':description' => $data[1]
        ]);
    }

    echo 'Article types created</br>';
}

function fillArticles(PDO $pdo): void
{
    // How many articles should be created
    $articleCount = 500;

    // Which images should be used
    $images = ['img_1.png', 'img_2.png', 'img_3.png', 'img_4.png', 'img_5.png',
        'img_6.png', 'img_7.png', 'img_8.png', 'img_9.png'];

    // What dates should be used
    $rangeStartTS = strtotime('2025-01-01');
    $rangeFinishTS = strtotime('2025-12-31');
    $dateRangeTS = $rangeFinishTS - $rangeStartTS;

    $sql = "INSERT INTO articles (image_link, name, description, text, created) 
                VALUES(:image_link, :name, :description, :text, :created)";

    for ($i = 1; $i <= $articleCount; $i++) {
        $randomImageLink = '/assets/images/' . $images[mt_rand(0, count($images) - 1)];
        $randomDate = date("Y-m-d H:i:s", $rangeStartTS + mt_rand(0, $dateRangeTS));

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':image_link' => $randomImageLink,
            ':name' => 'Lorem ipsum',
            ':description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor 
                incididunt ut labore et dolore magna aliqua.',
            ':text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi 
                ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse 
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa 
                qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing 
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis 
                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat 
                cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum 
                dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna 
                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea 
                commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu 
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia 
                deserunt mollit anim id est laborum.',
            ':created' => $randomDate
        ]);
    }

    echo 'Articles created</br>';
}

function fillRelations(PDO $pdo): void
{
    // How many articles
    $articleCount = 500;

    // How many categories
    $articleTypeCount = 5;

    $sql = "INSERT INTO relations (article_id, article_type_id) 
            VALUES(:article_id, :article_type_id)";

    for ($articleId = 1; $articleId <= $articleCount; $articleId++) {

        // To how many groups article should be related (1-3)
        $articleGroupCount = mt_rand(1, 3);

        // Usable article types
        $availableTypes = range(1, $articleTypeCount);

        // Random unique types for an article
        $selectedTypes = [];
        for ($j = 0; $j < $articleGroupCount; $j++) {

            // Selecting random article type
            $randomIndex = mt_rand(0, count($availableTypes) - 1);
            $selectedType = $availableTypes[$randomIndex];
            $selectedTypes[] = $selectedType;

            // Removing article type that was already used
            array_splice($availableTypes, $randomIndex, 1);

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':article_id' => $articleId,
                ':article_type_id' => $selectedType
            ]);
        }
    }

    echo 'Relations created</br>';
}

?>
