<?php

// On définit la racine du projet
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

// Inclusion des classes de base
require_once(ROOT . 'app/Model.php');
require_once(ROOT . 'app/Controller.php');

// Vérification de l’existence du paramètre "p"
$p = isset($_GET['p']) ? trim($_GET['p']) : '';
$params = explode('/', filter_var($p, FILTER_SANITIZE_URL));

// Définition du contrôleur et de l'action
$controllerName = !empty($params[0]) ? ucfirst($params[0]) : 'Main';
$action = isset($params[1]) ? $params[1] : 'index';

// Vérification de l'existence du fichier du contrôleur
$controllerFile = ROOT . 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once($controllerFile);

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $action)) {
            unset($params[0], $params[1]); // Supprimer les éléments déjà utilisés
            call_user_func_array([$controller, $action], $params);
        } else {
            notFound("L'action '$action' n'existe pas dans le contrôleur '$controllerName'.");
        }
    } else {
        notFound("Le contrôleur '$controllerName' n'existe pas.");
    }
} else {
    notFound("Le fichier du contrôleur '$controllerName.php' est introuvable.");
}

// Fonction pour gérer les erreurs 404
function notFound($message = "Page introuvable.")
{
    http_response_code(404);
    echo "<h1>Erreur 404</h1><p>$message</p>";
    exit;
}

