<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user) {
            // Handle the case when the user is not authenticated.
            return redirect()->route('login'); // You can change the route to your login route.
        }

        $user_id = $user->id;

        config([
            'database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => 'db_' . $user_id,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        $userDatabaseConnection = DB::connection('dynamic');
        $userTable = $userDatabaseConnection->table('users')->get();

        dd($userTable);

        return view('dashboard', compact('userTable'));
    }
}
