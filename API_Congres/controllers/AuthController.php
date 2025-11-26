<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repository/AuthRepository.php';

class AuthController extends BaseController {
    private $authRepo;
    public function __construct(PDO $db) {
        $this->authRepo = new AuthRepository($db);
    }

    public function showRegister(array $post) {
        $error = null;
        if (!empty($post)) {
            $email = $post['email'] ?? '';
            $existing = $this->authRepo->findByEmail($email);
            if ($existing) $error = 'Email déjà utilisé';
            else {
                $hash = password_hash($post['password'], PASSWORD_DEFAULT);
                $res = $this->authRepo->create([
                    'nom' => $post['nom'] ?? '',
                    'prenom' => $post['prenom'] ?? '',
                    'adresse' => $post['adresse'] ?? '',
                    'email' => $email,
                    'password' => $hash,
                    'acompte' => 0,
                    'supplement' => 0,
                    'nb_etoile' => 0
                ]);
                if (isset($res['id'])) {
                    session_start();
                    $_SESSION['user_id'] = $res['id'];
                    $_SESSION['email'] = $post['email'];
                    // default: only admin (id 25) is an agent; extend your logic as needed
                    $_SESSION['is_agent'] = ($_SESSION['user_id'] == 25);
                    header('Location: index.php?c=account&a=monespace'); exit;
                } else $error = 'Erreur création';
            }
        }
        $this->render('auth/register', ['error' => $error]);
    }

    public function showLogin(array $post) {
        $error = null;
        if (!empty($post)) {
            $email = $post['email'] ?? '';
            $user = $this->authRepo->findByEmail($email);
            if (!$user || !password_verify($post['password'] ?? '', $user->password ?? '')) {
                $error = 'Email ou mot de passe invalide';
            } else {
                session_start();
                $_SESSION['user_id'] = $user->id_congressiste;
                $_SESSION['email'] = $user->email;
                // mark agent if admin id 25
                $_SESSION['is_agent'] = ($user->id_congressiste == 25);
                header('Location: index.php?c=account&a=monespace'); exit;
            }
        }
        $this->render('auth/login', ['error' => $error]);
    }
}
