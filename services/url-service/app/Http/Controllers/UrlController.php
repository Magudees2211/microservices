<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ShortCode;
use App\Models\Url;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $code = ShortCode::generate();

        $url = Url::create([
            'original_url' => $request->url,
            'short_code' => $code
        ]);

        Cache::put("url:$code", $url->original_url);

        return response()->json([
            'short_url' => url($code)
        ]);
    }

    public function redirect($code)
    {
        $url = Cache::get("url:$code");

        if (!$url) {
            $record = Url::where('short_code', $code)->firstOrFail();
            $url = $record->original_url;

            Cache::put("url:$code", $url);
        }

        Url::where('short_code', $code)->increment('clicks');

        return redirect($url);
    }
}
