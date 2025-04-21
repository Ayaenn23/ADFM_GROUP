<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $cin = $_POST['cin'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';

    // Préparation de l'URL de redirection avec les valeurs des champs
    $redirect_url = "index.php?" . http_build_query([
        'cin' => $cin,
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'telephone' => $telephone
    ]);

    $has_errors = false;

    // Vérifier si le CIN existe déjà
    $checkCIN = $conn->prepare("SELECT CIN FROM user WHERE CIN = ?");
    $checkCIN->bind_param("s", $cin);
    $checkCIN->execute();
    $checkCIN->store_result();

    if ($checkCIN->num_rows > 0) {
        $redirect_url .= "&cin_error=" . urlencode("Ce CIN est déjà utilisé. Veuillez en saisir un autre.");
        $has_errors = true;
    }
    $checkCIN->close();

    // Validation CIN : 2 lettres majuscules + 5 chiffres
    if (empty($cin)) {
        $redirect_url .= "&cin_error=" . urlencode("Le CIN est obligatoire");
        $has_errors = true;
    } elseif (!preg_match("/^[A-Z]{2}\d{5}$/", $cin)) {
        $redirect_url .= "&cin_error=" . urlencode("Le CIN doit contenir 2 lettres majuscules suivies de 5 chiffres (ex: AB12345)");
        $has_errors = true;
    }

    // Validation prénom
    if (empty($prenom)) {
        $redirect_url .= "&prenom_error=" . urlencode("Le prénom est obligatoire");
        $has_errors = true;
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
        $redirect_url .= "&prenom_error=" . urlencode("Le prénom doit contenir uniquement des lettres");
        $has_errors = true;
    }

    // Validation nom
    if (empty($nom)) {
        $redirect_url .= "&nom_error=" . urlencode("Le nom est obligatoire");
        $has_errors = true;
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $redirect_url .= "&nom_error=" . urlencode("Le nom doit contenir uniquement des lettres");
        $has_errors = true;
    }

    // Validation email : @gmail.com + au moins 3 caractères avant
    if (empty($email)) {
        $redirect_url .= "&email_error=" . urlencode("L'email est obligatoire");
        $has_errors = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $redirect_url .= "&email_error=" . urlencode("Format d'email invalide");
        $has_errors = true;
    } elseif (!preg_match("/^[a-zA-Z0-9._%+-]{3,}@gmail\.com$/", $email)) {
        $redirect_url .= "&email_error=" . urlencode("L'email doit être au format quelquechose@gmail.com (au moins 3 caractères avant @)");
        $has_errors = true;
    }

    // Validation téléphone
    if (empty($telephone)) {
        $redirect_url .= "&telephone_error=" . urlencode("Le téléphone est obligatoire");
        $has_errors = true;
    } elseif (!preg_match("/^06\d{8}$/", $telephone)) {
        $redirect_url .= "&telephone_error=" . urlencode("Le téléphone doit être au format 06XXXXXXXX");
        $has_errors = true;
    }

    // Validation mot de passe
    if (empty($password)) {
        $redirect_url .= "&password_error=" . urlencode("Le mot de passe est obligatoire");
        $has_errors = true;
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@\-\/_!]).{8,}$/", $password)) {
        $redirect_url .= "&password_error=" . urlencode("Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un symbole (@ - / _ !)");
        $has_errors = true;
    }

    if ($has_errors) {
        header("Location: " . $redirect_url);
        exit();
    }

    // Insertion
    $stmt = $conn->prepare("INSERT INTO user (CIN, nom, `prénom`, email, `téléphone`, `mot_de_passe`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cin, $nom, $prenom, $email, $telephone, $password);
    
    if ($stmt->execute()) {
        // Génération automatique du fichier JSON après insertion
        $sql_json = "SELECT * FROM user";
        $result_json = $conn->query($sql_json);

        if ($result_json->num_rows > 0) {
            $data = [];
            while ($row = $result_json->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents("utilisateurs.json", $json_data);
        }

        // Redirection vers le tableau
        header("Location: tableau.php");
        exit();
    } else {
        // En cas d'erreur d'insertion
        $redirect_url .= "&error=" . urlencode("Une erreur est survenue lors de l'inscription");
        header("Location: " . $redirect_url);
        exit();
    }

    $stmt->close();

}

$conn->close();
?>






