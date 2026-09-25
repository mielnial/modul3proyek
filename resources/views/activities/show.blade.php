@extends('layouts.app')

@section('content')
    <a href="{{ route('activities.index') }}">&larr; Kembali</a>
    <h1>{{ $activity->title }}</h1>

    <p><strong>Tanggal:</strong> {{ $activity->activity_date->format('d M Y') }}</p>
    <p><strong>Kategori:</strong> {{ $activity->category }}</p>
    <p><strong>Status:</strong>
        <span class="status status-{{ $activity->status }}">{{ $activity->status }}</span>
    </p>
    <p><strong>Deskripsi:</strong><br>
        {{ $activity->description ?? '-' }}
    </p>
    <div style="margin-top: 16px;">
        <a href="{{ route('activities.edit', $activity) }}">Ubah</a>

        <form action="{{ route('activities.destroy', $activity) }}" method="POST"
            style="display:inline"
            onsubmit="return confirm('Yakin mau hapus kegiatan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    </div>
@endsection