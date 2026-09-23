<?php
$hashEnBD = password_hash('secreto123', PASSWORD_DEFAULT);

$usuariosGuardados = [
    'Bairon' => password_hash('2365', PASSWORD_DEFAULT),
    'Brayan' => password_hash('1234', PASSWORD_DEFAULT),
    'Fernando' => password_hash('7434', PASSWORD_DEFAULT)
];

$usuarioForm = $_POST['usuario'] ?? '';
$passwordForm = $_POST['password'] ?? '';

if (isset($usuariosGuardados[$usuarioForm]) && password_verify($passwordForm, $usuariosGuardados[$usuarioForm])) {
    session_start();
    
    $_SESSION['usuario_logeado'] = $usuarioForm;
    
    header("Location: dashboard.php");
    exit;
} else {
    echo "Usuario o contraseña incorrectos.";
}

?>