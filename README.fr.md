# Résumé

<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Front.png" height="200" />
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/3DModel.gif" height="200" />
</p>


Ce dispositif permet de monitorer la clôture éléctrique des parc d'élevage. Il s'agit d'un équipement autonome, connecté et simple d'utilisation qui avertit l'éleveur d'une chute de la puissance électrique et donc d'une possible fuite des animaux.
Cet appareil est connecté au moyen du réseau SigFox.

# Contexte
La clôture électrique (https://fr.wikipedia.org/wiki/%C3%89lectrificateur_de_cl%C3%B4ture) est un dispositif agricole permettant de retenir les animaux dans un parc.
Une différence de potentiel électrique d'environ 10000V pendant 1ms est régulièrement déchargé entre la terre et le fil électrique.
Lorsqu'un animal, qui touche la terre, entre en contact avec le fil, il est traversé par un courant électrique qui le repousse.
<p align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Electric_fence_01.jpg/220px-Electric_fence_01.jpg" />
</p>

Le problème régulièrement rencontré est la divagation des animaux qui passent au travers de la barrière électrique, soit parce qu'elle est rompue, soit parce que la décharge électrique n'est pas suffisante et donc non ressentie par l'animal.
Une cause souvent rencontré qui cause la perte de puissance et la présence de végétaux sur le fil qui relient la terre au fil est cause un perte de charge.

# Idée
L'idée est de créer un dispositif, simple d'utilisation, qui est relié à la terre et au fil électrique et qui communique régulièrement (par exemple toutes les heures) la puissance électrique mesurée de la clôture. Si la valeur chute en deçà d'un seuil (par exemple 2000V), une alerte est envoyée à l'éleveur.

# Etat de l'art
En février 2015, le boom des objets connectés commence. L'agriculture est un terreau immense et peu d'acteur sont présent.
Les objets connectés les plus rencontrés sont à destinations des céréaliers avec notamment des stations météorologiques.
Concernant la surveillance de clôture électrique, peu de produit sont sur le marché et les seuls solutions trouvées sont à base de GSM, très energivore et nécessitant un abonnement.
Le réseau Sigfox (https://www.sigfox.com/) fait sont apparition et prévoit de couvrir la France puis de nombreux autres pays à l'avenir. Il s'agit d'un réseau longue porté à très faible débit (quelques octet / heure, mais pour envoyer une mesure c'est adapté). Il nécessite un abonnement de moins de 10€/an/objet.

<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Couverture%20Sigfox.JPG" />
</p>

# Prototype
## Electronique
Voir : https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/Hardware

Plusieurs microcontroleurs sont préconisés par Sigfox (voir : https://partners.sigfox.com/products/soc). Le choix s'est porté sur la version API du chip AX-SFEU développé par ON Semiconductor : http://www.onsemi.com/PowerSolutions/product.do?id=AX-SFEU
<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/AX-SFEU_BlocDiagram.PNG" />
</p>
Ce boitier inclut principalement un coeur de calcul 16 bits, un périphérique radio utilisable en émission et réception, des GPIO (TOR et analogique) et des timers.
Il est très basse consommation du fait de sa capacité à pouvoir s'endormir (deep sleep) durant un temps dérisé.
Voici le schéma incluant ce chip et les divers composants passifs permettant l'emission radio :
<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/SchematicRadio.PNG" />
</p>

La partie mesure est constitué de 2 résistances formant un simple pont diviseur par 10000 permettant de ramener la tension de la clôture électrique à un viveau acceptable par le microcontroleur. Une diode Zener permet de protéger le système des éventuelles trop haute tension ainsi que des inversions de courant.
<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/SchematicRadio.PNG" />
</p>

L'antenne est une antenne SMA fouet 1/4 d'onde 868MHz. Le connecteur SMA traverse le boitier.

L'énergie électrique est fournie par 2 piles lithium 1/2 AA SAFT LS14250 3.6V 1.2Ah. Cela permet une autonomie de 3 ans pour 10 mesures et messages radio emis par jour.

Le routage est réalisé de manière a ce que les parties puissance et radio soient éloignées.
<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/Routage.PNG" />
</p>


## Mécanique
<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/3DModel.gif" height="200" />
</p>
La mécanique est constitué d'un boitier IP64 étanche.


Pour réaliser la mesure de différence de potentiel, le boitier doit à la fois être connecté à la terre et au fil électrique.
A l'arrière du boitier sont présents 2 clips métalliques. Lorsqu'ils sont relié (au clips sur un cyclindre métallique, comme un piquet de clôture), l'appareil démarre. De plus, ces dexu clips doivent être reliés à la terre.
Enfin, l'anneau latéral doit être connecté au fil de clôture.

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Power.gif" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Front.png" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Side.png" height="200" />
</p>


## Logiciel embarqué
Le processeur AX-SFEU est reprogrammable au moyen d'un kit de développement. Son architecture est basé sur un coeur 8052. Le compilateur IAR pour 8052 a été utilisé pour générer le binaire compilé pour cette cible. Le tout est intégré dans le logiciel AxCode::Blocks (http://www.codeblocks.org/).

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/CodeBlocks.PNG" height="200" />
</p>

Le source C embarqué dans le processeur est disponible ici : https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/EmbeddedSoftware
Malheureusement, une NDA signée avec OnSemi ne m'autorise pas à publier le code qui concerne la radio. Celui-ci est remplacé par un commentaire indiquant la confidentialité.

L'algorithme est basique :
- L'appareil se réveille toutes les 2h (utilisation du périphérique deep sleep)
- Il effecture une mesure : echantillonnage de la tension en sortie du pont diviseur pendant 1s à 100kHz (10µs), et conservation de la pus haute valeur mesurée qui correspond au pic de tension.
- Il envoie la mesure par radio via le réseau Sigfox
- L'appareil se rendort pendant 2h


## Backend
Une interface utilisateur a été développée en PHP. Il s'agit d'un plugin Wordpress disponible ici : https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/Web/wp-content/plugins/ConnectedFencePlugin
Le choix a été fait d'utiliser wordpress afin de bénéficier d'un gestionnaire de contenu déjà développé, une gestion des utilisateurs, un environnement pluginable à souhait et peu de graphisme à réaliser car Wordpress est thémifiable.
Le plugin permet à un utilisateur connecté de gérer ses objets connectés. Il peut en ajouter un nouveau via une interface qui lui propose d'entrer l'identifiant de l'objet nouvellement acquis.
Une autre interface permet de visionner les données. 
Le plugin se connecte directement, via CURL, au backend SigFox pour rapatrier les mesures. Aucune mesure n'est stockées en locale.

## Essais
L'utilisateur peut visionner la tension mesurée au cours du temps via ce type de courbe où on peut apercevoir une chute de tension :
<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Samples.PNG" />
</p>
10 Exemplaire ont été réalisé et testé en condition réelles :
<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Soldering.png" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Collection1.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Collection2.png"  height="200" />
  <br />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test1.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test2.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test3.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test4.png"  height="200" />
</p>

