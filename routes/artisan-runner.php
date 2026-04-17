<?php

// Pastikan kita berada di direktori root Laravel
chdir(dirname(__FILE__));

// Jalankan Artisan command secara manual
$output = [];

echo "<pre>";

echo "Running config:clear...\n";
exec('php artisan config:clear', $output);
echo implode("\n", $output) . "\n\n";

echo "Running cache:clear...\n";
exec('php artisan cache:clear', $output);
echo implode("\n", $output) . "\n\n";

echo "Running route:clear...\n";
exec('php artisan route:clear', $output);
echo implode("\n", $output) . "\n\n";

echo "Running view:clear...\n";
exec('php artisan view:clear', $output);
echo implode("\n", $output) . "\n\n";

echo "All commands executed.\n";
echo "</pre>";