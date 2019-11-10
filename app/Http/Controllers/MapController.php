<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourMapResource;
use App\Tour;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('tour.map');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function maps(Request $request)
    {
        return TourMapResource::collection(Tour::latest()->get());
    }
}
