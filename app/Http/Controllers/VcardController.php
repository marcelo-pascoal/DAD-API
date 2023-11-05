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
        $vcard->fill($request->all());
        $vcard->save();
        return new VcardResource($vcard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vcard $vcard)
    {
        $vcard->delete();
        return new VcardResource($vcard);
    }

    public function updateBlocked(Request $request, Vcard $vcard)
    {
        $vcard->blocked = $request->blocked;
        $vcard->save();
        return new VcardResource($vcard);
    }
}
