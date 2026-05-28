<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('flow-builder', [
        'templates' => [
            [
                'type' => 'message',
                'label' => 'Pesan',
                'description' => 'Kirim teks WhatsApp ke pelanggan.',
                'payload' => 'Halo {{nama}}, ada yang bisa kami bantu?',
            ],
            [
                'type' => 'question',
                'label' => 'Pertanyaan',
                'description' => 'Minta pelanggan memilih balasan.',
                'payload' => 'Silakan pilih kebutuhan Anda.',
            ],
            [
                'type' => 'condition',
                'label' => 'Kondisi',
                'description' => 'Cabangkan alur berdasarkan jawaban.',
                'payload' => 'Jika pelanggan memilih opsi tertentu.',
            ],
            [
                'type' => 'handover',
                'label' => 'Handover',
                'description' => 'Teruskan percakapan ke agent.',
                'payload' => 'Percakapan diteruskan ke agent.',
            ],
        ],
    ]);
})->name('flow-builder');

Route::post('/flows/preview', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:80'],
        'nodes' => ['required', 'array', 'min:1'],
        'nodes.*.id' => ['required', 'string', 'max:40'],
        'nodes.*.type' => ['required', 'string', 'max:40'],
        'nodes.*.title' => ['required', 'string', 'max:80'],
        'nodes.*.message' => ['nullable', 'string', 'max:1000'],
    ]);

    return response()->json([
        'status' => 'valid',
        'flow' => $validated,
        'summary' => [
            'nodes' => count($validated['nodes']),
            'channels' => ['whatsapp'],
        ],
    ]);
});
