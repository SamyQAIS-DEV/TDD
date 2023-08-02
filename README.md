# Projet exemple TDD

## Installation locale

### Docker

```
docker compose up --build -d
```

### Initialisation BDD de dev

```
docker compose exec web bin/console doctrine:schema:update -f --complete
docker compose exec web bin/console doctrine:fixtures:load -n
```


### Initialisation BDD de test

```
docker compose exec web bin/console doctrine:schema:update --env test -f --complete
docker compose exec web bin/console doctrine:fixtures:load --env test -n
```

### Lancement tests PHPUnit


```
docker compose exec web bin/phpunit
docker compose exec web bin/phpunit --testdox (pour avoir plus d'infos)
docker compose exec web bin/phpunit --filter UserTest (pour tester un fichier individuellement)
docker compose exec web bin/phpunit --filter UserTest::testInvalidUsedEmailEntity (pour tester une méthode individuellement)
```
