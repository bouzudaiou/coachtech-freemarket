<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
</head>
<body>
    <h1>商品一覧ページ</h1>
    <p>コントローラーとルーティングが正しく動作しています！</p>

@if(isset($products))
    <p>商品データが渡されています（件数: {{ count($products) }}件）</p>
@else
    <p>商品データがありません</p>
    @endif
    </body>
    </html>
