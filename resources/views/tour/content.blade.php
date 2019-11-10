<div class="card">
    <img class="card-img-top img-fluid" src="{{ $tour->image_url }}" alt="{{ $tour->name }}" style="height: 140px; object-fit: cover;">
    <div class="card-body">
        <h5 class="card-title text-truncate"><a href="{{ route('tour.show', $tour) }}">{{ $tour->name }}</a></h5>
        
        <h5 class="card-subtitle text-muted my-1">Alamat</h5>
        <p class="card-text">{!! nl2br($tour->address) !!}</p>

        <div class="clearfix my-4">
            <div class="float-left">
                <h5 class="card-subtitle text-muted">Jam Kerja</h5>
                <p class="card-text">{{ $tour->work_hour }}</p>
            </div>
            <div class="float-right">
                <h5 class="card-subtitle text-muted">Kontak</h5>
                <p class="card-text">{{ $tour->contact }}</p>
            </div>
        </div>

        <p class="card-text text-right">
            <a href="{{ route('tour.show', $tour) }}" target="_blank">Lihat Detail</a>
        </p>
    </div>
</div>