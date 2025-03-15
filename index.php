
<?php
try {
    // データベース接続情報
    $dbs = "";
    $user = '';
    $password = '';
    $dbh = new PDO($dbs, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームから送信されたデータを取得
    $selected_member = $_POST['selected_member'];
    
    // 取得したデータを表示してデバッグ
    if (isset($selected_member)) {
        echo "受け取ったデータ: " . htmlspecialchars($selected_member, ENT_QUOTES, 'UTF-8') . "<br>";
    } else {
        echo "データが受け取れませんでした。<br>";
    }

    // データを挿入するSQLクエリ
    $sql = "INSERT INTO user (name) VALUES (:selected_member)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':selected_member', $selected_member, PDO::PARAM_STR);

    // 実行
    $stmt->execute();

    // 最後に挿入されたデータを取得するSQLクエリ
    $lastInsertId = $dbh->lastInsertId();
    $sql = "SELECT name FROM user WHERE id = :lastInsertId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':lastInsertId', $lastInsertId, PDO::PARAM_INT);
    $stmt->execute();
    $lastInsertedRow = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "投票が登録されました: " . htmlspecialchars($lastInsertedRow['name'], ENT_QUOTES, 'UTF-8');

    // データベース接続を閉じる
    $dbh = null;

} catch (Exception $e) {
    print '接続失敗: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit();
}
?>

