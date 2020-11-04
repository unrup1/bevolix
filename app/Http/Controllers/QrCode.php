<?php

namespace App\Http\Controllers;

use App\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class QrCode extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getQrCode(Facility $facility): \Endroid\QrCode\QrCode {
        $url = config('app.url') . '/c/' . $facility->corona_hash;

        $qrCode = new \Endroid\QrCode\QrCode($url);
        return $qrCode;
    }

    private function responseFromQrCode(\Endroid\QrCode\QrCode $qrCode): Response {
        $response = response($qrCode->writeString());
        $response->header('Content-Type', $qrCode->getContentType());

        return $response;
    }

    public function png150(int $id) {
        $facility = $facility = \App\Facility::find($id);

        if (Auth::id() !== $facility->user_id) {
            return response('[]', 403);
        }

        $qrCode = $this->getQrCode($facility);
        $qrCode->setSize(150);

        return $this->responseFromQrCode($qrCode);
    }

    public function png300(int $id) {
        $facility = $facility = \App\Facility::find($id);

        if (Auth::id() !== $facility->user_id) {
            return response('[]', 403);
        }

        $qrCode = $this->getQrCode($facility);
        $qrCode->setSize(300);

        return $this->responseFromQrCode($qrCode);
    }

    public function svg(int $id) {
        $facility = $facility = \App\Facility::find($id);

        if (Auth::id() !== $facility->user_id) {
            return response('[]', 403);
        }

        $qrCode = $this->getQrCode($facility);
        $qrCode->setWriterByName('svg');

        return $this->responseFromQrCode($qrCode);
    }
}
