@extends('backend.v_layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form 
                    action="{{ route('backend.produk.update', $edit->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4>

                        <div class="row">
                            {{-- Kolom Foto --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>

                                    {{-- Preview Foto --}}
                                    @if ($edit->foto)
                                        <img 
                                            src="{{ asset('storage/img-produk/' . $edit->foto) }}" 
                                            class="img-fluid mb-2 foto-preview" 
                                            alt="Preview Foto"
                                        >
                                    @else
                                        <img 
                                            src="{{ asset('storage/img-produk/img-default.jpg') }}" 
                                            class="img-fluid mb-2 foto-preview" 
                                            alt="Default Foto"
                                        >
                                    @endif

                                    {{-- Input File --}}
                                    <input 
                                        type="file" 
                                        name="foto" 
                                        class="form-control @error('foto') is-invalid @enderror" 
                                        onchange="previewFoto()"
                                    >
                                    @error('foto')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kolom Data Produk --}}
                            <div class="col-md-8">
                                {{-- Status --}}
                                <div class="form-group">
                                    <label>Status</label>
                                    <select 
                                        name="status" 
                                        class="form-control @error('status') is-invalid @enderror"
                                    >
                                        <option value="">- Pilih Status -</option>
                                        <option 
                                            value="1" 
                                            {{ old('status', $edit->status) == '1' ? 'selected' : '' }}
                                        >
                                            Public
                                        </option>
                                        <option 
                                            value="0" 
                                            {{ old('status', $edit->status) == '0' ? 'selected' : '' }}
                                        >
                                            Blok
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select 
                                        name="kategori_id" 
                                        class="form-control @error('kategori_id') is-invalid @enderror"
                                    >
                                        <option value="">- Pilih Kategori -</option>
                                        @foreach ($kategori as $row)
                                            <option 
                                                value="{{ $row->id }}" 
                                                {{ old('kategori_id', $edit->kategori_id) == $row->id ? 'selected' : '' }}
                                            >
                                                {{ $row->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Nama Produk --}}
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input
                                        type="text"
                                        name="nama_produk"
                                        value="{{ old('nama_produk', $edit->nama_produk) }}"
                                        class="form-control @error('nama_produk') is-invalid @enderror"
                                        placeholder="Masukkan Nama Produk"
                                    >
                                    @error('nama_produk')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Detail --}}
                                <div class="form-group">
                                    <label>Detail</label>
                                    <textarea
                                        name="detail"
                                        id="ckeditor"
                                        class="form-control @error('detail') is-invalid @enderror"
                                    >{{ old('detail', $edit->detail) }}</textarea>
                                    @error('detail')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Harga --}}
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input
                                        type="text"
                                        name="harga"
                                        onkeypress="return hanyaAngka(event)"
                                        value="{{ old('harga', $edit->harga) }}"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        placeholder="Masukkan Harga Produk"
                                    >
                                    @error('harga')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Berat --}}
                                <div class="form-group">
                                    <label>Berat (gram)</label>
                                    <input
                                        type="text"
                                        name="berat"
                                        onkeypress="return hanyaAngka(event)"
                                        value="{{ old('berat', $edit->berat) }}"
                                        class="form-control @error('berat') is-invalid @enderror"
                                        placeholder="Masukkan Berat Produk"
                                    >
                                    @error('berat')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Stok --}}
                                <div class="form-group">
                                    <label>Stok</label>
                                    <input
                                        type="text"
                                        name="stok"
                                        onkeypress="return hanyaAngka(event)"
                                        value="{{ old('stok', $edit->stok) }}"
                                        class="form-control @error('stok') is-invalid @enderror"
                                        placeholder="Masukkan Stok Produk"
                                    >
                                    @error('stok')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer tombol --}}
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">
                                Perbaharui
                            </button>
                            <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load CKEditor --}}
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.querySelector('#ckeditor');
        if (el) {
            ClassicEditor
                .create(el, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', 'undo', 'redo'
                    ]
                })
                .catch(error => console.error('CKEditor init error:', error));
        }
    });

    function previewfoto() {
        const input = document.querySelector('input[name="foto"]');
        const preview = document.querySelector('.foto-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

