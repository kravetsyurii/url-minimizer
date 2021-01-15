<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    public function create(Request $request){

        $this->validate($request, [
            'url' => 'required|url|max:2048',
            'customUrl' => 'nullable|string|min:2|max:200|unique:urls,key',
            'lifeEndDate' => 'date|after_or_equal:now'
        ],
            [
                'url.required' => 'This field is required!',
                'url.url' => 'Should be a valid url!'
            ]);

        $customUrl = is_null($request->get('customUrl')) ? Str::random(10) : $request->get('customUrl');

        $url = new Url();
        $url->__set('key', $customUrl);
        $url->__set('redirect_url', $request->get('url'));
        $url->__set('life_end_date', $request->get('lifeEndDate'));

        if($url->save()){
            $response = [
                'shortLink' => $_SERVER['SERVER_NAME'].'/'.$customUrl,
                'statsLink' => url("/{$customUrl}/stats")
            ];

            return response()->json($response, 200);
        }else{
            throw new \Exception('Server error', 500);
        }
    }
}
