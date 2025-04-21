<?php
// Vérifier si un CIN a été fourni
if (!isset($_GET['cin']) || empty($_GET['cin'])) {
    header("Location: tableau.php");
    exit();
}

$cin = $_GET['cin'];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adfm";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT CIN, `prénom`, nom, email, `téléphone`, `mot_de_passe` FROM user WHERE CIN = ?");
$stmt->bind_param("s", $cin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Utilisateur non trouvé
    header("Location: tableau.php");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password']; // Dans un cas réel, vérifier si le mot de passe a été modifié

    // Validation côté serveur
    $errors = [];

    // Validation prénom
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
        $errors[] = "Le prénom doit contenir uniquement des lettres";
    }

    // Validation nom
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $errors[] = "Le nom doit contenir uniquement des lettres";
    }

    // Validation email
    if (empty($email)) {
        $errors[] = "L'email est obligatoire";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    } elseif (!preg_match("/@gmail\.com$/", $email)) {
        $errors[] = "L'email doit être au format @gmail.com";
    }

    // Validation téléphone
    if (empty($telephone)) {
        $errors[] = "Le téléphone est obligatoire";
    } elseif (!preg_match("/^06\d{8}$/", $telephone)) {
        $errors[] = "Le téléphone doit être au format 06XXXXXXXX";
    }

    // Validation mot de passe (seulement si fourni)
    if (!empty($password)) {
        // Si un nouveau mot de passe est fourni, on le valide
        // Sinon, on garde l'ancien
    } else {
        // Si le champ est vide, on utilise l'ancien mot de passe
        $password = $user['mot_de_passe'];
    }

    // S'il n'y a pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        // Mise à jour dans la base de données
        $update_stmt = $conn->prepare("UPDATE user SET `prénom` = ?, nom = ?, email = ?, `téléphone` = ?, `mot_de_passe` = ? WHERE CIN = ?");
        $update_stmt->bind_param("ssssss", $prenom, $nom, $email, $telephone, $password, $cin);

        if ($update_stmt->execute()) {
            // Mise à jour dans le fichier JSON
            $jsonFile = 'utilisateurs.json';
            if (file_exists($jsonFile)) {
                $jsonData = file_get_contents($jsonFile);
                $usersArray = json_decode($jsonData, true);
            } else {
                $usersArray = [];
            }

            // Mettre à jour l'utilisateur dans le tableau JSON
            foreach ($usersArray as &$userData) {
                if ($userData['CIN'] == $cin) {
                    $userData['prénom'] = $prenom;
                    $userData['nom'] = $nom;
                    $userData['email'] = $email;
                    $userData['téléphone'] = $telephone;
                    $userData['mot_de_passe'] = $password;
                    break;
                }
            }

            // Sauvegarder les données mises à jour dans le fichier JSON
            file_put_contents($jsonFile, json_encode($usersArray, JSON_PRETTY_PRINT));

            // Redirection vers le tableau avec un message de succès
            header("Location: tableau.php?success=Utilisateur modifié avec succès");
            exit();
        } else {
            $errors[] = "Erreur lors de la mise à jour: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
}

$conn->close();
?>

<!-- HTML code remains unchanged -->


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container">
        <h2>Modifier l'utilisateur</h2>

        <?php
        // Afficher les messages d'erreur
        if (isset($errors) && !empty($errors)) {
            echo '<div class="error-message">' . implode("<br>", $errors) . '</div>';
        }
        ?>


        <form id="modificationForm" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="cin">CIN:</label>
                <input type="text" id="cin" name="cin" value="<?php echo htmlspecialchars($user['CIN']); ?>" readonly>
                <span class="note">Le CIN ne peut pas être modifié</span>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prénom']); ?>">
                <span class="error" id="prenomError"></span>
            </div>

            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>">
                <span class="error" id="nomError"></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <span class="error" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone"
                    value="<?php echo htmlspecialchars($user['téléphone']); ?>">
                <span class="error" id="telephoneError"></span>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password"
                    placeholder="Laissez vide pour conserver l'ancien mot de passe">
                <span class="error" id="passwordError"></span>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">Enregistrer les modifications</button>

            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Réinitialiser les messages d'erreur
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            // Validation prénom
            const prenom = document.getElementById('prenom').value.trim();
            if (prenom === '') {
                document.getElementById('prenomError').textContent = 'Le prénom est obligatoire';
                isValid = false;
            } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(prenom)) {
                document.getElementById('prenomError').textContent = 'Le prénom doit contenir uniquement des lettres';
                isValid = false;
            }

            // Validation nom
            const nom = document.getElementById('nom').value.trim();
            if (nom === '') {
                document.getElementById('nomError').textContent = 'Le nom est obligatoire';
                isValid = false;
            } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nom)) {
                document.getElementById('nomError').textContent = 'Le nom doit contenir uniquement des lettres';
                isValid = false;
            }

            // Validation email
            const email = document.getElementById('email').value.trim();
            if (email === '') {
                document.getElementById('emailError').textContent = 'L\'email est obligatoire';
                isValid = false;
            } else if (!email.endsWith('@gmail.com')) {
                document.getElementById('emailError').textContent = 'L\'email doit être au format @gmail.com';
                isValid = false;
            }

            // Validation téléphone
            const telephone = document.getElementById('telephone').value.trim();
            if (telephone === '') {
                document.getElementById('telephoneError').textContent = 'Le téléphone est obligatoire';
                isValid = false;
            } else if (!/^\d+$/.test(telephone)) {
                document.getElementById('telephoneError').textContent = 'Le téléphone doit contenir uniquement des chiffres';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>