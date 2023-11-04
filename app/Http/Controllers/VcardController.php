<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Http\Request;
use App\Http\Resources\VcardResource;

class VcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vcards = Vcard::all();
        return VcardResource::collection($vcards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vcard $vcard)
    {
        return new VcardResource($vcard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vcard $vcard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vcard $vcard)
    {
        //
    }
}
