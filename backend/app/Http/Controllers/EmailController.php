<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(){
        $data = ['message'=>'this is a test'];
        Mail::to('hero2klove@gmail.com')->send(new TestEmail($data));
        return [
            'message'=>'success'
        ];
    }
}
