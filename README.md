![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# CYBER AUDIT CODE SYMFONY

Faire une audit de code du projet "Taxe Pasdebol"

### Pré-requis
- [ ] Installer Docker : [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)
- [ ] Installer Docker Compose : [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)

### Optionnel :
- [ ] Installer TaskFile > v3.20 : [https://taskfile.dev/installation/](https://taskfile.dev/installation/)

### Démarrer le projet

Installation et démarrage des services via docker
```shell
docker compose up -d
```
Démarrage du serveur Symfony
```shell
symfony serve -d
```

Si vous avez Taskfile
```
task start
```

Migration et installation des fixtures
```shell
symfony console d:m:m
symfony console d:f:l
```
Une fois les services disponibles :

Le code à auditer est disponible sur l'URL : [http://localhost:8000](http://localhost:8000)

L'administrateur à la base de donnée : [http://localhost:8081](http://localhost:8081)

### Comptes utilisateurs

Administrateur
```shell
admin@mail.dev / password
```
Entreprise
```shell
company@mail.dev / password
```
