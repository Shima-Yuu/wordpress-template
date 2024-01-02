<?php

/***************************************************************************
 **
 ** 汎用的なショートコード
 **
 ****************************************************************************/

/**
 * パス系
 */
add_shortcode('tempurl', function () {
  return get_stylesheet_directory_uri() . '/assets';
});
add_shortcode('homeurl', function () {
  return home_url();
});

/**
 * 年と月を取得する
 * @author shimazaki
 * @return string 〇〇年◯月の形式で文字列で返す
 */
add_shortcode('getdate', function () {
  $current_year  = date("Y");
  $current_month = date("n");
  return $current_year . '年' . $current_month . '月';
});

/**
 * include配下のPHPファイルを呼び出すショートコード
 * @param string $file ファイル名を記述(拡張子は省略できる) 
 * @return string 呼び出すファイルのHTMLを表示
 */
add_shortcode('myphp', function ($params = array()) {
  extract(shortcode_atts(array(
    'file' => 'default'
  ), $params));
  ob_start();
  include(get_theme_root() . '/' . get_template() . "/include/$file.php");
  return ob_get_clean();
});

/**
 * カスタムフィールドの値を本文に表示
 * @param string name 取得したいカスタムフィールドのnameを指定
 * @return string 指定したカスタムフィールドの値
 */
add_shortcode('cf', function ($atts = null) {
  global $post;

  $name = isset($atts['name']) ? $atts['name'] : '';
  $cf_post_id = $post->ID;
  $field = get_post_meta($cf_post_id, $name, true);
  return do_shortcode($field); // カスタムフィールドの値にショートコードが入ってた時用にdo_shortcode()関数
});

/**
 * タームカスタムフィールドの値を本文に表示
 * @param string tax タクソノミーを指定する
 * @param string name フィールド名を指定する
 * @return string 指定したタームカスタムフィールドの値
 */
add_shortcode('cf_term', function ($atts = null) {
  global $post;
  $tax = isset($atts['tax']) ? $atts['tax'] : '';
  $name = isset($atts['name']) ? $atts['name'] : '';
  if (!empty($tax)) {
    $terms = get_the_terms($post->ID, $tax);

    if (!empty($terms) && !empty($name)) {
      $term = $terms[0];

      $field = get_field($name, $tax . '_' . $term->term_id);
      return do_shortcode($field);
    }
  }
});
