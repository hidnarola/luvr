<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stripes {

    public function __construct() {
        require_once APPPATH . 'third_party/stripe/Stripe.php';
    }

}
