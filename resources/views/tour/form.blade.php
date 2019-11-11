@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ (isset($tour) ? 'Edit ' : 'Tambah ') . 'Wisata' }}</div>

                    <form method="POST" action="{{ isset($tour) ? route('tour.update', $tour) : route('tour.store') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        @isset($tour)
                            @method('PUT')
                        @endisset
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="input-name" class="col-md-2 col-form-label text-md-right">Nama Tempat</label>
                                <div class="col-md-8">
                                    <input id="input-name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $tour->name ?? null) }}" placeholder="Nama Tempat">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-description" class="col-md-2 col-form-label text-md-right">Deskripsi</label>
                                <div class="col-md-8">
                                    <textarea name="description" id="input-description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="3" placeholder="Deskripsi">{{ old('description', $tour->description ?? null) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-image" class="col-md-2 col-form-label text-md-right">Gambar</label>
                                <div class="col-md-10">
                                    <label for="input-image" style="cursor: pointer;">
                                        <img id="input-image-display" class="img-fluid mr-1" src="{{ $tour->image_url ?? asset('img/default-banner.jpg') }}" style="width: 180px; height: 180px; object-fit: cover;">
                                        <input type="file" name="image" id="input-image" class="d-none image-preview" accept="image/*" data-target="input-image-display">
                                    </label>
                                    @error('image')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-contact" class="col-md-2 col-form-label text-md-right">Kontak</label>
                                <div class="col-md-8">
                                    <input id="input-contact" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact', $tour->contact ?? null) }}" placeholder="Kontak">
                                    @error('contact')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right">Alamat</label>
                                <div class="col-md-8">
                                    <input id="input-latitude" type="hidden" name="latitude" value="{{ old('latitude', $tour->latitude ?? null) }}">
                                    <input id="input-longitude" type="hidden" name="longitude" value="{{ old('longitude', $tour->longitude ?? null) }}">
                                    <input id="input-address" type="text" class="form-control {{ $errors->hasAny('address', 'longitude', 'latitude') ? 'is-invalid' : null }}" name="address" value="{{ old('address', $tour->address ?? null) }}" placeholder="Alamat">
                                    @foreach (['latitude', 'longitude', 'address'] as $item)
                                        @error($item)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endforeach
                                    <div id="map" style="height:350px; width:100%;" class="mt-3"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-work_hour" class="col-md-2 col-form-label text-md-right">Jam Kerja</label>
                                <div class="col-md-8">
                                    <input id="input-work_hour" type="text" class="form-control @error('work_hour') is-invalid @enderror" name="work_hour" value="{{ old('work_hour', $tour->work_hour ?? null) }}" placeholder="Jam Kerja">
                                    @error('work_hour')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-barcode_ar" class="col-md-2 col-form-label text-md-right">Barcode AR</label>
                                <div class="col-md-10">
                                    <label for="input-barcode_ar" style="cursor: pointer;">
                                        <img id="input-barcode_ar-display" class="img-fluid mr-1" src="{{ $tour->barcode_ar_url ?? asset('img/default-banner.jpg') }}" style="width: 180px; height: 180px; object-fit: cover;">
                                        <input type="file" name="barcode_ar" id="input-barcode_ar" class="d-none image-preview" accept="image/*" data-target="input-barcode_ar-display">
                                    </label>
                                    @error('barcode_ar')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('tour.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success float-right">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function initMap() {
            var inputLocLat = document.getElementById('input-latitude');
            var inputLocLang = document.getElementById('input-longitude');
            var inputAddress = document.getElementById('input-address');

            inputAddress.addEventListener('keydown', function (e){
                if(e.keyCode == 13 || e.which == 13){
                    e.preventDefault();
                }
            });

            var defLatLang = new google.maps.LatLng(
                inputLocLat.value || {{ config('services.google_maps.map_center_latitude') }},
                inputLocLang.value || {{ config('services.google_maps.map_center_longitude') }}
            );

            var geocoder = new google.maps.Geocoder();

            var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 13,
				center: defLatLang,
                mapTypeControl: false,
            });

            var autocomplete = new google.maps.places.Autocomplete(inputAddress);
            
            var marker = new google.maps.Marker({
                position: defLatLang,
                map: map,
            });

            autocomplete.bindTo('bounds', map);
            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);

            autocomplete.addListener('place_changed', function () {
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                var latLng = place.geometry.location;

                marker.setPosition(latLng);
                marker.setVisible(true);
                map.panTo(latLng);

                inputLocLat.value = latLng.lat();
                inputLocLang.value = latLng.lng();
            });
        
            map.addListener('click', function (e) {
                marker.setPosition(e.latLng);
                map.panTo(e.latLng);

                geocoder.geocode({'location': e.latLng}, function (result, status) {
                    console.log({ result });

                    if (status === google.maps.GeocoderStatus.OK) {
                        if (result[0]) {
                            inputAddress.value = result[0].formatted_address;
                        }
                    }
                });

                inputLocLat.value = e.latLng.lat();
                inputLocLang.value = e.latLng.lng();
            });
        }

        window.onload = function () {
            $('.image-preview').on('change', function () {
                var targetEl = $(this).data('target');
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#'.concat(targetEl)).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        };
    </script>
    <script async defer src="{{ config('services.google_maps.js_url') }}?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap" type="text/javascript"></script>
@endpush
