<?php
// Standalone login page - completely outside Laravel middleware
session_start();

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Try to connect to Laravel's database
    try {
        // Load Laravel
        require dirname(__DIR__) .'/vendor/autoload.php';
        $app = require dirname(__DIR__) .'/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();
        
        // Try to login
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            $error = 'User not found';
        } elseif (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            $error = 'Invalid password';
        } else {
            // Set session manually
            $_SESSION['user_id'] = $user->id;
            $_SESSION['authenticated'] = true;
            $success = true;
            echo "<script>window.location.href = '/dashboard';</script>";
            exit;
        }
    } catch (\Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standalone Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card" style="width: 400px;">
        <div class="card-body">
            <h3 class="text-center mb-4">Standalone Login Test</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="test@test.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" value="12345" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login (Standalone)</button>
            </form>
            <hr>
            <p class="text-center text-muted small">This page is completely outside Laravel middleware</p>
        </div>
    </div>
</body>
</html>
