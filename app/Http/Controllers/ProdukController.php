<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\FotoProduk;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::orderBy('updated_at', 'desc')->get();

        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.create', [
            'judul'    => 'Tambah Produk',
            'kategori' => $kategori,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk',
            'detail'      => 'required',
            'harga'       => 'required|numeric',
            'berat'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'foto'        => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',
        ], [
            'foto.image' => 'Format gambar harus jpeg, jpg, png, atau gif.',
            'foto.max'   => 'Ukuran maksimal gambar adalah 1024 KB.',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status']  = 0;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->processImage($request->file('foto'));
        }

        Produk::create($validated);

        return redirect()
            ->route('backend.produk.index')
            ->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk   = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.show', [
            'judul'    => 'Detail Produk',
            'show'     => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk   = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.edit', [
            'judul'    => 'Ubah Produk',
            'edit'     => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $id,
            'kategori_id' => 'required',
            'status'      => 'required',
            'detail'      => 'required',
            'harga'       => 'required|numeric',
            'berat'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'foto'        => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar harus jpeg, jpg, png, atau gif.',
            'foto.max'   => 'Ukuran maksimal gambar adalah 1024 KB.',
        ];

        $validated = $request->validate($rules, $messages);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            // hapus gambar lama
            $this->deleteImageFiles($produk->foto);
            $validated['foto'] = $this->processImage($request->file('foto'));
        }

        $produk->update($validated);

        return redirect()
            ->route('backend.produk.index')
            ->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus file utama dan thumbnails
        $this->deleteImageFiles($produk->foto);

        // Hapus foto tambahan
        foreach (FotoProduk::where('produk_id', $id)->get() as $foto) {
            $this->deleteImageFiles($foto->foto);
            $foto->delete();
        }

        $produk->delete();

        return redirect()
            ->route('backend.produk.index')
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Store additional photos.
     */
    public function storeFoto(Request $request)
    {
        $request->validate([
            'produk_id'       => 'required|exists:produk,id',
            'foto_produk.*'   => 'image|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $filename = $this->processImage($file, 800, null);
                FotoProduk::create([
                    'produk_id' => $request->produk_id,
                    'foto'      => $filename,
                ]);
            }
        }

        return redirect()
            ->route('backend.produk.show', $request->produk_id)
            ->with('success', 'Foto berhasil ditambahkan.');
    }

    /**
     * Delete a single additional photo.
     */
    public function destroyFoto(string $id)
    {
        $foto      = FotoProduk::findOrFail($id);
        $produkId  = $foto->produk_id;

        $this->deleteImageFiles($foto->foto);
        $foto->delete();

        return redirect()
            ->route('backend.produk.show', $produkId)
            ->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Process upload and create thumbnails.
     */
    private function processImage($file, $lg = null, $md = null)
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $directory = 'storage/img-produk/';

        // Original
        ImageHelper::uploadAndResize($file, $directory, $filename);
        // Thumbnails
        ImageHelper::uploadAndResize($file, $directory, 'thumb_lg_' . $filename, 800, null);
        ImageHelper::uploadAndResize($file, $directory, 'thumb_md_' . $filename, 500, 519);
        ImageHelper::uploadAndResize($file, $directory, 'thumb_sm_' . $filename, 100, 110);

        return $filename;
    }

    /**
     * Delete image and its thumbnails.
     */
    private function deleteImageFiles(?string $filename)
    {
        if (! $filename) {
            return;
        }

        $directory = public_path('storage/img-produk/');
        foreach (['', 'thumb_lg_', 'thumb_md_', 'thumb_sm_'] as $prefix) {
            $path = $directory . $prefix . $filename;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
