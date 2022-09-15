# example-app

## development

```sh
# laravelコンテナを起動
./vendor/bin/sail up -d
# laravelコンテナを止める
./vendor/bin/sail stop
# laravelコンテナをまっさらにする
./vendor/bin/sail down
# laravelコンテナにアクセス（シェル起動）
./vendor/bin/sail exec laravel.test bash
# mysqlコンテナにアクセス（シェル起動）
./vendor/bin/sail exec mysql bash
# mysqlコマンド実行
./vendor/bin/sail exec mysql mysql -u sail -p
# ボリューム一覧の表示
docker volume ls
# ボリュームの削除
docker volume rm [NAME]
# dockerボリュームコマンドのヘルプ表示
docker volume -h 
# controllerの生成
php artisan make:controller [Controller]
# エイリアス登録
alias sail="./vendor/bin/sail"
# マイグレーションファイルの作成
php artisan make:migration create_users_table
# マイグレーションテーブルでカラムにnullを許可
$table->integer('age')->nullable();
# htmlのインプットタブに空文字を不許可かつ自動的に入力状態にする
<div>
    <x-input-label for="name" :value="__('Name')" />

    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
</div>
# controllerのリクエストに送るデータにおいて、空文字を不許可、nullを許可
$request->validate([
            'name' => ['required', 'string', 'max:255'],
            'age' => ['integer', 'nullable'],
        ]);
# ページの追加
# ルーティングを追加
Route::get('/home', function() {
    return view('home');
});
# ミドルウェアにより、
# ka-ru
curl URL | jq '.prop'
# jsonを取りやすくするためのパイプ
| jq '.data'
# ルーティングで追加した名前のbladeファイルを用意する。
<!DOCTYPE html>
# jwt認証
# モデル作成
php artisan make:model Item
# ルート確認
artisan route:list
# gitファイル作成(リポジトリとして初期化)
git init
# インデックスへファイルを追加(commitはここを参照する)
git add file
# 更新確定
git commit -m "name"
# commitの履歴参照
git log
# リモートリポジトリ(githubなどで作成したリポジトリ)をローカルリポジトリに紐付けする
git remote add origin https://github.com/ユーザ/rakus.git
# 名前をつけてリモートリポジトリを紐付けする。
git remote add upstream fork元のリポジトリURL
# リモートリポジトリから変更履歴を取得する(merge可能)
git fetch upstream master
# リモートリポジトリにローカルリポジトリの変更を反映する(branchが異なる場合はmasterをそれに変える)
git push origin master
# リモートリポジトリのmasterの変更内容をローカルのmasterに反映させる(自動的にmergeされている)
git pull
# branch確認とbranch作成
git branch, git branch branch
# branch移動
git checkout branch
# branch同士がどこで分岐しているかを取得(commitハッシュの取得)
git branch-base branch1 branch2
# commit地点のファイルにおける変更の出力
git diff hash branch file
# マージ
git merge subbranch
# 作成者の設定
git config --global user.name
# メールアドレスの設定
git config --global user.email
# sshの場所
ls -l ~/.ssh/
# sshの参照
cat ~/.ssh/file
# 
vsdemo branch kakuninn dekiru
# 
# sshパスワードを一回だけにする
ssh-add -K ~/.ssh/id_ed25519
# sshの履歴を全消し
ssh-add -D
```
# dockerのリセット
dockerリセットにおいて、ボリュームの問題がある。
docker rmにてコンテナを削除しても、ボリュームは残る。
これにより、完全な消去はできないため、初期化によるエラー解決を考えている場合はボリュームも削除すること。

# VsCodeの操作について
右下の奥から３番目のアイコンを押すとターミナルを起動できる。
右上２段目の奥から３番目のアイコンを押すとマークダウンを表示できる。

# LaravelのModelsについて
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class GetController extends Controller
{
    public function show(){
        var_dump(Post::select()->get());
    }
}
```
Modelsにはデータベースのモデル？がある。例えば、postsというテーブルがあるとき、Postモデルが定義されていると、それを活用することでpostsテーブルからSQL文でデータを取得できる。

# セッションについて
クッキーの中にセッションが入っており、サーバが受け取るとユーザを認識する。
セッションがないと値を保持できない。ログイン次のユーザを認識するためにある。
laravelはそれを抽象化している。ロードバランサを入れた時、複数台でログインした時におかしくなるため
データベースにセッションを管理させている。
```
session_start(php)
```
現在はステートレスを推奨している。
# Basic認証
これはパスワードがネットを流れる。