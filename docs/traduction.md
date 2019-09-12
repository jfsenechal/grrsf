## Traduction

### Choix de la langue

La langue d'application peut être définie à différents niveaux 

Du moins prioriatire au plus prioriataire :

- Dans [config](/config/packages/framework.yaml) la variable `default_locale: fr_FR`
- La langue du navigateur
- La langue choisie par l'utilisateur

### Carbon

Grâce à la librairie [carbon](https://carbon.nesbot.com/docs/) les noms des mois et des jours sont déjà traduit en de nombreuses langues

Tous les textes de l'applications sont stockés dans le dossier [translations](/translations)

Pour traduire par exemple en anglais, "il suffit de" copier coller chaque fichier et remplacer "fr" par "en"

messages.fr.yaml => messages.en.yaml

Et de traduire :-P 