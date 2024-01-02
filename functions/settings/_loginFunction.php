<?php

/***************************************************************************
 ** 
 ** Wordpressログイン時にかける処理
 ** 
 ****************************************************************************/
/**
 * テスト/本番 条件分岐
 */
$host_product = '';
$host_development = '';
function custom_body_class()
{
  global $post, $host_product, $host_development;

  $body_class = [];
  if (is_front_page()) {
    $body_class[] = "page_top";
  } else {
    // if( is_front_lp() ) {
    // 	$body_class[] = "page_front_lp";
    // }

    $class_post_type = 'type_' . get_post_type($post);
    $class_post_slug = is_page() || is_single() ? 'slug_' . $post->post_name : "";

    if (is_page() && $post->post_parent) {
      $parent_id = $post->post_parent;
      $class_parent_slug = 'parent_' . get_post($parent_id)->post_name;
    }

    array_push($body_class, "page_lower", $class_post_type, $class_post_slug, $class_parent_slug);
  }

  if (is_user_logged_in()) {

    if (strpos($_SERVER['HTTP_HOST'], $host_product) === 0) {
      $body_class[] = '--host_product';
    } else {
      $body_class[] = '--host_development';
    }
  }
  echo implode(" ", $body_class);
}


/**
 * テスト<-->本番切り替えボタン追加
 * 現在のページ表示に使用しているテンプレートを表示
 */
function add_custom_footer()
{

  if (is_user_logged_in()) {

    // 現在のページ表示に使用しているテンプレートを表示
    $inc_file_list = get_included_files();

    $html_template = '<div class="--debug --debug_template sp_none" id="anchor_debug_template">
		<div class="debug_head">デバッグ表示（WPログイン時のみ表示）</div>
		<div class="debug_content">
		<div class="title">現在のページで読み込んでいるテンプレ</div>
		<ul class="list_file">';
    /**
     * 特定のフォルダ配下のファイル及び特定のファイルを含む場合、falseを返す
     * @param string $str 対象の文字列
     * @return bool
     */
    function particularPage($str)
    {
      return stristr($str, '/themes/') && !stristr($str, '/global') && !stristr($str, '/pages') && !stristr($str, '/settings') && !stristr($str, '/_register_post_type.php') && !stristr($str, '/_default.php'); // 本番用
    };
    foreach ($inc_file_list as $ink_key => $ink_val) {
      if (particularPage($ink_val)) {
        $ink_temp = mb_strlen($ink_val) - strrpos($ink_val, '.');
        $ink_temp = strrpos($ink_val, 'themes/', $ink_temp);

        if (preg_match('/\/(functions|header|footer)./', $ink_val)) {
          $class = 'default';
        } else {
          $class = '';
        }
        $html_template .= '<li class="' . $class . '">' . substr($ink_val, $ink_temp) . '</li>';
      }
    }
    $html_template .= '</ul></div></div>';

    echo $html_template;
  }
}
add_action('wp_footer', 'add_custom_footer');


/**
 * 管理画面内にスラッグを表示 & タクソノミー別に絞り込み
 * @author _shimazaki
 */
if (is_admin()) {
  /**
   * ターム別に絞り込めるようにプルダウンメニューを追加
   * 
   * @see restrict_manage_posts：https://developer.wordpress.org/reference/hooks/restrict_manage_posts/
   */
  add_action('restrict_manage_posts', function () {
    global $post_type;
    $taxonomies = get_object_taxonomies($post_type);

    // タクソノミーがある時のみ表示
    if (!empty($taxonomies)) {
      foreach ($taxonomies as $taxonomy) {
        $taxonomy_info = get_taxonomy($taxonomy);
        $terms         = get_terms($taxonomy);

        // タームがないタクソノミーは表示しない
        if (empty($terms)) continue;  ?>

        <select name="<?= $taxonomy; ?>">
          <option value=""><?= $taxonomy_info->label; ?>指定なし</option>
          <?php
          foreach ($terms as $term) {
            $is_selected = '';
            if ($_GET[$taxonomy] == $term->slug)  $is_selected = ' selected'; ?>
            <option value="<?= $term->slug; ?>" <?= $is_selected; ?>><?= $term->name; ?></option>
          <?php }; ?>
        </select>
<?php }
    }
  });


  /**
   * 管理画面内にスラッグを追加 / タクソノミー別に絞り込めるようにする
   * 
   * 固定ページ(page)：スラッグを追加、スラッグでの昇順、降順を指定できるようにする
   * カスタム投稿    ：スラッグを追加、ターム別に表示、絞り込めるようにする
   * @param string $post_type user_id 固定ページの場合は「page」をカスタム投稿の場合は、「カスタム投稿のスラッグ」を設定
   */
  function customPostColumnOnTheAdmin($post_type)
  {
    /**
     * 管理画面内に列を追加
     * @param  array $columns 管理画面内の行の値を配列にしたもの
     * @return array 加工した行の配列を返却
     */
    add_filter('manage_edit-' . $post_type . '_columns', function ($columns) {
      global $post_type;

      // 共通のカラム
      $common_columns = array(
        'cb'     => $columns['cb'],
        'title'  => 'タイトル',
        'author' => '投稿者',
        'slug'   => 'スラッグ',
      );

      // 固定ページ
      if ($post_type === 'page') {
        $columns = array_merge($common_columns, array('date' => $columns['date']));
        echo '<style>.fixed .column-slug,.fixed .column-column_tag {width:10%;}</style>';

        // カスタム投稿タイプ
      } else {
        $custom_columns = array();

        // カスタム投稿に属するタクソノミーを取得
        $taxonomies = get_object_taxonomies($post_type);
        if (!empty($taxonomies)) {
          foreach ($taxonomies as $taxonomy) {
            $taxonomy_info = get_taxonomy($taxonomy);
            $custom_columns[$taxonomy] = $taxonomy_info->label;
          }
        }
        $columns = array_merge($common_columns, $custom_columns, array('date' => $columns['date']));

        // タクソノミーに応じてカラムの幅を調整
        $taxonomy_classes = array_map(function ($taxonomy) {
          return '.fixed .column-' . $taxonomy;
        }, array_keys($custom_columns));

        // 行の横幅調整
        $classes = !empty($taxonomy_classes) ? implode(',', $taxonomy_classes) . ',' : '';
        echo '<style>.fixed .column-slug,' . $classes . '.fixed .column-column_tag {width:10%;}</style>';
      }

      return $columns;
    });

    /**
     * スラッグを取得、カスタム投稿にはターム別に絞り込めるようにリンクをつける
     * 
     * @param string $column_name 行の名前
     * @param string $post_id     投稿id
     */
    add_action('manage_' . $post_type . '_posts_custom_column', function ($column_name, $post_id) {
      // 固定ページ
      if ($column_name == 'slug') {
        $post = get_post($post_id);
        $slug = $post->post_name;
        echo esc_attr($slug);

        // カスタム投稿タイプ
      } else {
        global $post_type;
        $taxonomies = get_object_taxonomies(get_post($post_id));
        foreach ($taxonomies as $taxonomy) {
          $terms = get_the_terms($post_id, $taxonomy);
          if (!empty($terms) && !is_wp_error($terms) && $column_name === $taxonomy) {
            foreach ($terms as $term) {
              echo '<a href="edit.php?post_type=' . $post_type . '&amp;' . $taxonomy . '=' . $term->slug . '">' . $term->name . '</a><br>';
            }
          }
        }
      }
    }, 10, 2);

    /**
     * スラッグの列に昇順、降順で絞り込めるように機能を追加
     * 
     * @param  array $sortable_columns ソートしたい行の情報の配列
     * @return array ソートした行の情報の配列を返却
     */
    add_filter('manage_edit-' . $post_type . '_sortable_columns', function ($sortable_columns) {
      $sortable_columns['slug'] = 'name';
      return $sortable_columns;
    });
  }

  // get_post_types()にてカスタム投稿取得
  // 固定ページ('page')とカスタム投稿だけ処理をする
  function custum_post_types()
  {
    $args = array(
      'public' => true,
      '_builtin' => false
    );
    $post_types = get_post_types($args);
    $post_types = array_merge($post_types, ['page' => 'page']);
    foreach ($post_types as $post_type) {
      customPostColumnOnTheAdmin($post_type);
    }
  }
  add_action('init', 'custum_post_types');
}
