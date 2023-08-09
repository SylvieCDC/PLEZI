<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "../../config/connx.php";
?>

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
            $total = 0;
            $ids = array_keys($_SESSION['panier']);
            if (empty($ids)) {
                echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
            } else {
                $inClause = implode(',', array_fill(0, count($ids), '?'));
                $query = "SELECT * FROM produits WHERE id IN ($inClause)";
                $stmt = $db->prepare($query);
                if ($stmt->execute($ids)) {
                    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products_in_cart as $product_in_cart) {
                        $product_id = $product_in_cart['id'];
                        $product_quantity = $_SESSION['panier'][$product_id];
                        if (is_numeric($product_in_cart['prix_produit']) && is_numeric($product_quantity)) {
                            $total += $product_in_cart['prix_produit'] * $product_quantity;
                        }
                        
                        ?>
                        <tr>
                            <td class="img_sidebar">
                                <img src="<?= $product_in_cart['image_produit'] ?>" alt="Photo <?= $product_in_cart['titre_produit'] ?>">
                            </td>
                            <td><?= $product_in_cart['titre_produit'] ?></td>
                            <td>
                                <?= $product_in_cart['prix_produit'] ? $product_in_cart['prix_produit']."&nbsp;€" : "0&nbsp;€" ?>
                            </td>
                            <td>
                                <form action="" method="post" onsubmit="updateQuantity(event, <?= $product_in_cart['id'] ?>)">
                                    <input type="number" name="quantity" value="<?= $product_quantity ?>" min="1">
                                    <button type="submit"><img src="refresh.png" width="30px"></button>
                                </form>
                            </td>
                            <td classe="sup">
                                <a href="delete_produit.php?id=<?= $product_in_cart['id'] ?>"><img src="delete.png" width="30px"></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    die("Error fetching products from the database.");
                }
            }
            $total_formatted = number_format($total, 2, ',', ' ');
            ?>
        </table>
    </div>
    <div class="totalButton">
        <div class="total">
            <div>Total : <?= $total_formatted ?>&nbsp;€</div>
        </div>
        <div class="buttonStyles">
            <?php if (!empty($ids)): ?>
                <div class="btn_remove_all btn_basket" onclick="removeAllFromCart()"><a>Vider le panier</a></div>
                <a class="btn_basket" href="pay.php">Payer</a>
            <?php endif; ?>
        </div>
    </div>
</div>
