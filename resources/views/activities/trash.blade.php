@extends('layouts.app')

@section('content')
    <a href="{{ route('activities.index') }}">&larr; Kembali ke daftar</a>

    <h1>Trash Kegiatan</h1>
    <p>Kegiatan di sini sudah di-soft-delete. Datanya masih ada di database dan dapat dipulihkan.</p>

    @forelse ($activities as $activity)
        <div class="card">
            <h3>{{ $activity->title }}</h3>
            <p>
                {{ $activity->activity_date->format('d M Y') }}
                - {{ $activity->category ? $activity->category->name : '-' }}
            </p>
            <span class="status status-{{ $activity->status }}">{{ $activity->status }}</span>
            <p>Dihapus pada: {{ $activity->deleted_at->format('d M Y H:i') }}</p>

            <form method="POST" action="{{ route('activities.restore', $activity) }}"
                  onsubmit="return confirm('Pulihkan kegiatan ini?')">
                @csrf
                @method('PATCH')
                <button type="submit">Restore</button>
            </form>
        </div>
    @empty
        <p>Trash kosong.</p>
    @endforelse

    <div style="margin-top: 1rem;">
        {{ $activities->links() }}
    </div>
@endsection