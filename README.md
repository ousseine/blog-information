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
- imageName
- imageFile (vich uploader)
- updated_at

### category
- name
- article_id (liaison avec les articles)

### comment
- name
- article
- message
- published_at

### user
- email
- role
- password

## Améliorations

#### Date : 12/12/22
- sécuriser la partie admin -> check
- ajouter du bootstrap & bootswatch -> check
- créer un bloc partagé -> check
- ajouter une image à la une -> check

#### Date : 13-14/12/22
- recherche -> check
- filtration (tri : knp paginator) -> check
- commentaires imbriqués -> check


