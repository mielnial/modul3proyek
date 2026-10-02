<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityService
{
    private const TRANSITIONS = [
        'Planned' => ['Planned', 'Ongoing'],
        'Ongoing' => ['Ongoing', 'Done'],
        'Done'    => ['Done'],
    ];

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $nextStatus = $data['status'] ?? $activity->status;

        $this->ensureValidTransition($activity->status, $nextStatus);
        
        // Business rule guard: Draft tidak lengkap ke Published (Planned -> Ongoing)
        if ($activity->status === 'Planned' && $nextStatus === 'Ongoing') {
            if (empty($data['description']) && empty($activity->description)) {
                throw new DomainException('Kegiatan tidak dapat diubah menjadi Ongoing karena deskripsi masih kosong.');
            }
        }

        $activity->update($data);

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
}
