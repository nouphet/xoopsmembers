# xoopsmembers2

登録ユーザの一覧を表示するモジュール。

## インストール

* モジュール管理でインストールする

## カスタマイズ

表示する項目をカスタマイズする場合は、「テンプレートマネージャ」または「テーマ下テンプレート」で `xoopsmembers_index.html` に手を入れます。

表示する項目は `<{$user->get('カラム名')|escape}>` の書式で取得できます。_カラム名_ の部分は `users` テーブルのカラム、profileモジュールのフィールド名に対応しています。



test
