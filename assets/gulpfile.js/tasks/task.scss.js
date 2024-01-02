const { src, dest, watch, series, parallel } = require('gulp');
const gulp = require('gulp');
const $ = require('../settings/modules.js');
const config = require('../settings/config.js');
//sass
const scss = () => {
  return src(config.scss)
    .pipe(
      $.plumber({
        errorHandler: $.notify.onError('Error:<%= error.message %>'),
      })
    )
    .pipe($.sourcemaps.init())
    .pipe($.sass({ outputStyle: 'expanded' }))
    .pipe($.postcss([$.mqpacker()]))
    .pipe($.postcss([$.cssnext(config.browsers)]))
    .pipe(dest(config.dest_path.css.before_compression))
    .pipe($.cleanCSS())
    .pipe($.sourcemaps.write('/maps'))
    .pipe(
      $.rename({
        suffix: '.min',
      })
    )
    .pipe(dest(config.dest_path.css.min));
};

exports.scssCompress = scss;
