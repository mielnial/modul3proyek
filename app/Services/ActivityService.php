<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

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
