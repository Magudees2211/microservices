<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
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
        $key = "visited:" . request()->ip() . ":" . $code;

        if (!Cache::has($key)) {
            $record = Url::where('short_code', $code)->firstOrFail();
            $record->increment('clicks');

            Cache::put($key, true, now()->addMinutes(1)); // prevent duplicate for 1 min
        }

        $url = Cache::get("url:$code");

        if (!$url) {
            $record = Url::where('short_code', $code)->firstOrFail();
            $url = $record->original_url;

            Cache::put("url:$code", $url);
        }

        return redirect($url);
    }
}
