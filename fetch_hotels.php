<?php
include 'db.php'; // Connexion à la base de données

// Requête pour récupérer tous les hôtels
$sql = "SELECT id, name, description, price_per_night, image FROM hotels";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Générer le HTML pour les lignes de tableau des hôtels
    foreach ($result as $hotel) {
        echo "<tr>";
        echo "<td>{$hotel['id']}</td>";
        echo "<td>{$hotel['name']}</td>";
        echo "<td>{$hotel['description']}</td>"; // Assurez-vous que 'description' est correctement récupéré
        echo "<td>{$hotel['price_per_night']} €</td>"; // Afficher le prix avec €
        echo "<td width='180px'>";
        echo "<button class='btn btn-info viewHotelBtn' data-hotelid='{$hotel['id']}'><i class='fa fa-eye'></i> </button>";
        echo "<button class='btn btn-primary editHotelBtn' data-hotelid='{$hotel['id']}'><i class='fa fa-edit'></i>  </button>";
        echo "<button class='btn btn-danger deleteHotelBtn' data-hotelid='{$hotel['id']}'><i class='fa fa-trash'></i>  </button>";
        echo "</td>";
        echo "</tr>";
}} else {
    echo '<tr><td colspan="4">Aucun hôtel trouvé.</td></tr>';
}

$conn->close();
?>
