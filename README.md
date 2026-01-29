cat > README.md << 'EOF'
# coachtech フリマアプリ

## アプリケーション概要
coachtechフリマは、アイテムの出品と購入を行うためのフリマアプリケーションです。ユーザーは商品を出品・購入でき、いいね機能やコメント機能で他のユーザーとコミュニケーションを取ることができます。

## 作成した目的
COACHTECHの模擬案件として、実践に近い開発経験を積み、定義された要件を実装する能力を身につけることを目的としています。

## アプリケーションURL
- ローカル環境: http://localhost
- Mailpit（メールテスト）: http://localhost:8025

## 機能一覧

### 認証機能
- 会員登録
- メール認証
- ログイン
- ログアウト

### 商品機能
- 商品一覧表示
- 商品詳細表示
- 商品検索（商品名で部分一致検索）
- 商品出品
- いいね機能
- コメント機能

### ユーザー機能
- マイページ
- プロフィール編集
- 出品した商品一覧
- 購入した商品一覧
- いいねした商品一覧

### 購入機能
- 商品購入
- 支払い方法選択（コンビニ払い・カード払い）
- 配送先住所設定・変更

## 使用技術

### バックエンド
- PHP 8.3
- Laravel 11.x
- Laravel Fortify（認証）

### フロントエンド
- Blade
- CSS

### データベース
- MySQL 8.0

### 開発環境
- Docker
- Docker Compose

### その他
- Mailpit（メールテスト）
- Git/GitHub

## テーブル設計

### usersテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | ユーザーID |
| name | VARCHAR(255) | NOT NULL | ユーザー名 |
| email | VARCHAR(255) | NOT NULL, UNIQUE | メールアドレス |
| email_verified_at | TIMESTAMP | NULLABLE | メール認証日時 |
| password | VARCHAR(255) | NOT NULL | パスワード |
| postal_code | VARCHAR(8) | NULLABLE | 郵便番号 |
| address | TEXT | NULLABLE | 住所 |
| building | VARCHAR(255) | NULLABLE | 建物名 |
| profile_image_path | VARCHAR(255) | NULLABLE | プロフィール画像パス |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### productsテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | 商品ID |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | 出品者ID |
| name | VARCHAR(255) | NOT NULL | 商品名 |
| brand | VARCHAR(255) | NULLABLE | ブランド名 |
| price | DECIMAL(10,2) | NOT NULL | 価格 |
| description | TEXT | NOT NULL | 商品説明 |
| image_path | VARCHAR(255) | NOT NULL | 商品画像パス |
| condition | VARCHAR(50) | NOT NULL | 商品の状態 |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

### categoriesテーブル
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | BIGINT UNSIGNED | PRIMARY KEY | カテゴリID |
| name | VARCHAR(255) | NOT NULL | カテゴリ名 |
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
| payment_method | VARCHAR(50) | NOT NULL | 支払い方法 |
| postal_code | VARCHAR(8) | NOT NULL | 配送先郵便番号 |
| address | TEXT | NOT NULL | 配送先住所 |
| building | VARCHAR(255) | NULLABLE | 配送先建物名 |
| created_at | TIMESTAMP | NOT NULL | 作成日時 |
| updated_at | TIMESTAMP | NOT NULL | 更新日時 |

## ER図
![ER図](er-diag<img width="840" height="581" alt="er-diagram" src="https://github.com/user-attachments/assets/7a980ed4-e026-4cef-9394-696b546330ab" />
ram.png)


## 環境構築手順

### 前提条件
- Docker Desktopがインストールされていること
- Gitがインストールされていること

### 1. リポジトリのクローン
```bash
git clone <リポジトリURL>![Uploading er-diagram.png…]()

cd coachtech-freemarket
```

### 2. 環境変数ファイルの作成
```bash
cp .env.example .env
```

### 3. .envファイルの編集
以下の項目を確認・編集してください：
```
APP_NAME="coachtech フリマ"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

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
- メールアドレス: test@example.com
- パスワード: password123

※シーダーで作成された商品データが10件登録されています

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
```bash
docker-compose down
docker-compose up -d --build
```

### パーミッションエラー
```bash
chmod -R 777 storage bootstrap/cache
```

### データベース接続エラー
- .envファイルのDB設定を確認
- Dockerコンテナが起動しているか確認: `docker-compose ps`

## 注意事項
- Stripe決済機能は開発中です
- 商品画像は`storage/app/public/products/`に保存されます

## ライセンス
このプロジェクトはCOACHTECHの教育目的で作成されています。
EOF
