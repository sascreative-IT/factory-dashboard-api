<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FileUploadController extends Controller
{
    protected $destination_path = '/storage/';

    public function uploadPoFile(Request $request)
    {
        try {
            $file = $request->file('file');
            $order = Order::where("merch_order_id", $request->merch_order_id)->first();

            $file_name = $order->id . "-" . trim(str_replace(" ", "_", $file->getClientOriginalName()));
            Storage::disk('public')->put($file_name, File::get($file));

            $order->update(['po' => $file_name]);
            $order->save();
        } catch (\Exception $e) {
            return response(['errors' => ['message' => $e->getMessage()]], 404);
        }

        return response()->json(
            [
                'path' => $file_name,
                'message' => 'The PO has been uploaded successfully'
            ],
            200
        );
    }
}
