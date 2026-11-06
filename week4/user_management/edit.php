<?php
require_once "db.php";

$id     = $_GET["id"] ?? "";
$errors = [];

$conn = getDBConnection();

// ===== 編集対象のユーザーを取得する =====
$stmt = $conn->prepare("SELECT id, username, email, age FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ユーザーが存在しない場合は一覧に戻す
if (!$user) {
    $conn->close();
    header("Location: index.php");
    exit;
}

// フォームの初期値はDBの現在の値にする
$username = $user["username"];
$email    = $user["email"];
$age      = $user["age"];

// ===== フォームが送信されたときだけ処理する =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $age      = trim($_POST["age"] ?? "");

    $errors = validateUser($username, $email, $age);

    // エラーが1つもなければ更新する
    if (count($errors) === 0) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, age = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $age, $user["id"]);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php");
            exit;
        }

        if ($stmt->errno === DB_ERROR_DUPLICATE) {
            $errors[] = "そのユーザー名またはメールアドレスはすでに登録されています。";
        } else {
            $errors[] = "更新に失敗しました。";
        }
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
<title>ユーザー編集</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>ユーザー編集</h1>
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

  <button type="submit">更新する</button>
</form>

</body>
</html>
