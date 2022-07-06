<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UtilityController extends Controller
{
    public function sendMail ($email, $subj, $message ){
        // multiple recipients
        //$to  = 'junior@akinfo.com.br' . ','; // note the comma
        $to = $email;

        // subject
        $subject = $subj;

        // message
       /* $message = '
        <html>
        <head>
         <title>Birthday Reminders for August</title>
        </head>
        <body>
        </body>
        </html>
        ';*/
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Additional headers
        //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";
        // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

        // Mail it
        echo($message);
       //return mail($to, $subject, $message, $headers);
    }
}
