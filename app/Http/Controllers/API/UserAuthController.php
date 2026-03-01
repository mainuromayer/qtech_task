<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class UserAuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';
            $user->avatar = 'https://static.vecteezy.com/system/resources/previews/048/926/084/non_2x/silver-membership-icon-default-avatar-profile-icon-membership-icon-social-media-user-image-illustration-vector.jpg';
            $user->status = 'active';
            $user->is_admin = 0;

            $user->save();

            // Generate OTP for email verification
            $this->generateOtp($user);

            DB::commit();

            return $this->success([
                'email' => $user->email,
                'otp' => $user->otp,
            ], 'Check your email to verify your account', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], 'Failed to register user' . $e->getMessage(), 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->error([], 'User not found', 404);
            }

            if (!$user->email_verified_at) {
                $this->generateOtp($user);
                return $this->success([
                    'email' => $user->email,
                    'otp' => $user->otp,
                ], 'Email not verified. Check your email for OTP.', 200);
            }

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error([], 'Invalid credentials', 401);
            }

            return $this->success([
                'token' => $this->respondWithToken($token),
                'user' => $user->only('id', 'name', 'email', 'avatar', 'role', 'status'),
            ], 'User logged in successfully.', 200);
        } catch (Exception $e) {
            return $this->error([], 'Login failed: ' . $e->getMessage(), 500);
        }
    }


    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors()->first(), 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->error([], 'User not found', 404);
            }

            $this->generateOtp($user);
            return $this->success([
                'email' => $user->email,
                'otp' => $user->otp,
            ], 'Check your email for password reset OTP', 200);
        } catch (Exception $e) {
            return $this->error([], 'Failed to send OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reset Password Controller
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
                'set_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->error([], 'User not found', 404);
            }

            // Verify the JWT set_token
            try {
                JWTAuth::setToken($request->set_token);
                $payload = JWTAuth::getPayload();
                if ($payload['sub'] != $user->id || ($payload['action'] != 'set_password' && $payload['action'] != 'forgot_password')) {
                    return $this->error([], 'Invalid or expired set token.', 400);
                }
            } catch (JWTException $e) {
                return $this->error([], 'Invalid or expired set token.', 400);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            // Invalidate the token after use
            JWTAuth::invalidate($request->set_token);

            return $this->success([
                'user' => $user->only('id', 'name', 'email', 'avatar', 'role', 'status'),
            ], 'Password set successfully.', 200);
        } catch (Exception $e) {
            return $this->error([], 'Failed to reset password: ' . $e->getMessage(), 500);
        }
    }

    // Resend Otp
    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'action' => 'required|in:email_verification,forgot_password',
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors()->first(), 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->error([], 'User not found', 404);
            }

            // Check for email verification
            if ($request->action === 'email_verification') {
                if ($user->email_verified_at) {
                    return $this->error([], 'Email is already verified.', 400);
                }
                $this->generateOtp($user);
                return $this->success([
                    'email' => $user->email,
                    'otp' => $user->otp,
                ], 'OTP for email verification resent successfully. Check your email.', 200);
            }

            // Check for forgot password
            if ($request->action === 'forgot_password') {
                $this->generateOtp($user);
                return $this->success([
                    'email' => $user->email,
                    'otp' => $user->otp,
                ], 'OTP for password reset resent successfully. Check your email.', 200);
            }

            return $this->error([], 'Invalid action specified.', 400);
        } catch (Exception $e) {
            return $this->error([], 'Failed to resend OTP: ' . $e->getMessage(), 500);
        }
    }


    public function varifyOtpWithOutAuth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|numeric|digits:4',
                'action' => 'required|in:email_verification,forgot_password',
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->error([], 'User not found', 404);
            }

            if ($request->action === 'email_verification') {
                if ($user->email_verified_at) {
                    return $this->error([], 'Email already verified.', 400);
                }

                if ((int) $request->otp !== (int) $user->otp) {
                    return $this->error([], 'Invalid OTP!', 400);
                }

                $user->email_verified_at = now();
                $user->save();

                $token = JWTAuth::fromUser($user);

                return $this->success([
                    'token' => $this->respondWithToken($token),
                    'user' => $user->only('id', 'name', 'email', 'avatar', 'role', 'status'),
                ], 'Email verified successfully. You are now logged in.', 200);
            }

            if ($request->action == 'forgot_password') {
                if ((int) $request->otp !== (int) $user->otp) {
                    return $this->error([], 'Invalid OTP!', 400);
                }
                if ($user->otp_created_at && now()->gt(Carbon::parse($user->otp_created_at)->addMinutes(15))) {
                    return $this->error([], 'OTP has expired.', 400);
                }
                $setToken = $this->generateSetToken($user, 'forgot_password');
                $user->otp = null;
                $user->otp_created_at = null;
                $user->otp_expires_at = null;
                $user->save();
                return $this->success(['set_token' => $setToken], 'OTP verified successfully. Use the token to set your password.', 200);
            }
        } catch (Exception $e) {
            return $this->error([], 'Verification failed: ' . $e->getMessage(), 500);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            auth('api')->logout();
            return $this->success([], 'Successfully logged out', 200);
        } catch (Exception $e) {
            return $this->error([], "Logout failed, please try again.", 400);
        }
    }

    public function me()
    {
        try {
            $user = Auth::user();

            return $this->success([
                'id' => $user->id,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'name' => $user->name,
                'email' => $user->email,
            ], 'User retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->error([], 'Failed to retrieve user: ' . $e->getMessage(), 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return $this->success([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ], 'Token refreshed successfully', 200);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage(), 400);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}