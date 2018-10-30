<?php

define("GOOGLE_API_KEY", "AAAAPv4DlAc:APA91bHCp4FQXJlLh55FU2tCNOPvqTvsi71rcFyoDujVw5G_4ewdG6KeK_ZWPy60bUfo08kxKI1rl-PDqtxQELXkcadQ-yRvE-XJ64El9Wo5S7asW7OMroTurnw3Mf_EDv5EsJVkx2Rk"); // Place your Google API Key

class GCM {

    //put your code here
    // constructor
    function __construct() {
        
    }

    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $message, $title) {
        // include config
        // include_once './config.php';
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message_data   = array('body' => $message, 'title' =>$title,"sound"=>"mySound");

        $fields = array(
            'registration_ids' 	=> $registatoin_ids,
            'notification'			=> $message_data,
            'content_available' => true,
            'priority' => 'high',
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            //  die('Curl failed: ' . curl_error($ch));
            //  echo $result;
        }

        // Close connection
        curl_close($ch);

        //echo $result;
    }


}

?>
