# coachtech フリマアプリ

## アプリケーション概要
COACHTECHフリマは、ユーザーが商品を出品・購入するためのフリマアプリケーションです。ユーザーは商品情報を出品し、購入でき、いいね機能やコメント機能で他のユーザーとコミュニケーションを取ることができます。

## 作成した目的
COACHTECHの模擬案件として、実践に近い開発経験を積み、必要な技術を養うことを目的としています。

## アプリケーションURL
- ローカル環境: http://localhost
- Mailpit (メールテスト): http://localhost:8025

## 機能一覧

### 認証機能
- 会員登録
- メール認証
- ログイン
- ログアウト

### 商品機能
- 商品一覧表示
- 商品詳細表示
- 商品検索
- 商品出品
- いいね機能
- コメント機能

### ユーザー機能
- プロフィール編集
- マイページ（出品した商品一覧、購入した商品一覧）

### 購入機能
- 商品購入
- Stripe決済（Checkout方式）
- 配送先住所設定・変更
- 支払い方法選択

## 使用技術

### バックエンド
- PHP 8.1以上
- Laravel 10.x
- Laravel Fortify（認証）

### フロントエンド
- Blade
- CSS
- JavaScript

### データベース
- MySQL 8.0.26

### 開発環境
- Docker
- Docker Compose

### その他
- Mailpit（メールテスト）
- Git/GitHub
- Stripe（決済処理）

## テーブル設計

### usersテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | ユーザーID |
| name | VARCHAR(255) | NOT NULL | ユーザー名 |
| email | VARCHAR(255) | NOT NULL, UNIQUE | メールアドレス |
| password | VARCHAR(255) | NOT NULL | パスワード |
| profile_image_path | VARCHAR(255) | NULLABLE | プロフィール画像パス |
| postal_code | VARCHAR(255) | NULLABLE | 郵便番号 |
| address | VARCHAR(255) | NULLABLE | 住所 |
| building | VARCHAR(255) | NULLABLE | 建物名 |
| email_verified_at | TIMESTAMP | NULLABLE | メール認証日時 |
| remember_token | VARCHAR(100) | NULLABLE | ログイン状態保持トークン |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### productsテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | 商品ID |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 出品者ID |
| image_path | VARCHAR(255) | NOT NULL | 商品画像パス |
| condition | ENUM | NOT NULL | 商品の状態（'良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'） |
| name | VARCHAR(255) | NOT NULL | 商品名 |
| brand | VARCHAR(255) | NULLABLE | ブランド名 |
| description | TEXT | NOT NULL | 商品説明 |
| price | INT UNSIGNED | NOT NULL | 価格（整数、円単位） |
| is_sold | BOOLEAN | NOT NULL, DEFAULT FALSE | 売却済みフラグ |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### categoriesテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | カテゴリID |
| name | VARCHAR(255) | NOT NULL, UNIQUE | カテゴリ名 |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### category_productsテーブル（中間テーブル）
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | ID |
| product_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 商品ID |
| category_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | カテゴリID |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### likesテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | いいねID |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | ユーザーID |
| product_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 商品ID |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### commentsテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | コメントID |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | ユーザーID |
| product_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 商品ID |
| content | TEXT | NOT NULL | コメント内容 |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### ordersテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | 注文ID |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 購入者ID |
| product_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 商品ID |
| payment_method | ENUM | NOT NULL | 支払い方法（'コンビニ払い', 'カード払い'） |
| postal_code | VARCHAR(255) | NOT NULL | 配送先郵便番号 |
| address | VARCHAR(255) | NOT NULL | 配送先住所 |
| building | VARCHAR(255) | NULLABLE | 配送先建物名 |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

## ER図
![ER図](https://github.com/user-attachments/assets/7a98eddc-e020-4cef-9394-d9eb5de530ab)

## 環境構築手順

### 前提条件
- Docker Desktopがインストールされていること
- Gitがインストールされていること

### 1. リポジトリのクローン
```bash
git clone <リポジトリURL>
cd coachtech-freemarket
```

### 2. 環境設定ファイルの作成
```bash
cp .env.example .env
```

### 3. .envファイルの編集
以下の項目を適切に編集してください：

```
APP_NAME=Laravel
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=freemarket_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

STRIPE_PUBLIC_KEY=<Stripeの公開鍵>
STRIPE_SECRET_KEY=<Stripeの秘密鍵>
```

※データベース設定はDocker Compose環境を前提としています。DB_HOSTの`mysql`はdocker-compose.ymlで定義されているサービス名です。

### 4. Dockerコンテナの起動
```bash
docker-compose up -d
```

### 5. コンテナ内に入る
```bash
docker-compose exec laravel.test bash
```

### 6. 依存パッケージのインストール
```bash
composer install
```

### 7. アプリケーションキーの生成
```bash
php artisan key:generate
```

### 8. データベースのマイグレーション
```bash
php artisan migrate
```

### 9. シーディング（ダミーデータ投入）
```bash
php artisan db:seed
```

### 10. ストレージリンクの作成
```bash
php artisan storage:link
```

### 11. アクセス確認
ブラウザで以下のURLにアクセスしてください：
- アプリケーション: http://localhost
- Mailpit: http://localhost:8025

## テストアカウント

### テストユーザー
- ユーザー名: taro
- メールアドレス: taro@abc.com
- パスワード: 123abc
- 登録住所: 〒123-4567 東京都渋谷区1-2-3

※このユーザーが出品した商品データが10件登録されています

## 開発者向け情報

### キャッシュクリア
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### マイグレーションのリセット
```bash
php artisan migrate:fresh --seed
```

## トラブルシューティング

### Dockerコンテナが起動しない
- .envファイルのDB設定を確認
- Dockerコンテナの起動状況を確認: `docker-compose ps`

### パーミッションエラー
```bash
chmod -R 777 storage bootstrap/cache
```

### データベース接続エラー
- .envファイルの内容を確認
- Dockerコンテナが起動しているか確認: `docker-compose ps`

## 注意事項
- Stripe決済機能は本番環境で使用する前に、Stripeアカウントの本番キーに切り替える必要があります
- 商品画像は`storage/app/public/products/`に保存されます
- メール認証機能では、会員登録後に表示される「認証はこちらから」ボタンをクリックすると、Mailpitが開きます

## ライセンス
このプロジェクトはCOACHTECHの教育目的で作成されています。
