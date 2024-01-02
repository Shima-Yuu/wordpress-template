const { src, dest, watch, series, parallel } = require('gulp');
const gulp = require('gulp');
const $ = require('../settings/modules.js');
const config = require('../settings/config.js');
const fs = require('fs');

const styleGuide = () => {
  fs.readdir('./css', (err, files) => {
    files.forEach((file) => {
      if (file.indexOf('.css') !== -1) {
        // ~/css/直下のcssファイルのみ対象
        config.cssFiles.push('../css/' + file);
      }
    });
  });
};

function styleGuideLoad(done) {
  return src(config.scss)
    .pipe(
      $.plumber({
        errorHandler: $.notify.onError('Error:<%= error.message %>'),
      })
    )
    .pipe(
      $.frontNote({
        out: config.styleGuide,
        overview: './' + config.styleGuide + '/overview.md',
        title: 'Style Guide',
        css: config.cssFiles,
        script: './assets/js/styleGuide.js',
      })
    )
    .on('end', done);  // タスクが終了したらdoneを呼ぶ
}
exports.styleGuide = styleGuide;
exports.styleGuideLoad = styleGuideLoad;
