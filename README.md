Voici ce que j'ai suivi comme règles pour le refacto :

- nommer les variable camelCase (j'ai renommé certaines variables qui commençaient par des underscores)
- tester les cas d'erreur au plus tôt, avec notamment des if en début de fonction
- respecter le principe SRP Single Responsability Principle
- J'ai déplacé le remplacement des 'token' dans différentes petites classes qui héritent toute d'une classe abstraite afin que dans le code appelant on ai tojours les mêmes fonctions à appeler
- une classe abstraite et pas une interface, car j'ai mis toute la logique commune dans cette classe (replacement avec str_replace)
- lister l'ensemble des classes 'token' dans une constante, afin d'obtenir une programmation par donnée (la boucle foreach dans TemplateManager)


Ce que j'aurai aimé faire en plus :

- Faire une classe pour le token user
- Sortir la liste des tokens et soit :
- La mettre dans une autre classe (par ex. TokenList)
- utiliser l'injection de dépendance de Symfony et notament !@tag pour obtenir l'ensemble des classes qui hérite de TemplateToken
- J'ai tout mis dans Helper, mais un dossier Token aurai plus aviser
- Créer une classe qui se charge de vérifier que les données reçues en tableau soient transformé en objet et effectue les tests qui sont faits dans TemplateManager::computeText (Notamment les tests sur $data['quote'])
- Ensuite j'aurai aimé ajouter plus de typage fort pour éliminer les tests 'instanceof', et aussi sur les retours de fonction


Avantage de la solution :
- l'ajout d'un nouveau comportement est très simple : une classe à ajouter et à mettre dans une liste (sauf si on utilise l'injection de Symfony)


Incovénient :
- Ne permet pas l'ajout de nouveau comportement qui nécessiterait d'autre donnée dans la méthode 'process'
