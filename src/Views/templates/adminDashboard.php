<?php
?>

<h2>Tableau de bord des articles</h2>
<table id="articlesTable" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Titre &#8597;</th>
            <th onclick="sortTable(1)">Vues &#8597;</th>
            <th onclick="sortTable(2)">Commentaires &#8597;</th>
            <th onclick="sortTable(3)">Date de publication &#8597;</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dashboardArticles as $article) { ?>
        <tr>
            <td><?= htmlspecialchars($article['article']->getTitle()) ?></td>
            <td><?= (int)$article['viewCount'] ?></td>
            <td><?= (int)$article['commentCount'] ?></td>
            <td><?= htmlspecialchars($article['article']->getFormattedDateCreation()) ?></td>
            <td>
                <a class="submit" href="index.php?route=/admin/articleForm&id=<?= $article['article']->getId() ?>">Modifier</a>
                <form action="index.php?route=/admin/delete&id=<?= $article['article']->getId() ?>" method="POST" style="display:inline;">
                    <button class="submit" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
document.querySelectorAll("#articlesTable th").forEach((th, idx) => {
    th.addEventListener("click", () => {
        const tbody = th.closest("table").querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const asc = th.dataset.asc === "1" ? false : true;
        th.dataset.asc = asc ? "1" : "0";
        rows.sort((a, b) => {
            let v1 = a.children[idx].textContent.trim();
            let v2 = b.children[idx].textContent.trim();
            // Numérique
            if (idx === 1 || idx === 2) {
                v1 = parseInt(v1, 10); v2 = parseInt(v2, 10);
                return asc ? v1 - v2 : v2 - v1;
            }
            // Date
            if (idx === 3) {
                v1 = new Date(v1); v2 = new Date(v2);
                return asc ? v1 - v2 : v2 - v1;
            }
            // Texte
            return asc ? v1.localeCompare(v2) : v2.localeCompare(v1);
        });
        rows.forEach(row => tbody.appendChild(row));
    });
});
</script>
<style>
table, th, td { border: 1px solid #ccc; }
th { cursor: pointer; background: #f4f4f4; }
td, th { padding: 8px; text-align: left; }
.submit { margin-right: 8px; }
</style>
