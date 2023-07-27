// Remplacez 'VOTRE_CLE_PUBLIQUE' par votre clé publique Stripe
const stripe = Stripe('pk_test_51NXLdeEk6z1bmabgbKVEw7dIrihBr89Dm9JnxnMoRp88O8h9BRndexdMgEtnyBBzFsy9Aczid7C4SND850DysEzB00iKmGmGeB');

// Créer un élément de carte (Card Element)
const elements = stripe.elements();
const cardElement = elements.create('card');

// Ajouter l'élément de carte au formulaire
cardElement.mount('.card-element');

// Gérer la soumission du formulaire
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Créer un token sécurisé à partir des informations de la carte saisies par l'utilisateur
    const { token, error } = await stripe.createToken(cardElement);

    if (error) {
        // Gérer l'erreur de collecte des informations de paiement
        console.error(error.message);
    } else {
        // Ajouter le token en tant qu'élément caché dans le formulaire
        const stripeTokenInput = document.getElementById('stripeToken');
        stripeTokenInput.value = token.id;

        // Soumettre le formulaire vers le fichier de traitement du paiement
        form.submit();
    }
});
