<?php
require_once "db.php";

// ===== 入力値と状態の初期化 =====
$title   = "";
$content = "";
$author  = "";
$errors  = [];

// ===== フォームが送信されたときだけ処理する =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title   = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $author  = trim($_POST["author"] ?? "");

    $errors = validatePost($title, $content, $author);

    // エラーが1つもなければ投稿する
    if (count($errors) === 0) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // 投稿に成功したら一覧へ移動する（再読み込みによる二重投稿を防ぐ）
            header("Location: index.php");
            exit;
        }

        $errors[] = "投稿に失敗しました。";
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>新規投稿</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>新規投稿</h1>
<p><a href="index.php">← 一覧に戻る</a></p>

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

  <button type="submit">投稿する</button>
</form>

</body>
</html>
