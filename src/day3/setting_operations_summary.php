<?php
/*
📚 Laravel開発環境：設定関係の運用まとめ（詳細版）
保存先：src/day3/setting_operations_summary.php
用途　：README.md の要点を補足し、学習記録・将来参照用に残す

────────────────────────────────────
1. artisanコマンド実行ルール（詳細）
────────────────────────────────────
- 基本方針：
WSLホスト側から実行することで、ファイル生成時の所有権が
ホストユーザー（例：KOH）になり、権限不整合を回避できる。
- DB接続を伴うartisan（migrate, db:seed, tinker等）をホストから実行する場合：
    DB_HOST=127.0.0.1 php artisan migrate
（理由：.env の DB_HOST=mysql はDockerネットワーク内専用で、ホストからは名前解決不可）
- 設定キャッシュの影響を避けるため、実行前に：
    php artisan config:clear

────────────────────────────────────
2. storage / bootstrap/cache の権限管理
────────────────────────────────────
- Laravelはログ（storage/logs/laravel.log）やBladeキャッシュを
    storage / bootstrap/cache に書き込むため、書込権限が必要。
- 初回セットアップまたは権限エラー時：
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
- 追加（推奨）：ホストユーザーにも書込可能にするACL設定
    sudo setfacl -R -m u:$USER:rwx storage bootstrap/cache
    sudo setfacl -dR -m u:$USER:rwx storage/bootstrap/cache
- ACL未使用の場合、作業前後で所有者を切り替える方法も可：
    sudo chown -R $USER:$USER storage bootstrap/cache
    ...作業...
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache

────────────────────────────────────
3. .env管理と共有時の注意
────────────────────────────────────
- `.env` はGitHubに含めない（.gitignore 済）
- `.env.example` を整備し、DB設定等はダミー値を記載
- READMEに開発・本番の使い分けや注意点を要約して記載
- 本番は本番用 .env を用意し、デプロイ時に：
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

────────────────────────────────────
4. DockerネットワークとDB接続の原理
────────────────────────────────────
- コンテナ間通信：DB_HOST=mysql（docker-composeのサービス名）
- ホストから接続：DB_HOST=127.0.0.1、公開ポート（例：3306）を利用
- docker-compose ps でMySQLコンテナの稼働状態を確認できる

────────────────────────────────────
5. 本番運用時の注意
────────────────────────────────────
- 本番サーバでも storage / bootstrap/cache の書込権限は必須
- デプロイ後に必ずキャッシュ作成：
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
- 権限やキャッシュの不整合は典型的な本番エラー原因

────────────────────────────────────
6. 補足（学習背景）
────────────────────────────────────
- ログ出力先は storage/logs/laravel.log
    → 書込不可だと即エラーになる
- 名前解決失敗（2002エラー）はホスト実行時のDB接続設定ミスマッチ
- 今回の設定運用は開発環境専用であり、GitHub共有や本番デプロイには
    悪影響を与えない
*/
