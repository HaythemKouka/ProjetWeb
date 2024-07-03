<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête pour récupérer les hôtels
$hotels_sql = "SELECT id, name FROM hotels";
$hotels_result = $conn->query($hotels_sql);

// Initialisation des variables
$selected_hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;
$chambres_sql = "SELECT chambres.id, chambres.nom AS chambre_nom, chambres.description AS chambre_description, chambres.image AS chambre_image, chambres.remise, chambres.pourcentage_remise, chambres.cout, hotels.name AS hotel_nom 
        FROM chambres 
        INNER JOIN hotels ON chambres.hotel_id = hotels.id";

// Filtrage par hôtel si un hôtel est sélectionné
if ($selected_hotel_id > 0) {
    $chambres_sql .= " WHERE chambres.hotel_id = $selected_hotel_id";
}

$chambres_result = $conn->query($chambres_sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chambres</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .room-card {
            margin: 10px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .card-img-top {
            height: 300px;
            transition: transform 0.5s ease;
        }
        .card-img-top:hover {
            transform: scale(1.1);
        }
        footer {
            position: relative;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 20px;
        }
        .card-img-top {
            height: 300px;
        }
    </style>
</head>

<body>
    <header style="background-color: rgb(136, 199, 255);">
        <div class="animated-text"></div>
        <nav>
            <ul>
                <li style="color: #f5f5f5;"><a href="acceuil.html">Accueil</a></li>
                <li style="color: #f5f5f5;"><a href="chambres.php">Chambres</a></li>
                <li style="color: #f5f5f5;"><a href="services.html">Services</a></li>
                <li style="color: #f5f5f5;"><a href="contact.html">Contact</a></li>
                <li class="right-btn" style="color: #f5f5f5;"><a href="inscrit.html"><i class="fas fa-user-plus"></i> Inscription</a></li>
                <li class="right-btn" style="color: #f5f5f5;"><a href="reservation.html"><i class="fas fa-calendar-plus"></i> Réservation</a></li>
            </ul>
        </nav>
    </header>
    <h1 style="color: rgb(10, 13, 14);margin-left: 40%;">Nos Chambre</h1>

    <!-- Formulaire de sélection de l'hôtel -->
    <form method="GET" action="chambre.php" style="margin: 20px;">
        <label for="hotel_id">Sélectionnez un hôtel :</label>
        <select name="hotel_id" id="hotel_id" width="100px" class="form-control" onchange="this.form.submit()">
            <option class="form-control" value="0">Tous les hôtels</option>
            <?php
            if ($hotels_result->num_rows > 0) {
                while ($hotel = $hotels_result->fetch_assoc()) {
                    $selected = ($hotel['id'] == $selected_hotel_id) ? 'selected' : '';
                    echo "<option  value='{$hotel['id']}' $selected>{$hotel['name']}</option>";
                }
            }
            ?>
        </select>
    </form>

    <section class="room-section">
        <div class="row">
            <?php
            if ($chambres_result->num_rows > 0) {
                while ($row = $chambres_result->fetch_assoc()) {
                    $image = base64_encode($row['chambre_image']);
                    $remise = $row['remise'] ? "<sup style='padding: 5px; color: #ffffff; background-color: rgb(234, 10, 84);border-radius: 33mm;'>Remise de " . $row['pourcentage_remise'] . "%</sup>" : "";
                    echo "
                    <div class='col-md-4'>
                        <div class='card room-card'>
                            <img src='data:image/jpeg;base64, $image' alt='{$row['chambre_nom']}' style='border-radius: 25% 10%;' class='card-img-top'>
                            <div class='card-body'>
                                <h3 class='card-title'>{$row['chambre_nom']} - {$row['hotel_nom']}</h3>
                                <p class='card-text'>{$row['chambre_description']}</p>
                                <p class='card-text'>Prix par nuit: \${$row['cout']} $remise</p>
                                <button class='btn btn-primary reserve-btn' data-chambre-id='{$row['id']}'>Réservez maintenant</button>
                            </div>
                        </div>
                    </div>";
                
                }
            } else {
                echo "<p>Aucune chambre disponible pour cet hôtel.</p>";
            }
            ?>
        </div>
    </section>
    <footer>
        <p>&copy; 2023 Réservation d'Hôtel</p>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionnez tous les boutons de réservation
    const reserveButtons = document.querySelectorAll('.reserve-btn');

    reserveButtons.forEach(button => {
        // Écoutez le clic sur chaque bouton
        button.addEventListener('click', function() {
            // Récupérez l'ID de la chambre à partir de l'attribut data
            const chambreId = button.getAttribute('data-chambre-id');

            // Stockez l'ID de la chambre dans le localStorage
            localStorage.setItem('chambreId', chambreId);

            // Redirigez vers la page de réservation
            window.location.href = 'reservation.html';
        });
    });
});
</script>

</body>

</html>

<?php
$conn->close();
?>
