<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$columns = Illuminate\Support\Facades\DB::select("
    SELECT table_name, column_name, data_type, is_nullable 
    FROM information_schema.columns 
    WHERE table_schema = 'public' 
    ORDER BY table_name, ordinal_position
");

echo json_encode($columns, JSON_PRETTY_PRINT);
