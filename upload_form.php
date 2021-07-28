<?php
//dbc.phpファイルを呼び出す
require_once "./dbc.php";
$files = getAllFile();
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="stylesheet" href="style.css">
    <title>アップロードフォーム</title>
  </head>
  <body>
    <h1>画像アップロードフォーム</h1>
<!--   enctype="multipart/form-data"様々なファイルを送れるおまじない-->
    <form enctype="multipart/form-data" action="./file_upload.php" method="POST">
      <div class="file-up">
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" /> <!--1Mまで-->
        <input name="img" type="file" accept="image/*" />　<!--accept="image/*"画像ファイルのみ選択するようにする -->
      </div>
      <div calss="title">
        ■タイトル：<input type="text" name="title" placeholder="タイトルを入力">
      </div>
      <div class="caption">
        <p>■キャプション（説明）</p>
        <textarea
          name="caption"
          placeholder="キャプション（140文字以下）"
          id="caption"
        ></textarea>
      </div>
      <div class="submit">
        <input type="submit" value="送信" class="btn" />
      </div>
    </form>
    <?php foreach($files as $file): ?>
    <div class="box">
      <img class="photo" src="<?php echo "{$file['file_path']}"; ?>" alt="">
      <p>タイトル：<?php echo h("{$file['title']}"); ?></p>
      <p>説明：<?php echo h("{$file['caption']}"); ?></p>
    </div>
    <?php endforeach; ?>
  </body>
</html>
