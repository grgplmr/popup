# Installation du Plugin Pop-up Glassmorphism

## Méthode 1 : Installation manuelle

1. **Télécharger le plugin**
   - Téléchargez le dossier `wordpress-popup-plugin`
   - Renommez-le en `popup-glassmorphism`

2. **Upload sur votre serveur**
   - Connectez-vous à votre serveur via FTP ou cPanel
   - Naviguez vers `/wp-content/plugins/`
   - Uploadez le dossier `popup-glassmorphism`

3. **Activation**
   - Connectez-vous à votre administration WordPress
   - Allez dans `Plugins > Plugins installés`
   - Trouvez "Pop-up Glassmorphism" et cliquez sur "Activer"

## Méthode 2 : Installation via ZIP

1. **Créer l'archive**
   - Compressez le dossier `wordpress-popup-plugin` en ZIP
   - Renommez l'archive en `popup-glassmorphism.zip`

2. **Upload via WordPress**
   - Dans votre admin WordPress, allez dans `Plugins > Ajouter`
   - Cliquez sur "Téléverser une extension"
   - Sélectionnez votre fichier ZIP et cliquez "Installer"
   - Activez le plugin

## Configuration initiale

1. **Accéder aux paramètres**
   - Dans le menu WordPress, cliquez sur "Pop-up Glassmorphism"

2. **Configurer le pop-up d'accueil**
   - Activez le pop-up
   - Définissez le délai d'apparition (en millisecondes)
   - Personnalisez le titre et le contenu
   - Ajustez les couleurs et dimensions

3. **Configurer le pop-up exit intent**
   - Activez le pop-up
   - Personnalisez le titre et le contenu
   - Ajustez les couleurs et dimensions

4. **Tester**
   - Utilisez les boutons de prévisualisation
   - Visitez votre site pour tester en conditions réelles

## Vérifications post-installation

- [ ] Le plugin apparaît dans la liste des plugins actifs
- [ ] Le menu "Pop-up Glassmorphism" est visible dans l'admin
- [ ] Les paramètres se sauvegardent correctement
- [ ] La prévisualisation fonctionne
- [ ] Les pop-ups s'affichent sur le frontend
- [ ] Le design responsive fonctionne sur mobile

## Dépannage

### Le plugin ne s'active pas
- Vérifiez que votre version de WordPress est 5.0 ou supérieure
- Vérifiez que PHP 7.4 ou supérieur est installé
- Consultez les logs d'erreur de votre serveur

### Les pop-ups ne s'affichent pas
- Vérifiez que les pop-ups sont activés dans les paramètres
- Désactivez temporairement les autres plugins pour identifier les conflits
- Vérifiez la console du navigateur pour les erreurs JavaScript

### Problèmes de style
- Vérifiez que les fichiers CSS se chargent correctement
- Testez avec un thème par défaut (Twenty Twenty-Three)
- Videz le cache si vous utilisez un plugin de cache

## Support

Pour obtenir de l'aide :
1. Vérifiez la documentation
2. Consultez les FAQ dans readme.txt
3. Contactez le support technique

## Désinstallation

Pour supprimer complètement le plugin :
1. Désactivez le plugin
2. Supprimez-le via `Plugins > Plugins installés`
3. Les paramètres seront automatiquement supprimés