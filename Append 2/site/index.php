<?php
session_start();
include './assets/php/bdd/db_pdo.php';

// Requête pour obtenir les signalements
$query = "SELECT s.signalementID, u.nom AS nomUtilisateur, l.nom AS nomLieu, l.typeLieu, s.descriptions, s.dateHeure, s.typeSignalement, l.latitude, l.longitude
FROM Signalement s
JOIN Utilisateur u ON s.userID = u.userID
JOIN Lieu l ON s.lieuID = l.lieuID
ORDER BY s.dateHeure DESC
LIMIT 10";

$stmt = $pdo->query($query);
$signalements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="FR">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Localisation et Points d'Arrêt Proches</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Vendor CSS Files - FrameWork -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="assets/css/main.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfQXcXmjZ7hyWaPlQ0TnnYDMhrQ13T7Qo&libraries=places,geometry"></script>
  </head>
  <body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="container">
        <div class="row">
          <div class="col-sm">
            <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
              <h1>PARISafe</h1>
              <span>.</span>
            </a>
          </div>
          <div class="col-sm">
            <nav id="navmenu" class="navmenu">
              <ul>
                <li>
                  <a href="index.html#home" class="active">Accueil</a>
                </li>
                <li>
                  <a href="">Contact</a>
                </li>
                <li>
                  <a href="login.php">Profil</a>
                </li>
              </ul>
              <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
          </div>
          <?php if(isset($_SESSION['user'])): ?>
              <div class="col-sm align-self-center">
                  <a class="btn btn-primary" href="assets/php/process_logout.php">Se déconnecter</a>
              </div>
            <?php endif; ?>
        </div>
      </div>
    </header>
    <main id="main">
      <!-- Signal Section - Home Page -->
      <section id="home" class="signal">
        <!--  Section Title -->
        <div class="container section-title" data-aos="fade-up">
          <h2>Signalements & Messages</h2>
          <p>Signaler incidents et communiquer rapidement via messages intégrés.</p>
        </div>
        <div id="successMessage" class="container mt-3 alert alert-success" style="display: none;">
          L'action a été fait avec succès!
        </div>
        <!-- End Section Title -->
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row gy-4">
            <div class="col-lg-6">
              <div class="row">
                <div class="">
                  <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="mt-1">
                  <button type="button" style="background-color: #dc3545;border-color: #dc3545;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> SIGNALER </button>
                </div>
              </div>
            </div>
            <div class="col-lg-6 bg-light">
              <div class="p-3">
                <h6 class="border-bottom pb-2 mb-0">Zone d'alerte</h6>
                <div class="zone_alert">
                <?php foreach ($signalements as $signalement): ?>
                  <div class="d-flex text-body-secondary p-2 hover-div" data-latitude="<?php echo htmlspecialchars($signalement['latitude']); ?>"  data-longitude="<?php echo htmlspecialchars($signalement['longitude']); ?>">
                      <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false">
                      <title>Placeholder</title>
                      <rect width="100%" height="100%" fill="#007bff"></rect>
                      <text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text>
                    </svg>
                    <p class="pb-3 mb-0 small lh-sm border-bottom">
                        <strong class="d-block text-gray-dark">@<?php echo htmlspecialchars($signalement['nomUtilisateur']); ?> - <?php echo htmlspecialchars($signalement['nomLieu']); ?></strong> <?php echo htmlspecialchars($signalement['descriptions']); ?>
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['typeUSER'] == 1): ?>
                            <a href="assets/php/process_delete_signalement.php?id=<?php echo $signalement['signalementID']; ?>" class="btn btn-danger">Supprimer</a>
                        <?php endif; ?>
                    </p>
                  </div>
                <?php endforeach; ?>
                <div id="velib_data"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- End Signal Section -->
    </main>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Signalements</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="mapPopUP" style="width: 100%; height: 400px;"></div>
          </div>
          <div class="modal-footer">
            <div class="card-body">
              <form action="./assets/php/process_signalement.php" method="post">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Type de transport</label>
                      <select name="type" id="transportTypeDropdown" onchange="updateStopsDropdown()" class="form-control">
                        <option value="">Sélectionnez un type de transport</option>
                        <option value="train_station">Gare</option>
                        <option value="bus_station">Arrêt de bus</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Arrêt ou Gare</label>
                      <select name="googlePlaceID" id="stopsDropdown" onchange="displayPlaceDetails()" class="form-control">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group mt-3">
                      <label for="description">Description du signalement</label>
                      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>                    
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <button type="submit" type="button" class="btn btn-success form-control input-block-level btn-block">Valider</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="container copyright text-center mt-4">
        <p>&copy; <span>Copyright</span>
          <strong class="px-1">PARISafe</strong>
          <span>All Rights Reserved</span>
        </p>
      </div>
    </footer>
    <!-- End Footer -->
    <!-- Scroll Top Button -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>
    <!-- Preloader -->
    <div id="preloader">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');

        if (success) {
            document.getElementById('successMessage').style.display = 'block';
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 5000);
        }
    });

    var transportStyles = [{
        featureType: 'all',
        elementType: 'labels',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'transit',
        elementType: 'geometry',
        stylers: [{
          visibility: 'on'
        }, {
          color: '#e5e5e5'
        }]
      }, {
        featureType: 'transit.station',
        elementType: 'labels.icon',
        stylers: [{
          visibility: 'on'
        }]
      }, {
        featureType: 'road',
        elementType: 'geometry',
        stylers: [{
          visibility: 'on'
        }]
      }];

    var map, mapPopUP, userPos, userCircle, selectedPlaceMarker;

    function initMaps() {
        var paris = {
            lat: 48.8566,
            lng: 2.3522
        };
        map = new google.maps.Map(document.getElementById('map'), {
            center: paris,
            zoom: 14,
            styles: transportStyles
        });
        mapPopUP = new google.maps.Map(document.getElementById('mapPopUP'), {
            center: paris,
            zoom: 14,
            styles: transportStyles
        });
    }

    function locateUser() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userPos = {
                  lat: 48.8566,
            lng: 2.3522
        };

                var userMarker = new google.maps.Marker({
                    position: userPos,
                    map: mapPopUP,
                    title: 'Position fixée à Paris!'
                });
                mapPopUP.setCenter(userPos);
                mapPopUP.setZoom(16);

                if (userCircle) {
                    userCircle.setMap(null);
                }
                userCircle = new google.maps.Circle({
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    map: mapPopUP,
                    center: userPos,
                    radius: 200,
                    clickable: false
                });
            });
        }
    }

function updateStopsDropdown() {
    var selectedTransportType = document.getElementById('transportTypeDropdown').value;
    if (!selectedTransportType) return;

    var service = new google.maps.places.PlacesService(mapPopUP);
    var searchText = "";

    if (selectedTransportType === "bus_station") {
        searchText = "Arrêt de bus";
    } else if (selectedTransportType === "train_station") {
        searchText = "Gare";
    }

    service.textSearch({
        location: userPos,
        radius: 200, 
        query: searchText,
    }, function(results, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            var dropdown = document.getElementById('stopsDropdown');
            dropdown.innerHTML = '<option value="">Sélectionnez un arrêt/gare...</option>';
            
            results = results.filter(function(place) {
            var distance = google.maps.geometry.spherical.computeDistanceBetween(
                new google.maps.LatLng(userPos.lat, userPos.lng),
                place.geometry.location
            );
            return distance <= 200;  // Filtrage des résultats par distance
        });
            results.forEach(function(place) {
                var option = document.createElement('option');
                option.value = place.place_id;
                option.textContent = place.name;
                dropdown.appendChild(option);
            });
        } else {
            console.error("Erreur lors de la recherche :", status);
        }
    });
}

function displayPlaceDetails() {
    var selectedPlaceId = document.getElementById('stopsDropdown').value;
    if (!selectedPlaceId) return;
    
    var service = new google.maps.places.PlacesService(mapPopUP);
    service.getDetails({
        placeId: selectedPlaceId
    }, function(place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            // Si un marqueur pour un arrêt/gare précédent existe, on le retire
            if (selectedPlaceMarker) {
                selectedPlaceMarker.setMap(null);
            }
            var lieuData = {
                googlePlaceID: place.place_id,
                nom: place.name,
                typeLieu: place.types[0], // prenez le premier type comme exemple
                latitude: place.geometry.location.lat(),
                longitude: place.geometry.location.lng(),
                descriptions: place.formatted_address || place.vicinity
            };
            
            sendLieuDataToServer(lieuData);

            // Création d'un nouveau marqueur pour l'arrêt/gare sélectionné(e) avec la couleur verte
            selectedPlaceMarker = new google.maps.Marker({
                map: mapPopUP,
                position: place.geometry.location,
                title: place.name,
                icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'  // URL de l'icône verte de Google
            });
            
            // Centre la carte sur l'arrêt/gare sélectionné(e)
            mapPopUP.setCenter(place.geometry.location);
        }
    });
}

    function sendLieuDataToServer(data) {
        $.ajax({
            url: './assets/php/process_lieu.php',
            method: 'POST',
            data: data,
            success: function(response) {
                console.log("Données envoyées avec succès :", response);
            },
            error: function(error) {
                console.error("Erreur lors de l'envoi des données :", error);
            }
        });
    }
    function attachClickEventToDiv(div, lat, lon, title) {
    div.addEventListener('click', function() {
        var position = new google.maps.LatLng(lat, lon);
        map.setCenter(position);
        map.setZoom(18);

        if (selectedPlaceMarker) {
            selectedPlaceMarker.setMap(null); 
        }

        selectedPlaceMarker = new google.maps.Marker({
            map: map,
            position: position,
            title: title,
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });
    });
}

document.querySelectorAll(".d-flex.text-body-secondary").forEach(function(div) {
    var lat = parseFloat(div.getAttribute("data-latitude"));
    var lon = parseFloat(div.getAttribute("data-longitude"));
    var title = div.querySelector('strong').textContent;
    attachClickEventToDiv(div, lat, lon, title);
});

    function fetchAndDisplayIncidents() {
        var apiUrl = "https://opendata.paris.fr/api/explore/v2.1/catalog/datasets/dans-ma-rue/records?limit=20";

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    var incidentsZone = document.querySelector('#velib_data');
                    incidentsZone.innerHTML = ""; 

                    data.results.forEach(incident => {
                        var div = document.createElement('div');
                        div.className = "d-flex text-body-secondary p-2 hover-div";

                        div.innerHTML = `
                            <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#dc3545"></rect>
                                <text x="50%" y="50%" fill="#dc3545" dy=".3em">32x32</text>
                            </svg>
                            <p class="pb-3 mb-0 small lh-sm border-bottom">
                                <strong class="d-block text-gray-dark">${incident.type} - ${incident.adresse}</strong>
                                ${incident.soustype} - Date: ${incident.datedecl}
                            </p>`;
                        div.setAttribute("data-latitude", incident.geo_point_2d.lat);
                        div.setAttribute("data-longitude", incident.geo_point_2d.lon);

                        var lat = parseFloat(incident.geo_point_2d.lat);
                        var lon = parseFloat(incident.geo_point_2d.lon);
                        var title = incident.type + " - " + incident.adresse;
                        attachClickEventToDiv(div, lat, lon, title);

                        incidentsZone.appendChild(div);
                    });
                }
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données de l'API:", error);
            });
    }

    window.onload = function() {
        initMaps();
        locateUser();
        fetchAndDisplayIncidents();
    };
</script>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
  </body>
</html>