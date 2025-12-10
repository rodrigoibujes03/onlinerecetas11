# RecetasOnline - instalación local (XAMPP)

1. Copia la carpeta `recetasonline` a `C:\xampp\htdocs\recetasonline`
2. Crea base de datos `recetasonline` y ejecuta el SQL (tablas incluidas). Usa phpMyAdmin.
   - Tablas: users, roles, categories, recipes, recipe_images, favorites, comments, recipe_tags
3. Edita `config/config.php` si tu base_url es distinta.
4. Abre XAMPP y levanta Apache + MySQL.
5. Accede en el navegador a: `http://localhost/recetasonline/public`
6. Si quieres probar con imágenes de ejemplo, copia:
   - `/mnt/data/8ca863c8-c252-4704-9783-1a54e26aa0af.png`
   - `/mnt/data/d4b0cfc2-5f17-4139-9f24-c71e6f1c62b7.png`
   a `public/uploads/`.
7. Crea un admin manualmente en la tabla `users`: define role_id = 2 para admin.
