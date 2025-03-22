# Web service - Mise à jour de la clé d'un quiz

Ce plugin Moodle fournit un web service permettant de mettre à jour la clé (mot de passe) d'un quiz.

## Description

Ce plugin ajoute un nouveau web service à Moodle qui permet de mettre à jour la clé d'un quiz. Il vérifie que le module spécifié est bien une activité de type quiz avant d'autoriser la mise à jour.

## Fonctionnalités

- Mise à jour de la clé d'un quiz via un web service
- Vérification que le module est bien une activité de type quiz
- Gestion des permissions utilisateur
- Messages d'erreur en français et en anglais

## Installation

1. Copier le contenu du plugin dans le dossier `local/ws_mod_quiz_update_key/` de votre installation Moodle
2. Se connecter à Moodle en tant qu'administrateur
3. Aller dans les notifications pour installer le plugin
4. Activer le web service dans Administration > Plugins > Web services > Gérer les services
5. Créer un token d'accès pour les utilisateurs qui utiliseront le service

## Utilisation

Le web service accepte les paramètres suivants :
- `cmid` : ID du module de cours (course module id)
- `key` : Nouvelle clé du quiz

### Exemple de réponse en cas de succès
```json
{
    "result": true
}
```

### Exemple de réponse en cas d'erreur
```json
{
    "exception": "moodle_exception",
    "errorcode": "modulenotquiz",
    "message": "Le module spécifié n'est pas une activité de type quiz"
}
```

## Tests unitaires

Le plugin inclut des tests unitaires qui vérifient :
- La mise à jour réussie de la clé d'un quiz
- La gestion des erreurs pour un module qui n'est pas un quiz

Pour exécuter les tests :
```bash
vendor/bin/phpunit local/ws_mod_quiz_update_key/tests/external_test.php
```

## Licence

Ce plugin est sous licence MIT. Voir le fichier LICENSE pour plus de détails. 