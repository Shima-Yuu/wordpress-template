# Wordpress サイト 新規構築テンプレート

## 集約シート

https://docs.google.com/spreadsheets/d/15iV0zgWneKSEFd7Q2_pRykN38Nm3xgoNOqf79lKpx3k/edit#gid=1848835498

## Gulp の環境構築

### 1. Node モジュールをインストール

assets ディレクトリに移動します。

```
.../themes/テーマ名> cd assets
```

各パッケージをインストール

```
.../themes/テーマ名/assets> npm i
```

### 2. .env ファイルの設定

assets ディレクトリ配下の「[sample.env](assets/sample.env)」をコピーして、同階層に「.env」ファイルを作成する  
.env ファイルの下記変数をローカル環境に合わせて書き換える

```
# 例）
LOCAL_URL=http://wp_template.com/
THEME_NAME=base
```

### 3. Gulp でタスクを走らせる

```
gulp dev
```

上記で「ローカルサイト」「Style Guide」の 2 つのページがブラウザで立ち上がります。  
ブラウザシンクの機能を利用して、効率化を図るので基本はこちらのタスクで作業を進めていただければと思います。

**【他タスク】**  
**SCSS のコンパイルと画像の圧縮&webp 化**

```
gulp run
```

**スタイルガイドを作成/更新する際に使用するタスク。(ファイルを編集する度にスタイルガイドページも更新されます)**

```
gulp styleGuide
```

**SCSS コンパイル専用(watch なし - winscp でサーバに上げる前のコンパイルの時に使ってください。)**

```
gulp compile
```

## HTML を管理するディレクトリの構造

Wordpress 内の HTML を「[~/staticHTML](staticHTML)」ディレクトリ配下にて管理します。  
ディレクトリ構造は下記のとおりです。
| 親ディレクトリ名 | ディレクトリ / ファイル名 | 説明 |
| :--- | :--- | :--- |
| staticHTML | [pages](staticHTML/pages) | 固定ページの HTML を管理します。 |
| pages | [page-slug.html](staticHTML/pages/page-slug.html) | 固定ページのスラッグをファイル名に指定します。 |
| pages/親ページのスラッグ | page-child-slug.html | 親ページが存在する場合は、親ページのディレクトリを作成し、その配下に小ページのスラッグでファイル名を指定して作成します。 |
| staticHTML | [post_type](staticHTML/post_type) | カスタム投稿タイプの投稿の HTML を管理します。 |
| post_type | [post_type_name](staticHTML/post_type/post_type_name) | カスタム投稿タイプのスラッグのディレクトリを作成します。その配下に投稿を格納します。 |
| post_type_name | [single-slug.html](staticHTML/post_type/post_type_name/single-slug.html) | 上階層のカスタム投稿タイプの投稿します。スラッグをファイル名に指定します。 |

## 進め方

### HTML ファイルの更新

1. [\_localFunction.php](functions/_localFunction.php)を開き、`$this_func_trigger = true`と書き換えます。  
   ※サーバ側にはこちらのファイルをあげないようにお願いします。
2. 「~/staticHTML/」配下に HTML ファイルを作成し、そちらを編集して作業を進めます。
3. サーバ側にアップする時は、サーバ側の Wordpress の投稿画面にて HTML を記入ください。  
   ※サーバ側には「~/staticHTML/」のファイルをあげないでください。

### SCSS ファイルの更新

基本はいつも通りで問題ないです。  
新たに追加したスタイルガイドを試してみたいので、scss ファイルを記述する際はスタイルガイド用の記述も加えてください。  
[StyleGuide の詳細はこちら](assets/styleGuide/overview.md)

### 画像の追加

`~/assets/images_bk`ディレクトリを作成します。  
上記に画像を格納することで`~/assets/images`に圧縮後の画像が格納されます。  
※サーバ側には`~/assets/images_bk`のファイルはあげないでください。

## ローカルにサーバ側のデータをマージしたい時

サーバ側の環境の「ALL-in-One WP Migration」プラグインにて下記の通りエクスポートする。(テーマのファイルはインポートしません)  
https://gyazo.com/f28c1fb58222bfd07dd6165fa69c72a2

エクスポートしたファイルをローカル環境でインポートしてください。  
上記により ACF やメディアファイルも完全にインポートできるはずです。

HTML は Git にて管理しているので、仮にサーバ側に上書かれたとしてもページに遷移すればローカル側のデータに上書かれるはずです。
