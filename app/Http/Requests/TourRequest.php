<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'latitude' => 'nullable|required_with:longitude|max:20',
            'longitude' => 'nullable|required_with:latitude|max:20',
            'contact' => 'required|string',
            'image_collections' => 'nullable|array',
            'image_collections.*' => 'image|mimes:png,jpg,jpeg',
            'work_hour' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Nama Tempat',
            'description' => 'Deskripsi',
            'address' => 'Alamat',
            'image' => 'Gambar',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'contact' => 'Kontak',
            'image_collections.*' => 'Koleksi Gambar',
            'work_hour' => 'Jam Kerja',
        ];
    }
}
