<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\TestMail;
use Mailgun\Mailgun;
class EmailController extends Controller
{
    public function checkMail()
    {
        $mail ='narendra@aeologic.com';
        $time ='2018-06-14 15:41:22';
//        $response = mail($mail,'Test','TestMessage suucess');
//        $response = Mail::to($mail)->later($time,new TestMail());
        $mgClient = new Mailgun('key-793dd60f3bbc837db5ec9c924d8881d8');
        $domain = "mg.aeologic.com";

# Make the call to the client.
        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'My Excited User <abhishek@aeologic.com>',
            'to'      => 'Test Bazz <narendra@aeologic.com>',
            'subject' => 'BUZZZ.sfdf xsc. New Test Buzz Mail',
            'text'    => 'Testing some Mailgun awesomness!',
            'o:deliverytime' => Carbon::tomorrow()->addHours(10)->toRfc2822String()
        ));


        dd($result);
    }

}
