<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ManagesResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ManagesResponse;
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum'], ['only' => ['user', 'logout']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'photo' => 'required|file|max:1000|mimes:png,jpg,gif,jpeg,bmp',
            'role' => 'sometimes|string|in:librarian,reader',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors(), 422);
        }
        try {
            $inputs = $request->except(['photo']);

            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $uniqueFileName = uniqid().'-profile_photo.'.$image->getClientOriginalExtension();

                //in reality, a cloud storage will be used
                $is_uploaded = $image->move(public_path('/assets/profile-photos'), $uniqueFileName);
                if (!$is_uploaded) {
                    return $this->sendError('failed to upload profile picture', [], 400);
                }
                $inputs['photo_url']= '/assets/profile-photos/'.$uniqueFileName;
            }

            if (!$request->has('role')) {
                $inputs['role'] = 'reader';
            }
            $inputs['is_verified'] = 1;
            $inputs['status'] = 'active';
            $inputs['password'] = bcrypt($request->get('password'));

            $user = User::create($inputs);
            if (empty($user)) {
                return $this->sendError('failed in creating user', [], 400);
            }
            return $this->sendResponse($user, 'registration successful');

        }catch (\Exception $exception) {
            return $this->sendError('server error', $exception->getTrace(), 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation error.', $validator->errors(), 422);
        };

        try {
            if (\auth()->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                $user = \auth()->user();
                $token = $user->createToken(uniqid())->plainTextToken;

                return $this->sendResponse($token, 'user logged in successfully.');
            } elseif (User::where('email', $request->get('email'))->exists()) {
                return $this->sendError( 'password not correct', ['error' => 'password or email dont match'], 404);
            } else {
                return $this->sendError('entry details does not exist', ['error' => 'record does not exist'], 404);
            }
        }catch (\Exception $exception) {
            return $this->sendError('exception error', $exception->getMessage(), 502);
        }
    }

    public function user(Request $request)
    {
        try {
            if (\auth()->check()) {
                $user = $request->user();
                return $this->sendResponse($user, 'logged user retrieved successfully');
            }
            return $this->sendError('user not logged in');
        }catch (\Exception $exception) {
            return $this->sendError('exception error', ['error' => $exception->getTrace()], 502);
        }
    }

    public function logout(Request $request)
    {
        try{
            if(method_exists(auth()->user()->currentAccessToken(), 'delete')) {
                auth()->user()->currentAccessToken()->delete();
            }

            auth()->guard('web')->logout();
            return $this->sendResponse(null, 'user logged out successfully');

        }catch (\Exception $exception) {
            auth('web')->logout();
            return $this->sendError('exception error', $exception->getMessage(), 502);
        }
    }
}
