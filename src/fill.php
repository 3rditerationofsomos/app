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

        // FILL ARTICLE TYPES TABLE
        fillArticleTypes($pdo);

        // FILL ARTICLES TABLE
        fillArticles($pdo);

        // FILL RELATIONS TABLE
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
        ['type 1', 'type 1 description'],
        ['type 2', 'type 2 description'],
        ['type 3', 'type 3 description']
    ];

    foreach ($dataArr as $data) {
        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':name' => $data[0],
            ':description' => $data[1]
        ]);

        if ($result) {
            echo $pdo->lastInsertId() . ' article type added</br>';
        }
    }
}

function fillArticles(PDO $pdo): void
{
    $sql = "INSERT INTO articles (image_link, name, description, text) 
                VALUES(:image_link, :name, :description, :text)";

    $dataArr = [
        ['article 1 image link', 'article 1', 'article 1 description', 'article 1 text'],
        ['article 2 image link', 'article 2', 'article 2 description', 'article 2 text'],
        ['article 3 image link', 'article 3', 'article 3 description', 'article 3 text'],
        ['article 4 image link', 'article 4', 'article 4 description', 'article 4 text'],
        ['article 5 image link', 'article 5', 'article 5 description', 'article 5 text'],
        ['article 6 image link', 'article 6', 'article 6 description', 'article 6 text'],
        ['article 7 image link', 'article 7', 'article 7 description', 'article 7 text'],
        ['article 8 image link', 'article 8', 'article 8 description', 'article 8 text'],
        ['article 9 image link', 'article 9', 'article 9 description', 'article 9 text']
    ];

    foreach ($dataArr as $data) {
        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':image_link' => $data[0],
            ':name' => $data[1],
            ':description' => $data[2],
            ':text' => $data[3]
        ]);

        if ($result) {
            echo $pdo->lastInsertId() . ' article added</br>';
        }
    }
}

function fillRelations(PDO $pdo): void
{
    $sql = "INSERT INTO relations (article_id, article_type_id) 
                VALUES(:article_id, :article_type_id)";

    $dataArr = [
        [1, 1],
        [2, 1],
        [3, 1],
        [4, 2],
        [5, 2],
        [6, 2],
        [7, 3],
        [8, 3],
        [9, 3]
    ];

    foreach ($dataArr as $data) {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':article_id' => $data[0],
            ':article_type_id' => $data[1]
        ]);
    }
}

?>