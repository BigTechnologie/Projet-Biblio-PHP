<?php
//Manipulation des entêtes HTTP)

header('Content-Type: text/html; charset=UTF-8');

require_once '../../helper/request.php';  // require_once: Inclus une seule fois, même si appelé plusieurs fois et arrête tout en cas d'erreur
require_once '../../helper/bdd.php';      // include: Warning, mais le script continue
require_once '../../helper/session.php';  // require: Génère une erreur fatale (fatal error) et interrompt l'exécution du script.

// Récupérer les paramètres de requête(query string)  

$query = query('q'); // Paramètre de recherche par titre // récupère la valeur du paramètre 'q' → 'chatons'
$field = query('field'); // récupère la valeur du paramètre 'field' → 'date' // Champ de tri
$sort = query('sort', "asc"); // récupère la valeur du paramètre 'sort' → 'asc' // Sens de tri

// Pagination
$page = query('page', 1); // récupère la valeur du paramètre 'page', par defaut 'page' → '1' // Page actuelle
$results_per_page = 4; // Nombre de résultats par page. Pour l'insatant je chois d'affichier 3 livres par page
$offset = ($page - 1) * $results_per_page; // Offset pour la pagination // $offset = (1-1) * 3 = 0 . $offset = (2-1) * 3 = 3

// Établir une connexion à la base de données
$c = connection();

// Construction du SELECT 
$sql = "SELECT id, titre, date_parution, image FROM livre";

// Clause WHERE si recherche
if ($query != null) {
    $sql .= " WHERE titre LIKE '%" . mysqli_real_escape_string($c, $query) . "%'";
}

// Clause ORDER BY
if ($field != null && $sort != null) {
    if ($sort != "asc" && $sort != "desc") {
        $sort = "asc";
    }
    $sql .= " ORDER BY " . mysqli_real_escape_string($c, $field) . " " . mysqli_real_escape_string($c, $sort);
}

// Limitation pagination
$sql .= " LIMIT $results_per_page OFFSET $offset";

// Exécution de la requête
$result = mysqli_query($c, $sql);
$livres = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Compter le total de livres
$count_sql = "SELECT COUNT(*) AS total FROM livre";
if ($query != null) {
    $count_sql .= " WHERE titre LIKE '%" . mysqli_real_escape_string($c, $query) . "%'";
}
$count_result = mysqli_query($c, $count_sql);
$total_livres = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_livres / $results_per_page);

// Formateur de date
$fmt = datefmt_create('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

require '../header.php';
?>
<h2>Liste des livres</h2>

<form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <div class="input-group mb-3">
        <input type="search" name="q" class="form-control" aria-label="Rechercher par le titre" placeholder="Rechercher par le titre" value="<?php echo $query; ?>"/>
        <?php if ($query): ?>
            <a href="/bibliotheque/livre/" class="btn btn-outline-secondary">Réinitialiser le filtre</a>
        <?php endif ?>
        <button class="btn btn-outline-secondary">Rechercher</button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Numero</th>
            <th>Titre du livre</th>
            <th>Image</th> <!-- IMAGE -->
            <th>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($_GET, ['field' => 'date_parution', 'sort' => $sort === 'asc' ? 'desc' : 'asc'])), ENT_QUOTES, 'UTF-8'); ?>">
                    Date de parution
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($livres): ?>
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?php echo htmlentities($livre['id']); ?></td>
                    <td><?php echo htmlentities($livre['titre']); ?></td>
                    <td>
                        <?php if (!empty($livre['image'])): ?> 
                            <img src="<?php echo htmlspecialchars($livre['image']); ?>" alt="Image du livre" style="max-width:100px; height:auto;"> <!-- IMAGE -->
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $livre['date_parution'] != null ? datefmt_format($fmt, date_create($livre['date_parution'])) : '-'; ?>
                    </td>
                    <td class="text-end">
                        <a href="/bibliotheque/livre/detail.php?id=<?php echo $livre['id'] ?>">Détail</a>
                        <?php if (has_user_connect()): ?>
                            -
                            <a href="/bibliotheque/livre/modifier.php?id=<?php echo $livre['id'] ?>">Modifier</a>
                            -
                            <a href="/bibliotheque/livre/supprimer.php?id=<?php echo $livre['id'] ?>">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">Aucun livre</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<nav aria-label="Pagination">
    <ul class="pagination">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Précédent</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Suivant</a>
        </li>
    </ul>
</nav>

<?php require '../footer.php' ?>

