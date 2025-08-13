<?php
/*
📚 ACL（Access Control List）基礎教材
保存先：src/day3/acl_basic_guide.php
用途　：Linux環境での権限管理を理解し、Laravel開発環境や他のプロジェクトでも応用できるようにする

────────────────────────────────────
1. ACLとは？
────────────────────────────────────
- ACL（Access Control List）は、ファイルやディレクトリのアクセス権限を「所有者（user）／グループ（group）／その他（others）」の3分類だけでなく、個別のユーザーやグループごとに追加設定できる仕組み。

- 標準的なパーミッション（rwx）だけでは足りない柔軟な権限管理が可能。

────────────────────────────────────
2. なぜ使うのか？
────────────────────────────────────
- 複数ユーザーが同じディレクトリにアクセスする必要がある場合に便利。
例：Laravel開発で、
    - Webサーバ（www-data）がログやキャッシュを書き込む
    - ホストユーザー（KOH）がソース編集や artisan 実行を行う
    - ACLを使えば、所有者を変更せずに両方に書き込み権限を与えられる。

────────────────────────────────────
3. 基本コマンド
────────────────────────────────────
# ACLツールのインストール（未インストール時）
sudo apt-get update && sudo apt-get install -y acl

# ACLを付与する（例：ホストユーザーにrwxを付与）
sudo setfacl -R -m u:$USER:rwx storage bootstrap/cache
sudo setfacl -dR -m u:$USER:rwx storage/bootstrap/cache
- -R : 再帰的に適用
- -dR: デフォルトACLとして新規ファイルにも適用

# ACLの確認
ls -ld storage
# パーミッション末尾に + が付いていればACLあり（例：drwxrwxr-x+）

getfacl storage
# 付与されたユーザーと権限が確認できる

# ACLの削除
sudo setfacl -b storage bootstrap/cache
- -b : すべてのACLエントリを削除

────────────────────────────────────
4. Laravel開発での具体例
────────────────────────────────────
- 初回セットアップ時：
    cd src/laravel
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
    sudo setfacl -R -m u:$USER:rwx storage bootstrap/cache
    sudo setfacl -dR -m u:$USER:rwx storage/bootstrap/cache

- メリット：
    1) www-data（PHP）がLaravelの実行中に正常にログ・キャッシュ書き込み可能
    2) ホストユーザー（KOH）がartisan実行やファイル編集で権限エラーを回避
    3) 毎回 chown を切り替える手間がなくなる

────────────────────────────────────
5. 注意点
────────────────────────────────────
- ACLはファイルシステムが対応している必要がある（WSL2のext4は対応）
- 誤設定で不要なユーザーに権限を与えるとセキュリティリスクになる
- 権限トラブル時は getfacl で現状を確認する

────────────────────────────────────
6. まとめ
────────────────────────────────────
- ACLはLinuxの柔軟な権限管理方法の一つで、実務でよく使われる
- Laravel開発では「www-dataと開発ユーザーの両方に書込権限」が必要なため、再発防止策として有効
- コマンドを覚えておくと、他の開発環境やサーバ運用でも役立つ
*/
