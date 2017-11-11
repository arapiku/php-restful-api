<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

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

// 該当IDの商品がなければ
if ($items->title != null) {
  // 配列を生成
  $items_arr = [
    "id" => $items->id,
    "title" => $items->title,
    "description" => $items->description,
    "price" => $items->price,
    "image" => $items->image
  ];
} else {
  http_response_code(404);
  $items_arr = ["message" => "No items found."];
}

// jsonを出力
print_r(json_encode($items_arr));
