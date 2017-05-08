<?php

if (isset($header)) {
    if ($header == "home_header") {
        $this->load->view('header_home');
    }
} else {
    if ($sub_view == "bio/video") {
        if ($show_header_footer == 1) {
            $this->load->view('header');
        }
    } else {
        $this->load->view('header');
    }
}
$this->load->view($sub_view);
if ($sub_view == "bio/video") {
    if ($show_header_footer == 1) {
        $this->load->view('footer');
    }
} else {
    $this->load->view('footer');
}
?>