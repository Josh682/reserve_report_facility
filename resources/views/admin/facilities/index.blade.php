<div>
    <h1>Daftar Fasilitas</h1>
    <ul>
        @foreach ($facilities as $facility)
            <li>{{ $facility->nama }} - {{ $facility->tipe }} - {{ $facility->status }}</li>
        @endforeach
    </ul>
    {{ $facilities->links() }}
</div>
