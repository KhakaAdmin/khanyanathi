# Running Khanya Nathi locally on your branch

**Time:** about 45 minutes the first time, then 2 minutes after that.
**You need:** Docker Desktop, Node 18+, Git, and KonsoleH access for the staging server (`dedi1138.jnb2.host-h.net`). Get the KonsoleH login from the password manager, not ClickUp.

## How the site is built (read this first)

| Piece | Where it lives | In Git? |
|---|---|---|
| WordPress core | Downloaded by wp-env | No |
| Breakdance, ACF PRO, WP Rocket, Gravity Forms, Rank Math, Solid Security, NinjaFirewall | `wp-content/plugins` (copied from staging) | **No.** These are licensed and large |
| Page layouts built in Breakdance | **The database** | **No** |
| ACF field groups, post types, taxonomies, options pages | `wp-content/acf-json/*.json` | **Yes** |
| Custom PHP (helpers, shortcodes, filters) | `wp-content/mu-plugins/khanyanathi-core.php` | **Yes** |
| Media (PDFs, images) | `wp-content/uploads` | No |

**The rule:** Git tracks code and ACF structure. Breakdance layouts and content live in the staging database, and **staging is the source of truth**. Use your local copy to try things safely. Do the final Breakdance build on staging, or you will overwrite someone else's work when you move a database.

---

## Step 1: Clone the branch

```bash
git clone https://github.com/khakaadmin/khanyanathi.git
cd khanyanathi
git checkout claude/laughing-lamport-krt6s3
npm i -g @wordpress/env
```

## Step 2: Pull a copy of staging

1. Log in to **KonsoleH**, then **Manage Servers**, then `dedi1138.jnb2.host-h.net`, then the `khanyanathi.launchweb.co.za` domain.
2. **Database:** open **phpMyAdmin**, select the site's database, click **Export**, choose **Quick / SQL**, and save the file as `backups/staging.sql`.
   - If you have SSH, this is quicker: `wp db export staging.sql` from the site root.
3. **Files:** use the KonsoleH File Manager or SFTP to download these three folders into the matching folders in this repo:
   - `public_html/wp-content/plugins` → `./wp-content/plugins`
   - `public_html/wp-content/themes` → `./wp-content/themes`
   - `public_html/wp-content/uploads` → `./wp-content/uploads`
   - If uploads is too big, you can skip it for now. Images will be broken locally, but layouts will still work.
4. Write down the staging **table prefix** (the `wp_` part of `wp_options`). You need it in step 3.

> ⚠️ xneelo reset all FTP passwords on 19 October 2026. If your SFTP login fails, set a new password in KonsoleH. Do not email Johan for it.

## Step 3: Start WordPress and import the database

```bash
wp-env start          # first run downloads WordPress, about 2 minutes

# wp-env can only see mapped folders, so put the dump in uploads temporarily
cp backups/staging.sql wp-content/uploads/staging.sql
wp-env run cli wp db import /var/www/html/wp-content/uploads/staging.sql
rm wp-content/uploads/staging.sql

# If staging's table prefix is not wp_, fix it before going further:
#   wp-env run cli wp config set table_prefix <prefix>

wp-env run cli wp search-replace 'https://khanyanathi.launchweb.co.za' 'http://localhost:8888' --all-tables --precise
wp-env run cli wp search-replace 'https:\/\/khanyanathi.launchweb.co.za' 'http:\/\/localhost:8888' --all-tables --precise   # Breakdance stores JSON with escaped slashes
```

## Step 4: Make the local copy safe

```bash
wp-env run cli wp plugin deactivate wp-rocket ninjafirewall better-wp-security
wp-env run cli wp option update blog_public 0
wp-env run cli wp user update LaunchAdmin --user_pass=local-only-password
```

- NinjaFirewall and Solid Security will lock you out locally. WP Rocket caches stale pages. Turn all three off.
- If NinjaFirewall still blocks you, delete `.htninja` and remove the `auto_prepend_file` line from any `.user.ini` or `.htaccess` you copied across.

## Step 5: Clear the Breakdance cache and check ACF

1. Open http://localhost:8888/wp-admin and log in as `LaunchAdmin` with your local password.
2. Go to **Breakdance → Settings → Tools** and **regenerate the CSS cache**. If you skip this, pages render unstyled.
3. Go to **ACF → Field Groups**. If you see a **Sync available** notice, click **Sync** so the database matches the JSON on your branch.
4. Open http://localhost:8888/ecd/ and check it looks like staging.

## Daily workflow

```bash
git pull                     # get the latest code and ACF JSON
wp-env start
# …work…
# ACF writes field-group changes to wp-content/acf-json automatically
git add wp-content/acf-json wp-content/mu-plugins
git commit -m "ECD: add ROOTS resource post type and fields"
git push -u origin claude/laughing-lamport-krt6s3
wp-env stop
```

### Getting your changes onto staging
1. **ACF structure:** upload the changed `acf-json/*.json` files to staging's `wp-content/acf-json/`, then go to **ACF → Sync** on staging. Do not rebuild fields by hand.
2. **Custom PHP:** upload `khanyanathi-core.php` to staging's `wp-content/mu-plugins/`.
3. **Breakdance layouts:** build them on staging. To move one section, use Breakdance's **copy and paste** (right-click → Copy, then paste in the staging editor) or **Save as Global Block**. Do not push your local database over staging.
4. **Before anything big on staging:** take a backup in KonsoleH first.

## Alternative: LocalWP
If you'd rather avoid Docker, install **LocalWP**, create a site, replace its `wp-content` with the staging copy, import the SQL with **Database → Adminer**, then symlink this repo's `wp-content/acf-json` and `wp-content/mu-plugins` into it. Steps 4 and 5 above still apply.

## Troubleshooting
| Symptom | Fix |
|---|---|
| Redirects to `khanyanathi.launchweb.co.za` | The search-replace missed something. Run `wp-env run cli wp option get siteurl` and re-run step 3 |
| Pages have no styling | Breakdance → Settings → Tools → regenerate the CSS cache |
| 403 "NinjaFirewall" page | Step 4: deactivate it and remove `.htninja` |
| "ACF PRO license" notice | It's fine locally. **On staging, check the licence is active before you build repeaters or options pages** |
| White screen | Check `wp-content/debug.log` |
