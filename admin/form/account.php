<?php
session_start();

require_once('../../config/connx.php');

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

if (!isset($_SESSION['Id_role'])) {
    // Redirigez vers une page d'erreur ou une autre page
    header('Location: /index.php');
    exit;
}


// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['Id_user'])) {
    $id = $_SESSION['Id_user'];
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    $email = $_SESSION['email'];
    $telephone = $_SESSION['telephone'];

    // Requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM users WHERE Id_user = :id_user";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_user", $id);

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user) {
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $email = $user['email'];
        $telephone = $user['telephone'];
    } 
} else {
    // Rediriger vers la page de connexion ou afficher un message d'erreur
    echo "Vous devez être connecté pour accéder à cette page.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/navbar.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Encode Sans', sans-serif;
            background-color: #FFFAF1;
        }

        a {
            text-decoration: none;
            color: #006474;
            font-size: 24px;
        }


        input[type='submit'].compte {
            padding: 15px 40px;
            background: #006474;
            color: #fcb045;
            border: none;
            border-radius: 30px;
            margin: 0;
            font-size: 20px;
            cursor: pointer;
            transition: 0.15s ease-in-out;
            font-family: 'Encode Sans', sans-serif;
        }

        input.compte:hover
         {
            background: #fcb045;
            color: #006474;
        }

        input,
        textarea {
            font-family: 'Encode Sans', sans-serif;
        }

        .settings {
            width: 640px;
            box-shadow: 0px 0px 10px 1px rgba(black, 0.35);
            overflow: hidden;
            margin: 160px auto;
            
        }

        .settings .tabs {
            height: 80vh;
            position: relative;
            display: flex;
            flex-wrap: wrap;
            transition: 0.35s ease-in-out;
        }

        .settings .tabs .tab {
            width: 640px;
            height: 100%;
            overflow-y: auto;
            background: white;
            padding: 20px;
        }

        .settings .tabs .tab.apps ul {
            padding: 0;
            list-style: none;
        }

        .settings .tabs .tab.apps ul li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }

        .settings .tabs .tab.apps ul li a.remove {
            font-size: 30px;
            #006474
            transition: 0.15s ease-in-out;
        }

        .settings .tabs .tab.apps ul li a.remove:hover {
            color: #006474;
        }

        .settings .tabs .tab.apps ul li img {
            width: 100%;
            height: 75px;
            max-width: 75px;
            border-radius: 100%;
        }

        .settings .tabs .tab.apps ul li .info {
            width: 100%;
            text-align: left;
            padding: 0 20px;
        }

        .settings .tabs .tab.apps ul li .info p {
            margin: 0;
        }

        .settings .tabs .tab.apps ul li .info p.title {
            font-size: 20px;
        }

        .settings .tabs .tab.apps ul li .info p.domain {
            font-size: .8em;
            font-style: italic;
            color: #FFFAF1;
        }

        .settings .tabs .tab.acc .field input[type='text'],
        .settings .tabs .tab.acc .field input[type='email'],
        .settings .tabs .tab.acc .field input[type='password'],
        .settings .tabs .tab.acc .field textarea {
            font-size: 24px;
        }

        .settings .tabs .tab.prof .field label {
            font-size: .8em;
            font-style: italic;
            
        }

        .settings .tabs .tab.prof .bio {
            padding: 20px 0;
            padding-bottom: 10px;
        }

        .settings .tabs .tab.prof .bio textarea {
            width: 100%;
            max-width: 100%;
            height: 150px;
        }

        .settings .tabs .tab.prof .img-name .flex {
            display: flex;
        }

        .settings .tabs .tab.prof .img-name .flex .field:nth-child(2) {
            align-self: center;
            padding: 20px;
        }

        .settings .tabs .tab.prof .img-name .flex .field:nth-child(2) input[type='text'] {
            padding: 5px 0px;
            font-size: 25px;
        }

        .settings .tabs .tab .field {
            margin: 10px 0;
        }

        .settings .tabs .tab .field input[type='file'].image {
            display: none;
        }

        .settings .tabs .tab .field input[type='file'].image+label {
            display: inline-block;
            border-radius: 100%;
            transition: 0.15s ease-in-out;
            box-shadow: 0px 0px 10px 2px rgba(black, 0.35);
        }

        .settings .tabs .tab .field input[type='file'].image+label:hover {
            box-shadow: 0px 0px 10px 2px rgba(#006474, 0.75);
        }

        .settings .tabs .tab .field input[type='file'].image+label img {
            display: block;
            border-radius: 100%;
            width: 100%;
            max-width: 200px;
            height: auto;
        }

        .settings .tabs .tab .field input[type='text'],
        .settings .tabs .tab .field input[type='email'],
        .settings .tabs .tab .field input[type='password'],
        .settings .tabs .tab .field textarea,
        .settings .tabs .tab .field label {
            display: block;
            border: none;
            width: 100%;
            margin-bottom: 10px;
        }

        .settings .tabs .tab .field input[type='text'] {
            font-size: 24px;
            margin-bottom: 50px;
        }

        .settings .tabs .tab .field input[type='text'],
        .settings .tabs .tab .field input[type='email'],
        .settings .tabs .tab .field input[type='password'],
        .settings .tabs .tab .field textarea {
            border-bottom: 1px solid #FFFAF1;
            outline: none;
            transition: 0.15s ease-in-out;
        }

        .settings .tabs .tab .field input[type='text']:focus,
        .settings .tabs .tab .field input[type='email']:focus,
        .settings .tabs .tab .field input[type='password']:focus,
        .settings .tabs .tab .field textarea:focus {
            border-bottom: 1px solid #006474;
        }

        .settings .tab-links {
            width: 100%;
            display: flex;
            background-color: #fcb045;
            justify-content: space-between;
            padding: 20px 0;
            box-shadow: 0px 0px 10px 1px rgba(black, 0.35);
            position: relative;
            z-index: 2;
        }

        .settings .tab-links .bar {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            background: #006474;
            transform: translate(0, 0);
            transition: 0.35s ease-in-out;
        }

        .settings .tab-links a {
            width: 100%;
            #006474
            text-decoration: none;
            text-align: center;
        }

        label {
            font-size: 1rem;
            color: gray;
        }

    </style>
</head>

<body>

<?php
  require_once('../../ttt/middleware.php');
  // Inclure le fichier de connexion à la base de données
  require_once("../../config/connx.php");
  include_once('../../src/navbar.php');

  ?>
    <div class='settings'>
        <div class='tab-links'>
            <a href='#' class='active'>Profil</a>
            <a href='#'>Compte</a>
            <div class='bar'></div>
        </div>
        <div class='tabs'>
            <div class='tab prof'>
                <h1 style='margin-top: 0'>Profil</h1>
                <form action="../crud/gestionCompte_ttt.php" method="POST">
                    <div class='img-name'>
                        <div class='flex'>
                            <!-- <div class='field'>
                            <input type='file' name='pfp' id='pfp' class='image' />
                            <label for='pfp'><img src='http://lorempixel.com/200/200' /></label>
                        </div> -->
                            <div class='field'>
                                
                                <input type='text' id='nom' name='nom' value='<?=$nom?>&nbsp;<?=$prenom?>' />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="delete_account">

                    <input type='submit' class="compte" value='Supprimer le compte' />
                </form>
            </div>
            <div class='tab acc'>
                <h1 style='margin-top: 0'>Compte</h1>
                <form action="../crud/gestionCompte_ttt.php" method="POST">
                    <div class='pass'>
                        <h2 style='margin: 0'>Modifiez votre mot de passe</h2>
                        <div class='field'>
                            <label for='password'>Nouveau Mot De Passe</label>
                            <input type='password' id='password' name="pass"/>
                        </div>
                        <div class='field'>
                            <label for='newpass2'>Confirmez Nouveau Mot De Passe</label>
                            <input type='password' id='newpass2' name="verifpass"/>
                        </div>
                    </div>
                    <div class='email'>
                        <h2 style='margin: 0'>Modifiez votre email <br> <?=$email?></h2>
                        <div class='field'>
                            <label for='newemail'>Nouvel Email</label>
                            <input type='email' id='newemail' />
                        </div>
                        <div class='field'>
                            <label for='newemail2'>Confirmez Nouvel Email</label>
                            <input type='email' id='newemail2' />
                        </div>
                    </div>
                    <div class='numb'>
                        <h2 style='margin: 0'>Modifiez votre numéro de téléphone <br><?=$telephone?></h2>
                        <div class='field'>
                            <label for='newphone'>Nouveau Téléphone</h2>
                        <div class='field'></label>
                            <input type='email' id='newphone' />
                        </div>
                        <div class='field'>
                            <label for='newphone2'>Confirmez Nouveau Téléphone</label>
                            <input type='email' id='newphone2' />
                        </div>
                    </div>
                  
                    <input type='submit' class="compte" value='Enregistrer' />
                </form>
            </div>

        </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../../assets/js/nav.js"></script>


        <script>

            var settings = $(".settings"),
                tabLinks = settings.find(".tab-links");
            tabs = settings.find(".tabs");

            function setBar() {
                var settings = $(".settings"),
                    tabLinks = settings.find(".tab-links"),
                    tabs = settings.find('.tabs'),
                    tabswidth = 0;

                tabs.children().each(function () {
                    tabswidth += $(this).outerWidth();
                })

                tabs.width(tabswidth)

                tabLinks.find(".bar").css({ 'width': " " + 100 / tabLinks.find("a").length + "% " })
                tabLinks.find(".bar").css({ 'transform': "translate(" + ((tabLinks.find(".active").offset().left) - (tabLinks.offset().left)) + "px, 0)" })
                tabs.find(".tab").eq(tabLinks.find(".active").index()).addClass("active")

            }

            setBar();

            tabLinks.find("a").not("a.acitve").on('click', function (e) {
                e.preventDefault();

                tabLinks.find(".active").removeClass("active")
                $(this).addClass("active")

                tabLinks.find(".bar").css({ 'transform': "translate(" + ((tabLinks.find(".active").offset().left) - (tabLinks.offset().left)) + "px, 0)" })
                tabs.find(".tab.active").removeClass("active")
                tabs.find(".tab").eq(tabLinks.find(".active").index()).addClass("active")

                var activeOffset = tabs.find(".active").offset().left - tabs.offset().left

                tabs.css({ "transform": "translate(-" + activeOffset + "px, 0px)" })

            })


        </script>
</body>

</html>