<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{

    /**
     * Store a newly certificate resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'description' => 'required',
            'valid_from' => 'required',
            'valid_untill' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }


        return response()->json(CertificateService::store($request));
    }

    /**
     * Display a listing of the certificate.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        return response()->json(CertificateService::index($request));
    }

    /**
     * Update the specified certificate resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'description' => 'required',
            'valid_from' => 'required',
            'valid_untill' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return response()->json(CertificateService::update($request));
    }

    /**
     * Delete record with the certificate id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return response()->json(CertificateService::destroy($request));
    }

    /**
     * Generate PDF for the certificate.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request){
        return CertificateService::generatePdf($request);
    }

}
