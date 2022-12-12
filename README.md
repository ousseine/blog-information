# Créer un blog

## Plan 
- créer une base de donnée Sqlite -> check
- créer les entités et leurs relations -> check
- générer des données grace à des Fixtures -> check
- créer l'interface utilisateur
- créer l'interface admin

## Interface utilisateur
- lister des articles (avec pagination) : home page -> check
- lister des articles par catégorie -> check
- lire un article (avec les commentaires associés) -> check
- ajouter un commentaire -> check

## interface admin
- ajouter un article -> check
- supprimer un article -> check
- éditer un article -> check
- ajouter une catégorie -> check
- supprimer une catégorie -> check
- éditer une catégorie -> check
- supprimer un commentaire -> check

## Les entités
### article
- id
- title
- slug
- summary
- content
- published_at
- category (liaison avec les catégories)

### category
- name
- article_id (liaison avec les articles)

### comment
- name
- article
- message
- published_at

### Demain matin : 12/12/22
- sécuriser la partie admin
- ajouter du bootstrap & bootswatch
- créer un bloc partagé
- ajouter une image à la une


