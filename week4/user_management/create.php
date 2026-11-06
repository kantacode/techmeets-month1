<?php
require_once "db.php";

// ===== 入力値と状態の初期化 =====
$username = "";
$email    = "";
$age      = "";
$errors   = [];

// ===== フォームが送信されたときだけ処理する =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $age      = trim($_POST["age"] ?? "");

    $errors = validateUser($username, $email, $age);

    // エラーが1つもなければ登録する
    if (count($errors) === 0) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO users (username, email, age) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $email, $age);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // 登録に成功したら一覧へ移動する（再読み込みによる二重登録を防ぐ）
            header("Location: index.php");
            exit;
        }

        if ($stmt->errno === DB_ERROR_DUPLICATE) {
            $errors[] = "そのユーザー名またはメールアドレスはすでに登録されています。";
        } else {
            $errors[] = "登録に失敗しました。";
        }
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
<title>ユーザー登録</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>新規ユーザー登録</h1>
<p><a href="index.php">← 一覧に戻る</a></p>

<?php if (count($errors) > 0): ?>
  <ul class="errors">
    <?php foreach ($errors as $error): ?>
      <li><?= h($error) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="POST">
  <label for="username">ユーザー名</label>
  <input type="text" id="username" name="username" value="<?= h($username) ?>">

  <label for="email">メールアドレス</label>
  <input type="text" id="email" name="email" value="<?= h($email) ?>">

  <label for="age">年齢</label>
  <input type="number" id="age" name="age" value="<?= h($age) ?>">

  <button type="submit">登録する</button>
</form>

</body>
</html>
