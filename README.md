# com_redeurform — Redeur Contact Form

**Version:** 1.0.9  
**Author:** Redooor LLP  
**License:** GNU General Public License v2 or later  
**Compatibility:** Joomla 3.x · Joomla 4.x  

---

## Overview

`com_redeurform` is a self-contained Joomla contact form component that provides:

- Name, Email, Phone (optional) and Message fields with front- and back-end validation
- Cloudflare Turnstile bot protection (works on localhost with test keys)
- Email notifications with a fully customisable template and Reply-To set to the submitter
- Submission storage in the database with admin list, search and bulk-delete
- Admin-configurable colour scheme (10 colour tokens, applied via CSS custom properties)
- Mail diagnostics panel and one-click test-send from the admin settings page
- Fully translated in `en-GB` and `zh-CN`
- Compatible with **Joomla 3** and **Joomla 4** from a single codebase

---

## File Structure

```
redeurform/
├── redeurform.xml                        Manifest — install / update / uninstall
│
├── site/                                Front-end (public-facing)
│   ├── redeurform.php                    Entry point
│   ├── controller.php                   Base controller
│   ├── controllers/
│   │   └── form.php                     Handles form POST + Turnstile verification
│   ├── models/
│   │   └── form.php                     saveSubmission() + sendEmail()
│   └── views/redeurform/
│       ├── view.html.php                Loads CSS, injects colour custom properties
│       └── tmpl/
│           ├── default.php              Form HTML + inline validation JS
│           └── default.xml             Menu item type metadata (required for menu picker)
│
├── admin/                               Back-end (administrator)
│   ├── redeurform.php                    Entry point (require_once's helper)
│   ├── controller.php                   Base controller (defaults to submissions view)
│   ├── controllers/
│   │   ├── submissions.php              Bulk-delete controller (extends JControllerAdmin)
│   │   └── redeurform.php               Settings save / apply / testEmail tasks
│   ├── helpers/
│   │   └── redeurform.php               RedeurformHelper — addSubmenu(), isJoomla4()
│   ├── models/
│   │   ├── submissions.php             Paginated, searchable list model
│   │   ├── submission.php              Single record model (delete)
│   │   ├── redeurform.php               Settings model — reads/writes component params
│   │   └── forms/
│   │       └── redeurform.xml           Settings form definition (email + Turnstile + colours)
│   ├── tables/
│   │   └── submission.php              JTable subclass with back-end field validation
│   ├── views/
│   │   ├── submissions/
│   │   │   ├── view.html.php           Renders submissions list
│   │   │   └── tmpl/default.php        HTML table with search bar and pagination
│   │   └── redeurform/
│   │       ├── view.html.php           Renders settings form
│   │       └── tmpl/default.php        Settings form + diagnostics panel + test email
│   └── sql/
│       ├── install.mysql.utf8.sql      Creates #__redeurform_submissions table
│       ├── uninstall.mysql.utf8.sql    Drops table on uninstall
│       └── updates/mysql/              One file per version for schema versioning
│           ├── 1.0.0.sql … 1.0.9.sql
│
├── media/
│   └── css/
│       └── redeurform.css               Self-contained component styles (CSS custom props)
│
└── language/
    ├── en-GB/
    │   ├── en-GB.com_redeurform.ini     All runtime strings (front + back end)
    │   └── en-GB.com_redeurform.sys.ini Strings needed by installer and menu manager
    └── zh-CN/
        ├── zh-CN.com_redeurform.ini
        └── zh-CN.com_redeurform.sys.ini
```

---

## Developer Notes

### Joomla 3 / 4 Compatibility

The codebase targets the **Joomla 3 MVC API** (`JControllerLegacy`, `JViewLegacy`, `JModelLegacy`, etc.) which Joomla 4 ships as a compatibility layer. No Joomla 4-only APIs are used, so the same ZIP installs on both versions.

The one behavioural difference is the admin sidebar:

- **Joomla 3** renders a left sidebar via `JHtmlSidebar`.  
- **Joomla 4** drops `JHtmlSidebar` entirely; the sidebar is provided by the Atum template automatically.

`RedeurformHelper::addSubmenu()` calls `class_exists('JHtmlSidebar')` before using it, and the view templates check `RedeurformHelper::isJoomla4()` to omit the `span2 / span10` Bootstrap grid wrappers that Joomla 4 does not need.

### Colour System

Colours are managed through **CSS custom properties** (variables) scoped to `#redeurform-app`. The ten tokens are:

| Token | Controls |
|---|---|
| `--rf-primary` | Title, input focus ring, accent bar start |
| `--rf-secondary` | Button focus ring, accent bar end |
| `--rf-bg` | Outer wrapper background |
| `--rf-text` | Input value text |
| `--rf-label` | Field label ALL-CAPS text |
| `--rf-btn-bg` | Submit button background |
| `--rf-btn-text` | Submit button text and icon |
| `--rf-accent1` | Card accent bar — left colour |
| `--rf-accent2` | Card accent bar — middle colour |
| `--rf-accent3` | Card accent bar — right colour |

`view.html.php` reads these from the component params, sanitises each value with a strict `#RGB` / `#RRGGBB` regex, and injects them as an inline `<style>` block via `$doc->addStyleDeclaration()`. Default values are also declared inside `redeurform.css` itself so the form renders correctly even without the injected block.

The `color` field type in `admin/models/forms/redeurform.xml` renders a native HTML colour picker in both Joomla 3 and 4.

### Turnstile vs reCAPTCHA

Cloudflare Turnstile was chosen over Google reCAPTCHA v3 because:

1. Turnstile's `siteverify` endpoint accepts POST bodies encoded as `application/x-www-form-urlencoded` and is tolerant of localhost domains.
2. The widget injects `cf-turnstile-response` into the form automatically — no JavaScript token fetching or hidden fields required on our side.
3. Cloudflare provides **official test keys** that always pass, making local development straightforward.

The `verifyTurnstile()` method in `site/controllers/form.php` sends the token using `http_build_query()` (not a PHP array) to ensure the correct Content-Type header. On cURL network failure the method **fails open** with a warning message, so a network hiccup does not silently block legitimate users.

### Adding a New Language

1. Create `language/xx-XX/xx-XX.com_redeurform.ini` and `xx-XX.com_redeurform.sys.ini`.
2. Copy all keys from `en-GB.com_redeurform.ini` and translate the values.
3. Register both files in `redeurform.xml` under `<languages>` and `<administration><languages>`.

### Adding Fields

1. Add the DB column in `admin/sql/updates/mysql/x.x.x.sql` and bump the version in `redeurform.xml`.
2. Add the `$input->getString()` call in `site/controllers/form.php`.
3. Add the field to the `saveSubmission()` object in `site/models/form.php`.
4. Add the field to `RedeurformTableSubmission::check()` if validation is needed.
5. Add the `<input>` or `<textarea>` in `site/views/redeurform/tmpl/default.php`.
6. Add the column header and cell in `admin/views/submissions/tmpl/default.php`.
7. Add language keys to both ini files.

### CSRF Protection

Every POST uses `JSession::checkToken()` verified against `JHtml::_('form.token')` in the template. The token field name is the session token itself, so it differs per session and resists replay attacks.

### Debug Mode

When Joomla's `JDEBUG` constant is `true`, the full Turnstile `siteverify` response is output as a notice message, making it easy to diagnose verification failures without opening server logs.

---

## Package User Guide

### Requirements

- Joomla 3.9+ or Joomla 4.x
- PHP 7.4+ with `curl` and `mbstring` extensions enabled
- MySQL / MariaDB

### Installation

1. Download `com_redeurform_v1.0.9.zip`.
2. In the Joomla Administrator, go to **Extensions → Manage → Install**.
3. Upload the ZIP file and click **Upload & Install**.
4. The component creates the `#__redeurform_submissions` table automatically.

### Upgrading

Install the new ZIP over the existing installation — the manifest uses `method="upgrade"` so Joomla will update files without dropping the database table or existing submissions.

### Creating a Menu Item

1. Go to **Menus → [your menu] → Add New Menu Item**.
2. Click **Select** next to Menu Item Type.
3. Find and click **Redeur Contact Form** in the list.
4. Set a title, choose the menu location, and **Save**.

### Configuring the Component

Go to **Components → Redeur Contact Form → Settings**.

#### Email Configuration

| Setting | Description |
|---|---|
| Receiving Email | **Required.** The address where form notifications land. |
| Send-as Email | The `From:` address on notifications. Leave blank to use the Joomla global sender. |
| Email Template | Free-text body. Use `{name}`, `{email}`, `{phone}`, `{message}`, `{receiving_email}`, `{sendfrom_email}` as placeholders. |

The notification email automatically sets **Reply-To** to the submitter's address, so you can reply directly from your mail client.

#### Cloudflare Turnstile

1. Register your site at [dash.cloudflare.com](https://dash.cloudflare.com) → **Turnstile → Add site**.
2. Choose **Managed** widget type and enter your production domain.
3. Copy the **Site Key** and **Secret Key** into the component settings.
4. Leave both fields blank to disable bot protection entirely.

**Local / staging test keys (always pass):**

| Field | Value |
|---|---|
| Site Key | `1x00000000000000000000AA` |
| Secret Key | `1x0000000000000000000000000000000AA` |

#### Colour Scheme

Ten colour pickers let you match the form to your brand without touching code. Changes take effect immediately on save — no cache clearing needed for the colour values themselves (they are injected inline on every page load).

| Colour | What it affects |
|---|---|
| Primary | Title text, input focus ring, accent bar start |
| Secondary | Button focus glow, accent bar end |
| Background | Outer wrapper background |
| Body Text | Input value colour |
| Field Label | ALL-CAPS label colour |
| Button Background | Submit button fill |
| Button Text | Text and arrow icon inside the button |
| Accent Bar Start / Middle / End | The three-stop gradient stripe at the top of the card |

### Managing Submissions

Go to **Components → Redeur Contact Form → Submissions**.

- **Search** by name or email using the filter bar.
- **Sort** by name, email or date by clicking column headers.
- **Delete** one or more rows by checking the checkbox(es) and clicking the Delete toolbar button.

Submissions are never emailed back to the submitter — they are stored in the database only, accessible to site administrators.

### Testing Email Locally

The diagnostics panel at the bottom of the Settings page shows your current Joomla mail configuration. To catch all outgoing mail locally without sending to real addresses:

1. Install **Laravel Herd** — it bundles Mailpit automatically.
2. In Joomla: **System → Global Configuration → Server → Mail Settings**:
   - Mailer: `SMTP`
   - SMTP Host: `127.0.0.1`
   - SMTP Port: `1025`
   - SMTP Security: `None`
   - SMTP Authentication: `No`
3. Save, then click **Send Test Email** in the component settings.
4. Open **http://localhost:8025** to see the email in Mailpit.

### Uninstalling

Go to **Extensions → Manage → Manage**, find **Redeur Contact Form**, tick it and click **Uninstall**. This removes all component files **and** drops the `#__redeurform_submissions` table. Export any submissions you need to keep before uninstalling.

---

## Changelog

| Version | Summary |
|---|---|
| 1.0.9 | Added admin colour pickers; Joomla 4 compatibility; CSS custom properties; developer README |
| 1.0.8 | Removed SVG icon and system messages from site template (delegated to Joomla theme) |
| 1.0.7 | Replaced reCAPTCHA v3 with Cloudflare Turnstile; updated all language strings |
| 1.0.6 | Fixed email sender/recipient/Reply-To; added `{receiving_email}` / `{sendfrom_email}` placeholders; mail diagnostics panel; test-send button |
| 1.0.5 | Fixed cURL POST encoding (BROWSER_ERROR); both-keys-required Turnstile logic; relaxed action check |
| 1.0.4 | Upgraded from reCAPTCHA v2 checkbox to reCAPTCHA v3 (later superseded by Turnstile) |
| 1.0.3 | Brand colours updated to primary #4e9d6d / secondary #f6eb14 on white |
| 1.0.2 | Replaced Tailwind CDN with self-contained custom CSS |
| 1.0.1 | Fixed Tailwind CDN URL (302 redirect); added `tw-` prefix |
| 1.0.0 | Initial release |

---

*Copyright (C) 2026 Redooor LLP. Released under the GNU General Public License v2 or later.*
