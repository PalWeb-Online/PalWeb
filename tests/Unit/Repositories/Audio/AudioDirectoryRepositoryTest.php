<?php

namespace Tests\Unit\Repositories\Audio;

use App\Exceptions\AudioFileException;
use App\Models\File;
use App\Models\User;
use App\Repositories\Audio\AudioDirectoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AudioDirectoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_addAudioFile_throws_exception_if_file_does_not_have_an_extension()
    {
        $this->expectException(AudioFileException::class);
        $user = User::factory()->make();
        $this->getRepository()->addAudioFile($user, 'no-extension');
    }

    public function test_addAudioFile_accepts_custom_path_if_provided()
    {
        $user = User::factory()->create();
        $this->getRepository()->addAudioFile($user, 'audio.mp3', '/cat/dog/');
        $this->assertEquals('/cat/dog/', File::first()->path);
    }

    public function test_addAudioFile_creates_a_database_record()
    {
        $user = User::factory()->create();
        $this->assertEquals(0, File::count());
        $this->getRepository()->addAudioFile($user, 'audio.mp3');
        $this->assertEquals(1, File::count());
        $file = File::first();

        $this->assertEquals('audio.mp3', $file->filename);
        $this->assertEquals('mp3', $file->extension);
        $this->assertEquals('audio/', $file->path);
    }

    protected function getRepository(): AudioDirectoryRepository
    {
        return new AudioDirectoryRepository;
    }
}
