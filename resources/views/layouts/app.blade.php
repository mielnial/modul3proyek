<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Activity Manager</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; padding: 0 16px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; }
        .status-Planned { background: #eee; }
        .status-Ongoing { background: #fff3cd; }
        .status-Done { background: #d4edda; }
        a { color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('activities.index') }}">Daftar Kegiatan</a>
    </nav>
    <hr>
    @yield('content')
</body>
</html>