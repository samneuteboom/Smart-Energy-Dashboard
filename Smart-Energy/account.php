<?php
// Start session with proper configuration
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

try {
    require 'config.php';
    
    // Check database connection
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception("Database connection failed or not properly initialized");
    }

    // Redirect to login if not logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['return_url'] = 'account.php';
        header('Location: login.php');
        exit;
    }

    // Get user data
    $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Database query preparation failed");
    }
    
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $message = '';
    $success = '';

    // Form processing
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        
        // 1. Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $message = 'Huidig wachtwoord is incorrect';
        } else {
            try {
                $pdo->beginTransaction();
                
                // 2. Update basic info
                if ($new_username !== $user['username'] || $new_email !== $user['email']) {
                    // Check if new username already exists
                    $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                    $check_stmt->execute([$new_username, $_SESSION['user_id']]);
                    
                    if ($check_stmt->rowCount() > 0) {
                        $message = 'Gebruikersnaam is al in gebruik';
                    } else {
                        $update_stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                        $update_stmt->execute([$new_username, $new_email, $_SESSION['user_id']]);
                        $_SESSION['username'] = $new_username;
                    }
                }
                
                // 3. Update password if provided
                if (!empty($new_password)) {
                    if (strlen($new_password) < 8) {
                        $message = 'Wachtwoord moet minimaal 8 tekens bevatten';
                    } else {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")
                            ->execute([$hashed_password, $_SESSION['user_id']]);
                    }
                }
                
                if (empty($message)) {
                    $pdo->commit();
                    $success = 'Account succesvol bijgewerkt!';
                    // Refresh user data
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                } else {
                    $pdo->rollBack();
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Database error: " . $e->getMessage());
                $message = 'Database fout bij bijwerken: ' . $e->getMessage();
            }
        }
    }
} catch (Exception $e) {
    die("Er is een ernstige fout opgetreden: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountinstellingen - <?= htmlspecialchars($user['username']) ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="./css/account.css">
    <style>
        
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        
        <div class="off-screen-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <nav>
            <div class="ham-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </nav>
    
    <div id="menuOverlay"></div>
    
    <div class="account-container">
        <div class="account-header">
            <div class="account-avatar">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            </div>
            <div>
                <h1 class="account-title"><?= htmlspecialchars($user['username']) ?></h1>
                <!-- <p class="account-subtitle">Lid sinds <?= date('j F Y', strtotime($user['created_at'])) ?></p> -->
            </div>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Gebruikersnaam</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Emailadres</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Huidig wachtwoord (verplicht voor wijzigingen)</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nieuw wachtwoord (laat leeg om niet te wijzigen)</label>
                <input type="password" name="new_password" class="form-control" id="newPassword">
                <div class="password-strength">
                    <div class="strength-meter" id="passwordStrength"></div>
                </div>
                <small class="text-muted">Minimaal 8 tekens, bij voorkeur met hoofdletters, cijfers en symbolen</small>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Wijzigingen opslaan
            </button>
        </form>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('passwordStrength');
            let strength = 0;
            
            if (password.length > 0) strength += 1;
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            const width = strength * 20;
            let color;
            
            if (strength <= 1) color = '#ff0000';
            else if (strength <= 3) color = '#ff9900';
            else color = '#00cc00';
            
            strengthMeter.style.width = width + '%';
            strengthMeter.style.background = color;
        });

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const hamMenu = document.querySelector(".ham-menu");
            const offScreenMenu = document.querySelector(".off-screen-menu");
            const menuOverlay = document.getElementById("menuOverlay");
            const body = document.body;

            const toggleMenu = () => {
                hamMenu.classList.toggle("active");
                offScreenMenu.classList.toggle("active");
                menuOverlay.classList.toggle("active");
                
                if(offScreenMenu.classList.contains('active')) {
                    body.style.overflow = 'hidden';
                } else {
                    body.style.overflow = '';
                }
            };

            hamMenu.addEventListener("click", function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            menuOverlay.addEventListener('click', toggleMenu);
            document.addEventListener('click', function(e) {
                if(!offScreenMenu.contains(e.target) && e.target !== hamMenu) {
                    if(offScreenMenu.classList.contains('active')) {
                        toggleMenu();
                    }
                }
            });

            offScreenMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>