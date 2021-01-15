<?php

namespace App\Http\Controllers;

use App\Statistic;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class StatsController extends Controller
{
    public function registerHit(Request $request, $key){

        $url = Url::where('key', $key)->first();

        $now = Carbon::now();

        if(!$url || $now->greaterThan(Carbon::create($url->life_end_date))){
            return abort(404);
        }

        $statistic = new Statistic();
        $statistic->__set('ip', $request->ip());

        if($location = Location::get($request->ip())){
            $statistic->__set('country_code', $location->countryCode);
        }

        $agent = new Agent();

        $statistic->__set('browser', $agent->browser());
        $statistic->__set('os', $agent->platform());
        $statistic->__set('device', $agent->device());

        $url->stats()->save($statistic);

        return redirect($url->redirect_url);

    }

    public function show($key){

        $url = Url::where('key', $key)->first();

        if(!$url){
            return abort(404);
        }

        $stats = $url->stats;

        $data = $stats->sortBy('date')->groupBy(function($item){
            $dt = Carbon::create($item->date);
            return $dt->toDateString();
        });

        return view('stats')->with(['data' => $data]);
    }
}
