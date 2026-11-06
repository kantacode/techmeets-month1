<?php
require_once "db.php";

// 削除はフォーム（POST）からのみ受け付ける
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = $_POST["id"] ?? "";

// idが指定されていない場合は一覧に戻す
if ($id === "") {
    header("Location: index.php");
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

// 削除後は一覧へ移動する
header("Location: index.php");
exit;
?>
