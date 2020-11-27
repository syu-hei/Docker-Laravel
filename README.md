# Docker-Laravel
## Docker
Dockerで[Laravel-API](https://github.com/syu-hei/Laravel-API)のLEMP環境を構築しました。
## Heroku
[Docker-Laravel/backend](https://github.com/syu-hei/Docker-Laravel/tree/main/backend)をHerokuにデプロイすることで[SocialGame](https://github.com/syu-hei/SocialGame)用の簡易サーバーを立てました。  
API URL : https://docker-laravel-201120.herokuapp.com/  
## Routes
### マスターデータを返す
* https://docker-laravel-201120.herokuapp.com/master_data
### ユーザーデータ返す
* https://docker-laravel-201120.herokuapp.com/registration
### ログイン日数を返す
* https://docker-laravel-201120.herokuapp.com/login
### チュートリアルの進行度を数値で返す
* https://docker-laravel-201120.herokuapp.com/quest_tutorial
### クエストのスタートを数値で返す
* https://docker-laravel-201120.herokuapp.com/quest_start
###  クエストの終わりを数値で返す
* https://docker-laravel-201120.herokuapp.com/quest_end
### キャラクターの種類をIDで返す
* https://docker-laravel-201120.herokuapp.com/character
### キャラクターの売却処理
* https://docker-laravel-201120.herokuapp.com/character_sell
### ガチャシステムの処理
* https://docker-laravel-201120.herokuapp.com/gacha
### 購入するアイテムのIDを返す
* https://docker-laravel-201120.herokuapp.com/shop
### ユーザーのプレゼントリストにあるアイテムを返す
* https://docker-laravel-201120.herokuapp.com/present_list
### 獲得できるプレゼントか判断する('取得期限が切れていないか'など)
* https://docker-laravel-201120.herokuapp.com/present
