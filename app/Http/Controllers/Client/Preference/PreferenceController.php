<?php

namespace App\Http\Controllers\Client\Preference;

use Session;
use App\Models\Preference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreferenceController extends Controller
{
    public function showForm(Request $request)
    {
        checkPermissionTo('change-preference');

        $logo = Preference::valueOf('logo');
        $phone = Preference::valueOf('phone');
        $email = Preference::valueOf('email');
        $profitSharingPercentage = Preference::valueOf('profit_sharing_percentage');

        return view('backend.preference.edit', compact('logo', 'phone', 'email', 'profitSharingPercentage'));
    }

    public function update(Request $request)
    {
        checkPermissionTo('change-preference');

        $this->validate($request, [
            'logo' => 'nullable|image|max:2048',
            'phone' => 'required',
            'email' => 'required',
            'profit_sharing_percentage' => 'required|numeric|min:1'
        ]);

        Preference::updateValueOf('phone', $request->phone);
        Preference::updateValueOf('email', $request->email);
        Preference::updateValueOf('profit_sharing_percentage', $request->profit_sharing_percentage);

        if ($request->hasFile('logo')) {
            if ($logo = Preference::valueOf('logo')) deleteFile($logo);

            $fileUrl = uploadFile($request->file('logo'), 'preference/logo');
            Preference::updateValueOf('logo', $fileUrl);
            Session::forget('header_logo');
        }

        return redirect()->route('client.preference.edit')->with('notif_success', 'Preference has been updated successfully!');
    }
}
