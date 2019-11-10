<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tour;
use Faker\Generator as Faker;

$factory->define(Tour::class, function (Faker $faker) {
    $mapCenterLatitude = config('services.google_maps.map_center_latitude', '-3.313695');
    $mapCenterLongitude = config('services.google_maps.map_center_longitude', '114.590148');
    $minLatitude = $mapCenterLatitude - 0.05;
    $maxLatitude = $mapCenterLatitude + 0.05;
    $minLongitude = $mapCenterLongitude - 0.07;
    $maxLongitude = $mapCenterLongitude + 0.07;

    $path = \Illuminate\Support\Facades\Storage::disk('public')->path(Tour::IMAGE_PATH);

    return [
        'name' => $faker->catchPhrase,
        'description' => $faker->paragraphs(5, true),
        'address' => $faker->address,
        'image' => function () use ($path, $faker) {
            return $faker->image($path, 800, 400, $faker->randomElement(['nature', 'food', 'business']), false);
        },
        'latitude' => $faker->latitude($minLatitude, $maxLatitude),
        'longitude' => $faker->longitude($minLongitude, $maxLongitude),
        'contact' => $faker->phoneNumber,
        'work_hour' => function () use ($faker) {
            return $faker->time() . ' - ' . $faker->time();
        },
        'creator_id' => function () {
            $user = \App\User::inRandomOrder()->first();
            return $user ? $user->getKey() : factory(App\User::class)->create();
        }
    ];
});
