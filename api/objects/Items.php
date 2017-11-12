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

    // print_r($data['title']);

    // パラメータをバリデートしつつバインド
    if(!empty($data['title']) && preg_match("/^[A-Za-z0-9\s]{1,100}$/", $data['title'])) {
      $stmt->bindParam(":title", $data['title']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 商品名が空か101文字以上で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    if(!empty($data['description']) && preg_match("/^[A-Za-z0-9\s]{1,500}$/", $data['description'])) {
      $stmt->bindParam(":description", $data['description']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 商品説明が空か501文字以上で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    if(!empty($data['price'])) {
      $stmt->bindParam(":price", $data['price']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 金額が空で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    $stmt->bindParam(":image", $data['image']);

    // クエリを実行
    if($stmt->execute()) {
      return true;
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 作成クエリの実行に失敗しました。\n", 3, '/var/tmp/error.log');
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
    // レコードを更新するクエリ
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

    // パラメータをバリデートしつつバインド
    $stmt->bindParam(":id", $data['id']);
    if(!empty($data['title']) && preg_match("/^[A-Za-z0-9\s]{1,100}$/", $data['title'])) {
      $stmt->bindParam(":title", $data['title']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 商品名が空か101文字以上で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    if(!empty($data['description']) && preg_match("/^[A-Za-z0-9\s]{1,500}$/", $data['description'])) {
      $stmt->bindParam(":description", $data['description']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 商品説明が空か501文字以上で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    if(!empty($data['price'])) {
      $stmt->bindParam(":price", $data['price']);
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 金額が空で入力されました。\n", 3, '/var/tmp/error.log');
      return false;
    }
    $stmt->bindParam(":image", $data['image']);


    // クエリを実行
    if($stmt->execute()) {
      return true;
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 更新クエリの実行に失敗しました。\n", 3, '/var/tmp/error.log');
      return false;
    }
  }

  // 削除メソッド
  function delete() {
    // レコードを削除するクエリ
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // パラメータをバインド
    $stmt->bindParam(1, $this->id);

    // クエリを実行
    if($stmt->execute()) {
      return true;
    } else {
      error_log(date("[Y/m/d H:i:s]") . " [ERROR] 削除クエリの実行に失敗しました。\n", 3, '/var/tmp/error.log');
      return false;
    }
  }

  // 文字列検索メソッド
  function search($keywords) {
    // 全てのレコードから$keywordsに該当するレコードを抽出するクエリ
    $query = "SELECT * FROM " . $this->table_name . " WHERE title LIKE ? OR description LIKE ? ORDER BY title OR description DESC";

    // クエリのステートメントを用意
    $stmt = $this->conn->prepare($query);

    // エスケープ
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // パラメータをバインド
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);

    // クエリを実行
    $stmt->execute();

    return $stmt;
  }

  /*
  ** バリデーション
  ** 組み込み方法に悩んだので一旦放置

  function validation() {
    $errors = [];

    // titleの長さチェック
    if (!preg_match("/^[A-Za-z0-9]{1,10}$/", $data['title'])) {
      $errors[] = "商品名は10文字以内で入力してください。";
    }

    // titleの空チェック
    if (empty($data['title'])) {
      $errors[] = "商品名は入力必須項目です。";
    }

    // descriptionの長さチェック
    if (!preg_match("/^[A-Za-z0-9]{1,10}$/", $data['description'])) {
      $errors[] = "商品説明は10文字以内で入力してください。";
    }

    // descriptionの空チェック
    if (empty($data['description'])) {
      $errors[] = "商品説明は入力必須項目です。";
    }

    // priceの空チェック
    if (empty($data['price'])) {
      $errors[] = "金額は入力必須項目です。";
    }
    return $errors;
  }

  */
}
