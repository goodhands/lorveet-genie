<?php 
    /**
     * This is a class for every automated action 
     */
    class genie{

        public function EmailNotify($subj, $content, $to = "contact@lorveet.com, admin@lorveet.com"){

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <genie@lorveet.com>' . "\r\n";
            $headers .= 'Cc: olaegbesamuel@gmail.com' . "\r\n";
            $headers .= 'Reply-to: olaegbesamuel@gmail.com' . "\r\n";

            mail($to, $subj, $content, $headers);
        }  
    
    }
?>