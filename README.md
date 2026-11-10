# techmeets month1

TechMeets Month 1（Week 1〜5）で作ったものをまとめたリポジトリです。
HTML / CSS / JavaScript の画面づくりから始めて、PHP のフォーム、PHP + MySQL の CRUD アプリ、Postman での API 操作まで学びました。

## Month 1で作ったもの

| Week | 作ったもの | 内容 | ファイル |
|---|---|---|---|
| 1 | ToDoリスト | HTML で作った最初のページ | [week1/index.html](week1/index.html) |
| 2 | スライドショー | HTML / CSS / JavaScript の練習 | [week2/experiment-1/](week2/experiment-1/) |
| 2 | プロフィールページ | CSS でレイアウトを組む練習 | [week2/experiment-2/](week2/experiment-2/) |
| 2 | ToDoリスト（改良版） | Week 1 のToDoリストを作り直したもの | [week2/experiment-3/](week2/experiment-3/) |
| 2 | 好きなものまとめ | カード型のレイアウトで好きなものを紹介するページ | [week2/experiment-4/](week2/experiment-4/) |
| 3 | ショッピングカート | JavaScript の練習 | [week3/js-practice/](week3/js-practice/) |
| 3 | PHPの練習・バグ修正 | AIが書いたコード（`comment.php`）のバグを見つけて直した | [week3/php-practice/](week3/php-practice/) |
| 3 | お問い合わせフォーム | 入力チェックとXSS対策をしたPHPのフォーム | [week3/contact/](week3/contact/) |
| 4 | ユーザー管理システム | PHP + MySQL でユーザーの登録・一覧・編集・削除（CRUD） | [week4/user_management/](week4/user_management/) |
| 4 | 記事投稿システム | 実践課題。記事の投稿・一覧・詳細・編集・削除 | [week4/post_management/](week4/post_management/) |
| 5 | Postmanのリクエスト | JSONPlaceholder の API に GET / POST を送った記録 | [postman/](postman/) |

各週の詳しい内容やふりかえりは、それぞれのフォルダにある `README.md` に書いています。

## 使った技術

- HTML / CSS / JavaScript
- PHP
- MySQL（Docker で起動）、phpMyAdmin
- Postman
- Git / GitHub

## 動かし方

- **HTML のファイル（Week 1〜3）**：VS Code の Live Server で開きます。
- **PHP のファイル（Week 3〜4）**：VS Code の PHP Server で開きます（Live Server では動きません）。
- **Week 4 のアプリ**：先に Docker で MySQL を起動し、テーブルを作る必要があります。手順は [week4/README.md](week4/README.md) にあります。
- **Week 5 の Postman**：Postman でこのフォルダを開くと、保存したリクエストを確認できます。

## 学んだこと

- フォームを作るときは、入力チェックと XSS 対策（`htmlspecialchars()`）をセットで考える。
- データベースを使うときは、プリペアドステートメントで SQL インジェクションを防ぐ。
- RESTful API では、URL は「何に対して」、HTTP メソッドは「何をするか」を表す。
- main に直接 push せず、ブランチを切って Pull Request からマージする。
