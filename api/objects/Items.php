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

  // 全件取得メソッド
  function read() {
    // 全てのレコードを選択するクエリ
    $query = "SELECT * FROM " . $this->table_name;

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // クエリを実行
    $stmt->execute();

    return $stmt;
  }

  // 新規作成メソッド
  function create() {
    // レコードを挿入するクエリ
    $query = "INSERT INTO
                " . $this->table_name . "
                (title, description, price, image)
              VALUES
                (:title, :description, :price, :image)";

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // POSTされた商品データを取得
    $data = json_decode(file_get_contents("php://input"), true);

    // パラメータをバインド
    $stmt->bindParam(":title", $data['title']);
    $stmt->bindParam(":description", $data['description']);
    $stmt->bindParam(":price", $data['price']);
    $stmt->bindParam(":image", $data['image']);

    // クエリを実行
    if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // id取得メソッド
  function readById() {
    // 単一レコードを取得するクエリ
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // idをバインド
    $stmt->bindParam(1, $this->id);

    // クエリを実行
    $stmt->execute();

    // 検索結果を取得
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // プロパティに値をセット
    $this->title = $row['title'];
    $this->description = $row['description'];
    $this->price = $row['price'];
    $this->image = $row['image'];
  }

  // 更新メソッド
  function update() {
    // レコードを挿入するクエリ
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  title = :title,
                  description = :description,
                  price = :price,
                  image = :image
              WHERE
                  id = :id";

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // POSTされた商品データを取得
    $data = json_decode(file_get_contents("php://input"), true);

    // パラメータをバインド
    $stmt->bindParam(":id", $data['id']);
    $stmt->bindParam(":title", $data['title']);
    $stmt->bindParam(":description", $data['description']);
    $stmt->bindParam(":price", $data['price']);
    $stmt->bindParam(":image", $data['image']);

    // クエリを実行
    if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
