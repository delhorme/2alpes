<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation et récupération des données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Clé secrète obtenue lors de l'enregistrement du site
    $secretKey = "6Leh8MMqAAAAAO0hAJxfxjdp6Ei2jEK7CSM068Zj";

    // Réponse du reCAPTCHA
    $response = $_POST['g-recaptcha-response'];

    // Vérification de la réponse
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $data = array(
        'secret' => $secretKey,
        'response' => $response
    );

    // Envoi de la demande de vérification via cURL (plus fiable)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseJson = curl_exec($ch);
    curl_close($ch);

    $responseArray = json_decode($responseJson);

    // Vérification du résultat
    if ($responseArray->success) {
        // Le reCAPTCHA est validé, on peut envoyer l'email

        // Vérifier si les champs sont bien remplis et si l'email est valide
        if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Configuration des en-têtes et du message
            $to = "contact@2alpeslocation.com"; // Remplacez par votre adresse email
            $subject = "Nouveau message de contact depuis le site 2alpeslocation.com";
            $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

            // En-têtes supplémentaires pour éviter les problèmes de spoofing
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            // Envoi de l'email
            if (mail($to, $subject, $body, $headers)) {
                echo "Message envoyé avec succès!";
                // Redirection après 2 secondes
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'contact.html'; // Remplacez 'contact.html' par l'URL de votre page de contact
                        }, 2000);
                      </script>";
            } else {
                echo "Erreur lors de l'envoi du message. Veuillez réessayer plus tard.";
            }
        } else {
            echo "Veuillez remplir tous les champs correctement. Assurez-vous que votre adresse email est valide.";
        }
    } else {
        // Si le reCAPTCHA échoue
        echo "La vérification reCAPTCHA a échoué. Veuillez réessayer.";
    }
}
?>
