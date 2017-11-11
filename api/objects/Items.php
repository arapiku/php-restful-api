<?php
class Product {
  // データベース＆テーブル接続
  private $conn;
  private $table_name = "products";

  // object properties
  public $id;
  public $title;
  public $description;
  public $price;
  public $image;

  // コンストラクタ
  public function __construct($db) {
    $this->conn = $db;
  }
}
