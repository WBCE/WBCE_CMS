**Ce document a été traduit automatiquement. Veuillez noter qu'il peut y avoir des erreurs ou des correspondances non exactes avec le libellé utilisé dans le backend.**

# Nouvelles avec images: Un nouveau module de nouvelles pour le CMS WBCE
Actualités avec images (en abrégé: NWI) facilite la création de pages de nouvelles ou de publications.
Il est basé sur "l'ancien" module d'actualités (3.5.12), mais a été étendu de plusieurs fonctions:
- Poster une photo
- galerie d'images intégrée (maçonnerie ou Fotorama)
- 2e zone de contenu facultative
- Trier les articles avec drag & drop
- Déplacement / copie de publications entre groupes et sections
- Importation de sujets et d'actualités "classiques"

La fonction de commentaire rudimentaire et peu sûr de l'ancien module de nouvelles a été abandonnée; Si nécessaire, cette fonction peut être intégrée aux modules appropriés (Global Comments / Easy Comments ou Reviews).

## Télécharger
Le module est un module de base à partir de WBCE CMS 1.4 et installé par défaut. De plus, le téléchargement est disponible dans le [Référentiel de modules complémentaires WBCE CMS] (https://addons.wbce.org).

## Licence
NWI est sous la [Licence publique générale GNU v3.0] (http://www.gnu.org/licenses/gpl-3.0.html).

## Configuration requise
NWI ne nécessite aucune configuration système particulière et fonctionne également avec les anciennes versions de WBCE ainsi que WebsiteBaker.


## installation
1. Si nécessaire, téléchargez la dernière version de [AOR] (https://addons.wbce.org)
2. Comme tout autre module WBCE via les extensions & gt; Installer des modules

## Utilisation

### Mise en route et écriture
1. Créez une nouvelle page avec "News with Images"
2. Cliquez sur "Ajouter un message"
3. Remplissez le titre et, si nécessaire, d'autres champs, sélectionnez éventuellement des images. La fonction des champs de saisie est probablement explicite.
4. Cliquez sur "Enregistrer" ou "Enregistrer et revenir en arrière"
5. Répétez les étapes 1. - 4. à quelques reprises et regardez le tout dans le frontal

En principe, NWI peut être combiné avec d'autres modules sur une page ou dans un bloc, mais il peut ensuite, comme tout module générant ses propres pages de détail, aboutir à des résultats qui ne répondent pas aux attentes / attentes.

### images dans le post
Une image d'aperçu peut être téléchargée pour chaque article. Elle est affichée sur la page d'aperçu et, si nécessaire, sur la page d'envoi. En outre, il est possible d’ajouter un nombre quelconque d’images à une publication, qui sont affichées sous forme de galerie d’images. La présentation de la galerie est présentée sous forme de galerie Fotorama (vignettes, image en largeur) ou de galerie de maçonnerie (mosaïque).

Le script de galerie utilisé est défini pour toutes les publications dans les paramètres de chaque section.

Les images de la galerie sont téléchargées au fur et à mesure que la publication est enregistrée, et peuvent ensuite être sous-titrées, utilisées ou supprimées.

Lors du téléchargement de fichiers portant le même nom que des images existantes, les fichiers existants ne sont pas écrasés, mais les fichiers suivants sont complétés par une numérotation consécutive (bild.jpg, bild_1.jpg, etc.).

La gestion des images ne se fait que sur la page de publication, pas sur l'administration des médias de WBCE, car NWI ne "sait" pas sinon, à quelle image appartiennent ou sont manquantes, etc.

### Groupes
Les messages peuvent être affectés à des groupes. Cela a d'une part une influence sur l'ordre (les articles sont triés en fonction du groupe, puis en fonction d'un critère supplémentaire à spécifier), et d'autre part, il est possible de générer des pages de synthèse par sujet. Celles-ci sont ensuite accessibles via l’URL de la page NWI avec le paramètre g? = GROUP_ID, par ex. news.php? g = 2.

Une publication ne peut être attribuée qu'à un seul groupe.

Un ou plusieurs messages peuvent être copiés et déplacés entre les groupes.

### fonction d'importation
Tant qu'aucune publication n'a été publiée dans la section NWI respective, les publications du module d'actualités classique, les autres sections de NWI ainsi que les sujets peuvent être importés automatiquement.
Les paramètres de page de la page source sont appliqués. Toutefois, lors de l’importation de publications Sujets, une retouche manuelle est toujours nécessaire si la fonction "Images supplémentaires" a été utilisée dans Sujets.

### Copier / déplacer des messages
À partir de l’aperçu des publications dans le backend, vous pouvez copier les publications individuelles, les publications sélectionnées ou toutes (marquées) d’une section, ou les copier ou les déplacer d’une section à une autre (même sur des pages différentes). Les publications copiées sont toujours initialement invisibles dans le frontal (sélection active: "non").

### Supprimer les messages
Vous pouvez supprimer un, plusieurs ou tous les articles (sélectionnés) de l'aperçu des articles. Après confirmation, les messages respectifs sont irrévocables ** DETRUITS **, il n'y a ** pas ** de moyen de les restaurer!

## configuration
Tous les réglages, sauf si un deuxième bloc doit être utilisé ou non, peuvent être effectués via le backend dans les paramètres du module (accessibles via le bouton "Options").

### page d'aperçu
- ** Classer par **: définition de l'ordre des publications (personnalisé = définition manuelle, les publications apparaissent telles qu'elles sont disposées dans le backend, date de début / date d'expiration / soumises (= date de création) / ID de soumission: chaque ordre décroissant selon au critère correspondant)
- ** Messages par page **: sélection du nombre d'entrées (image / texte teaser) par page à afficher
- ** header, post loop, footer **: code HTML pour formater la sortie
- ** Redimensionner l'image d'aperçu à ** Largeur / hauteur de l'image en pixels. ** aucun ** recalcul automatique n'aura lieu si des modifications sont apportées, il est donc logique de penser à l'avance
à propos de la taille souhaitée et puis ne changez pas la valeur à nouveau.

Espaces réservés autorisés:
#### En-tête / Pied de page
- [NEXT_PAGE_LINK] "Page suivante", liée à la page suivante (si la page de présentation est divisée en plusieurs pages),
- [NEXT_LINK], "Next", s.o.,
- [PREVIOUS_PAGE_LINK], "Page précédente", s.o.,
- [PREVIOUS_LINK], "Précédent", s.o.,
- [OUT_OF], [OF], "x de y",
- [DISPLAY_PREVIOUS_NEXT_LINKS] "hidden" / "visible", selon que la pagination est requise ou non
#### post loop
- [PAGE_TITLE] titre de la page,
- [GROUP_ID] ID du groupe auquel la publication est affectée, pour les publications sans groupe "0"
- [GROUP_TITLE] Titre du groupe auquel le poste est affecté, pour les postes sans groupe "",
- [GROUP_IMAGE] Image (& lt; img src ... / & gt;) du groupe auquel le poste est affecté pour les postes sans groupe "",
- [DISPLAY_GROUP] * hériter * ou * aucun *,
- [DISPLAY_IMAGE] * hériter * ou * aucun *,
- [TITRE] titre (titre) de l'article,
- [IMAGE] post image (& lt; img src = ... / & gt;),
- [SHORT] court texte,
- [LINK] Lien vers la vue détaillée de l'article,
- [MODI_DATE] date du dernier changement de post,
- [MODI_TIME] Heure (heure) du dernier changement de poste,
- [CREATED_DATE] Date de création de la publication,
- [CREATED_TIME] heure à laquelle le poste a été créé,
- date de début [PUBLISHED_DATE],
- [PUBLISHED_TIME] heure de début,
- [USER_ID] ID du créateur de la publication,
- [USERNAME] nom d'utilisateur du créateur de la publication,
- [DISPLAY_NAME] Nom complet du créateur de la publication,
- [EMAIL] Adresse email du créateur de la publication,
- [TEXT_READ_MORE] "Afficher les détails",
- [SHOW_READ_MORE], * caché * ou * visible *,
- [GROUP_IMAGE_URL] URL de l'image du groupe

### post view
- ** En-tête de message, Contenu, Pied de page, Bloc 2 **: Code HTML pour formater le message.

Espaces réservés autorisés:
#### En-tête de message, pied de message, bloc 2
- [PAGE_TITLE] titre de la page,
- [GROUP_ID] ID du groupe auquel la publication est affectée, pour les publications sans groupe "0"
- [GROUP_TITLE] Titre du groupe auquel le poste est affecté, pour les postes sans groupe "",
- [GROUP_IMAGE] Image (& lt; img src ... / & gt;) du groupe auquel le poste est affecté pour les postes sans groupe "",
- [DISPLAY_GROUP] * hériter * ou * aucun *,
- [DISPLAY_IMAGE] * hériter * ou * aucun *,
- [TITRE] titre (titre) de l'article,
- [IMAGE] post image (& lt; img src = ... / & gt;),
- [SHORT] court texte,
- [MODI_DATE] date du dernier changement de post,
- [MODI_TIME] Heure (heure) du dernier changement de poste,
- [CREATED_DATE] Date de création de la publication,
- [CREATED_TIME] heure à laquelle le poste a été créé,
- date de début [PUBLISHED_DATE],
- [PUBLISHED_TIME] heure de début,
- [USER_ID] ID du créateur de la publication,
- [USERNAME] nom d'utilisateur du créateur de la publication,
- [DISPLAY_NAME] Nom complet du créateur de la publication,
- [EMAIL] Adresse email du créateur de la publication

#### contenu de nouvelles
- [CONTENU] Contenu du message (HTML)
- [IMAGES] Images / Galerie HTML

### Paramètres Galerie / Image
- ** Galerie d'images **: Sélection du script de galerie à utiliser. Veuillez noter que toute personnalisation apportée au code de la galerie dans le champ Contenu du message sera perdue en cas de modification.
- ** Boucle d'image **: le code HTML pour la représentation d'une seule image doit correspondre au script de galerie correspondant.
- ** Max. Taille de l'image en octets **: Taille de fichier par fichier image, pourquoi cela doit maintenant être spécifié en octets et non pas en Ko lisible ou Mo, je ne sais tout simplement pas
- ** Redimensionner les images de la galerie à / Taille de la vignette largeur x hauteur **: exactement pareil. ** Aucun ** recalcul automatique n'aura lieu si des modifications sont apportées. Il est donc logique de penser à l'avance à la taille souhaitée et de ne pas modifier à nouveau la valeur.
- ** Recadrage **: Voir l'explication à la page.

### 2e bloc
Un deuxième bloc peut éventuellement être affiché si le modèle le prend en charge.
- Utiliser le bloc 2 (par défaut): Aucune entrée ou entrée * define ('NWI_USE_SECOND_BLOCK', true); * dans le fichier config.php dans la racine
- ne pas utiliser le bloc 2: entrée * define ('NWI_USE_SECOND_BLOCK', false); * dans le fichier config.php dans la racine