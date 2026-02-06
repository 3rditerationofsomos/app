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
        ['gundams 1', 'these are gundams 1'],
        ['gundams 2', 'these are gundams 2'],
        ['gundams 3', 'these are gundams 3'],
        ['gundams 4', 'these are gundams 4'],
        ['gundams 5', 'these are gundams 5']
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
    $sql = "INSERT INTO articles (image_link, name, description, text, created) 
                VALUES(:image_link, :name, :description, :text, :created)";

    $dataArr = [
        ['/assets/images/img_1.png', 'gundam 1', 'this is gundam 1', 'some info about this gundam 1', '2026-01-15 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 2', 'this is gundam 2', 'some info about this gundam 2', '2026-01-31 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 3', 'this is gundam 3', 'some info about this gundam 3', '2026-01-07 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 4', 'this is gundam 4', 'some info about this gundam 4', '2026-01-22 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 5', 'this is gundam 5', 'some info about this gundam 5', '2026-01-03 00:00:00'],
        ['/assets/images/img_6.png', 'gundam 6', 'this is gundam 6', 'some info about this gundam 6', '2026-01-29 00:00:00'],
        ['/assets/images/img_7.png', 'gundam 7', 'this is gundam 7', 'some info about this gundam 7', '2026-01-12 00:00:00'],
        ['/assets/images/img_8.png', 'gundam 8', 'this is gundam 8', 'some info about this gundam 8', '2026-01-01 00:00:00'],
        ['/assets/images/img_9.png', 'gundam 9', 'this is gundam 9', 'some info about this gundam 9', '2026-01-25 00:00:00'],
        ['/assets/images/img_1.png', 'gundam 10', 'this is gundam 10', 'some info about this gundam 10', '2026-01-18 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 11', 'this is gundam 11', 'some info about this gundam 11', '2026-01-05 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 12', 'this is gundam 12', 'some info about this gundam 12', '2026-01-30 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 13', 'this is gundam 13', 'some info about this gundam 13', '2026-01-09 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 14', 'this is gundam 14', 'some info about this gundam 14', '2026-01-27 00:00:00'],
        ['/assets/images/img_6.png', 'gundam 15', 'this is gundam 15', 'some info about this gundam 15', '2026-01-14 00:00:00'],
        ['/assets/images/img_7.png', 'gundam 16', 'this is gundam 16', 'some info about this gundam 16', '2026-01-02 00:00:00'],
        ['/assets/images/img_8.png', 'gundam 17', 'this is gundam 17', 'some info about this gundam 17', '2026-01-24 00:00:00'],
        ['/assets/images/img_9.png', 'gundam 18', 'this is gundam 18', 'some info about this gundam 18', '2026-01-19 00:00:00'],
        ['/assets/images/img_1.png', 'gundam 19', 'this is gundam 19', 'some info about this gundam 19', '2026-01-08 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 20', 'this is gundam 20', 'some info about this gundam 20', '2026-01-28 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 21', 'this is gundam 21', 'some info about this gundam 21', '2026-01-11 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 22', 'this is gundam 22', 'some info about this gundam 22', '2026-01-06 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 23', 'this is gundam 23', 'some info about this gundam 23', '2026-01-23 00:00:00'],
        ['/assets/images/img_6.png', 'gundam 24', 'this is gundam 24', 'some info about this gundam 24', '2026-01-16 00:00:00'],
        ['/assets/images/img_7.png', 'gundam 25', 'this is gundam 25', 'some info about this gundam 25', '2026-01-31 00:00:00'],
        ['/assets/images/img_8.png', 'gundam 26', 'this is gundam 26', 'some info about this gundam 26', '2026-01-04 00:00:00'],
        ['/assets/images/img_9.png', 'gundam 27', 'this is gundam 27', 'some info about this gundam 27', '2026-01-20 00:00:00'],
        ['/assets/images/img_1.png', 'gundam 28', 'this is gundam 28', 'some info about this gundam 28', '2026-01-13 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 29', 'this is gundam 29', 'some info about this gundam 29', '2026-01-26 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 30', 'this is gundam 30', 'some info about this gundam 30', '2026-01-10 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 31', 'this is gundam 31', 'some info about this gundam 31', '2026-01-29 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 32', 'this is gundam 32', 'some info about this gundam 32', '2026-01-21 00:00:00'],
        ['/assets/images/img_6.png', 'gundam 33', 'this is gundam 33', 'some info about this gundam 33', '2026-01-01 00:00:00'],
        ['/assets/images/img_7.png', 'gundam 34', 'this is gundam 34', 'some info about this gundam 34', '2026-01-17 00:00:00'],
        ['/assets/images/img_8.png', 'gundam 35', 'this is gundam 35', 'some info about this gundam 35', '2026-01-30 00:00:00'],
        ['/assets/images/img_9.png', 'gundam 36', 'this is gundam 36', 'some info about this gundam 36', '2026-01-05 00:00:00'],
        ['/assets/images/img_1.png', 'gundam 37', 'this is gundam 37', 'some info about this gundam 37', '2026-01-24 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 38', 'this is gundam 38', 'some info about this gundam 38', '2026-01-12 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 39', 'this is gundam 39', 'some info about this gundam 39', '2026-01-28 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 40', 'this is gundam 40', 'some info about this gundam 40', '2026-01-09 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 41', 'this is gundam 41', 'some info about this gundam 41', '2026-01-19 00:00:00'],
        ['/assets/images/img_6.png', 'gundam 42', 'this is gundam 42', 'some info about this gundam 42', '2026-01-07 00:00:00'],
        ['/assets/images/img_7.png', 'gundam 43', 'this is gundam 43', 'some info about this gundam 43', '2026-01-25 00:00:00'],
        ['/assets/images/img_8.png', 'gundam 44', 'this is gundam 44', 'some info about this gundam 44', '2026-01-14 00:00:00'],
        ['/assets/images/img_9.png', 'gundam 45', 'this is gundam 45', 'some info about this gundam 45', '2026-01-03 00:00:00'],
        ['/assets/images/img_1.png', 'gundam 46', 'this is gundam 46', 'some info about this gundam 46', '2026-01-22 00:00:00'],
        ['/assets/images/img_2.png', 'gundam 47', 'this is gundam 47', 'some info about this gundam 47', '2026-01-15 00:00:00'],
        ['/assets/images/img_3.png', 'gundam 48', 'this is gundam 48', 'some info about this gundam 48', '2026-01-31 00:00:00'],
        ['/assets/images/img_4.png', 'gundam 49', 'this is gundam 49', 'some info about this gundam 49', '2026-01-11 00:00:00'],
        ['/assets/images/img_5.png', 'gundam 50', 'this is gundam 50', 'some info about this gundam 50', '2026-01-06 00:00:00']
    ];

    foreach ($dataArr as $data) {
        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':image_link' => $data[0],
            ':name' => $data[1],
            ':description' => $data[2],
            ':text' => $data[3],
            ':created' => $data[4]
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
        [1, 1], [2, 1], [3, 1], [4, 1], [5, 1], [6, 1], [7, 1], [8, 1], [9, 1], [10, 1],
        [11, 2], [12, 2], [13, 2], [14, 2], [15, 2], [16, 2], [17, 2], [18, 2], [19, 2], [20, 2],
        [21, 3], [22, 3], [23, 3], [24, 3], [25, 3], [26, 3], [27, 3], [28, 3], [29, 3], [30, 3],
        [31, 4], [32, 4], [33, 4], [34, 4], [35, 4], [36, 4], [37, 4], [38, 4], [39, 4], [40, 4],
        [41, 5], [42, 5], [43, 5], [44, 5], [45, 5], [46, 5], [47, 5], [48, 5], [49, 5], [50, 5]
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