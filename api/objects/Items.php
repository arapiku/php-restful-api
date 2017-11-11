<?php
class Items {
  // データベース＆テーブル接続
  private $conn;
  private $table_name = "items";

  // オブジェクトのプロパティ
  public $id;
  public $title;
  public $description;
  public $price;
  public $image;

  // コンストラクタ
  public function __construct($db) {
    $this->conn = $db;
  }

  function read() {
    // 全てのクエリを選択
    $query = "SELECT * FROM " . $this->table_name;

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // クエリを実行
    $stmt->execute();

    return $stmt;
  }
}
