<?php
// ===== データベース接続情報 =====
define("DB_HOST", "127.0.0.1");
define("DB_NAME", "myapp_db");
define("DB_USER", "root");
define("DB_PASS", "root");

// TEXT型に保存できる最大バイト数
define("CONTENT_MAX_BYTES", 65535);

// PHP 8.1以降は、SQLエラーで例外が投げられて処理が止まるのが標準の動きになった
// execute() の戻り値でエラーを判定できるように、例外を投げない設定にする
mysqli_report(MYSQLI_REPORT_OFF);

// ===== データベース接続 =====
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("接続失敗: " . h($conn->connect_error));
    }

    // 日本語が文字化けしないように文字コードを設定する
    $conn->set_charset("utf8mb4");
    return $conn;
}

// ===== 画面に出すときのエスケープ（XSS対策） =====
function h($str) {
    return htmlspecialchars((string) $str, ENT_QUOTES, "UTF-8");
}

// ===== 文字数が上限以内かを調べる =====
// strlen() はバイト数を数えるので、日本語でも1文字として数えられる正規表現を使う
function isWithinLength($str, $max) {
    return preg_match("/\A.{0,{$max}}\z/su", $str) === 1;
}

// ===== 投稿・編集で共通の入力チェック =====
// エラーメッセージの配列を返す（問題がなければ空の配列）
function validatePost($title, $content, $author) {
    $errors = [];

    if ($title === "") {
        $errors[] = "タイトルを入力してください。";
    } elseif (!isWithinLength($title, 200)) {
        $errors[] = "タイトルは200文字以内で入力してください。";
    }

    if ($content === "") {
        $errors[] = "本文を入力してください。";
    } elseif (strlen($content) > CONTENT_MAX_BYTES) {
        $errors[] = "本文が長すぎます。";
    }

    if ($author === "") {
        $errors[] = "投稿者を入力してください。";
    } elseif (!isWithinLength($author, 50)) {
        $errors[] = "投稿者は50文字以内で入力してください。";
    }

    return $errors;
}
?>
