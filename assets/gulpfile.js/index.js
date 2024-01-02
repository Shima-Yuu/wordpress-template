const { src, dest, watch, series, parallel } = require('gulp');
const gulp = require('gulp');
const config = require('./settings/config.js');
const $ = require('./settings/modules.js'); // プラグインを読み込む。接頭辞に$をつける。
const { createFolders } = require('./settings/functions.js'); // 汎用的なfunctionを記述
const { scssCompress } = require('./tasks/task.scss.js'); // scss 画像圧縮読み込み
const { styleGuide, styleGuideLoad } = require('./tasks/task.styleGuide.js'); // styleGuide 汎用パーツ記述
const { EJScompile } = require('./tasks/task.EJScompile'); // wpHTML内で変数等を使えるようにする
const { imgCompress } = require('./tasks/task.imgCompress'); // imgcompress 画像圧縮読み込み

function firstAction(done) {
  createFolders(config.src_path.imgFolder, config.dest_path.css.min);
  styleGuide();
  scssCompress();
  styleGuideLoad(done);
}

function styleGuideSync() {
  require('browser-sync')
  .create('styleGuide')
  .init({
    host: 'localhost',
    port: 8889,
    ui: {
      port: 8889,
    },
    mode: 'proxy',
    proxy: process.env.LOCAL_URL + 'wp-content/themes/' + process.env.THEME_NAME + '/assets/styleGuide/',
  });
}

exports.run = series(firstAction,function secondAction(done){
  gulp.watch(config.scss, scssCompress);
  gulp.watch(config.src_path.img, imgCompress);
  done();
});

exports.dev = series(firstAction,function secondAction(done) {
  $.browserSync.init({
    host: 'localhost',
    port: 8888,
    mode: 'proxy',
    files: ['../**/*.php', './**/*.js', '../**/*.html', './**/*.scss', './**/*.ejs'],
    proxy: process.env.LOCAL_URL,
  });
  styleGuideSync();
  gulp.watch(config.scss, scssCompress);
  gulp.watch(['./**/*.ejs', '!./src/_*.ejs'], EJScompile);
  gulp.watch(config.src_path.img, imgCompress);
  done();
});

exports.styleGuide = series(firstAction,function secondAction(done) {
  styleGuideSync();
  gulp.watch(config.scss, series(scssCompress,styleGuideLoad)); // frontnoteとscssのコンパイルを行う
  done();
});

exports.compile = (done) => {
  scssCompress();
  done();
};