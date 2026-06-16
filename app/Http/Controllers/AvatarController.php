<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvatarResource;
use App\Http\Resources\UserEditorResource;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AvatarController extends Controller
{
    private const string STORAGE_PATH = 'images/avatars';

    public function index(User $user): JsonResponse
    {
        Gate::authorize('viewAny', [Avatar::class, $user]);

        return response()->json([
            'avatars' => AvatarResource::collection($user->uploadedAvatars()->latest()->get()),
        ]);
    }

    public function store(Request $request, User $user): JsonResponse
    {
        Gate::authorize('create', [Avatar::class, $user]);

        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $filename = Str::uuid().'.webp';
        $processedAvatarPath = $this->processAvatar($request->file('avatar'));

        try {
            $avatar = Avatar::create([
                'user_id' => $user->id,
                'filename' => $filename,
            ]);

            if (app()->environment(['production'])) {
                Storage::disk('s3')->putFileAs(
                    self::STORAGE_PATH,
                    new File($processedAvatarPath),
                    $filename,
                    'public'
                );
            } else {
                Log::info('Simulating image file upload to s3: '.$avatar->filename);
            }

        } catch (\Exception $e) {
            Log::error('Failed to upload image file: '.$e->getMessage());
            throw $e;

        } finally {
            @unlink($processedAvatarPath);
        }

        return response()->json([
            'avatar' => new AvatarResource($avatar),
        ], 201);
    }

    public function destroy(Avatar $avatar): JsonResponse
    {
        Gate::authorize('delete', $avatar);

        $user = $avatar->user;
        $filename = $avatar->filename;

        try {
            $path = self::STORAGE_PATH.'/'.$filename;

            if (! Storage::disk('s3')->exists($path)) {
                throw new \Exception("File not found: {$path}");
            }

            if (app()->environment(['production'])) {
                Storage::disk('s3')->delete($path);
            } else {
                Log::info('Simulating avatar file deletion from s3: '.$filename);
            }

        } catch (\Exception $e) {
            throw new \Exception(
                "Failed to delete file: ".self::STORAGE_PATH."/{$filename}. Error: ".$e->getMessage(),
                0,
                $e
            );
        }

        $avatar->delete();

        return response()->json([
            'user' => new UserEditorResource($user->fresh(['dialect', 'teacher', 'roles', 'uploadedAvatars'])),
        ]);
    }

    private function processAvatar(UploadedFile $avatar): string
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'palweb_avatar_');
        $processedPath = $temporaryPath.'.webp';

        @unlink($temporaryPath);

        try {
            new ImageManager(new Driver())
                ->read($avatar->getPathname())
                ->cover(512, 512)
                ->toWebp(quality: 100)
                ->save($processedPath);

        } catch (\Exception $e) {
            @unlink($processedPath);
            throw $e;
        }

        return $processedPath;
    }
}
