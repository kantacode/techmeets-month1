# ユーザー管理システム

## 概要

PHP と MySQL で作ったユーザー管理システムです。ユーザーの登録・一覧表示・編集・削除（CRUD）ができます。

| ファイル | 役割 |
|---|---|
| `user_management/db.php` | DB接続、エスケープ用の `h()`、入力チェック |
| `user_management/index.php` | ユーザー一覧（登録日の新しい順） |
| `user_management/create.php` | ユーザー登録 |
| `user_management/edit.php` | ユーザー編集 |
| `user_management/delete.php` | ユーザー削除 |
| `user_management/style.css` | スタイル |

## 動かし方

1. Docker Desktop を起動し、`week4` フォルダで MySQL と phpMyAdmin を起動します。

   ```bash
   docker-compose up -d
   ```

2. 初回のみ、phpMyAdmin（http://localhost:8080 、ユーザー名・パスワードは `root`）の `myapp_db` で以下の SQL を実行します。

   ```sql
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       username VARCHAR(50) NOT NULL UNIQUE,
       email VARCHAR(100) NOT NULL UNIQUE,
       age INT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   );
   ```

3. VS Code の PHP Server で `user_management/index.php` を開きます。

4. 作業が終わったらコンテナを停止します。

   ```bash
   docker-compose down
   ```

## セキュリティ対策

- **SQLインジェクション対策**：全ての SQL 文でプリペアドステートメントを使う
- **XSS対策**：画面に出す値は全て `h()`（`htmlspecialchars()`）でエスケープする

## 教材の実装例から変えた点

- **全項目を必須にした**：年齢が空のまま保存すると `0` 歳として登録されるため、年齢も入力チェックの対象にした。
- **重複登録をエラーメッセージで表示する**：PHP 8.1 以降は SQL エラーで例外が投げられ、実装例のままだと画面が Fatal error で止まる。`mysqli_report(MYSQLI_REPORT_OFF)` を設定し、同じユーザー名・メールアドレスのときはメッセージを表示するようにした。
- **削除を POST で受け付ける**：GET のリンクだと、URL を開くだけでデータが消えてしまうため、フォームから送る形にした。
- **入力チェックを共通化した**：登録と編集で同じチェックが必要なので、`db.php` の `validateUser()` にまとめた。
