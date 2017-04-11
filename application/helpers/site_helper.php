<?php

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
        /*$config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';*/
        return $config;
    }

}
?>