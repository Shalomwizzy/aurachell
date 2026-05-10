<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    private string $disk = 'local';

    public function index(): View
    {
        $backups = $this->getBackups();

        return view('admin.system.backups', compact('backups'));
    }

    public function run(): RedirectResponse
    {
        try {
            Artisan::call('backup:run --only-db');

            return back()->with('success', 'Backup completed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: '.$e->getMessage());
        }
    }

    public function download(Request $request): StreamedResponse|RedirectResponse
    {
        $file = $request->query('file');

        if (! $file || ! $this->fileExists($file)) {
            return back()->with('error', 'Backup file not found.');
        }

        $path = config('app.name').'/'.$file;

        return Storage::disk($this->disk)->download($path, basename($file));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $file = $request->query('file');

        if (! $file || ! $this->fileExists($file)) {
            return back()->with('error', 'File not found.');
        }

        Storage::disk($this->disk)->delete(config('app.name').'/'.$file);

        return back()->with('success', 'Backup deleted.');
    }

    private function getBackups(): array
    {
        $folder = config('app.name');
        $files = Storage::disk($this->disk)->files($folder);
        $backups = [];

        foreach ($files as $file) {
            if (! str_ends_with($file, '.zip')) {
                continue;
            }
            $size = Storage::disk($this->disk)->size($file);
            $backups[] = [
                'filename' => basename($file),
                'size' => $this->formatBytes($size),
                'size_raw' => $size,
                'date' => Carbon::createFromTimestamp(
                    Storage::disk($this->disk)->lastModified($file)
                ),
            ];
        }

        usort($backups, fn ($a, $b) => $b['date']->timestamp - $a['date']->timestamp);

        return $backups;
    }

    private function fileExists(string $filename): bool
    {
        return Storage::disk($this->disk)->exists(config('app.name').'/'.$filename);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
