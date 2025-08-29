<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Database Data ===\n";
echo "Users count: " . \App\Models\User::count() . "\n";
echo "Barang count: " . \App\Models\Barang::count() . "\n";
echo "Monitoring count: " . \App\Models\Monitoring::count() . "\n";

echo "\n=== Users Data ===\n";
$users = \App\Models\User::select('id', 'name', 'email', 'role')->get();
foreach($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

echo "\n=== Barang Data ===\n";
$barang = \App\Models\Barang::select('id_barang', 'nama_barang', 'jenis', 'stok')->get();
foreach($barang as $item) {
    echo "ID: {$item->id_barang}, Name: {$item->nama_barang}, Jenis: {$item->jenis}, Stok: {$item->stok}\n";
}
