@extends('layouts.app')

@push('styles')
    <style>
        #map {
            min-height: 500px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="mb-4">{{ config('app.name') }}</h1>
            <div class="col-md-12">
                <div class="card">
                    <div id="map" class="card-body"></div>
                    <div class="card-footer text-right">
                        <a href="{{ route('tour.index') }}">Lihat Daftar Wisata</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var map, bounds, infoWindow;

        function initMap() {
            var mapElement = document.getElementById('map');

            var defLatLang = new google.maps.LatLng(
                {{ config('services.google_maps.map_center_latitude') }},
                {{ config('services.google_maps.map_center_longitude') }}
            );

            infoWindow = new google.maps.InfoWindow({ maxWidth: 500 });
            bounds = new google.maps.LatLngBounds();

            map = new google.maps.Map(mapElement, {
				zoom: 10,
				center: defLatLang,
                mapTypeControl: false,
            });

            map.addListener('click', function () {
                infoWindow.close();
            });
        }

        window.onload = function () {
            $.getJSON("{{ route('maps') }}", function (response) {
                $.each(response.data, function (key, data) {
                    var position = new google.maps.LatLng(data.latitude, data.longitude);

                    var marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: data.name,
                    });

                    marker.addListener('click', function () {
                        infoWindow.setContent(data.content);
                        infoWindow.open(map, marker);
                    });

                    map.fitBounds(bounds.extend(position));
                });
            });
        }
    </script>
    <script async defer src="{{ config('services.google_maps.js_url') }}?key={{ config('services.google_maps.api_key') }}&callback=initMap" type="text/javascript"></script>
@endpush