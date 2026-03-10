# Local XAMPP setup and "Index not found" troubleshooting

This project is a small PHP/MySQL app. If you saw an Apache 404 or the server serving an `index.html` instead of `index.php`, follow these steps.

Summary of fixes we added in the repository:
- Added `.htaccess` (in this folder) with `DirectoryIndex index.php index.html` and `Options -Indexes` so Apache serves `index.php` first when `.htaccess` is allowed.
- Fixed incorrect absolute links (`/book_crud/...`) and converted them to relative paths.

If you still see the wrong page on http://localhost/ or a 404, do the following on your macOS/XAMPP system.

1) Confirm the URL you are loading in the browser

Use the full path to your project. For example:

  - http://localhost/bookscrud/books/
  - or http://localhost/bookscrud/books/index.php

2) If Apache is still serving a different index file, ensure Apache allows `.htaccess`

Open the XAMPP Apache config file and enable `AllowOverride All` for your htdocs directory.

File to edit (backup first):

  /Applications/XAMPP/xamppfiles/etc/httpd.conf

Look for a block that references `"/Applications/XAMPP/xamppfiles/htdocs"` and change `AllowOverride None` to `AllowOverride All`.

Example (edit with sudo):

```bash
# backup first
sudo cp /Applications/XAMPP/xamppfiles/etc/httpd.conf /Applications/XAMPP/xamppfiles/etc/httpd.conf.bak

# then open in a terminal editor (or use your GUI editor)
sudo nano /Applications/XAMPP/xamppfiles/etc/httpd.conf
```

Inside the file find the Directory block and modify:

```apacheconf
<Directory "/Applications/XAMPP/xamppfiles/htdocs">
    # ... other directives ...
    AllowOverride All
</Directory>
```

3) Ensure Apache will prefer `index.php`

If you prefer to make this global (instead of `.htaccess`), add or update the `DirectoryIndex` directive in the same Apache configuration file (or keep the `.htaccess` we added). For example:

```apacheconf
DirectoryIndex index.php index.html
```

4) Restart XAMPP Apache

```bash
sudo /Applications/XAMPP/xamppfiles/xampp restart
```

5) Verify with a quick HTTP header check

```bash
curl -I http://localhost/bookscrud/books/
```

You should see `HTTP/1.1 200 OK` and the `Server` header. If you still get a 404, confirm the project folder path under `htdocs` and that `index.php` exists in `/Applications/XAMPP/xamppfiles/htdocs/bookscrud/books/index.php`.

6) If you cannot change Apache global config (shared host, permissions) then make sure `.htaccess` is respected by the server or always use the direct `index.php` URL in the browser.

Optional: If you want me to make the app deployable to an online PHP host, I can add a small `Dockerfile` and show deployment instructions for Render, Fly, or a VPS — tell me which provider you prefer.

If you want me to edit anything in the repo now (add a `Dockerfile`, add a base URL constant in `config.php`, or auto-update other files), tell me which and I'll apply the change.
# books