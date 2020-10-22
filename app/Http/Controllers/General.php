<?php

namespace App\Http\Controllers;

use App\Facility;
use Illuminate\Http\Request;

class General extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function spa(Request $request)
    {
        if ($request->wantsJson()) {
            return response([], 404);
        };

        return view('spa');
    }
    /**
     * Show the application
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function corona(string $hash)
    {
        $facility = Facility::where('corona_hash', $hash)->first();

        if ($facility === null) {
            return view('404');
        }

        return view('corona', [
            'facility' => $facility
        ]);
    }

    public function imprint() {
        return view('imprint');
    }

    public function privacyPolicy() {
        return view('privacy-policy');
    }


}
