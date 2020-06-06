<?php

namespace App\Http\Controllers\Admin\Preference;

use Session;
use App\Models\Preference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreferenceController extends Controller
{
    public function showForm (Request $request)
    {
        checkPermissionTo('change-preference');

        $logo = Preference::valueOf('logo');
        $phone = Preference::valueOf('phone');
        $email = Preference::valueOf('email');

        return view('backend.preference.edit', compact('logo', 'phone', 'email'));
    }

    public function update (Request $request)
    {
        checkPermissionTo('change-preference');

        $this->validate($request, [
            'logo' => 'nullable|image|max:2048',
            'phone' => 'required',
            'email' => 'required'
        ]);

        Preference::updateValueOf('phone', $request->phone);
        Preference::updateValueOf('email', $request->email);

        if ($request->hasFile('logo')) {
            if ($logo = Preference::valueOf('logo')) deleteFile($logo);

            $fileUrl = uploadFile($request->file('logo'), 'preference/logo');
            Preference::updateValueOf('logo', $fileUrl);
            Session::forget('header_logo');
        }

        return redirect()->route('admin.preference.edit')->with('notif_success', 'Preference has been updated successfully!');
    }
}