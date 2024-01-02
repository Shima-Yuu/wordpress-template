module.exports = {
  scss: 'scss/**/**.scss',
  // 参照先パス
  src_path: {
    img: 'images_bk/**/*',
    imgFolder: 'images_bk/',
  },
  // 出力先パス
  dest_path: {
    img: 'images/',
    css: {
      min: 'css/',
      before_compression: 'css/before_compression',
    },
  },
  styleGuide: 'styleGuide',
  browsers: ['last 2 versions', '> 5%', 'ie = 11', 'not ie <= 10', 'ios >= 8', 'and_chr >= 5', 'Android >= 5'], // postcss-cssnext ブラウザ対応条件 prefix 自動付与
  cssFiles: ['assets/css/styleGuide.css'], // StyleGuideにて読み込むcss
};
