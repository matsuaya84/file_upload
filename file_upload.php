<?php
//dbc.phpファイルを呼び出す
require_once "./dbc.php";

//ファイル関連の取得
$file = $_FILES['img'];
$filename = basename($file['name']);//basename:ファイルパスの最後の部分だけ返す
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = 'images/'; //画像ファイルの保存先
$save_filename = date('YmdHis') . $filename; //ファイル名が被らないよう日付を入れる
$err_msgs = array();
$save_path = $upload_dir . $save_filename;

//キャプションの取得
//filter_input/INPUT_POST:POSTで送られたデータを表示する、SANITIZE:セキュリティーチェック
$caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_SPECIAL_CHARS);

//キャプションのバリデーション
//未入力チェック
if(empty($caption)) {
	array_push($err_msgs, 'キャプションを入力してください。');
}
//文字数チェック
if(strlen($caption) > 140) {
	array_push($err_msgs, 'キャプションは140文字以内で入力して下さい。');
}

//タイトルの取得
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

//タイトルのバリデーション
//未入力チェック
if(empty($title)) {
	array_push($err_msgs, 'タイトルを入力してください。');
}
//文字数チェック
if(strlen($title) > 30) {
	array_push($err_msgs, 'タイトルは30文字以内で入力して下さい。');
}

//ファイルのバリデーション
//ファイルサイズチェック
if($filesize > 1048576 || $file_err == 2) {
	array_push($err_msgs, 'ファイルサイズは1MB未満にして下さい。');
}

//拡張子チェック
$allow_ext = array('jpg', 'jpeg', 'png');
//pathinfo():ファイルパスに関しての情報を配列で取得する関数。
//extensionで引数でしてしたファイル名の拡張子のみ抽出する。
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)) {
	array_push($err_msgs, '画像ファイルを添付して下さい。');
}

if(count($err_msgs) === 0) {
	
 //ファイルアップロードされたかチェック
 if(is_uploaded_file($tmp_path)) {
	 //ファイルを一時保存場所tmpから移動
	 if(move_uploaded_file($tmp_path, $save_path)) {
		 echo $filename . 'を' . $upload_dir . 'にアップしました。';
		 //DBに保存する（ファイル名、ファイルパス、タイトル、キャプション）
		 $result = fileSave($filename, $save_path, $title, $caption);
		 if($result) {
			 echo 'データベースに保存しました';
		 } else {
			 echo 'データベースの保存に失敗しました';
		 }
		 
	 } else {
		 echo 'ファイルが保存できませんでした。';
	 }
 } else {
	 echo 'ファイルが選択されていません。';
	 echo '<br>';
 }
} else {
	foreach($err_msgs as $msg) {
		echo $msg;
		echo '<br>';
	}
}

?>


<a href="./upload_form.php">戻る</a>
