@extends('backend.v_layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">{{ $judul }}</h4>

          <div class="row">
            {{-- Kiri: Detail Produk --}}
            <div class="col-md-6">
              <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" disabled>
                  <option>- Pilih Kategori -</option>
                  @foreach($kategori as $row)
                    <option value="{{ $row->id }}"
                      {{ $show->kategori_id == $row->id ? 'selected' : '' }}>
                      {{ $row->nama_kategori }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" class="form-control" disabled value="{{ $show->nama_produk }}">
              </div>
              <div class="form-group">
                <label>Detail</label>
                <div class="form-control" style="height:auto; background-color:#e9ecef;">
                  {!! $show->detail !!}
                </div>
              </div>
            </div>

            {{-- Kanan: Foto Produk --}}
            <div class="col-md-6">
              {{-- Foto Utama --}}
              <div class="form-group">
                <label>Foto Utama</label><br>
                <img src="{{ asset('storage/img-produk/' . $show->foto) }}"
                     class="mb-3 w-100"
                     style="max-height:300px; object-fit:cover;">
              </div>

              {{-- Foto Tambahan --}}
              <div class="form-group">
                <label>Foto Tambahan</label>
                <div id="foto-container">
                  @foreach($show->fotoProduk as $gambar)
                    <div class="d-flex mb-3 align-items-start">
                      <div class="flex-fill me-2">
                        <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}"
                             class="w-100"
                             style="max-height:200px; object-fit:cover;">
                      </div>
                      <div>
                        <form action="{{ route('backend.foto_produk.destroy', $gambar->id) }}"
                              method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                                  class="btn btn-danger btn-sm"
                                  onclick="return confirm('Yakin hapus foto ini?')">
                            Hapus
                          </button>
                        </form>
                      </div>
                    </div>
                  @endforeach
                </div>

                {{-- Tombol Tambah Foto --}}
                <button type="button" class="btn btn-primary add-foto">
                  Tambah Foto
                </button>
              </div>
            </div>
          </div>

        </div>
        <div class="border-top">
          <div class="card-body">
            <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fotoContainer = document.getElementById('foto-container');
  const addBtn        = document.querySelector('.add-foto');

  addBtn.addEventListener('click', () => {
    // jika sudah ada baris aktif, abaikan
    if (fotoContainer.querySelector('.dynamic-row')) return;

    // sembunyikan tombol Tambah Foto
    addBtn.style.display = 'none';

    // buat wrapper baru
    const wrapper = document.createElement('div');
    wrapper.classList.add('d-flex', 'mb-3', 'align-items-start', 'dynamic-row');

    wrapper.innerHTML = `
      <div class="flex-fill me-2">
        <img src="#" class="upload-preview w-100 mb-2"
             style="display:none; max-height:200px; object-fit:cover;">
        <input type="file" name="foto_produk[]" class="form-control mb-2 file-input" accept="image/*">
        <div class="invalid-feedback d-block" style="display:none;"></div>
        <form action="{{ route('backend.foto_produk.store') }}"
              method="POST" enctype="multipart/form-data" class="d-inline-block upload-form">
          @csrf
          <input type="hidden" name="produk_id" value="{{ $show->id }}">
          <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </form>
      </div>
      <div>
        <button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button>
      </div>
    `;

    fotoContainer.appendChild(wrapper);

    const fileInput     = wrapper.querySelector('.file-input');
    const previewImg    = wrapper.querySelector('.upload-preview');
    const removeBtn     = wrapper.querySelector('.btn-remove');
    const form          = wrapper.querySelector('.upload-form');
    const feedbackDiv   = wrapper.querySelector('.invalid-feedback');

    // preview gambar saat pilih file
    fileInput.addEventListener('change', function () {
      feedbackDiv.style.display = 'none';
      feedbackDiv.textContent = '';

      if (!this.files[0]) return;
      const reader = new FileReader();
      reader.onload = e => {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
      };
      reader.readAsDataURL(this.files[0]);
    });

    // validasi sebelum submit: periksa ada file
    form.addEventListener('submit', function (e) {
      if (!fileInput.value) {
        e.preventDefault();
        feedbackDiv.textContent = 'Silakan pilih file gambar terlebih dahulu!';
        feedbackDiv.style.display = 'block';
      }
    });

    // tombol Hapus: hapus baris dan tampilkan kembali tombol Tambah Foto
    removeBtn.addEventListener('click', () => {
      wrapper.remove();
      addBtn.style.display = 'inline-block';
    });
  });
});
</script>
@endpush
