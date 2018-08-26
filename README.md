# Résumé
--> photo dispositif
Ce dispositif permet de monitorer la clôture éléctrique des pars d'élevage. Il s'agit d'un équipement autonome, connecté et simple d'utilisation qui avertit l'éleveur d'une chute de la puissance électrique et donc d'une possible fuite des animaux.
Cet appareil est connecté au moyen du réseau SigFox.

# Contexte
La clôture électrique (https://fr.wikipedia.org/wiki/%C3%89lectrificateur_de_cl%C3%B4ture) est un dispositif agricole permettant de retenir les animaux dans un parc.
Une différence de potentiel électrique d'environ 10000V pendant 1ms est régulièrement déchargé entre la terre et le fil électrique.
Lorsqu'un animal, qui touche la terre, entre en contact avec le fil, il est traversé par un courant électrique qui le repousse.
--> image cloture

Le problème régulièrement rencontré est la divagation des animaux qui passent au travers de la barrière électrique, soit parce qu'elle est rompue, soit parce que la décharge électrique n'est pas suffisante et donc non ressentie par l'animal.
Une cause souvent rencontré qui cause la perte de puissance et la présence de végétaux sur le fil qui relient la terre au fil est cause un perte de charge.

# Idée
L'idée est de créer un dispositif, simple d'utilisation, qui est relié à la terre et au fil électrique et qui communique régulièrement (par exemple toutes les heures) la puissance électrique mesurée de la clôture. Si la valeur chute en deçà d'un seuil (par exemple 2000V), une alerte est envoyée à l'éleveur.

# Etat de l'art
En février 2015, le boom des objets connectés commence. L'agriculture est un terreau immense et peu d'acteur sont présent.
Les objets connectés les plus rencontrés sont à destinations des céréaliers avec notamment des stations météorologiques.
Concernant la surveillance de clôture électrique, peu d'acteurs sont sur le marché et les seuls solutions trouvé sont à base de GSM, très energivore et nécessitant un abonnement.
Le réseau Sigfox (https://www.sigfox.com/) fait sont apparition et prévoit de couvrir la France puis de nombreux autres pays à l'avenir. Il s'agit d'un réseau longue porté à très faible débit (quelques octet / heure, mais pour envoyer une mesure c'est adapté). Il nécessite un abonnement de moins de 10€/an/objet.

-> image couverture sigfox

# Prototype
## Electronique

## Backend

## Essais


# Conclusion
