<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>コメント投稿</title>
</head>
<body>

<h1>コメント投稿フォーム</h1>

<!-- ===== 入力フォーム ===== -->
<!-- action を省略しているので、送信先はこのファイル自身になる -->
<form method="POST">
  <label>名前:</label>
  <input type="text" name="name"><br>
  <label>コメント:</label>
  <textarea name="comment"></textarea><br>
  <button type="submit">投稿する</button>
</form>

<?php
// ===== フォームが送信されたときだけ処理する =====
// 最初にページを開いたとき（GET）は何もしない
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 入力値を受け取る
    // ?? "" … 値が送られてこなかったときは空文字にする（エラー防止）
    // trim() … 前後の空白を消して、スペースだけの入力を「空」として扱う
    $name    = trim($_POST["name"] ?? "");
    $comment = trim($_POST["comment"] ?? "");

    // ===== バリデーション（入力チェック） =====
    // 【修正1】元は if ($name = "") で「代入」になっていた
    //          === で「比較」するように直した
    if ($name === "") {
        echo "名前を入力してください。";
    // 【修正3】元はコメントのチェックがなく、空でも投稿できた
    } elseif ($comment === "") {
        echo "コメントを入力してください。";
    } else {
        // ===== 結果の表示 =====
        // 【修正2】元は入力値をそのまま出力していた（XSSの危険）
        //          htmlspecialchars() で < > " ' & を文字に変換してから出力する
        //          ENT_QUOTES … ' と " の両方を変換する
        //          "UTF-8"   … 文字コードを指定して文字化けを防ぐ
        echo "<p>" . htmlspecialchars($name, ENT_QUOTES, "UTF-8") . "さんのコメント:</p>";
        echo "<p>" . htmlspecialchars($comment, ENT_QUOTES, "UTF-8") . "</p>";
    }
}
?>

</body>
</html>
