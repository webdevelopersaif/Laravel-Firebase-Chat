<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">User List</h1>

        <!-- User List with Bootstrap styling -->
        <ul class="list-group">
            @foreach ($users as $userId => $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $user['name'] }}
                    <a href="{{ route('chat.show', $userId) }}" class="text-decoration-none"><span class="badge bg-primary rounded-pill">Chat</span></a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
