<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

acf_add_local_field_group(array(
  'key' => 'group_5bcde52f3147b',
  'title' => 'Report History Settings',
  'fields' => array(
    array(
      'key' => 'field_5bcde537c6286',
      'label' => 'How long to store reports?',
      'name' => 'how_long_to_store_reports',
      'type' => 'number',
      'instructions' => 'Enter the number of days to keep reports before they are deleted.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '25',
        'class' => '',
        'id' => ''
      ),
      'default_value' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => 'days',
      'min' => '',
      'max' => '',
      'step' => 1
    )
  ),
  'location' => array(
    array(
      array(
        'param' => 'options_page',
        'operator' => '==',
        'value' => 'general-settings'
      )
    )
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => 1,
  'description' => ''
));
/*
acf_add_local_field_group(array(
  'key' => 'group_5bf56d9a31048',
  'title' => 'Number of Times Shown in Search Results',
  'fields' => array(
    array(
      'key' => 'field_5bf56db6a2d37',
      'label' => 'Number of Times Shown in Search Results',
      'name' => 'search_results_count',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => 0,
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'min' => '',
      'max' => '',
      'step' => 1,
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'activities',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'impacts',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'resources',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'side',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => 1,
  'description' => '',
));*/

acf_add_local_field_group(array(
  'key' => 'group_5bf5dc4ee5038',
  'title' => 'Email Reports Settings',
  'fields' => array(
    array(
      'key' => 'field_5bf5dc59b7941',
      'label' => 'Number of results to include in report',
      'name' => 'number_of_results',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => 20,
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'min' => '',
      'max' => '',
      'step' => 1,
    ),
    array(
      'key' => 'field_5bf5dca7b7942',
      'label' => 'Saved to Report',
      'name' => 'saved_to_report',
      'type' => 'group',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'block',
      'sub_fields' => array(
        array(
          'key' => 'field_5bf5dd04b7943',
          'label' => 'Email Addresses to Send Weekly',
          'name' => 'weekly_email_addresses',
          'type' => 'repeater',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'collapsed' => '',
          'min' => 0,
          'max' => 0,
          'layout' => 'table',
          'button_label' => 'Add Email Address',
          'sub_fields' => array(
            array(
              'key' => 'field_5bf5dd38b7944',
              'label' => 'Email Address',
              'name' => 'email_address',
              'type' => 'email',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'default_value' => '',
              'placeholder' => '',
              'prepend' => '',
              'append' => '',
            ),
          ),
        ),
        array(
          'key' => 'field_5bf5dd69b7945',
          'label' => 'Email Addresses to Send Monthly',
          'name' => 'monthly_email_addresses',
          'type' => 'repeater',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'collapsed' => '',
          'min' => 0,
          'max' => 0,
          'layout' => 'table',
          'button_label' => 'Add Email Address',
          'sub_fields' => array(
            array(
              'key' => 'field_5bf5dd81b7946',
              'label' => 'Email Address',
              'name' => 'email_address',
              'type' => 'email',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'default_value' => '',
              'placeholder' => '',
              'prepend' => '',
              'append' => '',
            ),
          ),
        ),
      ),
    ),
    array(
      'key' => 'field_5bf5ddb3b7947',
      'label' => 'Searched Terms',
      'name' => 'searched_terms',
      'type' => 'group',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'block',
      'sub_fields' => array(
        array(
          'key' => 'field_5bf5ddd8b7948',
          'label' => 'Email Addresses to Send Weekly',
          'name' => 'weekly_email_addresses',
          'type' => 'repeater',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'collapsed' => '',
          'min' => 0,
          'max' => 0,
          'layout' => 'table',
          'button_label' => 'Add Email Address',
          'sub_fields' => array(
            array(
              'key' => 'field_5bf5ddedb7949',
              'label' => 'Email Address',
              'name' => 'email_address',
              'type' => 'email',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'default_value' => '',
              'placeholder' => '',
              'prepend' => '',
              'append' => '',
            ),
          ),
        ),
        array(
          'key' => 'field_5bf5de13b794a',
          'label' => 'Email Addresses to Send Monthly',
          'name' => 'monthly_email_addresses',
          'type' => 'repeater',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'collapsed' => '',
          'min' => 0,
          'max' => 0,
          'layout' => 'table',
          'button_label' => 'Add Email Address',
          'sub_fields' => array(
            array(
              'key' => 'field_5bf5de27b794b',
              'label' => 'Email Address',
              'name' => 'email_address',
              'type' => 'email',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'default_value' => '',
              'placeholder' => '',
              'prepend' => '',
              'append' => '',
            ),
          ),
        ),
      ),
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'options_page',
        'operator' => '==',
        'value' => 'general-settings',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => 1,
  'description' => '',
));
