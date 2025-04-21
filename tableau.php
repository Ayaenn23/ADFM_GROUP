<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Utilisateurs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            padding: 10px;
            display: block;
        }

        .container {
            max-width: 80%;
            width: 1200px;
            margin: 20px auto;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 16px;
        }

        th,
        td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgba(255, 255, 255, 0.28);
            color: black;
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(181, 181, 181, 0.25);
        }

        .back-btn,
        .action-btn {
            background-color: rgb(30, 125, 22);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }

        .back-btn {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 16px;
        }

        .action-btn.delete {
            background-color: #e74c3c;
        }

        .action-btn:hover,
        .back-btn:hover {
            opacity: 0.9;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .actions-cell {
            white-space: nowrap;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .success-message {
            background-color: #e8f5e9;
            color: #43a047;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #43a047;
            font-size: 16px;
        }

        .error-message {
            background-color: #ffebee;
            color: #e53935;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #e53935;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="theme-buttons">
            <button class="theme-icon" data-theme="violet" style="background-color: #8a2be2;"></button>
            <button class="theme-icon" data-theme="rose" style="background-color: #ff69b4;"></button>
            <button class="theme-icon" data-theme="vert" style="background-color: #32cd32;"></button>
            <button class="theme-icon" data-theme="orange" style="background-color:rgb(255, 72, 0);"></button>
            <button class="theme-icon" data-theme="jaune" style="background-color: #ffd700;"></button>
            <button class="theme-icon" data-theme="bleu" style="background-color: #007bff;"></button>
            <button class="theme-icon" data-theme="cyan" style="background-color:rgb(255, 0, 0);"></button>
            <button class="theme-icon" data-theme="magenta" style="background-color:rgb(181, 10, 115);"></button>
            <button class="theme-icon" data-theme="blanc" style="background-color: #ffffff;"></button>
            <button class="theme-icon" data-theme="noir" style="background-color: #000000;"></button>
        </div>
        <h2>Tableau des Utilisateurs</h2>

        <?php
        // Afficher les messages de succès ou d'erreur
        if (isset($_GET['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_GET['success']) . '</div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>CIN</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "adfm";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $conn->set_charset("utf8mb4"); // Important pour les caractères accentués
                    
                    if ($conn->connect_error) {
                        die("La connexion a échoué: " . $conn->connect_error);
                    }

                    // Vérifier s'il y a une demande de suppression
                    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
                        $cin_to_delete = $_GET['delete'];

                        // Préparer la requête de suppression
                        $delete_stmt = $conn->prepare("DELETE FROM user WHERE CIN = ?");
                        $delete_stmt->bind_param("s", $cin_to_delete);

                        if ($delete_stmt->execute()) {
                            echo '<div class="success-message">Utilisateur supprimé avec succès!</div>';
                        } else {
                            echo '<div class="error-message">Erreur lors de la suppression: ' . $delete_stmt->error . '</div>';
                        }

                        $delete_stmt->close();
                    }

                    // Récupérer tous les utilisateurs
                    $sql = "SELECT CIN, `prénom`, nom, email, `téléphone` FROM user";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Afficher les données de chaque utilisateur
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["CIN"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["prénom"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["téléphone"]) . "</td>";
                            echo "<td class='actions-cell'>";
                            echo "<a href='modifier.php?cin=" . $row["CIN"] . "' class='action-btn'>Modifier</a>";
                            echo "<a href='javascript:void(0)' onclick='confirmDelete(\"" . $row["CIN"] . "\")' class='action-btn delete'>Supprimer</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='no-data'>Aucun utilisateur trouvé</td></tr>";
                    }
                    // Vérifier s'il y a une demande de suppression
                    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
                        $cin_to_delete = $_GET['delete'];

                        // Charger le fichier JSON
                        $json_file = 'utilisateurs.json';  // Le chemin vers ton fichier JSON
                        $data = json_decode(file_get_contents($json_file), true);  // Lire et décoder le JSON en tableau PHP
                    
                        // Vérifier que le fichier JSON a bien été chargé
                        if ($data === null) {
                            echo 'Erreur lors du chargement du fichier JSON';
                            exit;
                        }

                        // Filtrer les utilisateurs pour exclure celui à supprimer
                        $filtered_data = array_filter($data, function ($user) use ($cin_to_delete) {
                            return $user['CIN'] !== $cin_to_delete;  // Comparer le CIN et exclure l'utilisateur
                        });

                        // Réindexer les données (car array_filter préserve les clés)
                        $filtered_data = array_values($filtered_data);

                        // Sauvegarder les données filtrées dans le fichier JSON
                        if (file_put_contents($json_file, json_encode($filtered_data, JSON_PRETTY_PRINT))) {
                            echo '';
                        } else {
                            echo '';
                        }
                    }


                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" class="back-btn">Retour au formulaire</a>
    </div>

    <script>
        function confirmDelete(cin) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
                window.location.href = "tableau.php?delete=" + cin;
            }
        }
    </script>
    <script src="theme.js" defer></script>

</body>

</html>