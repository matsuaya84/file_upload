<?php

//DB接続
function dbc()
{
	$host = "localhost";
	$dbname = "photo";
	$user = "root";
	$pass = "root";
	
	try {//DB接続に失敗していないか
		
		$dns = "mysql:host=$host;dbname=$dbname;charset=utf8";
		$pdo = new PDO($dns, $user, $pass,
		[	//オプション
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);
		return $pdo;
	
	} catch(PDOException $e) {
		exit($e->getMessage()); //エラー内容が出力される
	}
	
}


/**
*ファイルデータを保存
*@param string $filename ファイル名
*@param string $save_parh 保存先のパス
*@param string $title タイトル
*@param string $caption 説明
*@return bool $result
*/
//
function fileSave($filename, $save_path, $title, $caption)
{
	$result = False;
	
	$sql = "INSERT INTO file_table (file_name, file_path, title, caption) VALUE (?, ?, ?, ?)"; //プレースホルダーで値をエスケープ
	
	try{
		$stmt = dbc()->prepare($sql); //sqlの準備
		$stmt->bindValue(1, $filename);
		$stmt->bindValue(2, $save_path);
		$stmt->bindValue(3, $title);
		$stmt->bindValue(4, $caption);
		$result = $stmt->execute(); //sqlの実行
		return $result;
	} catch(\Exception $e) {
		echo $e->getMessage();
		return $result;
	}
}

/**
*ファイルデータを取得
*@return array $fileData
*/
//
function getAllFile()
{
	$sql = "SELECT * FROM file_table";
	
	$fileData = dbc()->query($sql);
	
	return $fileData;
}

//captionに入るデータをエスケープする

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}