<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminsRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Traits\Images;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function AddAccountant(Request $request)
    {
        if(!Auth::guard('admin-api')->user()->role == 'Admin')
        {
            return response()->json('UnAuthorized to do this Action',Response::HTTP_FORBIDDEN);
        }

        $validator=Validator::make($request->all(),[
                'name' => ['required', 'string'],
                'email' => ['required', 'email', Rule::unique('admins')],
                'phone' => ['required', 'string'],
                'photo' => ['required', 'image:jpeg,png,jpg,gif,bmp,svg', 'max:2048'],

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        $image_url = Images::SaveUserImage($request);
        $random_password = substr(number_format(rand(), 0, '', ''), 0, 6);
        $random_password = Hash::make($random_password);

        $user = Admin::query()->create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => $random_password,
            'phone' => $request->phone,
            'role' => 'Accountant',
            'photo' => $image_url,

        ]);
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function AddSupplier(StoreAdminsRequest $request)
    {

        $image_url = Images::SaveUserImage($request);
        $random_password = substr(number_format(rand(), 0, '', ''), 0, 6);
        $random_password = Hash::make($random_password);


        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $random_password,
            'phone' => $request->phone,
            'role' =>'supplier' ,
            'photo' => $image_url,

        ]);
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function AddSeller(StoreAdminsRequest $request)
    {

        $image_url = Images::SaveUserImage($request);

        $random_password = substr(number_format(rand(), 0, '', ''), 0, 6);
        $random_password = Hash::make($random_password);

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $random_password,
            'phone' => $request->phone,
            'bonus' => $request->bonus,
            'role' => 'Seller',
            'photo' => $image_url,

        ]);

        WalletController::create($user->id);

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => ['required', Rule::exists('users')],
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = User::query()->where('email', '=', $request->email)->first();
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date;
        $user->save();

        $tokenResult = $user->createToken('personal Access Token')->accessToken;
        $data["message"] = 'Email verified!';
        $data['user'] = $user;
        $data["TokenType"] = 'Bearer';
        $data['Token'] = $tokenResult;
        return response()->json($data, Response::HTTP_OK);
    }

    public function verifyAccountRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => ['required', Rule::exists('users')],
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::query()->where('email', '=', $request->email)->first();

        if ($user->email_verified_at != NULL) {
            return response()->json("User already have verified email!", 422);
        }
        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $user->SendEmailAccount_PaaswordVerify($verification_code);

        $result['Message'] = "The notification has been submitted";
        $result['Code'] = $verification_code;

        return response()->json($result, Response::HTTP_OK);
    }


    /**
     * @throws AuthenticationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => ['required', 'string', Rule::exists('users')],
                'id' => ['required', Rule::exists('users')],
                'password' => ['required', 'min:8'],
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt(['name' => request('name'), 'id' => request('id'), 'password' => request('password')])) {

            $user = $request->user();
            $tokenResult = $user->createToken('personal Access Token')->accessToken;
            $data['user'] = $user;
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

    public function ProfileUpdate(UpdateUserRequest $request)
    {

        $image_url = Images::SaveUserImage($request->photo);

        Auth::guard('api')->user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'photo' => $image_url,
        ]);

        $user = User::query()->where('id', '=', Auth::guard('api')->id())->get();

        return response()->json($user, Response::HTTP_OK);
    }

    public function ResetPasswordRequest()
    {
        config(['auth.guards.api.provider' => 'api']);

        $user = User::find(Auth::guard('api')->id());

        $verification_code = substr(number_format(rand(), 0, '', ''), 0, 6);
        $user->sendEmailVerificationPassword($verification_code);

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
        $user = User::find(Auth::guard('api')->id());
        $user->update([
            'password' => $request->password,
        ]);
        return response()->json("password reset has done successfully !", Response::HTTP_OK);
    }

    public function MyProfile()
    {
        $user = User::find(Auth::guard('api')->id());
        return response()->json($user, Response::HTTP_OK);
    }
}
