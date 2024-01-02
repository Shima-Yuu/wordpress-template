<?php

/***************************************************************************
 **
 ** カスタム投稿タイプを定義
 **
 ****************************************************************************/
add_action('init', function () {
  // add_action('init',function(){
  // 	register_post_type( 'column', [
  // 		'label' => 'コラム',
  // 		'public' => true,
  // 		'has_archive' => true,
  // 		'taxonomies' => ['column_category','column_tag'],
  // 		'menu_position' => 57,
  // 		'supports' => ['title','editor'],
  // 	]);
  // 	register_taxonomy('column_category','column', [
  // 		'label' => 'コラムカテゴリー',
  // 		'hierarchical' => true, // カテゴリー
  // 	]);
  // 	register_taxonomy('column_tag','column', [
  // 		'label' => 'コラムタグ',
  // 		'hierarchical' => true, // タグ
  // 		'show_ui'        => true,
  // 		'query_var'      => true,
  // 		'rewrite'        => true,
  // 		'singular_label' => 'コラムタグ',
  // 		'show_in_rest'   => true,
  // 	]);

  // 	add_theme_support( 'post-thumbnails', ['column'] );
  // });

  /***************************************************************************
   ** クリニック
   ****************************************************************************/
  // register_post_type('clinic', [
  //   'label'       => '',
  //   'public'      => true,
  //   'has_archive' => true,
  //   'taxonomies'  => [''],
  //   'supports'    => ['title', 'editor', 'thumbnail'],
  //   'menu_icon'   => 'dashicons-star-filled',
  // ]);

  // register_taxonomy('', "clinic", [
  //   'label'        => '都道府県',
  //   'hierarchical' => true,
  // ]);
});
