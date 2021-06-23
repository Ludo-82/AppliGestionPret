# AppliGestionPret
Application de gestion de prêt de matériel

Nous avons travaillé sur ce projet en serveur local à savoir WAMP. Quelques pages ne sont pas opérationnelles à 100%.

Pour la page retour.php : A la ligne 34 il faut remplacer "monmail@gmail.com" par son mail.
La page ne permet pas d'envoyer une validation d'une demande de retour automatiquement. Elle permet d'envoyer un mail de confirmation à un mail prédéfini, ce dernier ce trouve à la ligne 85.

La page ajout.php correspond à la page d'ajout de matériel. 

La page delete.php permet de supprimer les plages horaires de la page horaires.php.

La page demande.php qui correspond à la page de demande de matériel permet d'envoyer un mail à la personne qui gère le prêt. Ce mail est à changé par le votre à la ligne 28.

La page intervention.php est la page la plus complexe, c'est aussi celle qui est la moins opérationnelle. 

La page modèle.php est simplement un modèle pour l'entête de chaque page. 

Les pages receptionmatnormal.php et receptionmaturgent.php contienent aussi des mails à changer par le votre. Ligne 34 pour la première et ligne 38 pour la deuxième.

La connexion à la base de données se fait via un PDO à chaque début de page. 
