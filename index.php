<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="style.css">
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
            <button class="theme-icon" data-theme="magenta" style="background-color:rgb(180, 4, 103);"></button>
            <button class="theme-icon" data-theme="blanc" style="background-color: #ffffff;"></button>
            <button class="theme-icon" data-theme="noir" style="background-color: #000000;"></button>
        </div>
        <h2>Formulaire d'Inscription</h2>

        <form id="registrationForm" action="process.php" method="POST" onsubmit="return validateForm()">
            <?php
            // Récupérer les valeurs des champs si elles existent
            $cin = isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : '';
            $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
            $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $telephone = isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '';
            ?>

            <div class="form-group">
                <label for="cin">CIN:</label>
                <input type="text" id="cin" name="cin" placeholder="Entrez votre CIN" value="<?php echo $cin; ?>">
                <span class="error" id="cinError">
                    <?php 
                    if (isset($_GET['cin_error'])) {
                        echo htmlspecialchars($_GET['cin_error']);
                    }
                    ?>
                </span>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" value="<?php echo $prenom; ?>">
                <span class="error" id="prenomError">
                    <?php 
                    if (isset($_GET['prenom_error'])) {
                        echo htmlspecialchars($_GET['prenom_error']);
                    }
                    ?>
                </span>
            </div>

            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" value="<?php echo $nom; ?>">
                <span class="error" id="nomError">
                    <?php 
                    if (isset($_GET['nom_error'])) {
                        echo htmlspecialchars($_GET['nom_error']);
                    }
                    ?>
                </span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" value="<?php echo $email; ?>">
                <span class="error" id="emailError">
                    <?php 
                    if (isset($_GET['email_error'])) {
                        echo htmlspecialchars($_GET['email_error']);
                    }
                    ?>
                </span>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone" placeholder="Entrez votre numéro de téléphone" value="<?php echo $telephone; ?>">
                <span class="error" id="telephoneError">
                    <?php 
                    if (isset($_GET['telephone_error'])) {
                        echo htmlspecialchars($_GET['telephone_error']);
                    }
                    ?>
                </span>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
                <span class="error" id="passwordError">
                    <?php 
                    if (isset($_GET['password_error'])) {
                        echo htmlspecialchars($_GET['password_error']);
                    }
                    ?>
                </span>
            </div>

            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Réinitialiser les messages d'erreur
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            // Validation CIN
            const cin = document.getElementById('cin').value.trim();
            if (cin === '') {
                document.getElementById('cinError').textContent = 'Le CIN est obligatoire';
                isValid = false;
            } else if (!/^[A-Z]{2}\d{5}$/.test(cin)) {
                document.getElementById('cinError').textContent = 'Le CIN doit contenir 2 lettres majuscules suivies de 5 chiffres (ex: AB12345)';
                isValid = false;
            }

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
            } else if (!/^[a-zA-Z0-9._%+-]{3,}@gmail\.com$/.test(email)) {
                document.getElementById('emailError').textContent = 'L\'email doit être au format quelquechose@gmail.com (au moins 3 caractères avant @)';
                isValid = false;
            }

            // Validation téléphone
            const telephone = document.getElementById('telephone').value.trim();
            if (telephone === '') {
                document.getElementById('telephoneError').textContent = 'Le téléphone est obligatoire';
                isValid = false;
            } else if (!/^06\d{8}$/.test(telephone)) {
                document.getElementById('telephoneError').textContent = 'Le téléphone doit être au format 06XXXXXXXX';
                isValid = false;
            }

            // Validation mot de passe
            const password = document.getElementById('password').value;
            if (password === '') {
                document.getElementById('passwordError').textContent = 'Le mot de passe est obligatoire';
                isValid = false;
            } else if (!/^(?=.*[A-Z])(?=.*\d)(?=.*[@\-\/_!]).{8,}$/.test(password)) {
                document.getElementById('passwordError').textContent = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un symbole (@ - / _ !)';
                isValid = false;
            }

            return isValid;
        }
    </script>

    <script src="theme.js"></script>
</body>
</html>