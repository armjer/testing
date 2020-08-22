<?php

namespace App\Models;

/**
 * отправка почти
 */
class Email
{
    public static function send_mail($to_address, $from_address, $from_name, $email_subject, $email_body, $attachments = false)
    {
        $eol = "\r\n";
        $mime_boundary = md5(time());
        $headers = '';
        $msg = '';
        $html = (mb_strpos($email_body, "<html>") !== false);

        $mail_site = "send_mail@" . $_SERVER['SERVER_NAME'];

        # Common Headers
        $headers .= 'Sender: ' . $mail_site . $eol;
        $headers .= 'From: ' . mb_encode_mimeheader($from_name) . " <" . $mail_site . ">" . $eol;
        $headers .= 'Reply-To: ' . mb_encode_mimeheader($from_name) . " <$from_address>" . $eol;
        $headers .= 'Return-Path: ' . mb_encode_mimeheader($from_name) . " <$from_address>" . $eol;    // these two to set reply address
        $headers .= "Message-ID: <" . time() . $mime_boundary . "@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
        $headers .= "X-Mailer: PHP v" . phpversion() . $eol;          // These two to help avoid spam-filters

        # Boundry for marking the split & Multitype Headers
        $headers .= 'MIME-Version: 1.0' . $eol;

        if ($attachments !== false) {
            $headers .= "Content-Type: multipart/mixed; boundary=\"1$mime_boundary\"" . $eol;
            $msg .= "--1" . $mime_boundary . $eol;
            if ($html) {
                $msg .= "Content-Type: multipart/alternative; boundary=\"2$mime_boundary\"" . $eol . $eol;
            }
        } else {
            if ($html) {
                $headers .= "Content-Type: multipart/alternative; boundary=\"2$mime_boundary\"" . $eol;
            }
        }

        # Setup for text OR html

        # Text Version
        if ($html) {
            $msg .= "--2" . $mime_boundary . $eol;
            $msg .= "Content-Type: text/plain; charset=UTF-8" . $eol;
            $msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        } else {
            $headers .= "Content-Type: text/plain; charset=UTF-8" . $eol;
            $headers .= "Content-Transfer-Encoding: 8bit";
        }
        $msg .= strip_tags(str_replace("<br>", $eol, str_replace("<br />", $eol, $email_body))) . $eol . $eol;

        if ($html) {
            # HTML Version
            $msg .= "--2" . $mime_boundary . $eol;
            $msg .= "Content-Type: text/html; charset=UTF-8" . $eol;
            $msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
            $msg .= $email_body . $eol . $eol;

            # Finished
            $msg .= "--2" . $mime_boundary . "--" . $eol . $eol;  // finish with two eol's for better security. see Injection.
        }

        if ($attachments !== false) {

            for ($i = 0; $i < count($attachments); $i++) {
                if (is_file($attachments[$i]["file"])) {
                    # File for Attachment
                    $file_name = substr($attachments[$i]["file"], (strrpos($attachments[$i]["file"], "/") + 1));

                    $handle = fopen($attachments[$i]["file"], 'rb');
                    $f_contents = fread($handle, filesize($attachments[$i]["file"]));
                    $f_contents = chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
                    fclose($handle);

                    # Attachment
                    $msg .= "--1" . $mime_boundary . $eol;
                    $msg .= "Content-Type: " . $attachments[$i]["content_type"] . "; name=\"" . $file_name . "\"" . $eol;
                    $msg .= "Content-Transfer-Encoding: base64" . $eol;
                    $msg .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"" . $eol . $eol; // !! This line needs TWO end of lines !! IMPORTANT !!
                    $msg .= $f_contents . $eol . $eol;

                }
            }
            $msg .= "--1" . $mime_boundary . "--" . $eol . $eol;  // finish with two eol's for better security. see Injection.
        }

        # SEND THE EMAIL
        ini_set('sendmail_from', $from_address);  // the INI lines are to force the From Address to be used !
        $r = mail($to_address, mb_encode_mimeheader($email_subject), $msg, $headers);
        ini_restore('sendmail_from');
        if ($r) {
            return true;
        } else {
            return false;
        }
    }
}