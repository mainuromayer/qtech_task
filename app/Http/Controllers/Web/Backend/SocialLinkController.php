<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
//    /**
//     * Display the footer settings page.
//     */
//    public function index() {
//        $social_link = SocialLink::latest('id')->first();
//        return view('backend.layouts.social-link.index', compact('social_link'));
//    }
//
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(Request $request)
//    {
//        $request->validate([
//            'linkedin_link' => 'nullable|url|max:255',
//            'instagram_link' => 'nullable|url|max:255',
//            'tiktok_link' => 'nullable|url|max:255',
//            'twitter_link' => 'nullable|url|max:255',
//        ]);
//
//        $data                   = SocialLink::firstOrNew();
//        $data->linkedin_link    = $request->linkedin_link;
//        $data->instagram_link    = $request->instagram_link;
//        $data->tiktok_link    = $request->tiktok_link;
//        $data->twitter_link     = $request->twitter_link;
//        $data->save();
//
//        return back()->with('notify-success', 'Data Updated Successfully');
//    }
}
