<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Helpers\ImageHelper; // Pastikan helper ini sudah dibuat

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();
        
        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        
        return view('backend.v_produk.create', [
            'judul' => 'Tambah Produk',
            'kategori' => $kategori
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   public function store(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'kategori_id' => 'required',
        'nama_produk' => 'required|max:255|unique:produk',
        'detail' => 'required',
        'harga' => 'required',
        'berat' => 'required',
        'stok' => 'required',
        'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
    ], [
        'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
        'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
    ]);

    $validatedData['status'] = 0; // Set default status
    $validatedData['user_id'] = auth()->id(); // ✅ Tambahkan user_id dari user yang login

    // Handle file upload
    if ($request->file('foto')) {
        $file = $request->file('foto');
        $extension = $file->getClientOriginalExtension();
        $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $directory = 'storage/img-produk/';

        // Simpan gambar asli
        ImageHelper::uploadAndResize($file, $directory, $originalFileName);
        $validatedData['foto'] = $originalFileName;

        // Buat thumbnail besar (800px)
        $thumbnailLg = 'thumb_lg_' . $originalFileName;
        ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);

        // Buat thumbnail medium (500x519px)
        $thumbnailMd = 'thumb_md_' . $originalFileName;
        ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);

        // Buat thumbnail kecil (100x110px)
        $thumbnailSm = 'thumb_sm_' . $originalFileName;
        ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
    }

    // Simpan data produk
    Produk::create($validatedData);

    return redirect()->route('backend.produk.index')
        ->with('success', 'Data berhasil tersimpan');
}


    /**
     * Display the specified resource.
     * @param  string  $id
     */
    public function show(string $id)
{
$produk = Produk::with('fotoProduk')->findOrFail($id);
$kategori = Kategori::orderBy('nama_kategori', 'asc')->get(); return view('backend.v_produk.show', [
'judul' => 'Detail Produk', 'show' => $produk,
'kategori' => $kategori ]);
}

    /**
     * Show the form for editing the specified resource.
     * @param  string  $id
     */
    public function edit(string $id)
    {
        // Implementasi bisa ditambahkan di sini
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     */
    public function update(Request $request, string $id)
    {
        // Implementasi bisa ditambahkan di sini
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     */
    public function destroy(string $id)
    {
        // Implementasi bisa ditambahkan di sini
    }
}