<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use Illuminate\Support\Str;

class MapBarangImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:barang-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map images in public/images/barangs to Barang.image field automatically';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $base = public_path('images/barangs/');
        if (! is_dir($base)) {
            $this->error('Directory not found: ' . $base);
            return 1;
        }

        $files = array_values(array_diff(scandir($base), ['.', '..']));
        $assigned = 0;

        foreach (Barang::all() as $b) {
            // if already has a valid image, skip
            if (! empty($b->image) && file_exists($base . $b->image)) {
                $this->line("Skip #{$b->id} ({$b->name}) — already set: {$b->image}");
                continue;
            }

            $candidates = [];
            if (! empty($b->image)) $candidates[] = $b->image;
            $candidates[] = $b->id . '.jpg';
            $candidates[] = $b->id . '.png';
            $candidates[] = Str::slug($b->name) . '.jpg';
            $candidates[] = Str::slug($b->name) . '.png';

            $found = null;
            foreach ($candidates as $c) {
                if (! empty($c) && file_exists($base . $c)) { $found = $c; break; }
            }

            // fuzzy fallback: try to match by containing name parts or id
            if (! $found) {
                $plain = preg_replace('/[^a-z0-9]+/i', '', strtolower($b->name));
                foreach ($files as $f) {
                    $lf = strtolower($f);
                    if ($plain && strpos($lf, $plain) !== false) { $found = $f; break; }
                    if (strpos($lf, (string)$b->id) !== false) { $found = $f; break; }
                }
            }

            if ($found) {
                $b->image = $found;
                $b->save();
                $this->info("Assigned {$found} to Barang #{$b->id} ({$b->name})");
                $assigned++;
            } else {
                $this->line("No image found for Barang #{$b->id} ({$b->name})");
            }
        }

        $this->info("Done. Assigned {$assigned} images.");
        return 0;
    }
}
