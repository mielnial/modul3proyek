@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">+ Tambah Kegiatan</a>

    <div class="filters" style="margin-bottom: 1rem;">
        <a href="{{ route('activities.index') }}"
           style="font-weight: {{ !$selectedStatus ? 'bold' : 'normal' }};">
            Semua
        </a>
        @foreach (\App\Models\Activity::STATUSES as $statusOption)
            <a href="{{ route('activities.index', ['status' => $statusOption]) }}"
               style="font-weight: {{ $selectedStatus === $statusOption ? 'bold' : 'normal' }}; margin-left: 10px;">
                {{ $statusOption }}
            </a>
        @endforeach
    </div>

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