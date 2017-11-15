<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// データベースファイルとオブジェクトファイルを読み込む
include_once '../config/database.php';
include_once '../objects/Items.php';

// dbのインスタンスを作成
$database = new Database();
$db = $database->getConnection();

// オブジェクトを初期化
$items = new Items($db);

// クエリを呼び出す
$stmt = $items->read();
$num = $stmt->rowCount();

// もしレコードが1件以上なら
if($num > 0) {

  // 配列を生成
  $items_arr = [];
  $items_arr["records"] = [];

  // db検索
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 配列からシンボルテーブルに変数をインポート
    extract($row);

    // 商品データ
    $items_data = [
      "id" => $id,
      "title" => $title,
      "description" => html_entity_decode($description),
      "price" => $price,
      "image" => $image
    ];

    // 配列に格納
    array_push($items_arr["records"], $items_data);
  }

  echo json_encode($items_arr);
} else {
  http_response_code(404);
  echo json_encode(
      ["message" => "No items found."]
  );
}

// PDOの接続を断つ
$db = null;
$stmt = null;
