<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// データベースファイルとオブジェクトファイルを読み込む
include_once '../config/database.php';
include_once '../objects/Items.php';

// インスタンスを作成
$database = new Database();
$db = $database->getConnection();

// オブジェクトを初期化
$items = new Items($db);
