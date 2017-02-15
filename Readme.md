** For the installation **

- D'abord on l'ajoute à son composer `composer require cowaboo/api`
- ensuite on met à jour son autoload `composer dumpautoload`
- pour finir on publish les assets `php artisan vendor:publish --provider="Cowaboo\Api\ApiServiceProvider" --tag=assets`
