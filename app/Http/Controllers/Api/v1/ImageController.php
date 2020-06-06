<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class ImageController extends ApiController
{
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $image = Image::findOrFail($request->input('id'));
        if ($image->url) deleteFile($image->url);
        $image->delete();

        return $this->makeResponse(null, 'Image has been deleted');
    }
}
