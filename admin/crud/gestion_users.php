<?php
session_start();
require_once('../../config/connx.php');

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

if (!isset($_SESSION['Id_role']) || $_SESSION['Id_role'] != 1) {
    // Redirigez vers une page d'erreur ou une autre page
    header('Location: /index.php');
    exit;
}

// Requête pour récupérer tous les utilisateurs avec leurs rôles
$sql = "SELECT users.*, role.* FROM users
        LEFT JOIN role ON users.Id_role = role.Id_role";
$stmt = $db->prepare($sql);
$stmt->execute();

// Récupération des users dans un tableau
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fermeture de la connexion à la base de données
$db = null;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les produits</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">


    <link rel="stylesheet" href="/DataTables/datatables.min.css">



    <style>
        .icon-space {
            display: inline-block;
            width: 10px;
        }

        svg:hover path {
            color: #fcb045 !important;
        }

        h1 {
            color: #006474;
            background-color: #fcb045;
            margin: 24px auto !important;
            text-align: center;
        }

        .btn-primary,
        .btn-primary:active,
        .btn-primary:visited {
            background-color: #006474 !important;
            border: none !important;
        }

        .btn-primary:hover {
            background-color: #fcb045 !important;
            border: none !important;
            transition: all 0.3s ease-in-out;
        }

        .dataTables_filter {
            margin-bottom: 36px !important;
        }

        .boutons_bas {
            margin-top: 36px;
        }

        .dataTables_paginate .paginate_button.current {
            background: #fcb045 !important;
            border: none !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #fcb045 !important;
            border: none !important;
            transition: all 0.3s ease-in-out;
        }


        button.bt {
            cursor: pointer;
            border: none;
            background: none;
        }

        .modBtn {
            cursor: pointer;
            background: #fcb045;
        }
    </style>

</head>

<body>
    <main class="container">
        <div class="row">
            <!-- Affichage d'un message d'erreur s'il y a une erreur dans l'URL (pour récupérer l'ID du produit) -->
            <?php
            if (!empty($_SESSION['erreur'])) {
                echo '<div class="alert alert-danger" role="alert">
                    ' . $_SESSION['erreur'] . '
                </div>';
                $_SESSION['erreur'] = "";
            }

            // Affichage d'un message de succès
            if (!empty($_SESSION['message'])) {
                echo '<div class="alert alert-success" role="alert">
                    ' . $_SESSION['message'] . '
                </div>';
                $_SESSION['message'] = "";
            }
            ?>
            <h1>Gérer les utilisateurs</h1>
            <section class="col-12">
                <!-- Création d'un tableau -->
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date d'inscription</th>
                            <th>ID ROLE</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Boucle sur la variable $users pour afficher les valeurs de chaque produit
                        foreach ($users as $user) {
                            $userId = $user['Id_user'];
                            $nom = $user['nom'];
                            $prenom = $user['prenom'];
                            $email = $user['email'];
                            $telephone = $user['telephone'];
                            $inscription = $user['inscription_date'];
                            $roleId = $user['Id_role'];
                            $role = $user['nom_role']; 

                            ?>
                            <tr>
                                <td>
                                    <?= $userId ?>
                                </td>
                                <td>
                                    <?= $nom ?>
                                </td>
                                <td>
                                <?= $prenom ?>
                                </td>

                                <td>
                                    <?= $email?>
                                </td>
                                <td>
                                    <?= $telephone ?>
                                </td>
                                <td>
                                    <?= $inscription ?>
                                </td>
                                <td>
                                    <?= $roleId ?>
                                </td>
                                <td>
                                    <?= $role ?>
                                </td>
                                <td>
                                    <a href="../form/update_user.php?id=<?= $userId ?>"><svg
                                            xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <!-- Icon for update action -->

                                            <path
                                                d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                                        </svg></a>
                                    <span class="icon-space"></span>
                                    <!-- <a href="delete_crud_produit.php?delete_id=<?= $userId ?>"> -->
                                    <button type="button" class="bt" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_<?= $userId ?>"> <svg
                                            xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <style>
                                                svg {
                                                    fill: #006474
                                                }

                                                svg:hover {
                                                    fill: #fcb045;
                                                    transition: all 0.3s ease-in-out;
                                                }
                                            </style>
                                            <path
                                                d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z" />
                                        </svg>

                                    </button>


                                </td>


                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Ajout d'un lien pour "ajouter" un produit -->
                <div class="boutons_bas">
                    <a href="../form/add_user.php" class="btn btn-primary">Ajouter un utilisateur</a>
                    <a href="../../index.php" class="btn btn-primary">Retour Accueil</a>
                </div>
            </section>
        </div>
    </main>
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/DataTables/datatables.min.js"></script>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <?php
    foreach ($users as $key => $user) { ?>
        <form method="get" action="delete_crud_user.php">
            <div class="modal fade " id="staticBackdrop_<?= $userId ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"> Supprimer
                                <?= $nom ?>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Voulez vous supprimer définitivement cet utilisateur ?
                        </div>
                        <input type="hidden" name="delete_id" value=<?= $userId ?>>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary modBtn" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary modBtn">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php }

    ?>

    <!-- Delete Confirmation Modal -->






    <script>
        var table = new DataTable('#myTable', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json',
            },
        });


    </script>
</body>

</html>