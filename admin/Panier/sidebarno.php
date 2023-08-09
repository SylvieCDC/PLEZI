<div class="sideBar_product">
    <div class="marge"></div>
    <div class="sideBar_title">

        <h2>Récapitulatif Panier</h2>
    </div>

    <div class="tableStyle">
        <table>
            <tr>
                <th class="img_sidebar"></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            <?php
            // Liste des produits dans le panier
            // Récupérer les clés du tableau session
            $ids = array_keys($_SESSION['panier']);
            // S'il n'y a aucune clé dans le tableau
            if (empty($ids)) {
                echo "<tr><td colspan='4'>Votre panier est vide</td></tr>";
            } else {
                // Si oui
                $inClause = implode(',', array_fill(0, count($ids), '?'));
                $query = "SELECT * FROM produits WHERE id IN ($inClause)";
                $stmt = $db->prepare($query);
                if ($stmt->execute($ids)) {
                    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Liste des produits dans le panier avec une boucle foreach
            
                    foreach ($products_in_cart as $product_in_cart):
                        // Récupérer les informations du produit à partir de la session
                        $product_id = $product_in_cart['id'];
                        $product_quantity = $_SESSION['panier'][$product_id];
                        // Formater le total avec deux décimales
                        $total_formatted = number_format($total, 2, ',', ' ');
                        ?>
                        <tr>

                            <td class="img_sidebar">
                                <img src="<?= $product_in_cart['image_produit'] ?>"
                                    alt="Photo <?= $product_in_cart['titre_produit'] ?>">
                            </td>
                            <td>
                                <?= $product_in_cart['titre_produit'] ?>
                            </td>
                            <td>
                                <?php if ($product_in_cart['prix_produit'] !== null && $row['prix_produit'] !== 0): ?>
                                    <?= $product_in_cart['prix_produit'] ?>&nbsp;€
                                <?php else: ?>
                                    <?= $product_in_cart['prix_produit'] ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="" method="post" onsubmit="updateQuantity(event, <?= $product_in_cart['id'] ?>)">

                                    <input type="number" name="quantity" value="<?= $product_quantity ?>" min="1">
                                    <button type="submit"><img src="refresh.png" width="30px"></button>
                                </form>
                            </td>


                            <td classe="sup">

                                <a href="delete_produit.php?id=<?= $product_in_cart['id'] ?>"><img src="delete.png"
                                        width="30px"></a>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                } else {
                    // Handle the error appropriately (e.g., log the error or display a user-friendly message)
                    die("Error fetching products from the database.");
                }
            }
            ?>

        </table>
    </div>
    <div class="totalButton">
        <div class="total">
            <?php if (empty($ids)) {
                ?>
                <div>Total : &nbsp;
                    0,00 &nbsp;€
                </div>
                <?php

            } else {
                ?>
                <div>Total : &nbsp;
                    <?= $total_formatted ?> &nbsp;€
                </div>
                <?php

            } ?>



        </div>


        <div class="buttonStyles">
            <?php
            if (empty($ids)) {
                ?>
                <?php
            } else {
                ?>
                <div class="btn_remove_all btn_basket" onclick="removeAllFromCart()"><a>Vider le panier</a></div>
                <a class="btn_basket" href="pay.php">Payer</a>
                <?php
            }
            ?>
        </div>
    </div>