<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Category;
use App\Services\ActivityService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $category_id = $request->query('category_id');
        $search = $request->query('search');
        $sort = $request->query('sort', 'desc');

        $activities = Activity::query()
            ->with('category')
            ->when(
                in_array($status, Activity::STATUSES, true),
                fn ($query) => $query->where('status', $status)
            )
            ->when($category_id, fn ($query) => $query->where('category_id', $category_id))
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->orderBy('activity_date', $sort === 'asc' ? 'asc' : 'desc')
            ->paginate(5)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('activities.index', [
            'activities' => $activities,
            'selectedStatus' => $status,
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('activities.create', compact('categories'));
    }

    public function store(StoreActivityRequest $request, ActivityService $service): RedirectResponse
    {
        $data = $request->validated();
        $poster = $data['poster'] ?? null;
        unset($data['poster']);

        $activity = $service->create($data, $poster);

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $categories = Category::all();

        return view('activities.edit', compact('activity', 'categories'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity, ActivityService $service): RedirectResponse
    {
        $data = $request->validated();
        $poster = $data['poster'] ?? null;
        unset($data['poster']);

        try {
            $service->update($activity, $data, $poster);
        } catch (DomainException $exception) {
            return back()
                ->withErrors(['status' => $exception->getMessage()])
                ->withInput();
        }

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity, ActivityService $service): RedirectResponse
    {
        $service->delete($activity);

        return to_route('activities.index')
            ->with('success', 'Kegiatan dipindahkan ke Trash dan masih dapat dipulihkan.');
    }

    public function trash(ActivityService $service): View
    {
        return view('activities.trash', [
            'activities' => $service->paginateTrashed(),
        ]);
    }

    public function restore(Activity $activity, ActivityService $service): RedirectResponse
    {
        $service->restore($activity);

        return to_route('activities.index')
            ->with('success', 'Kegiatan berhasil dipulihkan.');
    }
}
