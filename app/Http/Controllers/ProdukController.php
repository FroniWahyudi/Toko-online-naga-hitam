<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\FotoProduk;
use App\Helpers\ImageHelper;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk.
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
     * Menampilkan form tambah produk.
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
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_id'   => 'required',
            'nama_produk'   => 'required|max:255|unique:produk',
            'detail'        => 'required',
            'harga'         => 'required',
            'berat'         => 'required',
            'stok'          => 'required',
            'foto'          => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',
        ], [
            'foto.image'    => 'Format gambar harus jpeg, jpg, png, atau gif.',
            'foto.max'      => 'Ukuran maksimal gambar adalah 1024 KB.'
        ]);

        $validatedData['status'] = 0;
        $validatedData['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';

            // Simpan gambar utama
            ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $originalFileName;

            // Thumbnail 800px
            ImageHelper::uploadAndResize($file, $directory, 'thumb_lg_' . $originalFileName, 800, null);

            // Thumbnail 500x519px
            ImageHelper::uploadAndResize($file, $directory, 'thumb_md_' . $originalFileName, 500, 519);

            // Thumbnail 100x110px
            ImageHelper::uploadAndResize($file, $directory, 'thumb_sm_' . $originalFileName, 100, 110);
        }

        Produk::create($validatedData);

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(string $id)
    {
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.show', [
            'judul'    => 'Detail Produk',
            'show'     => $produk,
            'kategori' => $kategori
        ]);
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(string $id)
    {
        // Tambahkan logika edit jika diperlukan
    }

    /**
     * Update data produk.
     */
    public function update(Request $request, string $id)
    {
        // Tambahkan logika update jika diperlukan
    }

    /**
     * Hapus produk.
     */
    public function destroy(string $id)
    {
        // Tambahkan logika hapus jika diperlukan
    }

    /**
     * Simpan foto tambahan untuk produk.
     */
    public function storeFoto(Request $request)
    {
        $request->validate([
            'produk_id'       => 'required|exists:produk,id',
            'foto_produk.*'   => 'image|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;
                $directory = 'storage/img-produk/';

                // Simpan dan resize gambar
                ImageHelper::uploadAndResize($file, $directory, $filename, 800, null);

                // Simpan ke database
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
     * Hapus foto produk tambahan.
     */
    public function destroyFoto($id)
    {
        $foto = FotoProduk::findOrFail($id);
        $produkId = $foto->produk_id;

        $imagePath = public_path('storage/img-produk/') . $foto->foto;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $foto->delete();

        return redirect()
            ->route('backend.produk.show', $produkId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
