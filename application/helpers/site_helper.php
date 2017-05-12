<?php

function qry($is_die = false) {
    $CI = & get_instance();
    echo $CI->db->last_query();
    if ($is_die == true) {
        die();
    }
}

function my_img_url($img_type, $img_url){
    if($img_type == '1'){
        return base_url().'bio/show_img/'.$img_url.'/1';
    }
    if($img_type == '2'){
        $img_url = str_replace('.mp4','.png',$img_url);
        return base_url().'bio/show_img/'.$img_url.'/1';
    }
    if($img_type == '3'){
        return $img_url;
    }
    if($img_type == '4'){
        return $img_url;
    }
}

function _createThumbnail($img_path, $thumb_path) {
    $CI = & get_instance();

    $config['image_library'] = 'gd2';
    $config['source_image'] = $img_path;
    $config['new_image'] = $thumb_path;
    $config['maintain_ratio'] = TRUE;
    $config['width'] = 500;
    $config['height'] = 500;

    $CI->load->library('image_lib', $config);
    if (!$CI->image_lib->resize()) {
        echo $CI->image_lib->display_errors();
    }
}

if (!function_exists('pr')) {

    function pr($value, $exit = 0) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        if ($exit == 1)
            exit();
    }

}

if (!function_exists('extractIDS')) {

    function extractIDS($key, $array) {
        if (!empty($array) && is_array($array) && !empty($key)) {
            $extracted_ids = array_column($array, $key);
            $extracted_ids = array_unique($extracted_ids);
            $extracted_ids = array_filter($extracted_ids);
            $extracted_ids = array_values($extracted_ids);
            return $extracted_ids;
        }
        return $array;
    }

}

if (!function_exists('last_query')) {

    function last_query($die = 0) {
        $CI = & get_instance();
        echo $CI->db->last_query();
        if ($die == 1)
            die;
    }

}

if (!function_exists('pagination_config')) {

    function pagination_config() {

        $config['full_tag_open'] = '<div><ul class="pagination pagination-sm pagination-centered">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";

        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";

        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";

        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";

        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $config['num_links'] = 5;
        /* $config['page_query_string'] = TRUE;
          $config['query_string_segment'] = 'per_page'; */
        return $config;
    }

}
if (!function_exists('distance')) {

    function distance($lat1, $lon1, $lat2, $lon2, $unit = "M") {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

}
if (!function_exists('replace_extension')) {

    function replace_extension($filename, $new_extension) {
        $info = pathinfo($filename);
        return $info['filename'] . '.' . $new_extension;
    }

}
if (!function_exists('isUserActiveSubscriber')) {

    function isUserActiveSubscriber($user_id) {
        if (!empty($user_id)) {
            $CI = & get_instance();
            $user_settings = $CI->Users_model->getUserSetings('userid', $user_id);
            $active = $user_settings['is_premium_member'];
            if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s"))) && $user_settings['is_premium_member'] == 1) {
                $active = 1;
            } else {
                $active = 0;
            }
            return $active;
        }
        return 0;
    }

}

if (!function_exists('getUserIP')) {

    function getUserIP() {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

}
?>