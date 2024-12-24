# フリマアプリ
フリマアプリを作成しました。  
ユーザーはログイン後、以下の機能を利用可能です。
- マイリスト（いいねした商品）一覧取得
- いいね登録/解除
- コメント送信
- 商品の購入/出品  
決済機能はStripeを利用しています。  
商品購入画面で支払方法（コンビニ払い又はカード支払い）をプルダウンメニューから選択の上、決済ページに移行し決済を完了する事で購入が完了します。  
※自分が出品した商品・購入済商品は購入不可となっています。
- 商品配送先の変更  
商品配送先を変更しても、プロフィールの住所は変更されないようになっています。  
（商品配送先はセッションに保存する事で、プロフィールの住所と分けて管理しています）
- 購入した商品一覧（プロフィール画面）取得
- 出品した商品一覧（プロフィール画面）取得
- プロフィール編集
<img width="1512" alt="スクリーンショット 2024-12-24 15 48 23" src="https://github.com/user-attachments/assets/db941dc6-2258-429b-a8bb-7548b091cf7b" />

## 作成した目的
学習のアウトプットとして作成しました。

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/

## 機能一覧
- 会員登録機能
- メール認証
- ログイン機能
- ログアウト機能
- 商品一覧取得
- マイリスト（いいねした商品）一覧取得
- 商品検索（商品名）
- 商品詳細情報取得
- いいね登録/解除
- コメント送信
- 商品の購入/出品
- 商品配送先の変更
- 購入した商品一覧（プロフィール画面）取得
- 出品した商品一覧（プロフィール画面）取得
- プロフィール編集

## 使用技術（実行環境）
- laravel 8.83.29
- MySQL 8.0.26
- docker 25.0.2
- PHP 8.3.13
- laravel-fortify 1.19
- Stripe 16.3
- javascript 22.11.0

## テーブル設計
<img width="599" alt="スクリーンショット 2024-12-24 12 52 52" src="https://github.com/user-attachments/assets/ca18f15b-6944-4ada-9d47-a88c052e18f4" />
<img width="599" alt="スクリーンショット 2024-12-24 12 53 14" src="https://github.com/user-attachments/assets/e70b5cde-6c5b-482a-8e04-acdb9aeeebd4" />

## ER図
![ER_diagram](https://github.com/user-attachments/assets/1a56a192-1216-4aa4-a921-fc38c43b5feb)

# 開発環境
## Dockerビルド

1.ディレクトリの作成  
プロジェクトのディレクトリ構造を以下のように作成して下さい。
<pre>
FleaMarket
├── docker  
│   ├── mysql  
│   │   ├── data  
│   │   └── my.cnf  
│   ├── nginx  
│   │   └── default.conf  
│   └── php  
│       ├── Dockerfile  
│       └── php.ini  
├── docker-compose.yml  
└── src  
</pre>

2.docker-compose.ymlの作成  
`docker-compose.yml`ファイルに、以下の内容を追加して下さい。  
```
version: '3.8'

services:
    nginx:
        image: nginx:1.21.1
        ports:
            - "80:80"
        volumes:
            - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
            - ./src:/var/www/
        depends_on:
            - php

    php:
        build: ./docker/php
        volumes:
            - ./src:/var/www/

    mysql:
        image: mysql:8.0.26
        environment:
            MYSQL_ROOT_PASSWORD: root
            MYSQL_DATABASE: laravel_db
            MYSQL_USER: laravel_user
            MYSQL_PASSWORD: laravel_pass
        command:
            mysqld --default-authentication-plugin=mysql_native_password
        volumes:
            - ./docker/mysql/data:/var/lib/mysql
            - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
```

3.Nginxの設定  
`docker/nginx/default.conf`ファイルに以下の内容を追加して下さい。
```
server {
    listen 80;
    index index.php index.html;
    server_name localhost;

    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

4.PHPの設定  
`docker/php/Dockerfile`ファイルに以下の内容を追加して下さい。
```
FROM php:8.2-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
    && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
    && apt install -y libpng-dev libjpeg-dev libfreetype6-dev libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

WORKDIR /var/www
```

5.MySQLの設定  
`docker/mysql/my.cnf`ファイルに以下の内容を追加して下さい。
```
[mysqld]
character-set-server = utf8mb4

collation-server = utf8mb4_unicode_ci

default-time-zone = 'Asia/Tokyo'
```

6.phpMyAdminの設定  
`docker-compose.yml`ファイルに、以下の内容を追加して下さい。
```
    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        environment:
            - PMA_ARBITRARY=1
            - PMA_HOST=mysql
            - PMA_USER=laravel_user
            - PMA_PASSWORD=laravel_pass
        depends_on:
            - mysql
        ports:
            - 8080:80
```

7.コンテナの作成・起動
```
docker-compose up -d --build
```

## Laravel環境開発

1.Dockerコンテナ内にアクセス
```
docker-compose exec php bash
```

2.composerのインストール
```
composer install
```

3..env.exampleファイルから.envを作成し、環境変数を変更

4.アプリケーションキーの生成
```
php artisan key:generate
```

5.マイグレーションの実行
```
php artisan migrate
```

6.シーディングの実行
```
php artisan db:seed
```

## メール認証機能について
このプロジェクトでは、Mailtrapを使用して開発環境でのメール送信をトラップし、安全にテストを行うことができます。 
以下にセットアップ方法を記載します。

### セットアップ方法

1.Mailtrap アカウントの作成
Mailtrap にアクセスし、無料または有料アカウントを作成します。

2.SMTP 設定情報を取得
Mailtrap ダッシュボードにログインし、新しい「Inbox」を作成します。  
作成した Inbox の「Integrations」タブから Laravel 用のSMTP 設定情報を確認できます。

3..env ファイルを更新
.env ファイルに以下の設定を追加または更新してください。
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Your App Name"
```
your_mailtrap_username と your_mailtrap_password には、Mailtrap の認証情報を入力してください。

4.動作確認
ローカル環境でユーザー登録を行い、登録確認メールがMailtrapダッシュボードに届くことを確認してください。

## Stripeを使用した決済機能について
このプロジェクトでは、Stripeを使用して決済機能を実装しています。  
以下にセットアップ方法や利用方法を記載します。

### 前提条件
- Dockerがインストールされていること
- Docker Composeがインストールされていること

### セットアップ方法

1.Stripeアカウントの作成とAPIキーの取得
Stripeの公式サイトでアカウントを作成し、ダッシュボードから「公開可能キー」と「シークレットキー」を取得します。

2.Dockerコンテナ内にアクセス
```
docker-compose exec php bash
```

3.Laravelのパッケージインストール
```
composer require stripe/stripe-php
```

4..envファイルの環境変数の設定
Stripeダッシュボードから取得した「公開可能キー」と「シークレットキー」を設定します。
```
STRIPE_KEY=your-publishable-key
STRIPE_SECRET=your-secret-key
```

5.Stripeのサービス設定
サービス設定ファイル (config/services.php) にStripeの情報を追加します。
```
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

### 利用方法
Stripe のテストモードを使用する場合、以下のカード情報を使用して動作確認を行うことができます。
- カード番号: 4242 4242 4242 4242
- 有効期限: 任意の未来の日付 (例: 2025/12/31)
- セキュリティコード: 任意の3桁 (例: 123)

## ダミーデータの説明
ユーザー　　email: test@test.com  
　　　　　　password: password

## テスト
このプロジェクトには、PHPUnitを使用したユニットテストが含まれています。  
テストの実行には、Docker環境の使用を推奨します。

### 前提条件
- Dockerがインストールされていること
- Docker Composeがインストールされていること

### PHPUnitのインストールとテスト実行
Dockerコンテナ内でPHPUnitをインストールしテストを実行するには、以下の手順で行います。

1.Dockerコンテナ内にアクセス
```
docker-compose exec php bash
```

2.PHPUnitのインストール
```
composer require --dev phpunit/phpunit
```

3.テストの実行
```
php artisan test
```

### テスト時の挙動変更について
このプロジェクトでは、以下の処理において実際の挙動とテスト時の挙動を変更しています。

1.新規会員登録後の画面遷移
- 実際の挙動: プロフィール設定画面に遷移
- テスト時: ログイン画面に遷移
```php
// テスト時
return redirect()->route('login');

// 実際の環境
return redirect()->route('profile.edit');
```

2.商品購入画面「購入する」ボタン押下後の処理
- 実際の挙動: Stripe決済画面に遷移
- テスト時: 購入が完了
```php
// テスト時
return $this->success($item_id);

// 実際の環境
return redirect($session->url);
```
