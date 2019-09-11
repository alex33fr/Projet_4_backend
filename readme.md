Systeme de réservation pour Musée du Louvre


Procedure de l'installation
1. Comment recuperer le code?

Il suffit de cloner depuis GitHub
https://github.com/alex33fr/Projet_4_backend.git

ou saisir la commande dans la console:

git clone https://github.com/alex33fr/Projet_4_backend.git

2. Télécharger les vendors via le composer

Renseignez le fichier .env lors de l'installation :

APP_ENV=
APP_SECRET=
DATABASE_URL=
MAILER_URL=
STRIPE_PUBLIC_KEY=
STRIPE_PRIVATE_KEY=

3. Créez la base de données
Si la base de données que vous avez renseignée dans l'étape 2 n'existe pas déjà, créez-la :

php bin/console doctrine:database:create

Puis créez les tables via commande console Doctrine :

php bin/console make:migration
php bin/console doctrine:migrations:migrate


A vous de jouer!