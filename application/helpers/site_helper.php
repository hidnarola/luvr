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
?>