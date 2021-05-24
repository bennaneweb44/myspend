## Réinitialiser la base de données
php bin/console doctrine:database:drop --force

## Supprimer tout le pool du cache serveur
php bin/console cache:pool:clear cache.global_clearer

## Création de la base de données
php bin/console doctrine:database:create

## Exécuter les migrations
php bin/console doctrine:migrations:migrate

## Option redéploiement : Supprimer les caches doctrine
php bin/console doctrine:cache:clear-query-region
php bin/console doctrine:cache:clear-query
php bin/console doctrine:cache:clear-result

## Option redéploiement : forcer un reload du classmap de composer
composer dump-autoload --no-cache