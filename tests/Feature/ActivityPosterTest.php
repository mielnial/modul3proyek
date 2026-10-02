<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ActivityPosterTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->category = Category::create(['name' => 'Teknologi']);
    }

    public function test_activity_poster_can_be_uploaded_and_displayed(): void
    {
        $this->post(route('activities.store'), $this->activityData([
            'poster' => $this->validPoster(),
        ]))->assertRedirect();

        $activity = Activity::firstOrFail();
        $this->assertNotNull($activity->poster_path);
        Storage::disk('public')->assertExists($activity->poster_path);

        $this->get(route('activities.show', $activity))
            ->assertOk()
            ->assertSee(asset('storage/'.$activity->poster_path), false);
    }

    public function test_non_image_poster_is_rejected(): void
    {
        $this->post(route('activities.store'), $this->activityData([
            'poster' => UploadedFile::fake()->create('notes.txt', 1, 'text/plain'),
        ]))->assertSessionHasErrors('poster');

        $this->assertDatabaseCount('activities', 0);
    }

    public function test_poster_over_two_megabytes_is_rejected(): void
    {
        $largePoster = $this->pngContent().str_repeat('x', 2_100_000);

        $this->post(route('activities.store'), $this->activityData([
            'poster' => UploadedFile::fake()->createWithContent('large.png', $largePoster),
        ]))->assertSessionHasErrors('poster');

        $this->assertDatabaseCount('activities', 0);
    }

    public function test_replacing_poster_removes_the_old_file(): void
    {
        $oldPosterPath = 'activity-posters/old.png';
        Storage::disk('public')->put($oldPosterPath, $this->pngContent());
        $activity = Activity::create($this->activityData([
            'poster_path' => $oldPosterPath,
        ]));

        $this->put(route('activities.update', $activity), $this->activityData([
            'poster' => $this->validPoster(),
        ]))->assertRedirect();

        $activity->refresh();
        $this->assertNotSame($oldPosterPath, $activity->poster_path);
        Storage::disk('public')->assertMissing($oldPosterPath);
        Storage::disk('public')->assertExists($activity->poster_path);
    }

    public function test_soft_delete_and_restore_preserve_the_poster_file(): void
    {
        $posterPath = 'activity-posters/keep.png';
        Storage::disk('public')->put($posterPath, $this->pngContent());
        $activity = Activity::create($this->activityData([
            'poster_path' => $posterPath,
        ]));

        $this->delete(route('activities.destroy', $activity))->assertRedirect();
        Storage::disk('public')->assertExists($posterPath);

        $this->patch(route('activities.restore', $activity))->assertRedirect();
        Storage::disk('public')->assertExists($posterPath);
        $this->assertNull($activity->fresh()->deleted_at);
    }

    private function activityData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Kegiatan Poster',
            'description' => 'Deskripsi kegiatan untuk pengujian.',
            'activity_date' => '2026-11-01',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ], $overrides);
    }

    private function validPoster(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('poster.png', $this->pngContent());
    }

    private function pngContent(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'
        );
    }
}
