<?php
add_action('admin_post_movie_save', 'movie_save');

function movie_save()
{
    global $wpdb;
    if ($_FILES['image']['name']) {
        $file_id = media_handle_upload('image', 0);
    }
    $table = $wpdb->prefix . "movie_entries";
    $presentation_table = $wpdb->prefix . "presentations";
    $data = array(
        'title'    => $_POST['title'],
        'text'    => $_POST['text'],
        'url'    => $_POST['url'],
        "image" => $file_id
    );
    $format = array(
        '%s', '%s', '%s', '%s'
    );

    $movie_id = $_POST['id'];
    if ($_POST['id']) {
        $where = ["id" => $movie_id];
        $where_format = array(
            '%d'
        );
        $success = $wpdb->update($table, $data, $where, $format, $where_format);
    } else {
        $success = $wpdb->insert($table, $data, $format);
        $movie_id = $wpdb->insert_id;
    }
    foreach ($_POST['presentation_date'] as $index => $presentation_date) {
        $presentation_time = $_POST['presentation_time'][$index];
        if (!$presentation_date || !$presentation_time) {
            continue;
        }
        $presentation_data = array(
            'movie' => $movie_id,
            'presentation' => $presentation_date . " " . $presentation_time,
        );
        $presentation_format = array(
            '%d', '%s'
        );
        if ($_POST['presentation_id'][$index]) {
            $presentation_where = ["id" => $_POST['presentation_id'][$index]];
            $presentation_where_format = array(
                '%d'
            );
            $wpdb->update($presentation_table, $presentation_data, $presentation_where, $presentation_format, $presentation_where_format);
        } else {
            $wpdb->insert($presentation_table, $presentation_data, $presentation_format);
        }
    }
    wp_redirect(admin_url() . 'admin.php?page=movie_menu_list');
    exit();
}
