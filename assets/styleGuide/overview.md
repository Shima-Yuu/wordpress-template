# 目次

- [目次](#目次)
  - [FrontNote 公式ドキュメント](#document-)
  - [ルール](#rule-)
  - [コメントの書き方](#comment-)
    - [ファイル概要](#overview-)
    - [セクション](#section-)
    - [カラーパレット ※試験的に使ってみて不要であれば削除](#color-pallet-)

## Document - 公式ドキュメント

[gulp での利用方法に関して](https://github.com/sable-virt/gulp-frontnote)  
[オプションやコメントの書き方について](https://github.com/sable-virt/frontnote)

## Rule - ルール

- スタイルガイドの対象のファイルは下記に限定します。 **※流用できるファイルが対象です。**
  - scss/object/component/配下
  - scss/object/project/配下 **※固有ページの独自のスタイルを記述しているファイルは対象外です。**
- 記載する項目
  - [ファイル概要](#overview-)
  - [セクション](#section-)
  - [カラーパレット](#color-pallet-)

## Comment - コメントの書き方

### Overview - ファイル概要

1 ファイルに 1 ブロックだけ記述できます。

```
/*
#overview
①タイトル

②詳細な情報
*/
```

`#overview`で概要を定義できます。  
① タイトルと ② 詳細な情報は、画面で言う下記箇所に該当します。  
[https://gyazo.com/b1a7db978480f835a6b47e28f365ca2f](https://gyazo.com/b1a7db978480f835a6b47e28f365ca2f)

### Section - セクション

要素のスタイルのパターンを定義します。

````
/*
#styleguide
①タイトル

②詳細な情報

```
③htmlのコード
例）
<div class="c-btn"></div>
```

*/
````

① タイトルと ② 詳細な情報と ③html のコードは、画面で言う下記箇所に該当します。  
[https://gyazo.com/b5eafae17ddc3defe63bc4a2124162f4](https://gyazo.com/b5eafae17ddc3defe63bc4a2124162f4)  
※② は省略してもいいです。

ラベルをつけることもできます。

````
/*
#styleguide
①タイトル

@deprecated
@非推奨
@todo
@好きな名前

```
③htmlのコード
```
*/
````

[見た目はこちら](https://gyazo.com/df5fd38dfc574ff2de37f60776284c91)  
「@非推奨 / @todo」を使用する場合は、② 詳細な情報に理由や内容を書いてほしいです。

### Color Pallet - カラーパレット ※試験的に使ってみて不要であれば削除

**\_variable.scss**ファイルのみ記述します。

```
/*
#colors

@$baseWhite #eaeaea
@$baseGray #a4a4a4
*/
```

`#colors`でカラーパレットを定義できます。  
`@カラーコードの変数 カラーコード`の記法で統一してます。
