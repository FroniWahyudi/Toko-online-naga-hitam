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
                      {{-- Gambar --}}
                      <div class="flex-fill me-2">
                        <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}"
                             class="w-100"
                             style="max-height:200px; object-fit:cover;">
                      </div>
                      {{-- Tombol Hapus --}}
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
  const addBtn = document.querySelector('.add-foto');

  addBtn.addEventListener('click', () => {
    const wrapper = document.createElement('div');
    wrapper.classList.add('mb-3');

    // Pindahkan tombol dari DOM dan simpan referensinya
    const movedAddBtn = addBtn;
    movedAddBtn.classList.remove('add-foto'); // agar tidak bind ganda
    movedAddBtn.classList.add('btn-sm', 'mb-2');

    wrapper.innerHTML = `
      <div class="d-flex align-items-start">
        <div class="flex-fill me-2">
          <img src="#" class="upload-preview w-100 mb-2"
               style="display:none; max-height:200px; object-fit:cover;">
          <!-- Tombol Tambah Foto akan disisipkan di sini -->
          <div class="add-btn-placeholder mb-2"></div>
          <input type="file" name="foto_produk[]" class="form-control file-input mb-2" accept="image/*">
          <form action="{{ route('backend.foto_produk.store') }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $show->id }}">
            <button type="submit" class="btn btn-success btn-sm">
              Simpan
            </button>
          </form>
        </div>
        <div>
          <button type="button" class="btn btn-danger btn-sm remove-row">
            Hapus
          </button>
        </div>
      </div>
    `;

    fotoContainer.appendChild(wrapper);

    // Sisipkan tombol ke dalam form baru
    wrapper.querySelector('.add-btn-placeholder').appendChild(movedAddBtn);

    const fileInput = wrapper.querySelector('.file-input');
    const previewImg = wrapper.querySelector('.upload-preview');
    const removeBtn = wrapper.querySelector('.remove-row');

    fileInput.addEventListener('change', function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = e => {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        };
      }
    });

    removeBtn.addEventListener('click', () => {
      wrapper.remove();
      // Kembalikan tombol ke bawah jika ingin
      fotoContainer.parentNode.appendChild(movedAddBtn);
      movedAddBtn.classList.add('add-foto');
    });
  });
});
</script>
@endpush


