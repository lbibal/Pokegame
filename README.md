# pokegame
Il faut une version PHP supérieur à 8 car le projet est en Symfony 6.3.*, il faut avoir accès à Internet pour pouvoir avoir le rendu Bootstrap.

Il faut créer la base de donnée appelé webtp8, sous MariaDB.

Il faut ensuite modifié le fichier .env en suivant votre configuration (``` DATABASE_URL="mysql://root:@127.0.0.1:3307/webtp8"```), s'il y a un problème il faut regarder le port.

Suivez les instructions ci-dessous : 

pour mettre à jour les dépendances : ```composer update```

Si les tables ne sont pas générés  :

- pour générer les tables dans la base de données : ```php bin/console doctrine:schema:update --force```

- pour générer les datas nécessaire : ``` php bin/console doctrine:fixtures:load ```

pour lancer l'application : ``` symfony server:start ``` 

pour fermer le serveur: ``` symfony server:stop ``` 

Dans l'application il y a deux comptes : compte par défaut : username : Lucas; mot de passe : password;
et username : pascal; mot de passe : azerty;
