<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Chambres</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            padding: 20px;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Gestion des Chambres</h2>
        <button class="btn btn-primary my-3" data-toggle="modal" data-target="#roomModal"><i class="fa fa-plus"></i> Ajouter une Chambre</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Remise</th>
                    <th>Pourcentage Remise</th>
                    <th>Coût</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="roomTable">
                <!-- Room rows will be inserted here by PHP -->
                <?php
                include 'db.php';
                $sql = "SELECT * FROM chambres";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['description']}</td>
                            <td><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' width='100' height='100'/></td>
                            <td>" . ($row['remise'] ? 'Oui' : 'Non') . "</td>
                            <td>{$row['pourcentage_remise']}</td>
                            <td>{$row['cout']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['id']}'><i class='fa fa-edit'></i></button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'><i class='fa fa-trash'></i></button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Aucune chambre trouvée</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Add/Edit Room -->
    <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomModalLabel">Ajouter une Chambre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="roomForm" enctype="multipart/form-data">
                        <input type="hidden" id="roomId" name="roomId">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="form-group">
                            <label for="remise">Remise</label>
                            <input type="checkbox" id="remise" name="remise">
                        </div>
                        <div class="form-group">
                            <label for="pourcentage_remise">Pourcentage Remise</label>
                            <input type="number" class="form-control" id="pourcentage_remise" name="pourcentage_remise" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="cout">Coût</label>
                            <input type="number" class="form-control" id="cout" name="cout" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="hotel_id">ID de l'Hôtel</label>
                            <input type="number" class="form-control" id="hotel_id" name="hotel_id" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submit
            $('#roomForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'save_room.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        location.reload();
                    }
                });
            });

            // Handle edit button click
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_room.php',
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        var room = JSON.parse(response);
                        $('#roomId').val(room.id);
                        $('#nom').val(room.nom);
                        $('#description').val(room.description);
                        $('#pourcentage_remise').val(room.pourcentage_remise);
                        $('#cout').val(room.cout);
                        $('#hotel_id').val(room.hotel_id);
                        $('#remise').prop('checked', room.remise == 1);
                        $('#roomModalLabel').text('Modifier la Chambre');
                        $('#roomModal').modal('show');
                    }
                });
            });

            // Handle delete button click
            $('.delete-btn').on('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'delete_room.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            alert(response);
                            location.reload();
                        }
                    });
                }
            });

            // Reset form on modal close
            $('#roomModal').on('hidden.bs.modal', function() {
                $('#roomForm')[0].reset();
                $('#roomId').val('');
                $('#roomModalLabel').text('Ajouter une Chambre');
            });
        });
    </script>
</body>
</html>
