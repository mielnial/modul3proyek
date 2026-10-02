<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ActivityService
{
    private const TRANSITIONS = [
        'Planned' => ['Planned', 'Ongoing'],
        'Ongoing' => ['Ongoing', 'Done'],
        'Done' => ['Done'],
    ];

    public function create(array $data, ?UploadedFile $poster = null): Activity
    {
        $posterPath = $poster ? $this->storePoster($poster) : null;

        if ($posterPath !== null) {
            $data['poster_path'] = $posterPath;
        }

        try {
            return Activity::create($data);
        } catch (Throwable $exception) {
            if ($posterPath !== null) {
                Storage::disk('public')->delete($posterPath);
            }

            throw $exception;
        }
    }

    public function update(Activity $activity, array $data, ?UploadedFile $poster = null): Activity
    {
        $nextStatus = $data['status'] ?? $activity->status;

        $this->ensureValidTransition($activity->status, $nextStatus);

        // Business rule guard: Draft tidak lengkap ke Published (Planned -> Ongoing)
        if ($activity->status === 'Planned' && $nextStatus === 'Ongoing') {
            if (empty($data['description']) && empty($activity->description)) {
                throw new DomainException('Kegiatan tidak dapat diubah menjadi Ongoing karena deskripsi masih kosong.');
            }
        }

        $oldPosterPath = $activity->poster_path;
        $newPosterPath = $poster ? $this->storePoster($poster) : null;

        if ($newPosterPath !== null) {
            $data['poster_path'] = $newPosterPath;
        }

        try {
            $activity->update($data);
        } catch (Throwable $exception) {
            if ($newPosterPath !== null) {
                Storage::disk('public')->delete($newPosterPath);
            }

            throw $exception;
        }

        if ($newPosterPath !== null && $oldPosterPath !== null) {
            Storage::disk('public')->delete($oldPosterPath);
        }

        return $activity->refresh();
    }

    /**
     * Soft delete: mengisi deleted_at, record tetap ada di database.
     */
    public function delete(Activity $activity): void
    {
        $activity->delete();
    }

    /**
     * Restore: mengosongkan kembali deleted_at sehingga kegiatan kembali ke daftar aktif.
     */
    public function restore(Activity $activity): Activity
    {
        $activity->restore();

        return $activity->refresh();
    }

    /**
     * Daftar kegiatan yang sudah di-soft-delete (halaman Trash).
     */
    public function paginateTrashed(int $perPage = 5): LengthAwarePaginator
    {
        return Activity::onlyTrashed()
            ->with('category')
            ->latest('deleted_at')
            ->paginate($perPage);
    }

    private function ensureValidTransition(string $current, string $next): void
    {
        $allowed = self::TRANSITIONS[$current] ?? [];

        if (! in_array($next, $allowed, true)) {
            throw new DomainException(
                "Transisi status {$current} ke {$next} tidak diizinkan."
            );
        }
    }

    private function storePoster(UploadedFile $poster): string
    {
        $path = $poster->store('activity-posters', 'public');

        if ($path === false) {
            throw new RuntimeException('Poster gagal disimpan.');
        }

        return $path;
    }
}
