@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">+ Tambah Kegiatan</a>
    |
    <a href="{{ route('activities.trash') }}">Trash (data terhapus)</a>

    <form method="GET" action="{{ route('activities.index') }}" style="margin: 1rem 0; padding: 1rem; border: 1px solid #ccc; background: #f9f9f9;">
        <input type="text" name="search" placeholder="Cari judul..." value="{{ request('search') }}" style="margin-right: 10px;">
        
        <select name="category_id" style="margin-right: 10px;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>

        <select name="status" style="margin-right: 10px;">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Activity::STATUSES as $stat)
                <option value="{{ $stat }}" @selected(request('status') === $stat)>{{ $stat }}</option>
            @endforeach
        </select>

        <select name="sort" style="margin-right: 10px;">
            <option value="desc" @selected(request('sort') === 'desc')>Terbaru (Desc)</option>
            <option value="asc" @selected(request('sort') === 'asc')>Terlama (Asc)</option>
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('activities.index') }}">Reset</a>
    </form>

    @forelse ($activities as $activity)
        <div class="card">
            <h3>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h3>
            <p>{{ $activity->activity_date->format('d M Y') }} - {{ $activity->category ? $activity->category->name : '-' }}</p>
            <span class="status status-{{ $activity->status }}">{{ $activity->status }}</span>

                        <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                                    style="display:inline; margin-left: 10px;"
                                    onsubmit="return confirm('Pindahkan kegiatan ini ke Trash?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                        </form>
        </div>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse

    <div style="margin-top: 1rem;">
        {{ $activities->links() }}
    </div>
@endsection
