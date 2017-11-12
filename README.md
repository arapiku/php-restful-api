# 課題１

## 使用した技術要素
- Vagrant 1.9.8
- CentOS 7.3.1
- PHP 7.1.11
- MySQL 5.6.3
- Apache 2.4.6

## 全体の設計・構成
|               　　　　　　 | HTTPMETHOD          |
|:-------------------------|:-------------------:|
| /api/items/read.php      | GET 商品の一覧表示      |
| /api/items/search.php    | GET タイトルで商品検索   |
| /api/items/read_by_id.php| GET 商品のシングルページ |
| /api/items/create.php    | POST 商品の新規作成     |
| /api/items/update.php    | PUT 商品の更新         |
| /api/items/delete.php    | DELETE 商品の削除      |

##### 入力値の制限
- 商品タイトル　100文字以内
- 商品説明　500文字以内

## 開発環境のセットアップ手順

#### Vagrant準備
- BOX追加

  ``` コマンド
  vagrant box add CentOS7 https://github.com/holms/vagrant-centos7-box/releases/download/7.1.1503.001/CentOS-7.1.1503-x86_64-netboot.box
  ```

- 初期化

  ``` コマンド
  mkdir /vagrant/CentOS7 && cd $_
  vagrant init CentOS7
  ```

- 設定

  Vagrantfileを編集

  ``` Vagrantfile
  # ホスト名
  config.vm.hostname = "centos7.vm"
  # ネットワーク
  config.vm.network "private_network", ip: "192.168.33.10"
  ```

- 起動

  ``` コマンド
  vagrant up
  ```

#### CentOS7初期設定
- rootユーザーになっておく

  ``` コマンド
  sudo su -
  ```

- yumのアップデート

  ``` コマンド
  yum -y update
  ```

- Firewall設定
  - Firewallを止める

    ``` コマンド
    systemctl stop firewalld
    ```

  - サービスを無効にする

    ``` コマンド
    systemctl disable firewalld
    ```

#### MySQLのインストール
- 各種リポジトリ登録
  - EPEL

    ``` コマンド
    rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm
    ```

  - Remi

    ``` コマンド
    rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
    ```

  - mysql-communityの登録

    ``` コマンド
    yum install -y http://repo.mysql.com/mysql-community-release-el6-5.noarch.rpm
    ```

- MySQLのインストール

  ``` コマンド
  yum install -y mysql mysql-server mysql-devel
  ```

- phpmyadmin

  ``` コマンド
  yum install -y --enablerepo=remi,remi-php56 phpMyAdmin
  ```

- mysqlの起動

  ``` コマンド
  systemctl mysqld start
    ```

- 再起動

  ``` コマンド
  reboot
  ```

#### PHPのインストール
- 依存関係のあるパッケージのインストール

  ``` コマンド
  yum install -y --skip-broken --enablerepo=epel libmcrypt libtidy
  ```

- インストール

  ``` コマンド
  yum install -y --skip-broken --enablerepo=remi,remi-php71 php php-devel php-pear php-mbstring php-pdo php-gd php-zip php-xml php-fpm php-mcrypt php-mysqlnd php-pecl-apcu php-pecl-zendopcache
  ```

#### Apacheのインストール
- Apacheのインストール

  ``` コマンド
  yum -y install httpd httpd-devel
  ```

- apacheを起動

  ``` コマンド
  systemctl httpd start
  ```

- apacheの自動起動設定

  ``` コマンド
  chkconfig httpd on
  ```

#### ビルドインサーバーの起動
- プロジェクトフォルダへ移動

  ``` コマンド
  vagrant ssh
  cd /var/www/php_project
  ```

- ビルドインサーバーの起動

  ``` コマンド
  php -S 192.168.33.10:8000
  ```
  ※ビルドインサーバーにはログも出力されます

## ディレクトリ構成
  ```
  .
  └── api/ .. API関連フォルダ
  	├── config/ .. db等設定ファイル
  	├── items/ .. itemsに関するコントローラーファイル
  	└── objects/ .. モデルファイル

  ```

## テスト実行手順
APIをcurlコマンドで叩く
（postmanも有効）

- 商品の一覧表示

  ```  
  curl -i -X GET http://localhost/api/items/read.php

  ```

- タイトルで商品検索

  ```
  curl -i -X GET http://localhost/api/items/search.php?s=keywords
  ```
  ※keywordsの箇所に検索したい文字列を入れる

- 商品のシングルページ

  ```
  curl -i -X GET http://localhost/api/items/read_by_id.php?id=num
  ```
  ※numの箇所に検索したいidを入れる

- 商品の新規登録

  ```
  curl -i -X POST -d '{"title":"itemA","description":"sample text","price":12345,"image":"imageA"}' http://localhost/api/items/create.php
  ```

- 商品の更新

  ```
  curl -i -X PUT -d '{"title":"update item","description":"update text","price":54321,"image":"update image"}' http://localhost/api/items/update.php?id=num
  ```
  ※numの箇所に更新したいidを入れる

- 商品の削除

  ```
  curl -i -X DELETE http://localhost/api/items/delete.php?id=num
  ```
  ※numの箇所に削除したいidを入れる
