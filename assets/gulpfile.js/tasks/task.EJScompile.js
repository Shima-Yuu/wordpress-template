const { src, dest, watch, series, parallel } = require('gulp');
const gulp = require('gulp');
const $ = require('../settings/modules.js');
$.env('.env', { raise: false }); // check .env

// EJSコンパイル
const EJScompile = () => {
  gulp
    .src(['./wpHTML/pages/*.ejs', '!./src/_*.ejs'])
    .pipe(
      $.plumber(
        //エラーが出ても処理を止めない
        {
          errorHandler: $.notify.onError('Error:<%= error.message %>'),
        }
      )
    )
    .pipe($.ejs({}, {}, { ext: '.html' }))
    .pipe($.rename({ extname: '.html' }))
    .pipe(gulp.dest('./wpHTML/public/'));
};

exports.EJScompile = EJScompile;
