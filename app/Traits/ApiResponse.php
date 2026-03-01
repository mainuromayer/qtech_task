<?php

namespace App\Traits;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

trait ApiResponse
{
    /**
     * Return a successful response with a message and data.
     */
    public function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $code);
    }

    public function error($data, $message = null, $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $code);
    }


    protected function generateOtp(User $user)
    {
        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->otp_created_at = now();
        $user->otp_expires_at = now()->addMinutes(1);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($user, $otp));
    }

    protected function generateSetToken($user, $action)
    {
        $customClaims = [
            'sub' => $user->id,
            'email' => $user->email,
            'action' => $action,
            'exp' => now()->addDays(365)->timestamp, // Token valid for 1 year
        ];

        return JWTAuth::customClaims($customClaims)->fromUser($user);
    }


    /**
     * Return a paginated response with a message and pagination metadata.
     */
    public function paginateResponse($paginator, $message = "Data fetched successfully", $code = 200)
    {
        $items = $paginator->items();

        if (empty($items)) {
            return response()->json([
                'success' => true,
                'message' => 'No data found',
                'data' => [],
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ], $code);
    }
}
