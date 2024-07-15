<?php

/**
 * Plugin Name: Bluestoneapps Events Calendar
 * Plugin URI: https://www.bluestoneapps.com/
 * Description: This is an events calendar plugin created by BluestoneApps.
 * Version: 1.0.0
 * Author: Larry Allen
 * Author URI: https://www.bluestoneapps.com
 * License: GPL2
 */

define('BLUESTONE_EVENTS_POST_TYPE', 'bs_calendar_event');

function create_event_post_type()
{

  register_post_type(
    BLUESTONE_EVENTS_POST_TYPE,
    array(
      'labels' => array(
        'name' => __('Events'),
        'singular_name' => __('Event'),
        'add_new_item' => __('Add New Event'),
        'edit_item' => __('Edit Event'),
        'new_item' => __('New Event'),
        'view_item' => __('View Event'),
        'search_items' => __('Search Events'),
        'not_found' => __('No events found'),
        'not_found_in_trash' => __('No events found in Trash')
      ),
      'public' => true,
      'has_archive' => true,
      'menu_position' => 5,  // Position in the menu order
      'supports' => array('title', 'editor', 'author'),
      'show_in_menu' => true,
      'menu_icon' => 'dashicons-calendar',  // Icon for the menu
    )
  );
}

add_action('init', 'create_event_post_type');

function event_date_meta_box()
{
  add_meta_box('event_date', 'Event Details', 'event_date_meta_box_callback', BLUESTONE_EVENTS_POST_TYPE);
}

add_action('add_meta_boxes', 'event_date_meta_box');

function event_date_meta_box_callback($post)
{
  wp_nonce_field('event_date_save_meta_box_data', 'event_date_meta_box_nonce');

  $date_value = get_post_meta($post->ID, '_event_date', true);
  $from_time_value = get_post_meta($post->ID, '_event_from_time', true);
  $to_time_value = get_post_meta($post->ID, '_event_to_time', true);
  $street_address_value = get_post_meta($post->ID, '_event_street_address', true);
  $apt_suite_value = get_post_meta($post->ID, '_event_apt_suite', true);
  $city_value = get_post_meta($post->ID, '_event_city', true);
  $state_value = get_post_meta($post->ID, '_event_state', true);
  // Read and decode JSON file
  $json = file_get_contents(plugin_dir_path(__FILE__) . 'us-states.json');
  $states = json_decode($json, true);

  $zip_value = get_post_meta($post->ID, '_event_zip', true);
  $latitude_value = get_post_meta($post->ID, '_event_latitude', true);
  $longitude_value = get_post_meta($post->ID, '_event_longitude', true);
  $price_value = get_post_meta($post->ID, '_event_price', true);


  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_date_field">Date:</label> ';
  echo '<input type="date" id="event_date_field" name="event_date_field" value="' . esc_attr($date_value) . '" size="25" /></div>';
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_from_time_field">From:</label> ';
  echo '<input type="time" id="event_from_time_field" name="event_from_time_field" value="' . esc_attr($from_time_value) . '" size="25" /></div>';
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_to_time_field">To:</label> ';
  echo '<input type="time" id="event_to_time_field" name="event_to_time_field" value="' . esc_attr($to_time_value) . '" size="25" /></div>';
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_street_address_field">Street Address:</label> ';

  echo '<input type="text" id="event_street_address_field" name="event_street_address_field" value="' . esc_attr($street_address_value) . '" size="25" /></div>';

  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_apt_suite_field">Apt/Suite:</label> ';

  echo '<input type="text" id="event_apt_suite_field" name="event_apt_suite_field" value="' . esc_attr($apt_suite_value) . '" size="25" /></div>';


  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_city_field">City:</label> ';
  echo '<input type="text" id="event_city_field" name="event_city_field" value="' . esc_attr($city_value) . '" size="25" /></div>';


  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_state_field">State:</label> ';
  echo '<select id="event_state_field" name="event_state_field">';
  foreach ($states as $state) {
    echo '<option value="' . esc_attr($state['abbreviation']) . '"' . selected($state_value, $state['abbreviation'], false) . '>' . esc_html($state['name']) . '</option>';
  }
  echo '</select></div>';


  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_zip_field">Zip:</label> ';
  echo '<input type="text" id="event_zip_field" name="event_zip_field" value="' . esc_attr($zip_value) . '" size="25" /></div>';

  /*
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_latitude_field">Event Latitude:</label> ';
  echo '<input type="text" id="event_latitude_field" name="event_latitude_field" value="' . esc_attr($latitude_value) . '" size="25" /></div>';
  
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_longitude_field">Event Longitude:</label> ';
  echo '<input type="text" id="event_longitude_field" name="event_longitude_field" value="' . esc_attr($longitude_value) . '" size="25" /></div>';
  
  echo '<div style="display: flex; margin-top: 5px;"><label style="width: 160px;" for="event_price_field">Price:</label> ';
  echo '<input type="number" id="event_price_field" name="event_price_field" value="' .
  */

  esc_attr($price_value) . '" size="25" /></div>';
}

function save_event_date_meta_box_data($post_id)
{
  if (!isset($_POST['event_date_meta_box_nonce'])) {
    return;
  }

  if (!wp_verify_nonce($_POST['event_date_meta_box_nonce'], 'event_date_save_meta_box_data')) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (!isset($_POST['event_date_field']) || !isset($_POST['event_from_time_field']) || !isset($_POST['event_to_time_field']) || !isset($_POST['event_street_address_field']) || !isset($_POST['event_apt_suite_field']) || !isset($_POST['event_state_field']) || !isset($_POST['event_zip_field']) || !isset($_POST['event_latitude_field']) || !isset($_POST['event_longitude_field']) || !isset($_POST['event_price_field'])) {
    return;
  }

  $event_date = sanitize_text_field($_POST['event_date_field']);
  $event_from_time = sanitize_text_field($_POST['event_from_time_field']);
  $event_to_time = sanitize_text_field($_POST['event_to_time_field']);
  $event_street_address = sanitize_text_field($_POST['event_street_address_field']);
  $event_apt_suite = sanitize_text_field($_POST['event_apt_suite_field']);
  $event_city = sanitize_text_field($_POST['event_city_field']);
  $event_state = sanitize_text_field($_POST['event_state_field']);
  $event_zip = sanitize_text_field($_POST['event_zip_field']);
  $event_latitude = sanitize_text_field($_POST['event_latitude_field']);
  $event_longitude = sanitize_text_field($_POST['event_longitude_field']);
  $event_price = sanitize_text_field($_POST['event_price_field']);

  update_post_meta($post_id, '_event_date', $event_date);
  update_post_meta($post_id, '_event_from_time', $event_from_time);
  update_post_meta($post_id, '_event_to_time', $event_to_time);
  update_post_meta($post_id, '_event_street_address', $event_street_address);
  update_post_meta($post_id, '_event_apt_suite', $event_apt_suite);
  update_post_meta($post_id, '_event_city', $event_city);
  update_post_meta($post_id, '_event_state', $event_state);
  update_post_meta($post_id, '_event_zip', $event_zip);
  update_post_meta($post_id, '_event_latitude', $event_latitude);
  update_post_meta($post_id, '_event_longitude', $event_longitude);
  update_post_meta($post_id, '_event_price', $event_price);
}

add_action('save_post', 'save_event_date_meta_box_data');


function display_calendar($month = null, $year = null)
{
  $output = '';

  if ($month === null) {
    $month = date('m');
  }

  if ($year === null) {
    $year = date('Y');
  }

  $first_day_of_month = date('w', strtotime("$year-$month-01"));

  $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

  // Start of the calendar
  $output .= '<div id="bluestone_calendar">';
  $output .= '<div class="calendar-navigation">';
  $output .= '<button class="calendar-prev"><<</button>';
  $output .= '<div class="calendar-month-year">' . date('F Y', strtotime("$year-$month-01")) . '</div>';
  $output .= '<button class="calendar-next">>></button>';
  $output .= '</div>';

  $output .= '<table>';
  $output .= '<tr>';
  $weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
  foreach ($weekdays as $day) {
    $output .= "<th>$day</th>";
  }
  $output .= '</tr>';

  // Printing empty cells if the month does not start on Sunday
  $output .= '<tr>';
  for ($i = 0; $i < $first_day_of_month; $i++) {
    $output .= "<td></td>";
  }

  // Main loop for days in month
  for ($day = 1; $day <= $days_in_month; $day++) {
    // If this day of the week is Sunday, start a new row
    if (date('w', strtotime("$year-$month-$day")) == 0) {
      $output .= '</tr><tr>';
    }

    $date = "$year-$month-$day";
    $date_formatted = date("Y-m-d", strtotime($date));

    $current_date_class = '';
    if ($date_formatted == date("Y-m-d")) {
      $current_date_class = 'current-date';
    }

    $output .= "<td>";
    $output .= "<div class='day-of-month $current_date_class'>$day</div>";

    $date = "$year-$month-$day";
    $date_formatted = date("Y-m-d", strtotime($date));
    $current_date_class = '';
    if ($date_formatted == date("Y-m-d")) {
      $current_date_class = 'current-date';
    }

    $events = new WP_Query(array(
      'post_type' => BLUESTONE_EVENTS_POST_TYPE,
      'meta_query' => array(
        array(
          'key' => '_event_date',
          'value' => $date_formatted,
          'compare' => '=',
          'type' => 'DATE'
        )
      )
    ));

    if ($events->have_posts()) {
      $output .= "<ul>";

      while ($events->have_posts()) {
        $events->the_post();
        $start_time = get_post_meta(get_the_ID(), '_event_from_time', true);
        $end_time = get_post_meta(get_the_ID(), '_event_to_time', true);
        $start_time_formatted = date("h:i A", strtotime($start_time));
        $end_time_formatted = date("h:i A", strtotime($end_time));

        // Get the URL of the event post
        $event_url = get_permalink();

        // Get the event's date
        $event_date = get_post_meta(get_the_ID(), '_event_date', true);

        // Append the start time to the event date
        $event_date_time = new DateTime($event_date . " " . $start_time);
        $current_date_time = new DateTime();

        // Get the formatted strings for today's date and the event's date
        $current_date_str = $current_date_time->format('Y-m-d');
        $event_date_str = $event_date_time->format('Y-m-d');

        // Choose color based on date
        $color = ($event_date_str < $current_date_str) ? '#428bca63' : '#337ab7';

        // Wrap the title in a link to the event post
        $output .= "<li><a href='" . esc_url($event_url) . "' style='color: " . $color . ";'>" . $start_time_formatted . " - " . get_the_title() . "</a></li>";
      }

      $output .= "</ul>";
    }
    wp_reset_postdata();

    $output .= '</td>';
  }

  // Printing empty cells if the month does not end on Saturday
  $last_day_of_month = date('w', strtotime("$year-$month-$days_in_month"));
  for ($i = $last_day_of_month + 1; $i <= 6; $i++) {
    $output .= "<td></td>";
  }

  $output .= '</tr>';
  $output .= '</table>';
  $output .= '</div>'; // Close the div with the id

  return $output;
}





function bluestone_calendar_shortcode()
{
  return display_calendar();
}

add_shortcode('bluestone_calendar', 'bluestone_calendar_shortcode');

function bluestone_calendar_styles_and_scripts()
{
  wp_enqueue_style('bluestone-calendar-style', plugins_url('bluestone-calendar.css', __FILE__));
  wp_register_script('bluestone-calendar-script', plugins_url('bluestone-calendar.js', __FILE__), array('jquery'), '1.0', true);
  wp_enqueue_script('bluestone-calendar-script');
  wp_localize_script('bluestone-calendar-script', 'bluestone_calendar_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));
}

add_action('wp_enqueue_scripts', 'bluestone_calendar_styles_and_scripts');



add_action('wp_ajax_change_month', 'change_month');
add_action('wp_ajax_nopriv_change_month', 'change_month');

function change_month()
{
  $month = $_POST['month'];
  $year = $_POST['year'];
  echo display_calendar($month, $year);
  wp_die();
}
