<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name twilio.php
 * @description twilio demo integration
 * @since Fri Mar 01 11:36:11 GMT 2013
 * @author t1gr0u <tigrou.m@gmail.com>
 */
class Twilio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Users_model'));
    }

    function getallcallsforthismonth() {
        try {
            // load the library
            $thisMonth = date('Y-m-d', mktime(3, 0, 0, date('m'), 1, date('Y')));

            // get all the call from the 1st of the month
            foreach ($this->twilio_services->account->calls->getIterator(0, 50, array(
                "Status" => "completed",
                "StartTime>" => $thisMonth
            )) as $call
            ) {
                print ' $call->direction: ' . $call->direction . ' date_created:' . $call->date_created . ' $call->from: ' . $call->from . ' $call->to: ' . $call->to . '<br/>';
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function makecall($id = null) {
        if (is_numeric($id) && $id != null) {
            $data['chat_user_data'] = $this->Users_model->fetch_userdata(['id' => $id], true);
            $this->load->view('twilio', $data);
        } else {
            show_404();
        }
    }

    function twiliol() {
        $this->load->view('twilio1');
    }

    function twilio2() {
        $this->load->view('remote');
    }

}

?>
