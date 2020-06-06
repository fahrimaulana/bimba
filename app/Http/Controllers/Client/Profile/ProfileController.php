<?php

namespace App\Http\Controllers\Client\Profile;

use Datatables;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Location\Province;
use App\Models\Location\City;
use App\Models\Location\District;
use App\Models\Location\Vilage;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function showForm(Request $request)
    {
        checkPermissionTo('edit-profile');

        $client = Client::findOrFail(clientId());

        $provinces = Province::all();
        $cities = City::all();
        $district = District::where('id', optional($client->address)->district)->first();
        $vilage = Vilage::where('id', optional($client->address)->vilage)->first();

        return view('client.profile.form', compact('client', 'provinces', 'cities', 'district', 'vilage'));
    }

    public function update(Request $request)
    {
        checkPermissionTo('edit-profile');
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required|min:5|max:5',
            'staff_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'vilage' => 'required',
            'account_bank' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
        ]);

        $client = Client::findOrFail(clientId());

        $client->name = $request->name;
        $client->code = $request->code;
        $client->staff_name = $request->staff_name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->address = [
            'address' => $request->address,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'pos_code' => $request->pos_code,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'vilage' => $request->vilage,
        ];
        $client->account_bank = $request->account_bank;
        $client->account_number = $request->account_number;
        $client->account_name = $request->account_name;
        $client->save();

        return redirect()->route('client.profile.show-form')->with('notif_success', 'Profil telah berhasil diubah!');
    }
}