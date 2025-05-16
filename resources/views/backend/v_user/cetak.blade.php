<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table th,
    table td {
        padding: 6px;
        border: 1px solid #ccc;
    }

    table th {
        font-weight: bold;
        background-color: #f2f2f2;
        text-align: left;
    }
</style>

<table>
    {{-- Header Gambar (Opsional) --}}
    {{--
    <tr>
        <td align="center">
            <img src="{{ asset('images/header.png') }}" width="50%">
        </td>
    </tr>
    --}}
    <tr>
        <td align="left">
            <strong>Perihal:</strong> {{ $judul }}<br>
            <strong>Tanggal Awal:</strong> {{ $tanggalAwal }} s/d <strong>Tanggal Akhir:</strong> {{ $tanggalAkhir }}
        </td>
    </tr>
</table>

<p></p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Email</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cetak as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->nama }}</td>
                <td>
                    @if ($row->role == 1)
                        Super Admin
                    @elseif($row->role == 0)
                        Admin
                    @endif
                </td>
                <td>
                    @if ($row->status == 1)
                        Aktif
                    @elseif($row->status == 0)
                        NonAktif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    window.onload = function () {
        printStruk();
    };

    function printStruk() {
        window.print();
    }
</script>
