<?php

namespace App\Http\Controllers\Api;

use App\CoronaDataProtection;
use App\Facility;
use App\Guest;
use ConvertApi\ConvertApi;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\TemplateProcessor;

;

class ApiFacility extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getAll() {
        return Facility::where('user_id', Auth::id())->get();
    }

    public function create(Request $request) {
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $facility = Facility::create([
            'name' => $request->name,
            'description' => $request->description,
            'has_image' => false,
            'corona_hash' => '',
            'user_id' => Auth::id()
        ]);

        $hashids = new Hashids(config('app.key'), 8);
        $facility->corona_hash = $hashids->encode($facility->id);
        $facility->save();

        return response($facility);
    }

    public function getOne(int $id) {
        $facility = \App\Facility::find($id);

        if ($facility === null) {
            return response([], 404);
        }

        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        return response($facility);
    }

    public function update(Request $request, int $id) {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $facility = Facility::find($id);

        if ($facility === null) {
            return response([], 404);
        }

        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->save();

        return response($facility);
    }

    public function delete(int $id) {
        $facility = Facility::find($id);

        if ($facility === null) {
            return response([], 404);
        }

        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        $facility->delete();

        return response([]);
    }

    public function getGuests(int $id) {
        $facility = Facility::find($id);

        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        return Guest::where('facility_id', $id)
            ->orderBy('created_at')
            ->get();
    }

    public function getDataProtection(Request $request, Facility $facility) {
        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        // get data
        $dataProtection = CoronaDataProtection::where('facility_id', $facility->id)->first();
        $path = storage_path('app/data_protection/' . $facility->id . '.pdf');

        return response([
            'has_file' => file_exists($path),
            'generate' => $dataProtection
        ]);
    }

    public function removeDataProtection(Request $request, Facility $facility) {
        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        // remove all files
        CoronaDataProtection::where('facility_id', $facility->id)->delete();
        $path = storage_path('app/data_protection/' . $facility->id . '.pdf');
        @unlink($path);

        return response([]);
    }

    public function uploadDataProtection(Request $request, Facility $facility) {
        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        $file = $request->file('data-protection');

        if ($file->getClientOriginalExtension() != 'pdf') {
            return response(['message' => 'File not pdf', 'extension' => $file->getClientOriginalExtension()], 404);
        }

        $file->storeAs('data_protection', $facility->id . '.pdf');

        // remove generated data
        CoronaDataProtection::where('facility_id', $facility->id)->delete();

        return response([]);
    }

    public function generateDataProtection(Request $request, Facility $facility) {
        if ($facility->user_id !== Auth::id()) {
            return response([], 403);
        }

        $data = $request->validate([
            'privacy_name' => 'required|max:255',
            'facility_name' => 'required|max:255',
            'facility_address' => 'required|max:255',
            'facility_location' => 'required|max:255',
            'owner_name' => 'required|max:255',
            'telephone_number' => 'required|max:255'
        ]);

        // save data in db
        $model = CoronaDataProtection::create(array_merge($data, [
            'facility_id' => $facility->id
        ]));

        // fill template with data
        $template = new TemplateProcessor(storage_path('/data_protection_template.docx'));
        $template->setValues($data);

        // save template as doxc
        $pathForDocx= storage_path('app/data_protection/' . $facility->id . '.docx');
        $template->saveAs($pathForDocx);

        // render docx to pdf
        ConvertApi::setApiSecret($_ENV['CONVERT_API_SECRET']);
        $result = ConvertApi::convert('pdf', [
            'File' => $pathForDocx,
            'PdfTitle' => 'Datenschutzerklärung',
            'PdfSubject' => 'Datenschutzerklärung',
            'PdfAuthor' => 'Bevolix'
        ]);

        $pathForPdf= storage_path('app/data_protection/' . $facility->id . '.pdf');
        $result->getFile()->save($pathForPdf);

        // remove docx & without notice message
        @unlink($pathForDocx);

        return response($model);
    }

}
