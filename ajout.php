<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adfm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST["cin"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $mot_de_passe = $_POST["mot_de_passe"];

    $sql = "INSERT INTO user (CIN, nom, prénom, email, téléphone, mot_de_passe)
  VALUES ('$cin', '$nom', '$prenom', '$email', '$telephone', '$mot_de_passe')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvel enregistrement créé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $json_data = json_encode($data, JSON_PRETTY_PRINT);

    file_put_contents("utilisateurs.json", $json_data);
}

$conn->close();
?>