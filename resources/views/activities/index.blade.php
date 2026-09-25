@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">+ Tambah Kegiatan</a>

    @forelse ($activities as $activity)
        <div class="card">
            <h3>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h3>
            <p>{{ $activity->activity_date->format('d M Y') }} — {{ $activity->category }}</p>
            <span class="status status-{{ $activity->status }}">{{ $activity->status }}</span>
        </div>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse
@endsection