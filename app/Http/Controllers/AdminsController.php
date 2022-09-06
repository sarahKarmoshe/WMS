<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminsRequest;
use App\Http\Requests\UpdateAdminsRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\Images;

class AdminsController extends Controller
{

    public function signUp(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('admins')],
            'password' => ['required', 'min:8'],
            'c_password' => ['required', 'same:password'],
            'phone' => ['required', 'string'],
            'photo' => ['required', 'image:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $image_url = Images::SaveUserImage($request);

        $request['password'] = Hash::make($request['password']);

        $admin = Admin::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => 'Admin',
            'photo' => $image_url,

        ]);

        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $admin->sendApiEmailVerification($verification_code);

        $tokenResult = $admin->createToken('personal Access Token')->accessToken;
        $data["Admin"] = $admin;
        $data["verification_code"] = $verification_code;
        $data["tokenType"] = 'Bearer';
        $data["access_token"] = $tokenResult;

        return response()->json($data, Response::HTTP_CREATED);

    }

    public function verifyAccountRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => ['required', Rule::exists('admins')]
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $admin = Admin::query()->where('email', '=', $request->email)->first();

        if ($admin->email_verified_at != NULL) {
            return response()->json("User already have verified email!", 422);
        }
        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $admin->sendApiEmailVerification($verification_code);

        $result['Message'] = "The notification has been submitted";
        $result['Code'] = $verification_code;

        return response()->json($result, Response::HTTP_OK);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => ['required', Rule::exists('admins')],
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $admin = Admin::query()->where('email', '=', $request->email)->first();
        $date = date("Y-m-d g:i:s");
        $admin->email_verified_at = $date;
        $admin->save();

        $tokenResult = $admin->createToken('personal Access Token')->accessToken;
        $data["message"] = 'Email verified!';
        $data['user'] = $admin;
        $data["TokenType"] = 'Bearer';
        $data['Token'] = $tokenResult;
        return response()->json($data, Response::HTTP_OK);
    }


    public function AdminVerify()
    {
        $admin = Admin::find(auth()->guard('admin-api')->user()->id);
        $date = date("Y-m-d g:i:s");
        $admin->email_verified_at = $date;
        $admin->save();
        return response()->json("Email verified!", Response::HTTP_OK);
    }

    public function resend()
    {
        $admin = Admin::find(auth()->guard('admin-api')->user()->id);

        if ($admin->hasVerifiedEmail()) {
            return response()->json("User already have verified email!", 422);
        }
        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $admin->sendApiEmailVerification($verification_code);

        return response()->json("The notification has been resubmitted");
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => ['required', 'string', Rule::exists('admins')],
                'id' => ['required', Rule::exists('admins')],
                'password' => ['required', 'min:8'],
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (auth()->guard('admin')->attempt(['name' => request('name'), 'id' => request('id'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'admin']);

            $admin = Admin::find(auth()->guard('admin')->user()->id);

            $tokenResult = $admin->createToken('personal Access Token')->accessToken;
            $data['admin'] = $admin;
            $data["TokenType"] = 'Bearer';
            $data['Token'] = $tokenResult;
        } else {
            throw new AuthenticationException();

        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json("logged out", Response::HTTP_OK);

    }

    public function ProfileUpdate(UpdateAdminsRequest $request)
    {

        $image_url = Images::SaveUserImage($request->photo);

        Auth::guard('admin-api')->user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'photo' => $image_url,
        ]);

        $admin = Admin::query()->where('id', '=', Auth::guard('admin-api')->id())->get();

        return response()->json($admin, Response::HTTP_OK);
    }

    public function ResetPasswordRequest()
    {
        config(['auth.guards.api.provider' => 'admin']);

        $admin = Admin::find(Auth::guard('admin-api')->id());

        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $admin->sendEmailVerificationPassword($verification_code);

        $response['reset password code'] = $verification_code;
        return response()->json($response, Response::HTTP_OK);
    }


    public function ResetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:8'],
            'c_password' => ['required', 'min:8', 'same:password'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request['password'] = Hash::make($request['password']);
        $admin = Admin::find(Auth::guard('admin-api')->id());
        $admin->update([
            'password' => $request->password,
        ]);
        return response()->json("password reset has done successfully !", Response::HTTP_OK);
    }

    public function MyProfile()
    {
        $admin = Admin::query()->find(Auth::guard('admin-api')->id());
        return response()->json($admin, Response::HTTP_OK);
    }

    public function AddSupervisor(StoreAdminsRequest $adminsRequest)
    {
        if (!Auth::guard('admin-api')->user()->role == 'Admin') {
            return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);
        }

        $image_url = Images::SaveUserImage($adminsRequest);

        $random_password = substr(number_format(rand(), 0, '', ''), 0, 6);

        $random_password = Hash::make($random_password);

        $admin = Admin::query()->create([
            'name' => $adminsRequest->name,
            'email' => $adminsRequest->email,
            'password' => $random_password,
            'phone' => $adminsRequest->phone,
            'role' => 'Supervisor',
            'photo' => $image_url,

        ]);

        return response()->json($admin, Response::HTTP_CREATED);

    }

    public function getAllSupervisor()
    {
        if (!Auth::guard('admin-api')->user()->role == 'Admin') {
            return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);
        }


        $supervisor = Admin::query()->where('role', '=', 'Supervisor')->get();

        return response()->json($supervisor, Response::HTTP_OK);
    }

    public function getAllUsersAndAdmins()
    {
        if (!Auth::guard('admin-api')->user()->role == 'Admin') {
            return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);
        }


        $users = User::all();
        $admins = Admin::all();

        $result['users'] = $users;
        $result['admins'] = $admins;

        return response()->json($result, Response::HTTP_OK);


    }

    public function showAllAccountant()
    {
        if (!Auth::guard('admin-api')->user()->role == 'Admin') {
            return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);
        }

        $accountant = Admin::query()->where('role', '=', 'Accountant')->get();
        return response()->json($accountant, Response::HTTP_OK);

    }

    public function deleteUserAccount(User $user)
    {
        if (!Auth::guard('admin-api')->user()->role == 'Admin') {
            return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);
        }

        if($user->is_exist){
            return response()->json('you cant delete this account , it is valid account ',Response::HTTP_OK);
        }
        $user->delete();
        return response()->json('deleted successfully ',Response::HTTP_OK);
    }
}
