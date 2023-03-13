<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\SendCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Codes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CodesController extends Controller
{
    public function sendMail(Request $request)
    {
        $userId = $request->input('user');
        $path = URL::temporarySignedRoute(
            'generated-code', now()->addMinutes(5), ['user' => $userId]
        );

        $mailData = [
            'title' => 'Código de verificación',
            'body' => 'Selecciona el enlace inferior para continuar.',
            'path' => $path
        ];
        $user = User::where('id', $userId)->first();
        Mail::to($user->email)->send(new SendCode($mailData));
        return view('codes.sendMail');
    }

    public function generatedCode(Request $request)
    {
        $userId = $request->input('user');

        // Generate code
        $code = rand(111111, 999999);

        Codes::where('user_id', $userId)->delete();

        // Insert mail_code
        $codes = new Codes;
        $codes->mail_code = Hash::make($code);
        $codes->user_id = $userId;
        $codes->save();

        $path = URL::temporarySignedRoute(
            'submit-code', now()->addMinutes(10), ['user' => $userId]
        );

        return view('codes.generatedCode', ["code"=>$code, "path"=>$path]);
    }

    public function sumbmitCodeFromMobile(Request $request)
    {
        // Get Data
        $inputCode = $request->input('code');
        $email = $request->input('email');
        // Get User
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response(["valid"=>false, "status"=>5, "data"=>"", "message"=>"El correo ingresado no existe"], 400); // no email
        }

        // Get Code
        $authCode = Codes::where('user_id', $user->id)->first();
        if (!$authCode) {
            return response(["valid"=>false, "status"=>4, "data"=>"", "message"=>"Código inválido"], 400); // no code
        }

        $mailCode = $authCode->mail_code;

        if (!Hash::check($inputCode, $mailCode)) {
            $authCode->mail_strikes += 1;
            $authCode->save();
            if ($authCode->mail_strikes < 3) {
                return response(["valid"=>false, "status"=>2, "data"=>"", "message"=>"Código inválido, intentos restantes: " . (3 - $authCode->mail_strikes)], 400); // Strike
            }
            Codes::where('user_id', $user->id)->delete();
            return response(["valid"=>false, "status"=>3, "data"=>"Código inválido. Se ha alcanzado el límite de intentos, por favor genera otro código"], 400); // Out of strikes
        }

        // Generate code
        $newCode = rand(111111, 999999);

        // Insert mail_code
        $authCode->mobile_code = Hash::make($newCode);
        $authCode->save();

        return response(["valid"=>true, "status"=>1, "data"=>$newCode, "message"=>"Código correcto"], 200); // correct
    }

    public function submitCode(Request $request)
    {
        $userId = $request->input('user');
        return view('codes.submitCode', ["message"=>""]);
    }

    public function sumbmitCodeFromWeb(Request $request)
    {
        // Get Data
        $inputCode = $request->input('code');
        $userId = $request->user()->id;
        // Get Code
        $authCode = Codes::where('user_id', $userId)->first();
        if (!$authCode) {
            return view('codes.submitCode', ["message"=>"Código inválido"]);
        }

        $mobileCode = $authCode->mobile_code;

        if (!Hash::check($inputCode, $mobileCode)) {
            $authCode->mobile_strikes += 1;
            $authCode->save();
            if ($authCode->mobile_strikes < 3) {
                return view('codes.submitCode', ["message"=>"Código inválido, intentos restantes: " . (3 - $authCode->mobile_strikes)]);
            }
            Codes::where('user_id', $userId)->delete();
            return view('codes.submitCode', ["message"=>"Código inválido. Se ha alcanzado el límite de intentos, por favor genera otro código"]);
        }
        $authCode->confirmed = true;
        $authCode->save();
        return redirect()->intended('dashboard');
    }
}
