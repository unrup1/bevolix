<?php

namespace App\Http\Controllers;

use App\Facility;
use App\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExportDocument extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportGuests(int $id, Request $request) {
        $facility = $facility = Facility::find($id);

        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'startTime' => 'nullable|regex:/^[0-2][0-9]\:[0-5][0-9]$/',
            'endTime' => 'nullable|regex:/^[0-2][0-9]\:[0-5][0-9]$/'
        ]);

        if ($validator->fails()) {
            return view('404');
        }

        $data = Guest::where('facility_id', $facility->id)
            ->whereDate('created_at', '=', $request->get('date'))
            ->where(function($query) use ($request) {
                $query
                    ->where('endTime', '>', $request->get('startTime') ?: '00:00:00')
                    ->orWhere('startTime', '<', $request->get('endTime') ?: '24:00:00');
            })
            ->orderBy('created_at')
            ->get();

        return response()
            ->streamDownload(function() use ($data) {
                $columns = ['Name', 'Straße', 'PLZ', 'Ort', 'Telefonnummer', 'Datum', 'Eintritt', 'Verlassen'];

                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($data as $guest) {
                    fputcsv($file, [
                        $guest->name,
                        $guest->street,
                        $guest->postalCode,
                        $guest->location,
                        $guest->telephoneNumber,
                        date_format($guest->created_at, 'd.m.Y'),
                        $guest->startTime,
                        $guest->endTime
                    ]);
                }
                fclose($file);
            }, $facility->name . ' Export ' . $request->get('date') . '.csv');
    }
}
