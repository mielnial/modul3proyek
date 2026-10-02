<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActivityQueryQualityTest extends TestCase
{
    use RefreshDatabase;

    public function test_eager_loading_avoids_one_category_query_per_activity(): void
    {
        for ($index = 1; $index <= 3; $index++) {
            $category = Category::create(['name' => "Kategori {$index}"]);

            Activity::create([
                'title' => "Kegiatan {$index}",
                'description' => 'Deskripsi untuk pengujian query.',
                'activity_date' => '2026-11-01',
                'category_id' => $category->id,
                'status' => 'Planned',
            ]);
        }

        DB::enableQueryLog();
        DB::flushQueryLog();

        $activities = Activity::query()->orderBy('id')->get();
        foreach ($activities as $activity) {
            $activity->category;
        }
        $lazyQueryCount = count(DB::getQueryLog());

        DB::flushQueryLog();

        $activities = Activity::query()->with('category')->orderBy('id')->get();
        foreach ($activities as $activity) {
            $activity->category;
        }
        $eagerQueryCount = count(DB::getQueryLog());

        DB::disableQueryLog();

        $this->assertSame(4, $lazyQueryCount);
        $this->assertSame(2, $eagerQueryCount);
    }
}
