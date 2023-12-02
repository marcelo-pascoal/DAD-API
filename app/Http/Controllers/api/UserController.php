<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\Base64Services;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function storeBase64AsFile(User $user, String $base64String)
    {
        $targetDir = storage_path('app/public/fotos');
        $newfilename = $user->id . "_" . rand(1000, 9999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }

    public function index()
    {
        return UserResource::collection(User::where('user_type', 'A')->get());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    // REGISTRATION - CREATES A NEW USER
    public function store(StoreUserRequest $request)
    {
        $dataToSave = $request->validated();
        $user = new User();
        $user->name = $dataToSave['name'];
        $user->email = $dataToSave['email'];
        $user->password = bcrypt($dataToSave['password']);
        $user->remember_token = Str::random(10);

        $user->save();
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dataToSave = $request->validated();

        $user->fill($dataToSave);

        $user->save();
        return new UserResource($user);
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function destroy(User $user)
    {
        $userToDelete = User::find($user->id);
        if (!$userToDelete) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $userToDelete->delete();

        return new UserResource($userToDelete);
    }
}
