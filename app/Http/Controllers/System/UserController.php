<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\UserRequest;
use App\Http\Resources\System\UserResource;
use App\Models\System\User;

class UserController extends Controller
{
    public function create()
    {
        return view('system.users.form');
    }

    public function record()
    {
        $user = User::first();

        return new UserResource($user);
    }

    public function store(UserRequest $request)
    {
        $id = $request->input('id');
        $user = User::firstOrNew(['id' => $id]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password') !== '') {
            if (config('tenant.password_change')) {
                $user->password = bcrypt($request->input('password'));
            }
        }
        $user->save();

        return [
            'success' => true,
            'message' => 'Usuario actualizado'
        ];
    }
}