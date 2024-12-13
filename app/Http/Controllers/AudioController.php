<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Speaker;
use Flasher\Prime\FlasherInterface;

class AudioController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
    ) {
    }

    public function index()
    {
        $audios = Audio::with('speaker')
            ->orderByDesc('id')
            ->paginate(100)
            ->onEachSide(1);

        return view('community.audios.index', [
            'audios' => $audios,
            'bodyBackground' => 'purple-pastel'
        ]);
    }

    public function speaker(Speaker $speaker)
    {
        $audios = $speaker->audios()
            ->with('speaker')
            ->orderByDesc('id')
            ->paginate(50)
            ->onEachSide(1);

        return view('community.audios.speaker', [
            'user' => $speaker->user,
            'speaker' => $speaker,
            'audios' => $audios,
            'bodyBackground' => 'purple-pastel'
        ]);
    }

    public function destroy(Audio $audio)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($audio->speaker->user_id === $user->id) {
                $success = $this->deleteRemote($audio->filename);

                if ($success) {
                    $audio->delete();
                    $this->flasher->addSuccess(__('deleted', ['thing' => $audio->filename]));

                } else {
                    $this->flasher->addError('Unable to delete file from cloud storage.');
                }

                return to_route('audios.speaker', $user->speaker);

            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    private function deleteRemote(string $filename)
    {
//        TODO: Delete file from cloud server.
        return true;
    }
}
