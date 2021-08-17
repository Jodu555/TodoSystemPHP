<?php
include('include.php');

$error;
$haserror = false;

if (isset($_SESSION['login'])) {
    sendTo("dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = validate_input($_POST['username']);
            $password = validate_input($_POST['password']);

            if ($username !== "" && $password !== "") {
                $ip = $_SERVER['REMOTE_ADDR'];
                $sql = "SELECT * FROM accounts WHERE username='" . $username . "'";
                $result = $conn->query($sql);
                $error = false;
                while ($row = $result->fetch_assoc()) {
                    if ($row["password"] == $password) {
                        $_SESSION['username'] = $username;
                        $_SESSION['login'] = "true";
                        $_SESSION['role'] = $row['role'];
                        $haserror = false;
                        sendTo("dashboard.php");
                    } else {
                        $haserror = true;
                        $error = "Benutzer konnte nicht gefunden werden oder das Passwort ist falsch!";
                    }
                }
                $haserror = true;
                $error = "Benutzer konnte nicht gefunden werden oder das Passwort ist falsch!";
            } else {
                $haserror = true;
                $error = "Bitte fülle alle Felder des Formulars aus!";
            }
        } else {
            $haserror = true;
            $error = "Bitte fülle alle Felder des Formulars aus!";
        }
    }
}

?>

<body>
    <div class="container">
        <br>
        <?php if ($haserror) {
        ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php
        } ?>
        <br>
        <br>
        <div class="card text-white bg-secondary">
            <div class="card-header">Zum ToDo System Einloggen</div>
            <div class="card-body">
                <h5 class="card-title">Hier kannst du dich zum ToDo System Einloggen!</h5>
                <p class="card-text">Dann kannst du dir alle eingesendeten Themen angucken.</p>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Benutzername</label>
                        <input type="text" class="form-control" id="username" name="username" aria-describedby="username" placeholder="Trage hier deinen Benutzernamen ein!" require>
                    </div>
                    <div class="form-group">
                        <label for="password">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Trage hier dein Passwort ein!">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success btn-lg btn-block">Login</button>
                </form>
            </div>
        </div>
        <br>
        <br>
    </div>
</body>