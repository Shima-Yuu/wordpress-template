<?php
/* =======================================================
 * header.php
 * ===================================================== */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">

<head>
  <?php
  /***************************************************************************
   ** GTMタグの表示/非表示
   ****************************************************************************/
  global $head_GTM_01;
  // タグマネ1を表示
  if ($head_GTM_01) echo $head_GTM_01 . "\n";
  ?>

  <?php
  /***************************************************************************
   ** meta情報
   ****************************************************************************/
  ?>
  <title></title>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  <link rel="icon" href="<?= home_url() ?>/favicon.ico">

  <?php
  /***************************************************************************
   **  全ページに適用
   ****************************************************************************/
  ?>
  <script src="<?= get_stylesheet_directory_uri(); ?>/assets/js/jquery-3.6.0.min.js"></script>
  <script src="<?= get_stylesheet_directory_uri(); ?>/assets/js/common.js" defer></script>

  <?php
  /***************************************************************************
   ** css, jsの自動読み込み
   ** スラッグ及び親階層の情報から読み込むcss,jsを指定する
   ****************************************************************************/
  global $post;

  $slug = '';
  
  if (is_front_page()) {
      $slug = 'top';
  } elseif (is_tax() || is_archive() || is_single()) {
      $term = get_queried_object();
      $slug = is_single() ? $term->post_type : $term->name;
  } elseif (is_page() || $post->post_parent) {
      $slug = $post->post_name;
  }
  
  $css_file = dirname(__FILE__) . '/assets/css/' . $slug . '.min.css';
  $js_file  = dirname(__FILE__) . '/assets/js/' . $slug . '.js';
  
  echo '<link rel="stylesheet" href="' . (file_exists($css_file) ? TEMP . '/css/' . $slug . '.min.css' : TEMP . '/css/common.min.css') . '">';
  
  if (file_exists($js_file)) {
    echo '<script src="' . TEMP . '/js/' . $slug . '.js' . '" defer></script>';
  }

  ?>

  <?php
  /***************************************************************************
   ** 全ページに読み込まれているファイル｜cssにて記述可能(緊急用)
   ****************************************************************************/
  ?>
  <link rel="stylesheet" href="<?= TEMP ?>/css/critical.css">

  <?php
  /***************************************************************************
   ** WPにログイン時のみ適用
   ****************************************************************************/
  ?>
  <?php if (is_user_logged_in()) : ?>
    <link rel="stylesheet" href="<?= TEMP ?>/css/wp-admin/admin_custom_front.css">
  <?php endif; ?>

  <?php wp_head(); ?>
</head>


<body>
  <?php
  // タグマネ2を表示
  global $head_GTM_02;
  if ($head_GTM_02) echo $head_GTM_02 . "\n";
  ?>

  <!-- 追従ボタン -->
  <div id="js--btn-top" class="s-top-btn"></div>

  <!-- ヘッダーのHTML -->
  <?php require_once 'include/header-html.php'; ?>

  <!-- メインコンテンツ -->
  <main class="l-main">