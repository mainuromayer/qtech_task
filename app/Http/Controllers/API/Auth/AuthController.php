<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Register a new user and send OTP for verification.
     *
     * @param Request $request
     * @return JsonResponse
     */

    // User Registration

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 400);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            $token = JWTAuth::fromUser($user);

            $otp = rand(1000, 9999);
            $user->otp = $otp;
            $user->otp_expiration = Carbon::now()->addMinutes(15);
            $user->save();

            // Send OTP
            Mail::send('emails.otp-reg', ['otp' => $otp], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Your Registration OTP');
            });

            DB::commit();

            return $this->success([
                'status' => true,
                'code' => 201,
                'message' => 'OTP sent. Please verify your email.',
                'token' => $token,
                'otp' => $otp,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Registration step one failed.', 500);
        }
    }


    /**
     * Verify the OTP entered during registration.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyRegistrationOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 400);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if ($user->otp != $request->otp || Carbon::now()->greaterThan($user->otp_expiration)) {
                return $this->error('Invalid or expired OTP.', 400);
            }

            $user->email_verified_at = Carbon::now();
            $user->otp = null;
            $user->otp_expiration = null;
            $user->save();

            return $this->success('Email verified successfully.', 200);
        } catch (Exception $e) {
            return $this->error('An error occurred while verifying OTP.', 500);
        }
    }

    /**
     * Resend OTP for email verification.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendRegistrationOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 400);
        }

        try {
            $user = User::where('email', $request->email)->first();

            // Generate new OTP
            //  $otp = rand(100000, 999999);
            $otp = rand(1000, 9999);
            $user->otp = $otp;
            $user->otp_expiration = Carbon::now()->addMinutes(15);
            $user->save();

            // Send OTP via email
            Mail::send('emails.otp-reg', ['otp' => $otp], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Your Registration OTP');
            });

            return $this->success([
                'status' => true,
                'code' => 200,
                'message' => 'A new OTP has been sent to your email.',
                'otp' => $otp,
            ], 200);
        } catch (Exception $e) {
            return $this->error('An error occurred while resending OTP.', 500);
        }
    }

    public function completeRegistration(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return $this->error('Invalid or expired token.', 401);
        }

        if (is_null($user->email_verified_at)) {
            return $this->error('Please verify your email before completing registration.', 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 400);
        }

        try {
            $avatarPath = $user->avatar;

            if ($request->hasFile('avatar')) {
                // Generate a unique name using the user's ID and timestamp
                $avatarName = 'user-' . $user->id . '-' . time();

                // Use the same helper function to upload the avatar
                $avatarPath = Helper::fileUpload(
                    $request->file('avatar'),
                    'user-avatar',
                    $avatarName
                );
            }

            $user->update([
                'name' => $request->name,
                'avatar' => url($avatarPath),
            ]);

            return $this->success([
                'status' => true,
                'code' => 200,
                'message' => 'User registration completed successfully.',
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return $this->error('Error completing registration.', 500);
        }
    }



    /**
     * Log in an existing user.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('User not found.', 404);
        }

        if (!$user->email_verified_at) {
            return $this->error('Please verify your email before logging in.', 403);
        }

        $token = auth()->guard('api')->attempt($validator->validated());
        if (!$token) {
            return $this->error('Incorrect credentials, please try again.', 403);
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'avatar' => $user->avatar ? url($user->avatar) : null,
        ];

        return $this->success([
            'message' => 'User logged in successfully.',
            'token' => $token,
            'userData' => $userData,
            'token_type' => 'Bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL(),
            'code' => 200,
        ], 200);
    }




    /**
     * Verify user's email address manually.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function manualEmailVerify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return $this->error('Email already verified.', 400);
        }

        $user->email_verified_at = now();
        $user->save();

        return $this->success([
            'message' => 'Email verified successfully.',
        ], 200);
    }


    /**
     * Log out the user and invalidate token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Invalidate token
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success([
                'message' => 'Successfully logged out',
            ], 200);
        } catch (Exception $e) {
            return $this->error('Failed to log out, please try again.', 500);
        }
    }
}
