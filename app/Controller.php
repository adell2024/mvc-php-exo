<?php
abstract class Controller {
    /**
     * Afficher une vue
     *
     * @param string $fichier
     * @param array $data
     * @return void
     */
    public function render(string $fichier, array $data = []) {
        extract($data);

        // Chemin de la vue
        $viewPath = ROOT . 'views/' . strtolower(get_class($this)) . '/' . $fichier . '.php';

        // Vérification de l'existence de la vue
        if (!file_exists($viewPath)) {
            die("Erreur : La vue '$fichier.php' est introuvable.");
        }

        // On démarre le buffer de sortie
        ob_start();

        // On inclut la vue
        require_once($viewPath);

        // On stocke le contenu dans $content
        $content = ob_get_clean();

        // Vérification du layout
        $layoutPath = ROOT . 'views/layout/default.php';
        if (!file_exists($layoutPath)) {
            die("Erreur : Le fichier de layout est introuvable.");
        }

        // On inclut le layout principal
        require_once($layoutPath);
    }

    /**
     * Permet de charger un modèle
     *
     * @param string $model
     * @return void
     */
    public function loadModel(string $model) {
        // Chemin du fichier du modèle
        $modelPath = ROOT . 'models/' . $model . '.php';

        // Vérification de l'existence du fichier du modèle
        if (!file_exists($modelPath)) {
            die("Erreur : Le modèle '$model' est introuvable.");
        }

        require_once($modelPath);

        // Vérification de l'existence de la classe du modèle
        if (!class_exists($model)) {
            die("Erreur : La classe du modèle '$model' n'existe pas.");
        }

        // Vérification si la propriété n'existe pas déjà pour éviter les conflits
        if (!property_exists($this, $model)) {
            $this->$model = new $model();
        } else {
            die("Erreur : La propriété '$model' existe déjà dans le contrôleur.");
        }
    }
}
