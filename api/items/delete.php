<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// データベースファイルとオブジェクトファイルを読み込む
include_once '../config/database.php';
include_once '../objects/Items.php';

// dbのインスタンスを作成
$database = new Database();
$db = $database->getConnection();

// オブジェクトを初期化
$items = new Items($db);

// IDプロパティをセット
$items->id = isset($_GET['id']) ? $_GET['id'] : die();

// レコードを取得
$items->readById();

// 該当IDの商品があれば
if ($items->title != null) {

  // 商品データを削除
  if ($items->delete()) {
    http_response_code(204);
    echo '{';
      echo '"message": "Items was deleted."';
    echo '}';
  // アップデート削除
  } else {
    error_log(date("[Y/m/d H:i:s]") . " [ERROR] 該当IDの商品を削除できませんでした。\n", 3, ERROR_LOG_PATH);
    http_response_code(409);
    echo '{';
      echo '"message": "Unable to delete items."';
    echo '}';
  }
// 該当IDの商品がなければ
} else {
  http_response_code(404);
  echo '{';
    echo '"message": "No item found."';
  echo '}';
}

// PDOの接続を断つ
$db = null;
