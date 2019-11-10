@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>Daftar Wisata</h1>
            </div>
            <div class="col-md-{{ auth()->check() ? 4 : 6 }}">
                <form action="{{ route('tour.index') }}" method="get" accept-charset="UTF-8" class="mb-2">
                    <div class="input-group">
                        <input name="keyword" value="{{ $params['keyword'] ?? null }}" type="text" class="form-control" id="input-keyword" placeholder="Masukan Kata Kunci">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            @auth
                <div class="col-md-2">
                    <a href="{{ route('tour.create') }}" class="btn btn-primary btn-block">Tambah Wisata</a>
                </div>
            @endauth
        </div>
        @foreach ($tours as $tour)
            @if ($loop->iteration % 3 == 1)
                <div class="row">
            @endif
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img class="card-img-top img-fluid" src="{{ $tour->image_url }}" alt="{{ $tour->name }}" style="height: 140px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><a href="{{ route('tour.show', $tour) }}">{{ $tour->name }}</a></h5>
                                <p class="card-text">{{ substr($tour->description, 0, 70) . ' ...' }}</p>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('tour.show', $tour) }}" class="btn btn-primary {{ auth()->check() ?'mr-1' : null }}">Detail</a>
                                @auth
                                    <div class="btn-group" role="group">
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
            @if ($loop->iteration % 3 == 0 || $loop->last)
                </div>
            @endif
        @endforeach
        <div class="mt-2 d-flex justify-content-center">
            {!! $tours->links() !!}
        </div>
    </div>
@endsection