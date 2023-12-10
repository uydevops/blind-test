<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class UserHomeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $user_email = $user->id;

        config([
            'database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => 'db_' . $user_email, 
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        $userDatabaseConnection = DB::connection('dynamic'); // Dinamik yazmasamda olur np :D


        dd($userDatabaseConnection->getDatabaseName());
    }
}
