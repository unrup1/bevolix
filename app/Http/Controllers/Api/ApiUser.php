<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiUser extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getOne(Request $request) {
        return $request->user();
    }

    public function update(Request $request, int $id) {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);

        $user = User::find($id);

        if ($user === null) {
            return response([], 404);
        }

        if ($user->id !== Auth::id()) {
            return response([], 403);
        }

        /*if ($user->email != $request->email) {
            // TODO new email verify
        }*/

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response($user);
    }

    public function changePassword(Request $request, int $id) {
        $request->validate([
            'password' => 'required'
        ]);

        $user = User::find($id);

        if ($user === null) {
            return response([], 404);
        }

        if ($user->id !== Auth::id()) {
            return response([], 403);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response($user);
    }

    public function delete(int $id) {
        $user = User::find($id);

        if ($user === null) {
            return response([], 404);
        }

        if ($user->id !== Auth::id()) {
            return response([], 403);
        }

        $user->delete();

        return response([]);
    }
}
