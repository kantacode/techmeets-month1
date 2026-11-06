<?php
require_once "db.php";
$conn = getDBConnection();

// ===== 全ユーザーを登録日の新しい順に取得する =====
// 同じ秒に登録された場合も順番が変わらないように、id でも並べる
$stmt = $conn->prepare("SELECT id, username, email, age, created_at FROM users ORDER BY created_at DESC, id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ユーザー管理システム</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>ユーザー一覧</h1>
<p><a href="create.php">新規ユーザー登録</a></p>

<?php if ($result->num_rows === 0): ?>
  <p>登録されているユーザーはいません。</p>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>ユーザー名</th>
        <th>メール</th>
        <th>年齢</th>
        <th>登録日</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= h($row["id"]) ?></td>
          <td><?= h($row["username"]) ?></td>
          <td><?= h($row["email"]) ?></td>
          <td><?= h($row["age"]) ?></td>
          <td><?= h($row["created_at"]) ?></td>
          <td class="actions">
            <a href="edit.php?id=<?= h($row["id"]) ?>">編集</a>
            <!-- データを変更する操作なのでリンク（GET）ではなくフォーム（POST）で送る -->
            <form method="POST" action="delete.php" onsubmit="return confirm('本当に削除しますか？')">
              <input type="hidden" name="id" value="<?= h($row["id"]) ?>">
              <button type="submit">削除</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php endif; ?>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
