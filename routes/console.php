<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Map images in public/images/barangs to Barang.image field
Artisan::command('map:barang-images', function () {
    $base = public_path('images/barangs/');
    if (! is_dir($base)) {
        $this->error('Directory not found: ' . $base);
        return 1;
    }

    $files = array_values(array_diff(scandir($base), ['.', '..']));
    $assigned = 0;

    foreach (App\Models\Barang::all() as $b) {
        if (! empty($b->image) && file_exists($base . $b->image)) {
            $this->line("Skip #{$b->id} ({$b->name}) — already set: {$b->image}");
            continue;
        }

        $candidates = [];
        if (! empty($b->image)) $candidates[] = $b->image;
        $candidates[] = $b->id . '.jpg';
        $candidates[] = $b->id . '.png';
        $candidates[] = Illuminate\Support\Str::slug($b->name) . '.jpg';
        $candidates[] = Illuminate\Support\Str::slug($b->name) . '.png';

        $found = null;
        foreach ($candidates as $c) {
            if (! empty($c) && file_exists($base . $c)) { $found = $c; break; }
        }

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
})->purpose('Map existing image files to Barang.image');

// List Barang images for debugging
Artisan::command('list:barang-images', function () {
    $this->info("Listing Barang (id | name | image):");
    foreach (App\Models\Barang::orderBy('id')->get() as $b) {
        $this->line("{$b->id} | {$b->name} | " . ($b->image ?? '(null)'));
    }
    return 0;
})->purpose('List Barang images');

Artisan::command('list:loans', function () {
    $this->info('Latest peminjaman records (id | user_id | barang_id | barang_name | image | status):');
    foreach (App\Models\Peminjaman::with('barang','user')->latest('created_at')->take(20)->get() as $p) {
        $this->line("{$p->id} | {$p->user_id} | {$p->barang_id} | " . ($p->barang->name ?? '(no-barang)') . " | " . ($p->barang->image ?? '(null)') . " | {$p->status}");
    }
    return 0;
})->purpose('List recent peminjaman with barang images');

// Fix image path values by stripping directory prefixes and keeping filename only
Artisan::command('fix:barang-image-paths', function () {
    $this->info('Normalizing Barang.image values...');
    $count = 0;
    foreach (App\Models\Barang::all() as $b) {
        if (empty($b->image)) continue;
        $img = $b->image;
        // If image contains a path, normalize to basename
        if (str_contains($img, '/')) {
            $new = basename($img);
            if ($new !== $img) {
                $b->image = $new;
                $b->save();
                $this->line("Updated #{$b->id}: {$img} -> {$new}");
                $count++;
            }
        }
    }
    $this->info("Done. Normalized {$count} records.");
    return 0;
})->purpose('Strip path prefixes from Barang.image values');
