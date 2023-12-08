<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class registerController extends Controller
{




    public function index()
    {
        return view('welcome');
    }

    public function Register(Request $req)
    {
        $email = $req->input('email');
        $password = $req->input('password');

        if ($this->EmailExists($email)) {
            return response()->json(['durum' => 'error', 'mesaj' => 'Bu email adresi zaten kayıtlı']);
        } else {
            $data = array('email' => $email, 'password' => Hash::make($password));
            DB::table('users')->insert($data);
            return response()->json(['status' => 'success', 'mesaj' => 'Kayıt başarılı']);
        }
    }



    private function EmailExists($email)
    {
        $data = DB::table('users')->where('email', $email)->get();
        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
