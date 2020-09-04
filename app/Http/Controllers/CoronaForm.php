<?php

namespace App\Http\Controllers;

use App\Facility;
use App\Guest;
use Illuminate\Http\Request;

class CoronaForm extends Controller
{
    public function createGuest(Request $request, string $hash) {
        $facility = Facility::where('corona_hash', $hash)->first();

        if ($facility === null) {
            return response([], 404);
        }

        $data = $request->validate([
            'name' => 'required|max:255',
            'street' => 'required|max:255',
            'postalCode' => 'required|max:255',
            'location' => 'required|max:255',
            'telephoneNumber' => 'required|max:255',
            'startTime' => 'required|max:255',
            'endTime' => 'required|max:255'
        ]);

        $data['facility_id'] = $facility->id;

        $guest = Guest::create($data);

        return response($guest);
    }

    public function getDataProtection(Request $request, string $hash) {
        $facility = Facility::where('corona_hash', $hash)->first();

        if ($facility === null) {
            return view('404');
        }

        return response()->file(storage_path('app/data_protection/' . $facility->id . '.pdf'));
    }


}
