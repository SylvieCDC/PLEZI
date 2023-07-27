<form action="ajouter_panier.php" method="post" class="product">
    <div class="image_product" style="background: url('../upload_images/<?= $row['image_produit'] ?>') center/ cover">
        <!-- <img src="../upload_images/<?= $row['image_produit'] ?>" alt="<?= $row['titre_produit'] ?>"> -->
    </div>
    <div class="content">
        <h4 class="name">
            <?= $row['titre_produit'] ?>
        </h4>
        <?php if ($row['prix_produit'] !== null && $row['prix_produit'] !== 0): ?>
            <h2 class="price">
                <?= $row['prix_produit'] ?>€
            </h2>
        <?php else: ?>
            <h2 class="price transparente">
                0 €
            </h2>
            
        <?php endif; ?>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <?php if ($row['id_categorie'] !== 8): ?>
            <div class="descriptif">
                <p><?= $row['enonce_produit'] ?></p>
            </div>

            <a href="ajouter_panier.php?id=<?= $row['id'] ?>" class="id_product">Ajouter au panier</a>
        <?php endif; ?>

    </div>
</form>