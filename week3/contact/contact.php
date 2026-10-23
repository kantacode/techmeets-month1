<?php
// ===== 画面に出すときのエスケープ（XSS対策） =====
// < > " ' & などを文字として表示させ、入力されたHTMLやスクリプトが実行されないようにする
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// ===== 入力値と状態の初期化 =====
$name    = "";
$email   = "";
$subject = "";
$message = "";
$errors  = [];     // エラーメッセージを入れる配列
$success = false;  // 送信に成功したら true にする

// ===== フォームが送信されたときだけ処理する =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 入力値を受け取る（?? "" で値がないときも空文字にする）
    // trim() で前後の空白を消し、スペースだけの入力を空として扱う
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // ----- バリデーション（全項目必須） -----
    if ($name === "") {
        $errors[] = "名前を入力してください。";
    }

    if ($email === "") {
        $errors[] = "メールアドレスを入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // メールアドレスの形式チェック（PHPに用意されている仕組みを使う）
        $errors[] = "メールアドレスの形式が正しくありません。";
    }

    if ($subject === "") {
        $errors[] = "件名を入力してください。";
    }

    if ($message === "") {
        $errors[] = "メッセージを入力してください。";
    }

    // エラーが1つもなければ送信成功
    if (count($errors) === 0) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>お問い合わせ</title>
<style>
  body { font-family: sans-serif; max-width: 600px; margin: 40px auto; padding: 0 16px; }
  label { display: block; margin-top: 16px; font-weight: bold; }
  input, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
  textarea { height: 120px; }
  button { margin-top: 20px; padding: 10px 24px; }
  .errors { color: #c00; background: #fee; padding: 12px 24px; border-radius: 6px; }
  .confirm th { text-align: left; padding: 8px 16px 8px 0; vertical-align: top; white-space: nowrap; }
  .confirm td { padding: 8px 0; white-space: pre-wrap; }
</style>
</head>
<body>

<?php if ($success): ?>
  <!-- ===== 確認画面（送信成功時） ===== -->
  <h1>送信内容の確認</h1>
  <p>以下の内容で受け付けました。</p>
  <table class="confirm">
    <tr><th>名前</th><td><?= h($name) ?></td></tr>
    <tr><th>メールアドレス</th><td><?= h($email) ?></td></tr>
    <tr><th>件名</th><td><?= h($subject) ?></td></tr>
    <tr><th>メッセージ</th><td><?= h($message) ?></td></tr>
  </table>
  <p><a href="contact.php">フォームに戻る</a></p>

<?php else: ?>
  <!-- ===== 入力フォーム ===== -->
  <h1>お問い合わせフォーム</h1>

  <?php if (count($errors) > 0): ?>
    <!-- エラーがあるときだけ一覧で表示する -->
    <ul class="errors">
      <?php foreach ($errors as $error): ?>
        <li><?= h($error) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <!-- value に入力値を戻すことで、エラー時も入力した内容が消えないようにする -->
  <form method="POST">
    <label for="name">名前</label>
    <input type="text" id="name" name="name" value="<?= h($name) ?>">

    <label for="email">メールアドレス</label>
    <input type="text" id="email" name="email" value="<?= h($email) ?>">

    <label for="subject">件名</label>
    <input type="text" id="subject" name="subject" value="<?= h($subject) ?>">

    <label for="message">メッセージ</label>
    <textarea id="message" name="message"><?= h($message) ?></textarea>

    <button type="submit">送信する</button>
  </form>
<?php endif; ?>

</body>
</html>
