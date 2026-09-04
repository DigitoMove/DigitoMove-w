Live dashboard repair

The live website returns HTTP 404 for /assets/vendor/css/core.css,
/assets/vendor/css/theme-default.css, /assets/vendor/fonts/boxicons.css,
and /assets/vendor/libs/jquery/jquery.js.

Extract admin-assets.zip into the website's PUBLIC document root:
the directory containing the public index.php and the assets directory.
For example, if the document root is public_html, the resulting path must be:
public_html/assets/vendor/css/core.css
Do not put this archive into the Laravel Composer vendor directory.

This archive contains public static files only; it contains no credentials.
It restores the missing theme assets and updates the admin stylesheet.
Hard-refresh the browser after upload (Cmd+Shift+R on Mac / Ctrl+Shift+R on Windows).

The redesigned dashboard, invoice search, navigation fixes and versioned asset
URLs also require deploying the changed Laravel app/resources files from this
repository. After that deployment, run php artisan view:clear from apps/web.
The corrected root .gitignore must be included, and the public/assets/vendor
and resources/assets/vendor files must be added to your deployment/commit.
Only apps/web/vendor (Composer dependencies) should be excluded from Git.
