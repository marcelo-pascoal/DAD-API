<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vcard;
use Illuminate\Http\Request;
use App\Http\Resources\VcardResource;
use App\Http\Resources\UserResource;
use App\Services\Base64Services;
use App\Http\Requests\StoreVcardRequest;

class VcardController extends Controller
{
    private function storeBase64AsFile(Vcard $vcard, String $base64String)
    {
        $targetDir = storage_path('app/public/fotos');
        $newfilename = $vcard->phone_number . "_" . rand(1000, 9999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }
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
    public function store(StoreVcardRequest $request)
    {
        $dataToSave = $request->validated();

        $base64ImagePhoto = array_key_exists("base64ImagePhoto", $dataToSave) ?
            $dataToSave["base64ImagePhoto"] : ($dataToSave["base64ImagePhoto"] ?? null);
        unset($dataToSave["base64ImagePhoto"]);

        $vcard = new Vcard();
        $vcard->phone_number = $dataToSave['phone_number'];
        $vcard->name = $dataToSave['name'];
        $vcard->email = $dataToSave['email'];
        $vcard->password = bcrypt($dataToSave['password']);
        $vcard->confirmation_code = bcrypt($dataToSave['confirmation_code']);
        $vcard->blocked = 0;
        $vcard->balance = 0;
        $vcard->max_debit = 500;

        // Create a new photo file from base64 content
        if ($base64ImagePhoto) {
            $vcard->photo_url = $this->storeBase64AsFile($vcard, $base64ImagePhoto);
        }
        $vcard->save();
        return new VcardResource($vcard);
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
