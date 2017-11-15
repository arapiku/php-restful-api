<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// データベースファイルとオブジェクトファイルを読み込む
include_once '../config/database.php';
include_once '../config/config.php';
include_once '../objects/Items.php';

// dbのインスタンスを作成
$database = new Database();
$db = $database->getConnection();

// オブジェクトを初期化
$items = new Items($db);

// 投稿された商品データを作成
if($items->create()) {
  http_response_code(201);
  echo '{';
    echo '"message": "Items was created."';
  echo '}';
} else {
  error_log(date("[Y/m/d H:i:s]") . " [ERROR] 商品を作成できませんでした。\n", 3, ERROR_LOG_PATH);
  http_response_code(409);
  echo '{';
    echo '"message": "Unable to create items."';
  echo '}';
}
