<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPLELAB - Demo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/simplelab.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
<div class="app-shell">
    @include('partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="brand">SIMPLELAB</div>
            <div class="top-actions">
                <div class="avatar">
                    <div class="user">Admin Laboran</div>
                </div>
            </div>
        </header>
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>
</div>
<script src="/js/simplelab.js"></script>
</body>
</html>
