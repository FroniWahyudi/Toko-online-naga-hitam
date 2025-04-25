<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>tokoonline</title>
</head>
<body>

  <!-- Navigasi -->
  <nav>
    <a href="{{ route('backend.beranda') }}">Beranda</a> |
    <a href="#">User</a> |
    <a href="#" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">Keluar</a>
  </nav>

  <!-- Konten Halaman -->
  <div>
    @yield('content')
  </div>

  <!-- Form Logout -->
  <form id="keluar-app" action="{{ route('backend.logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

</body>
</html>
