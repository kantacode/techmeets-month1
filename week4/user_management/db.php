<?php
// ===== データベース接続情報 =====
define("DB_HOST", "127.0.0.1");
define("DB_NAME", "myapp_db");
define("DB_USER", "root");
define("DB_PASS", "root");

// MySQLのエラーコード：UNIQUE制約違反（同じ値がすでに登録されている）
define("DB_ERROR_DUPLICATE", 1062);

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

// ===== 登録・編集で共通の入力チェック =====
// エラーメッセージの配列を返す（問題がなければ空の配列）
function validateUser($username, $email, $age) {
    $errors = [];

    if ($username === "") {
        $errors[] = "ユーザー名を入力してください。";
    } elseif (!isWithinLength($username, 50)) {
        $errors[] = "ユーザー名は50文字以内で入力してください。";
    }

    if ($email === "") {
        $errors[] = "メールアドレスを入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "メールアドレスの形式が正しくありません。";
    } elseif (!isWithinLength($email, 100)) {
        $errors[] = "メールアドレスは100文字以内で入力してください。";
    }

    if ($age === "") {
        $errors[] = "年齢を入力してください。";
    } elseif (filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 150]]) === false) {
        $errors[] = "年齢は0〜150の整数で入力してください。";
    }

    return $errors;
}
?>
