<?php
session_start();

require 'config.php';

$message = '';
$message_type = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $ip_address = $_SERVER['REMOTE_ADDR'];

    if (empty($username) || empty($password)) {
        $message      = "Kullanıcı adı ve şifre boş bırakılamaz.";
        $message_type = "error";

    } else {

        $hashed_password = hash('sha256', $password);

        $sql    = "SELECT * FROM tbl_user WHERE username = ?";
        $stmt   = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);


        if ($user && $user['password'] === $hashed_password) {
            // DURUM 1: Kimliği doğrulanmış (authenticated) kullanıcı
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            $message      = "✅ Giriş başarılı! Hoş geldiniz, <strong>" . htmlspecialchars($user['username']) . "</strong>. Yönlendiriliyorsunuz...";
            $message_type = "success";


        } elseif ($user && $user['password'] !== $hashed_password) {
            // DURUM 2: Kullanıcı adı mevcut AMA şifre yanlış → FRAUD
            $fraud_sql  = "INSERT INTO fraud (username, ip_address, reason) VALUES (?, ?, ?)";
            $fraud_stmt = mysqli_prepare($conn, $fraud_sql);
            $reason     = "Yanlış şifre girişimi";
            mysqli_stmt_bind_param($fraud_stmt, 'sss', $username, $ip_address, $reason);
            mysqli_stmt_execute($fraud_stmt);

            $message      = "🚨 Yetkisiz erişim girişimi tespit edildi. Bu olay kayıt altına alındı.";
            $message_type = "fraud";

        } else {
            // DURUM 3: Kullanıcı adı sistemde yok → İlk kez deneme (visitor)
            $visitor_sql  = "INSERT INTO visitor (username, ip_address) VALUES (?, ?)";
            $visitor_stmt = mysqli_prepare($conn, $visitor_sql);
            mysqli_stmt_bind_param($visitor_stmt, 'ss', $username, $ip_address);
            mysqli_stmt_execute($visitor_stmt);

            $message      = "🔵 Bu kullanıcı adı sistemde kayıtlı değil. Lütfen önce kayıt olun veya doğru bilgileri girin.";
            $message_type = "visitor";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap — Lab Sheet 4</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d0f14;
            --surface:   #161a22;
            --border:    #252b38;
            --accent:    #4fffb0;
            --accent2:   #00cfff;
            --text:      #e2e8f0;
            --muted:     #64748b;
            --danger:    #ff4d6d;
            --warn:      #f59e0b;
            --info:      #38bdf8;
            --radius:    6px;
            --mono:      'IBM Plex Mono', monospace;
            --sans:      'IBM Plex Sans', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image:
                radial-gradient(ellipse 60% 40% at 70% 20%, rgba(79,255,176,.06) 0%, transparent 70%),
                radial-gradient(ellipse 40% 60% at 20% 80%, rgba(0,207,255,.05) 0%, transparent 70%);
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2.5rem 2rem;
            box-shadow: 0 0 60px rgba(79,255,176,.04);
        }

        .logo {
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--accent);
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: .5rem;
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: .25rem;
        }

        .subtitle {
            font-size: .82rem;
            color: var(--muted);
            margin-bottom: 2rem;
            font-family: var(--mono);
        }

        .alert {
            border-radius: var(--radius);
            padding: .85rem 1rem;
            font-size: .88rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid;
            line-height: 1.5;
        }
        .alert.success { background: rgba(79,255,176,.07); border-color: var(--accent);  color: var(--accent);  }
        .alert.fraud   { background: rgba(255,77,109,.07);  border-color: var(--danger); color: var(--danger); }
        .alert.visitor { background: rgba(56,189,248,.07);  border-color: var(--info);   color: var(--info);   }
        .alert.error   { background: rgba(245,158,11,.07);  border-color: var(--warn);   color: var(--warn);   }

        label {
            display: block;
            font-size: .78rem;
            font-family: var(--mono);
            color: var(--muted);
            letter-spacing: .06em;
            margin-bottom: .4rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--mono);
            font-size: .95rem;
            padding: .7rem .9rem;
            border-radius: var(--radius);
            outline: none;
            transition: border-color .2s;
            margin-bottom: 1.2rem;
        }
        input:focus { border-color: var(--accent); }

        .btn {
            width: 100%;
            padding: .8rem;
            background: var(--accent);
            color: #0d0f14;
            font-family: var(--mono);
            font-size: .95rem;
            font-weight: 600;
            letter-spacing: .05em;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: opacity .15s, transform .1s;
        }
        .btn:hover  { opacity: .88; }
        .btn:active { transform: scale(.98); }

        .hash-info {
            margin-top: 1.5rem;
            padding: .75rem;
            background: rgba(255,255,255,.03);
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--muted);
            word-break: break-all;
        }
        .hash-info span { color: var(--accent2); }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        .test-creds {
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--muted);
            line-height: 1.8;
        }
        .test-creds strong { color: var(--text); }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">// auth_system v1.0</div>
    <h1>Giriş Yap</h1>
    <p class="subtitle">SHA-256 şifreleme ile güvenli kimlik doğrulama</p>

    <?php if ($message): ?>
    <div class="alert <?= htmlspecialchars($message_type) ?>">
        <?= $message ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="username">KULLANICI ADI</label>
        <input type="text" id="username" name="username"
               placeholder="kullanıcı_adı"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               autocomplete="off" required>

        <label for="password">ŞİFRE</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••" required>

        <button class="btn" type="submit">GİRİŞ YAP →</button>
    </form>

    <div class="hash-info">
        SHA-256("password123") =<br>
        <span>ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f</span>
    </div>

    <hr class="divider">

    <div class="test-creds">
        Test kullanıcıları:<br>
        <strong>admin</strong> / password123 → ✅ Authenticated<br>
        <strong>ahmet</strong> / ahmet123 &nbsp; → ✅ Authenticated<br>
        <strong>admin</strong> / yanlisşifre → 🚨 Fraud<br>
        <strong>yeni_biri</strong> / herhangi → 🔵 Visitor
    </div>
</div>
</body>
</html>
