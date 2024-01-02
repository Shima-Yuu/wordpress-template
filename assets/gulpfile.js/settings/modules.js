module.exports = {
  sass: require('gulp-sass')(require('sass')), // scssコンパイル
  plumber: require('gulp-plumber'), // エラーが発生しても強制終了させない
  notify: require('gulp-notify'), // エラー発生時のアラート出力
  postcss: require('gulp-postcss'), // PostCSS利用
  cssnext: require('postcss-cssnext'), // CSSNext利用
  cleanCSS: require('gulp-clean-css'), // 圧縮
  rename: require('gulp-rename'), // ファイル名変更
  sourcemaps: require('gulp-sourcemaps'), // ソースマップ作成
  mqpacker: require('css-mqpacker'), // メディアクエリをまとめる
  env: require('node-env-file'), // env作成
  browserSync: require('browser-sync').create(), // browserSync nodeサーバー立ち上げ
  ejs: require('gulp-ejs'), // browserSync ejs作る
  htmlbeautify: require('gulp-html-beautify'), //コンパイル後のhtmlをきれいにする
  frontNote: require('gulp-frontnote'), // スタイルガイド作成
  imagemin: require('gulp-imagemin'), // 画像圧縮
  imageminMozjpeg: require('imagemin-mozjpeg'), // 画像圧縮(jpg用)
  pngquant: require('imagemin-pngquant'), // 画像圧縮(png用)
  webp: require('gulp-webp'), // webpに変換
  newer: require('gulp-newer'), // 圧縮時変更されたファイルを識別する
};
