<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function register(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $this->createUser($email, $password);
        $this->createDatabase($email);
        $this->createTables($email);
    }

    private function createUser($email, $password)
    {
        $hashedPassword = Hash::make($password);
        $userData = ['email' => $email, 'password' => $hashedPassword];
        DB::table('users')->insert($userData);
    }

    private function createDatabase($email)
    {
        $sanitizedEmail = $this->sanitizeEmail($email);
        $databaseCreateQuery = "CREATE DATABASE db_" . $sanitizedEmail;
        DB::statement($databaseCreateQuery);
    }

    private function createTables($email)
    {
        $sanitizedEmail = $this->sanitizeEmail($email);
        $tableCreateQuery = "CREATE TABLE db_" . $sanitizedEmail . ".`users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_email_unique` (`email`)
          ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        DB::statement($tableCreateQuery);
    }

    private function sanitizeEmail($email)
    {
        return str_replace(['@', '.'], ['_', '_'], $email);
    }
}
