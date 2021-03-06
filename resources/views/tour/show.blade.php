@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <img class="card-img-top img-fluid" src="{{ $tour->image_url }}" alt="{{ $tour->name }}">
                    <div class="card-body">
                        <h3 class="card-title mb-4 font-weight-bold">{{ $tour->name }}</h3>

                        <h5 class="card-subtitle font-weight-bold">Deskripsi</h5>
                        <p class="card-text mb-4">{!! nl2br($tour->description) !!}</p>

                        <h5 class="card-subtitle font-weight-bold">Alamat</h5>
                        <p class="card-text">{!! nl2br($tour->address) !!}</p>
                        <div id="map" data-lat="{{ $tour->latitude }}" data-lng="{{ $tour->longitude }}" style="height:350px; width:100%;" class="mb-4"></div>

                        <h5 class="card-subtitle font-weight-bold">Jam Kerja</h5>
                        <p class="card-text mb-4">{{ $tour->work_hour }}</p>

                        <h5 class="card-subtitle font-weight-bold">Kontak</h5>
                        <p class="card-text">{{ $tour->contact }}</p>

                        @if ($tour->barcode_ar)
                            <div class="text-center">
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#img-barcode_ar" aria-expanded="false" aria-controls="img-barcode_ar">
                                    Lihat Wisata via AR
                                </button>
                                <div id="img-barcode_ar" class="collapse mt-3">
                                    <img class="rounded" src="{{ $tour->barcode_ar_url }}" alt="{{ $tour->name }}" style="max-width: 250px; max-height: 250px;">
                                    <p class="text-muted mt-2">Scan barcode diatas untuk menampilkan wisata dalam tampilan AR</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer clearfix">
                        <a href="{{ route('tour.index') }}" class="btn btn-secondary">Kembali</a>
                        @auth
                            <div class="btn-group float-right" role="group">
                                <button id="btnGroupDrop{{ $tour->id }}" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $tour->id }}">
                                    <a href="{{ route('tour.edit', $tour) }}" class="dropdown-item">Edit</a>
                                    <a href="{{ route('tour.show', $tour) }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $tour->id }}').submit();">Hapus</a>
                                    <form id="delete-form-{{ $tour->id }}" action="{{ route('tour.destroy', $tour) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="marker-element" class="d-none">
        @include("tour.content", compact("tour"))
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function initMap() {
            var mapElement = document.getElementById('map');

            var defLatLang = new google.maps.LatLng(
                mapElement.dataset.lat,
                mapElement.dataset.lng
            );

            var infoWindow = new google.maps.InfoWindow({ maxWidth: 500 });

            var map = new google.maps.Map(mapElement, {
				zoom: 13,
				center: defLatLang,
                mapTypeControl: false,
            });
            
            var marker = new google.maps.Marker({
                position: defLatLang,
                map: map,
            });

            marker.addListener('click', function () {
                infoWindow.setContent(document.getElementById('marker-element').innerHTML);
                infoWindow.open(map, marker);
            });

            map.addListener('click', function () {
                infoWindow.close();
            });
        }
    </script>
    <script async defer src="{{ config('services.google_maps.js_url') }}?key={{ config('services.google_maps.api_key') }}&callback=initMap" type="text/javascript"></script>
@endpush
