<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 13:56
 */

namespace App\Components\UserManagement\User;


use App\Components\Core\ActionLog\ActionLog;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class UserModel extends  Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $primaryKey = "uid";

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = "users";

    static function createUser($username, $password, $email, $currentUser) {

        ActionLog::addLog("User $username created", $currentUser);

        $user = new UserModel();
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->save();

        return $user;
    }




}