<?php
if ($_GET) {
    // if ($_GET['ville'] == "") {
    //     echo "formulaire vide";
    // }
    $ville = $_GET['ville'];
    $str = str_replace(" ", "-", $ville);



    //Requête de l'API Openweather//
    $ApiUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . $str . "&units=metric&appid=cebd7f8b4047d62a6f31812081e933de&lang=fr";

    //Connexion au serveur avec Curl//
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $ApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    //Fin de connexion//
    //Décodage des données afin de pour les exploiter//
    $data = json_decode($response);
    //Initialisation de la varible temps//
    $currentTime = time();
}
// var_dump($data);

// setlocale(LC_TIME, 'fr_FR');
// date_default_timezone_set('Europe/Paris');
// echo utf8_encode(strftime('%A %d %B %Y, %H:%M'));


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appli Météo</title>

    <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />

    <style type="text/css">
        #map {
            /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
            height: 400px;
        }
    </style>

    <!-- Jquery pour afficher photo wiki -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="apple-touch-icon" sizes="57x57" href="/Ressources/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/Ressources/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/Ressources/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/Ressources/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/Ressources/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/Ressources/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/Ressources/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/Ressources/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/Ressources/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/Ressources/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Ressources/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/Ressources/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Ressources/favicon-16x16.png">
    <link rel="manifest" href="/Ressources/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>






<body>

    <header>
        <nav class="navbar navbar-light navbarbg">
            <div class="container-fluid">
                <a class="navbar-brand nameColor">Météo</a>

                <form class="d-flex" action="index.php" method="GET">

                    <input class="form-control me-2" type="text" placeholder="Ville" aria-label="ville" name="ville" value="">

                    <button class="btn" type="submit">Recherche</button>
                </form>

            </div>
        </nav>
    </header>

    <section>

        <?php

        if ($_GET) { ?>
            <div class="row">

                <div class="col-sm-6">

                    <div class="col-sm-12 ville">
                        <h2><?php echo $data->name; ?> </h2>
                        <div><?php echo date("l g:i a", $currentTime); ?></div>
                        <div><?php echo date("jS F, Y", $currentTime); ?></div>
                    </div>

                    <div class="col-sm-12">
                        <img src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png" class="weather-icon" alt="Météo">
                        <?php echo $data->weather[0]->description; ?>
                    </div>

                    <div class="row">

                        <div class="col-sm-6 d-inline-flex align-items-center">
                            <img src="./Ressources/T=.png" class="picto1" alt="Température">
                            <p class="d-flex align-items-center"><?php echo $data->main->temp; ?>°C <br>
                                Ressentie : <?php echo $data->main->feels_like; ?>°C</p>
                        </div>

                        <div class="col-sm-6 min-temperature">
                            <img src="./Ressources/T+.png" class="picto" alt="Température max"><?php echo $data->main->temp_max; ?>°C</br>
                            <img src="./Ressources/T-.png" class="picto" alt="Température min"><?php echo $data->main->temp_min; ?>°C 
                        </div>

                    </div>

                    <div class="row time">

                        <div class="col-sm-6 d-inline-flex align-items-center"><img src="./Ressources/V.png" class="picto1" alt="Vent">
                            <p><?php echo $data->wind->speed; ?> km/h<br>
                                Direction : <?php echo $data->wind->deg; ?> °<br>
                                Raffale : <?php echo $data->wind->speed; ?> m/s</p>
                        </div>

                        <div class="col-sm-6 d-inline-flex align-items-center">
                            <img src="./Ressources/P.png" class="picto1" alt="Pression atmosphérique"><?php echo $data->main->pressure; ?> hPa
                        </div>

                    </div>

                     <div class="row">

                        <div class="col-sm-6">
                            <img src="./Ressources/H.png" class="picto1" alt="Humidité"><?php echo $data->main->humidity; ?> %
                        </div>

                        <div class="col-sm-6">
                            <img src="./Ressources/N.png" class="picto1" alt="Couverture Nuageuse"><?php echo $data->clouds->all; ?> %
                        </div>

                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="wikipics photo d-flex justify-content-center">
                        <!-- Photo wiki -->
                    </div>    
                </div>

                <div class="col-sm-12">
                    <div class="map" id="map">
                        <!-- Ici s'affichera la carte -->
                    </div>
                </div>

            </div>

<?php
        } else {
            ?>

                <div class="row d-flex justify-content-center">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <h4>Veuillez entrez le Nom d'une ville dans le champ de recherche</h4>
                    </div>
                    <div class="col-sm-1"></div>

                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <img class="imgVille" src="./Ressources/ville.png" alt="">
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            <?php
        }
            ?>

    </section>

    <footer>
        <div class="row">
            <p>© Erwan Gentric, Tous droits réservés.</p>

        </div>
    </footer>

    <!-- Script de connexion à l'API de wikipedia -->
    <script>
        $(document).ready(function() {
            var articles = $('.wikipics');
            var toSearch = '';
            var searchUrl = 'https://en.wikipedia.org/w/api.php';

            var ajaxArticleData = function() {
                $.ajax({
                    url: searchUrl,
                    dataType: 'jsonp',
                    data: {
                        action: 'query',
                        format: 'json',
                        generator: 'search',
                        gsrsearch: toSearch,
                        gsrnamespace: 0,
                        gsrlimit: 1,
                        prop: 'pageimages',
                        piprop: 'thumbnail',
                        pilimit: 'max',
                        pithumbsize: 400
                    },
                    success: function(json) {
                        var pages = json.query.pages;
                        $.map(pages, function(page) {
                            var pageElement = $('<div>');
                            if (page.thumbnail) pageElement.append($('<img class="photoVille">').attr('src', page.thumbnail.source));
                            articles.append(pageElement);
                        });
                    }
                });
            };
            articles.empty();
            toSearch = "<?php echo $data->name ?>";
            ajaxArticleData();


        });
    </script>




    <!-- Fichiers Javascript Open street Map-->
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin="">
    </script>
    <script type="text/javascript">
        // On initialise la latitude et la longitude de Paris (centre de la carte)
        var lat = <?php echo $data->coord->lat; ?>;
        var lon = <?php echo $data->coord->lon; ?>;
        var macarte = null;

        // Fonction d'initialisation de la carte
        function initMap() {
            // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
            macarte = L.map('map').setView([lat, lon], 11);
            // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                // Il est toujours bien de laisser le lien vers la source des données
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(macarte);

            // Nous ajoutons un marqueur
            var marker = L.marker([lat, lon]).addTo(macarte);

        }
        window.onload = function() {
            // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
            initMap();
        };
    </script>


    <!-- styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link rel="stylesheet" href="./Ressources/CSS/style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

</body>

</html>