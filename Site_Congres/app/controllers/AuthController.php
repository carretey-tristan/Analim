<?php
require_once __DIR__."/../ApiClient.php";

class AuthController {
    public function register() {
        $api = new ApiClient();
        $result = null;
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $api->register(
                $_POST['email'],
                $_POST['password'],
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['profession'],
                $_POST['ville']
            );
            if ($result && $result['success']) {
                $participant = $api->login($_POST['email'], $_POST['password']);
                if ($participant && $participant['success']) {
                    $_SESSION['participant_id'] = $participant['id'];
                    $_SESSION['email'] = $_POST['email'];
                    
                    header("Location: index.php?c=conference&a=list");
                    exit();
                } else {
                    $error = "Inscription réussie mais connexion impossible.";
                }
            } else {
                $error = "Échec de l'inscription. Veuillez réessayer.";
            }
        }
        include __DIR__ . '/../views/layout.php';
    }
    public function login() {
        $error = null;
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $api = new ApiClient();
            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];
            $participant = $api->login($data['email'], $data['password']);
            if ($participant && isset($participant['success']) && $participant['success'] === true) {
                $_SESSION['participant_id'] = $participant['id'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['password'] = $data['password'];
                header("Location: index.php?c=conference&a=list");
                exit();
            } else {
                $error = "Échec de la connexion. Vérifiez vos identifiants.";
            }
        }
        include __DIR__ . '/../views/layout.php';
    }
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?c=auth&a=login");
        exit();
    }
}
?>
<?php
if (isset($result['message'])) {
    echo "<div>{$result['message']}</div>";
}
?>