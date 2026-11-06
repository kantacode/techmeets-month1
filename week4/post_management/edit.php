<?php
require_once "db.php";

$id     = $_GET["id"] ?? "";
$errors = [];

$conn = getDBConnection();

// ===== 編集対象の記事を取得する =====
$stmt = $conn->prepare("SELECT id, title, content, author FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 記事が存在しない場合は一覧に戻す
if (!$post) {
    $conn->close();
    header("Location: index.php");
    exit;
}

// フォームの初期値はDBの現在の値にする
$title   = $post["title"];
$content = $post["content"];
$author  = $post["author"];

// ===== フォームが送信されたときだけ処理する =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title   = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $author  = trim($_POST["author"] ?? "");

    $errors = validatePost($title, $content, $author);

    // エラーが1つもなければ更新する
    if (count($errors) === 0) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, author = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $author, $post["id"]);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // 更新に成功したら、その記事の詳細ページへ移動する
            header("Location: show.php?id=" . $post["id"]);
            exit;
        }

        $errors[] = "更新に失敗しました。";
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>記事の編集</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>記事の編集</h1>
<p><a href="show.php?id=<?= h($post["id"]) ?>">← 記事に戻る</a></p>

<?php if (count($errors) > 0): ?>
  <ul class="errors">
    <?php foreach ($errors as $error): ?>
      <li><?= h($error) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="POST">
  <label for="title">タイトル</label>
  <input type="text" id="title" name="title" value="<?= h($title) ?>">

  <label for="author">投稿者</label>
  <input type="text" id="author" name="author" value="<?= h($author) ?>">

  <label for="content">本文</label>
  <textarea id="content" name="content"><?= h($content) ?></textarea>

  <button type="submit">更新する</button>
</form>

</body>
</html>
