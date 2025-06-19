<?php
require 'db.php';
require 'auth.php';

// Als al ingelogd, doorsturen naar dashboard
if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Validatie
    if (empty($email) || empty($username) || empty($password)) {
        $error = "Alle velden zijn verplicht";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig e-mailadres";
    } elseif (strlen($password) < 8) {
        $error = "Wachtwoord moet minimaal 8 tekens bevatten";
    } elseif ($password !== $confirm) {
        $error = "Wachtwoorden komen niet overeen";
    } else {
        // Controleer of email al bestaat
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Dit e-mailadres is al geregistreerd";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, username, password_hash, role) VALUES (?, ?, ?, 'user')");
            
            if ($stmt->execute([$email, $username, $hash])) {
                $message = "Registratie succesvol! Je kunt nu <a href='login.php'>inloggen</a>.";
            } else {
                $error = "Registratie mislukt. Probeer het later opnieuw.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Energy - Registreren</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .register-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-header {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #2980b9;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .link {
            text-align: center;
            margin-top: 15px;
            color: #555;
        }
        .link a {
            color: #3498db;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">Registreren</div>
        
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="message success"><?= $message ?></div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Wachtwoord (minimaal 8 tekens)</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirm">Bevestig wachtwoord</label>
                    <input type="password" id="confirm" name="confirm" required minlength="8">
                </div>
                
                <button type="submit">Registreren</button>
            </form>
            
            <p class="link">Heb je al een account? <a href="login.php">Log hier in</a></p>
        <?php endif; ?>
    </div>
</body>
</html>