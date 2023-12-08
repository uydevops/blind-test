<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = $this->createUser($request->input('email'), $request->input('password'));

        DB::transaction(function () use ($user) {
            $this->createDatabaseAndTables($user);
        });

    }

    private function createUser($email, $password)
    {
        return User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    private function createDatabaseAndTables(User $user)
    {
        $databaseName = $this->getDatabaseName($user);

        $this->createDatabase($databaseName);
        $this->createUserTable($databaseName);
    }

    private function createDatabase($databaseName)
    {
        $databaseCreateQuery = "CREATE DATABASE IF NOT EXISTS $databaseName";
        DB::statement($databaseCreateQuery);
    }

    private function createUserTable($databaseName)
    {
        $tableCreateQuery = "CREATE TABLE IF NOT EXISTS $databaseName.users (
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

    private function getDatabaseName(User $user)
    {
        return 'db_' . $this->sanitizeEmail($user->email);
    }

    private function sanitizeEmail($email)
    {
        return str_replace(['@', '.'], ['_', '_'], $email);
    }
}
