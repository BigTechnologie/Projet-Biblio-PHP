<?php
// Inclusion des dépendances
require_once '../security/security.php'; // sécuriser l’accès
require_once '../../helper/form_helper.php'; // gérer les formulaires
require_once '../../helper/bdd.php'; // établir la connexion à la base
require_once '../../helper/response.php'; // générer des réponses/retours
require_once '../../validator/validators.php'; // valider les champs
require_once '../../helper/request.php'; // lire les données de la requête
require_once '../../helper/session.php'; // gérer les sessions et messages flash
require_once '../../helper/debug.php'; // déboguer

$c = connection(); // est la connexion à la base de données.
$errors = []; // J'initialise un tableau qui stockera les messages d’erreurs de validation.

// Récupérer les données d'un formulaire avec la superglobale $_POST (9.1.1)
// 3. Vérifie si la méthode HTTP utilisée pour accéder à la page est une méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $titre = request('titre'); // Récupère la valeur du champ 'titre' de la requête.
    $resume = request('resume'); // Récupère la valeur du champ 'resume' de la requête.
    $date_parution = request('date_parution'); // Récupère la valeur du champ 'date_parution' de la requête.
    $image_path = null;

    
    if (!notBlank($titre)) { // Vérifie si le champ 'titre' n'est pas vide.
        $errors['titre'][] = "Le titre est obligatoire"; // Ajoute un message d'erreur au tableau $errors si le titre est vide.
    }
  
   
    if (!empty($_FILES['image']['name'])) { // $_FILES['image']['name'] est le nom original du fichier choisi par l’utilisateur (ex. : "couverture.jpg").
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif']; // $allowed_types est un tableau contenant les types MIME autorisés pour les images.
        $max_size = 2 * 1024 * 1024; // 2 Mo max // On définit la taille maximale du fichier autorisée.
        // 1024 = nombre d’octets dans 1 kilo-octet (Ko). 1024 * 1024 = 1 méga-octet (Mo), soit 1 048 576 octets. 2 * 1024 * 1024 = 2 097 152 octets, soit 2 Mo.

         // Lire et sauvegarder des fichiers uploadés ---
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) { // $_FILES['image']['error'] contient le code d'erreur de l'upload. Elle indique que le fichier a été uploadé avec succès, sans aucune erreur.
            $file_tmp = $_FILES['image']['tmp_name']; // $_FILES['image']['tmp_name'] est le nom du fichier temporaire sur le serveur.
            $file_name = basename($_FILES['image']['name']); // $_FILES['image']['name'] est le nom original du fichier choisi par l’utilisateur (ex. : "couverture.jpg").
            $file_size = $_FILES['image']['size']; // $_FILES['image']['size'] est la taille du fichier en octets.
            $file_type = mime_content_type($file_tmp); // mime_content_type() retourne le type MIME du fichier.

            if (!in_array($file_type, $allowed_types)) { // Vérifie si le type MIME du fichier est autorisé.
                $errors['image'][] = "Seules les images JPG, PNG ou GIF sont autorisées.";
            } elseif ($file_size > $max_size) { // Vérifie si la taille du fichier est inférieure à la taille maximale autorisée.
                $errors['image'][] = "L'image ne doit pas dépasser 2 Mo.";
            } else {
                // Crée le dossier uploads s'il n'existe pas
                $upload_dir = __DIR__ . '/../../uploads/'; // __DIR__ est le dossier courant. Le chemin du dossier. 
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true); // 0777 est la permission d'accès au dossier.
                    // Si true, alors tous les répertoires parents au directory spécifié seront également créés, avec les mêmes permissions. 
                }

                // Déplace le fichier temporaire vers le dossier d'upload
                $target_path = $upload_dir . $file_name; 
                if (move_uploaded_file($file_tmp, $target_path)) { 
                    $image_path = '/uploads/' . $file_name; 
                } else {
                    $errors['image'][] = "Erreur lors du téléchargement de l'image.";
                }
            }
        } else {
            $errors['image'][] = "Erreur lors du téléchargement de l'image.";
        }
    }

    if (count($errors) === 0) { 
        if ($date_parution != null) { 
            $date_parution = date_format(date_create($date_parution), 'Y-m-d');
        }

        // Protection contre les injections SQL
        $titre = $titre !== null ? mysqli_real_escape_string($c, $titre) : null; // Échappe les caractères spéciaux dans le titre.
        $resume = $resume !== null ? mysqli_real_escape_string($c, $resume) : null; // Échappe les caractères spéciaux dans le résumé.
        $date_parution = $date_parution !== null ? mysqli_real_escape_string($c, $date_parution) : null; 
        $image_path = $image_path !== null ? mysqli_real_escape_string($c, $image_path) : null; 

        $sql = "INSERT INTO livre (`titre`, `resume`, `date_parution`, `image`) VALUES ("; 
        $sql .= "'" . $titre . "'"; // Ajoute le titre à la requête.
        $sql .= $resume != null ? ", '" . $resume . "'" : ", NULL"; 
        $sql .= $date_parution != null ? ", '" . $date_parution . "'" : ", NULL"; 
        $sql .= $image_path != null ? ", '" . $image_path . "'" : ", NULL"; 
        $sql .= ")";

        mysqli_query($c, $sql); 
        mysqli_close($c); 

        create_message_flash('success', 'Le livre a bien été ajouté'); 
        redirect('/bibliotheque/livre/index.php'); 
    }
}

require '../header.php';
?>

<h2>Ajouter un livre</h2> 

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre:</label>
        <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du livre" value="">
        <?php echo htmlentities(form_errors('titre', $errors)); ?>
    </div>
    <div class="mb-3">
        <label for="resume" class="form-label">Résumé:</label>
        <textarea name="resume" id="resume" class="form-control" placeholder="Résumé du livre"></textarea>
        <?php echo htmlentities(form_errors('resume', $errors)); ?>
    </div>
    <div class="mb-3">
        <label for="date_parution" class="form-label">Date de parution:</label>
        <input type="date" class="form-control" id="date_parution" name="date_parution" value="">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image du livre::</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <?php echo htmlentities(form_errors('image', $errors)); ?>
    </div>

    <button type="submit" class="btn btn-outline-primary">Ajouter</button>
</form>

<?php require '../footer.php'; ?>
