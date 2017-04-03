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
?>