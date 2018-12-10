<?php
function usaidralf_create_post_type(){
  $activity_labels = array(
    'name' => _x('Activities', 'post type general name', 'usaidralf'),
    'singular_name' => _x('Activity', 'post type singular name', 'usaidralf'),
    'menu_name' => _x('Activities', 'admin menu', 'usaidralf'),
    'name_admin_bar' => _x('Activity', 'add new on admin bar', 'usaidralf'),
    'add_new' => _x('Add New', 'activity', 'usaidralf'),
    'add_new_item' => __('Add New Activity', 'usaidralf'),
    'new_item' => __('New Activity', 'usaidralf'),
    'edit_item' => __('Edit Activity', 'usaidralf'),
    'view_item' => __('View Activity', 'usaidralf'),
    'view_items' => __('View Activities', 'usaidralf'),
    'all_items' => __('All Activities', 'usaidralf'),
    'search_items' => __('Search Activities', 'usaidralf'),
    'not_found' => __('No Activities Found', 'usaidralf'),
    'not_found_in_trash' => __('No Activities Found in Trash', 'usaidralf')
  );
  $activity_args = array(
    'labels' => $activity_labels,
    'description' => __('RALF Activities', 'usaidralf'),
    'public' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-store',
    'supports' => array('title', 'author', 'revisions', 'editor')
  );
  register_post_type('activities', $activity_args);

  $impacts_labels = array(
    'name' => _x('Impacts', 'post type general name', 'usaidralf'),
    'singular_name' => _x('Impact', 'post type singular name', 'usaidralf'),
    'menu_name' => _x('Impacts', 'admin menu', 'usaidralf'),
    'name_admin_bar' => _x('Impact', 'add new on admin bar', 'usaidralf'),
    'add_new' => _x('Add New', 'impact', 'usaidralf'),
    'add_new_item' => __('Add New Impact', 'usaidralf'),
    'new_item' => __('New Impact', 'usaidralf'),
    'edit_item' => __('Edit Impact', 'usaidralf'),
    'view_item' => __('View Impact', 'usaidralf'),
    'view_items' => __('View Impacts', 'usaidralf'),
    'all_items' => __('All Impacts', 'usaidralf'),
    'search_items' => __('Search Impacts', 'usaidralf'),
    'not_found' => __('No Impacts Found', 'usaidralf'),
    'not_found_in_trash' => __('No Impacts Found in Trash', 'usaidralf')
  );
  $impacts_args = array(
    'labels' => $impacts_labels,
    'description' => __('RALF Impacts', 'usaidralf'),
    'public' => true,
    'menu_position' => 6,
    'menu_icon' => 'dashicons-lightbulb',
    'supports' => array('title', 'author', 'revisions', 'editor')
  );
  register_post_type('impacts', $impacts_args);

  $resources_labels = array(
    'name' => _x('Resources','post type general name', 'usaidralf'),
    'singular_name' => _x('Resource', 'post type singular name', 'usaidralf'),
    'menu_name' => _x('Resources', 'admin menu', 'usaidralf'),
    'name_admin_bar' => _x('Resource', 'add new on admin bar', 'usaidralf'),
    'add_new' => _x('Add New', 'resource', 'usaidralf'),
    'add_new_item' => __('Add New Resource', 'usaidralf'),
    'new_item' => __('New Resource', 'usaidralf'),
    'edit_item' => __('Edit Resource', 'usaidralf'),
    'view_item' => __('View Resource', 'usaidralf'),
    'view_items' => __('View Resources', 'usaidralf'),
    'all_items' => __('All Resources', 'usaidralf'),
    'search_items' => __('Search Resources', 'usaidralf'),
    'not_found' => __('No Resources Found', 'usaidralf'),
    'not_found_in_trash' => __('No Resources Found in Trash', 'usaidralf')
  );
  $resources_args = array(
    'labels' => $resources_labels,
    'description' => __('RALF Resources', 'usaidralf'),
    'public' => true,
    'menu_position' => 7,
    'menu_icon' => 'dashicons-book-alt',
    'supports' => array('title', 'author', 'revisions', 'editor')
  );
  register_post_type('resources', $resources_args);

  register_taxonomy('sectors',
    array('impacts', 'resources'),
    array(
      'hierarchical' => true,
      'show_admin_column' => true,
      'public' => true,
      'labels' => array(
        'name' => _x('Sectors', 'taxonomy general name', 'usaidralf'),
        'singular_name' => _x('Sector', 'taxonomy singular name', 'usaidralf'),
        'search_items' => __('Search Sectors', 'usaidralf'),
        'all_items' => __('All Sectors', 'usaidralf'),
        'parent_item' => __('Parent Sector', 'usaidralf'),
        'parent_item_colon' => __('Parent Sector:', 'usaidralf'),
        'edit_item' => __('Edit Sector', 'usaidralf'),
        'update_item' => __('Update Sector', 'usaidralf'),
        'add_new_item' => __('Add New Sector', 'usaidralf'),
        'new_item_name' => __('New Sector Name', 'usaidralf'),
        'menu_name' => __('Sectors', 'usaidralf')
      )
    )
  );
  register_taxonomy('impact_tags',
    'impacts',
    array(
      'hierarchical' => false,
      'show_admin_column' => true,
      'public' => true,
      'labels' => array(
        'name' => _x('Impact Tags', 'taxonomy general name', 'usaidralf'),
        'singular_name' => _x('Impact Tag', 'taxonomy singular name', 'usaidralf'),
        'search_items' => __('Search Impact Tags', 'usaidralf'),
        'popular_items' => __('Popular Impact Tags', 'usaidralf'),
        'all_items' => __('All Impact Tags', 'usaidralf'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Impact Tag', 'usaidralf'),
        'update_item' => __('Update Impact Tag', 'usaidralf'),
        'add_new_item' => __('Add New Impact Tag', 'usaidralf'),
        'new_item_name' => __('New Impact Tag Name', 'usaidralf'),
        'separate_items_with_commas' => __('Separate Impact Tags with commas', 'usaidralf'),
        'add_or_remove_items' => __('Add or Remove Impact Tags', 'usaidralf'),
        'choose_from_most_used' => __('Choose from the most used Impact Tags', 'usaidralf'),
        'not_found' => __('No Impact Tags Found', 'usaidralf'),
        'menu_name' => __('Impact Tags', 'usaidralf')
      )
    )
  );
  register_taxonomy('resource_types',
    'resources',
    array(
      'hierarchical' => true,
      'show_admin_column' => true,
      'public' => true,
      'labels' => array(
        'name' => _x('Resource Types', 'taxonomy general name', 'usaidralf'),
        'singular_name' => _x('Resource Type', 'taxonomy singular name', 'usaidralf'),
        'search_items' => __('Search Resource Types', 'usaidralf'),
        'all_items' => __('All Resource Types', 'usaidralf'),
        'parent_item' => __('Parent Resource Type', 'usaidralf'),
        'parent_item_colon' => __('Parent Resource Type:', 'usaidralf'),
        'edit_item' => __('Edit Resource Type', 'usaidralf'),
        'update_item' => __('Update Resource Type', 'usaidralf'),
        'add_new_item' => __('Add New Resource Type', 'usaidralf'),
        'new_item_name' => __('New Resource Type Name', 'usaidralf'),
        'menu_name' => __('Resource Types', 'usaidralf')
      )
    )
  );
  register_taxonomy('priority_keywords',
    array('impacts', 'activities', 'resources'),
    array(
      'hierarchical' => false,
      'show_admin_column' => false,
      'public' => true,
      'labels' => array(
        'name' => _x('Priority Keywords', 'taxonomy general name', 'usaidralf'),
        'singular_name' => _x('Priority Keyword', 'taxonomy singular name', 'usaidralf'),
        'search_items' => __('Search Priority Keywords', 'usaidralf'),
        'popular_items' => __('Popular Priority Keywords', 'usaidralf'),
        'all_items' => __('All Priority Keywords', 'usaidralf'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Priority Keyword', 'usaidralf'),
        'update_item' => __('Update Priority Keyword', 'usaidralf'),
        'add_new_item' => __('Add New Priority Keyword', 'usaidralf'),
        'new_item_name' => __('New Priority Keyword Name', 'usaidralf'),
        'separate_items_with_commas' => __('Separate Priority Keywords with Commas', 'usaidralf'),
        'add_or_remove_items' => __('Add or Remove Priority Keywords', 'usaidralf'),
        'choose_from_most_used' => __('Choose from the most used Priority Keywords', 'usaidralf'),
        'not_found' => __('No Priority Keywords Found', 'usaidralfd'),
        'menu_name' => __('Priority Keywords', 'usaidralf')
      )
    )
  );
}