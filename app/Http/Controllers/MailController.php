<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendCode;

class MailController extends Controller
{
    //
    public function index()
    {
        $mailData = [
            'title' => 'Código de verificación',
            'body' => 'Selecciona el enlace inferior para continuar.',
            'path' => 'https://www.youtube.com/'
        ];
         
        Mail::to('carlos.lpz.2k02@gmail.com')->send(new SendCode($mailData));
           
        return("Correo enviado correctamente.");
    }
}
