<?php

require_once 'configs/config.php';
require_once 'functions.php';

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

?>