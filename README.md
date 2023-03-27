# Challenge S1: Groupe 4 - Rattrapage

## Membres du groupe:
- Nikola PANIC
- Souleymane GUEYE
- Sacha FRANCISCO-LEBLANC
- Mehdi SABER

## Installation

Créez un fichier dans le dossier ``api`` et nommez-le ``.env.local``. 
Renseignez-y la variable ``MAILER_DSN`` avec les informations de votre compte mailtrap.io, ou bien tout serveur SMTP de votre choix.

Plus d'informations [ici](https://symfony.com/doc/current/mailer.html).

Puis exécutez:

```bash
docker compose build --pull --no-cache
docker compose up -d
```

