<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    /**
     * The Tour image path.
     */
    const IMAGE_PATH = 'tours';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'address',
        'image',
        'latitude',
        'longitude',
        'contact',
        'work_hour',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['coordinate', 'image_url'];

    /**
     * Get outlet coordinate attribute.
     *
     * @return string|null
     */
    public function getCoordinateAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ', ' . $this->longitude;
        }
    }

    /**
     * Get the image url.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->getImage($this->image);
    }

    /**
     * Get the url by given image file.
     *
     * @param string|null $gambar
     * @return string|null
     */
    public function getImage($image)
    {
        return $image ? Storage::disk('public')->url(static::IMAGE_PATH . '/' . $image) : null;
    }

    /**
     * Creator relation.
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
