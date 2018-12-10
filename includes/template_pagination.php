<?php
function usaidralf_pagination(){
  global $wp_query;

  if($wp_query->max_num_pages <= 1){ return; }

  $big = 999999999;
  $pages = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type' => 'array'
  ));

  if(is_array($pages)){
    $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');

    echo '<nav aria-label="Page navigation" class="pagination-nav"><ul class="pagination">';

    foreach($pages as $page){
      echo '<li>' . $page . '</li>';
    }

    echo '</ul></nav>';
  }
}