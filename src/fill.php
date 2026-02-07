<?php

require_once 'configs/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );

    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM articles");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result[0]['count'] == 0) {

        $pdo->beginTransaction();

        // Fill article types table
        fillArticleTypes($pdo);

        // Fill articles table
        fillArticles($pdo);

        // Fill relations table
        fillRelations($pdo);

        $pdo->commit();
        echo 'Seeding is complete!';
    }

} catch (PDOException $e) {
    $pdo->rollBack();
    echo 'Error: ' . $e->getMessage();
}

function fillArticleTypes(PDO $pdo): void
{
    $sql = "INSERT INTO article_types (name, description) 
                VALUES(:name, :description)";

    $dataArr = [
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.']
    ];

    foreach ($dataArr as $data) {
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