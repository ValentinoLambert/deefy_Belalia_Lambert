<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\db\ConnectionFactory;

/**
 * Action pour ajouter une piste a la playlist.
 */
class AddPodcasttrackAction extends Action {

    /**
     * Methode pour ajouter une piste a la playlist, en utilisant un formulaire.
     * @return string
     */
    public function execute(): string {

        // D'abord vérifier si une playlist est sélectionnée
        if (!isset($_SESSION['playlist']) || !$_SESSION['playlist']) {
            return "<p>Erreur : Aucune playlist courante n'est sélectionnée.</p>";
        }

        // Recuperation de ID playlist
        $playlistId = (int)$_SESSION['playlist'];

        // Verification des autorisations (si admin ou stadard user)
        if (!Auth::checkAccess($playlistId)) {
            return "<p>Erreur : Accès refusé pour cette playlist.</p>";
        }

        // Si la méthode HTTP est POST, traiter les données du formulaire, sinon afficher le formulaire
        if ($this->http_method == "POST") {

            // Recuperation des données du formulaire et filtrage
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_STRING);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
            $duree = (int)$_POST['duree'];
            $date = $_POST['date'];

            // On fais un fichier ou les fichiers seront stockés et on le stock dans le dossier uploads les chansons
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Si un fichier est téléchargé sans erreur
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

                // On recupere le nom du fichier
                $filename = basename($_FILES['file']['name']);
                $uploadFile = $uploadDir . $filename;

                // Déplacez le fichier téléchargé vers le dossier de destination
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {

                    // On se connecte à la base de données
                    $bd = ConnectionFactory::makeConnection();

                    // Ajouter la piste dans la table track
                    $stmt = $bd->prepare("INSERT INTO track (titre, genre, duree, filename, artiste_album, date_posdcast) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $nom);
                    $stmt->bindParam(2, $genre);
                    $stmt->bindParam(3, $duree, \PDO::PARAM_INT);
                    $stmt->bindParam(4, $filename);
                    $stmt->bindParam(5, $artiste);
                    $stmt->bindParam(6, $date);
                    $stmt->execute();

                    // Récupérer l'ID de la piste nouvellement ajoutée
                    $trackId = $bd->lastInsertId();

                    // Déterminer le numéro de piste dans la playlist (no_piste_dans_liste)
                    $query = "SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1 AS next_track_number 
                              FROM playlist2track 
                              WHERE id_pl = ?";
                    $stmt = $bd->prepare($query);
                    $stmt->bindParam(1, $playlistId, \PDO::PARAM_INT);
                    $stmt->execute();
                    $data = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $no_piste_dans_liste = $data['next_track_number'] ?? 1;

                    // Insérer la piste dans la table playlist2track
                    $insert = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)";
                    $stmt = $bd->prepare($insert);
                    $stmt->bindParam(1, $playlistId, \PDO::PARAM_INT);
                    $stmt->bindParam(2, $trackId, \PDO::PARAM_INT);
                    $stmt->bindParam(3, $no_piste_dans_liste, \PDO::PARAM_INT);
                    $stmt->execute();

                    // Finalement on retourne un message de succès
                    return "<p>Piste '$nom' ajoutée avec succès à la playlist.</p>";

                } else {

                    // Dans le cas d'une erreur de déplacement du fichier
                    return "<p>Erreur : Échec du déplacement du fichier vers '$uploadFile'.</p>";

                }
            } else {

                // Dans le cas d'une erreur de téléchargement du fichier
                return "<p>Erreur : Fichier non téléchargé ou erreur de téléchargement.</p>";

            }

        } else {

            // Afficher le formulaire d'ajout de piste
            return <<<HTML
                <form method="post" action="?action=add-podcasttrack" enctype="multipart/form-data">
                    <input type="text" name="nom" placeholder="nom" required autofocus>
                    <input type="text" name="artiste" placeholder="artiste" required>
                    <input type="text" name="genre" placeholder="genre" required>
                    <input type="number" name="duree" placeholder="duree" required>
                    <input type="date" name="date" placeholder="date" required>
                    <input type="file" name="file" accept="audio/mpeg" required>
                    <input type="submit" name="ajouter" value="Ajouter Track">
                </form>
            HTML;
        }
    }
}
