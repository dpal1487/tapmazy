<?php


namespace App\Http\Controllers;

use App\Helpers\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Request;
use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    use ImageManager;
    public function store(Request $request, $entity)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }
        $path = public_path("images/$entity");
        !is_dir($path) &&
            mkdir($path, 0777, true);
        if ($file = $request->file('image')) {
            $fileData = $this->uploadImage($path, $file);
            if ($request->image_id) {
                $image = ImageModel::where('entity_id', $request->image_id)->first();
                if ($image) {
                    if (file_exists($image->path . '/' . $image->name)) {
                        unlink($image->path . '/' . $image->name);
                    }
                    $imageUpdate = $image->update([
                        'name' => $fileData['fileName'],
                        'type' => $fileData['fileType'],
                        'path' => $fileData['filePath'],
                        'size' => $fileData['fileSize'],
                        'entity_type' => $entity
                    ]);
                    return response()->json(['data' => new ImageResource($image), 'success' => true]);
                }
            } else {
                $image = ImageModel::create([
                    'name' => $fileData['fileName'],
                    'type' => $fileData['fileType'],
                    'path' => $fileData['filePath'],
                    'size' => $fileData['fileSize'],
                    'entity_type' => $entity
                ]);
                return response()->json(['data' => new ImageResource($image), 'success' => true]);
            }
        }
    }
}
