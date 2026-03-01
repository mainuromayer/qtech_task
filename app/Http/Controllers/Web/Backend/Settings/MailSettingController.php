<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MailSettingController extends Controller
{
    /**
     * Display the Mail settings page if the user has the 'mail_setting' permission.
     *
     * @return View|RedirectResponse
     */

    public function index() {
        return view('backend.layouts.settings.mail_settings');
    }

    /**
     * Update the Mail settings in the .env file if the user has the 'mail_setting' permission.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request) {
        $request->validate([
            'mail_mailer'       => 'required|string',
            'mail_host'         => 'required|string',
            'mail_port'         => 'required|string',
            'mail_username'     => 'required|string',
            'mail_password'     => 'required|string',
            'mail_encryption'   => 'required|string',
            'mail_from_address' => 'required|string',
        ]);
        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak  = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PORT=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',
                '/MAIL_FROM_ADDRESS=(.*)\s/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }

            ToastMagic::success('Mail Settings Updated Successfully');
            return back();
        } catch (Exception) {
            ToastMagic::warning('Failed to update');
            return back();
        }

        return redirect()->back();
    }
}
