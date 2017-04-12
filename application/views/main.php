<?php

if (isset($header)) {
    if ($header == "home_header") {
        $this->load->view('header_home');
    }
} else
    $this->load->view('header');
$this->load->view($sub_view);
$this->load->view('footer');
?>