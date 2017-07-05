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

    function tst() {
        $one_liner = array(
            "Hey!  Just checking out Luvr, looking to find some new friends!",
            "I am uniquely perfect!  Swipe right and find out more!",
            "Do you like my video?",
            "Really looking for someone fun!",
            "How hot do you think you are?  1-10?  Tell me!",
            "Hey!  What are you looking at?! LOL",
            "Here to make friends and maybe more! ;)",
            "I came strictly for the videos!  JK!",
            "Hey everyone!  Come see and how good I look! ☺",
            "I am a meme, GIF, and emoji master!  Swipe right if you dare!",
        );
        $bios = array(
            "I am a person who is looking for the right match.  Are you my match?  Do you like adventure?  I do!  Only brave hearts need apply!",
            "I enjoy long walks on the beach…except I can’t walk, and the sand fucks up my tires!",
            "Our relationship should be like Nintendo 64 – classic, fun to spend hours with, and every issue is easily fixed by blowing on it then shoving it back in.",
            "If you can’t laugh and yourself, I will probably laugh at you.  Don’t take life to seriously…you’ll never get out alive.",
            "Let’s have a who’s better in bed contest.  I’m hoping to be a sore loser.",
            "The only thing lower than my standards is my self-esteem.  Just kidding…you better be friggin’ awesome!",
            "Your parents will love me, but your neighbors won’t.  That is a joke for how loud I can actually be if you catch my drift ;)",
            "Threesome? No thanks…if I wanted to disappoint two people in the same room, I’d have dinner with my parents.",
            "500 characters isn’t really enough to demonstrate my wit and intelligence so just look at my banging body for now.",
            "Professional Eugoogoolizer at the Derek Zoolander Center For Kids Who Can’t Read Good And Wanna Learn To Do Other Stuff Good Too.",
        );
        $rs = $this->db->query("select * from users where gender='male' and id>14910 limit 25")->result_array();
        pr($rs);
        if (!empty($rs)) {
            foreach ($rs as $r) {
                $data = array(
                    'bio' => $bios[array_rand($bios)],
                    'one_liner' => $one_liner[array_rand($one_liner)]
                );
                $this->db->where('id', $r['id']);
                $this->db->update('users', $data);
            }
        }
    }

}

?>
