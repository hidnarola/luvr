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

    function de() {
        $row = 1;
        if (($handle = fopen("assets/list" . $_GET['f'] . ".csv", "r")) !== FALSE) {
            $all_data = array();
            $males = $females = 0;
            $it = 0;
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                /* if ($row > 1) {
                  echo "<p> $num fields in line $row: <br /></p>\n";
                  } */
                $row++;
                if ($row > 2) {
                    for ($c = 0; $c < $num; $c++) {
                        /* echo $data[$c] . ": C :" . $c . "<br/>\n"; */
                        if ($data[1] == "female" && $c == 1 && $females < 29) {
                            $all_data[$it]['full_name'] = $data[3] . " " . $data[5];
                            $all_data[$it]['email'] = $data[13];
                            $all_data[$it]['user_name'] = $data[14];
                            $all_data[$it]['address'] = $data[6] . "," . $data[7] . "," . $data[8] . "," . $data[10] . "," . $data[11];
                            $all_data[$it]['age'] = $data[19];
                            $all_data[$it]['birthdate'] = date("Y-m-d", strtotime($data[18]));
                            $all_data[$it]['gender'] = $data[1];
                            $females++;
                        }
                        /* if ($data[1] == "female" && $c == 1)
                          $females++; */
                    }
                }
                $it++;
            }
            $all_data = array_values($all_data);
            pr($all_data);
            echo "male : " . $males . "<br/>";
            echo "female : " . $females . "<br/>";
            if (!empty($all_data)) {
                $this->db->insert_batch('users', $all_data);
            }
            fclose($handle);
        }
    }

}

?>
