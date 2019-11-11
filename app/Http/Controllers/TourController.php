<?php

namespace App\Http\Controllers;

use App\Http\Requests\TourRequest;
use App\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only('keyword');

        $tours = Tour::latest()->when($request->keyword ?? false, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        })->paginate(6)->appends($params);

        return view('tour.index', compact('tours', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tour.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TourRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TourRequest $request)
    {
        $tour = Tour::create($this->collectFields($request));

        return redirect()->route('tour.show', $tour);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function show(Tour $tour)
    {
        return view('tour.show', compact('tour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function edit(Tour $tour)
    {
        return view('tour.form', compact('tour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TourRequest  $request
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function update(TourRequest $request, Tour $tour)
    {
        $tour->update($this->collectFields($request, $tour));

        return redirect()->route('tour.show', $tour);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tour $tour)
    {
        if ($tour->delete()) {
            foreach (['image', 'barcode_ar'] as $image_key) {
                if ($image = $tour->{$image_key}) {
                    Storage::disk('public')->delete(Tour::IMAGE_PATH . '/' . $image);
                }
            }

            return redirect()->route('tour.index');
        }

        return redirect()->back();
    }

    /**
     * Collect the fields from request.
     *
     * @param \App\Http\Requests\TourRequest $request
     * @param \App\Models\Tour|null $tour
     * @return array
     */
    protected function collectFields(TourRequest $request, $tour = null)
    {
        $fields = $request->validated();

        $fields['creator_id'] = $request->user()->id;

        foreach (['image', 'barcode_ar'] as $image_key) {
            if ($request->hasFile($image_key)) {
                $image = $request->file($image_key);
                $image->store(Tour::IMAGE_PATH, 'public');

                if ($tour && $old_image = $tour->{$image_key}) {
                    Storage::disk('public')->delete(Tour::IMAGE_PATH . '/' . $old_image);
                }

                $fields[$image_key] = $image->hashName();
            }
        }

        return $fields;
    }
}
