<?php
require_once "db.php";

$id = $_GET["id"] ?? "";

$conn = getDBConnection();

// ===== 表示する記事を取得する =====
$stmt = $conn->prepare("SELECT id, title, content, author, created_at, updated_at FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

// 記事が存在しない場合は一覧に戻す
if (!$post) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($post["title"]) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<p><a href="index.php">← 一覧に戻る</a></p>

<h1><?= h($post["title"]) ?></h1>
<p class="meta">
  投稿者：<?= h($post["author"]) ?> ／ 投稿日：<?= h($post["created_at"]) ?> ／ 更新日：<?= h($post["updated_at"]) ?>
</p>

<div class="content"><?= h($post["content"]) ?></div>

<div class="actions">
  <a href="edit.php?id=<?= h($post["id"]) ?>">編集</a>
  <form method="POST" action="delete.php" onsubmit="return confirm('本当に削除しますか？')">
    <input type="hidden" name="id" value="<?= h($post["id"]) ?>">
    <button type="submit">削除</button>
  </form>
</div>

</body>
</html>
