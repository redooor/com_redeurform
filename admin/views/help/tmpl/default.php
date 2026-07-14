<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$isJ4 = RedeurformHelper::isJoomla4();
?>

<?php if (!$isJ4 && !empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else: ?>
<div id="j-main-container">
<?php endif; ?>

<div style="max-width:800px; padding: 20px 0;">

    <!-- Step 1 -->
    <h3>Step 1 — Add the contact form to a page</h3>
    <p>The contact form is displayed by creating a Joomla menu item that points to it.</p>
    <ol>
        <li>In the Joomla Administrator, go to <strong>Menus &rarr; [your menu] &rarr; Add New Menu Item</strong>.</li>
        <li>Click <strong>Select</strong> next to <em>Menu Item Type</em>.</li>
        <li>In the pop-up, find and click <strong>Redeur Contact Form</strong>.</li>
        <li>Enter a <strong>Title</strong> (e.g. <em>Contact Us</em>), choose your menu, and click <strong>Save &amp; Close</strong>.</li>
    </ol>
    <p>The form will now appear on the front end at the URL assigned to that menu item.</p>

    <hr />

    <!-- Step 2 -->
    <h3>Step 2 — Configure email notifications</h3>
    <p>Go to <strong>Components &rarr; Redeur Contact Form &rarr; Settings</strong> and fill in the <em>Email Configuration</em> section.</p>
    <ul>
        <li><strong>Receiving Email</strong> (required) — the address where form submission notifications are delivered.</li>
        <li><strong>Send-as Email</strong> (optional) — the <code>From:</code> address on notification emails. Leave blank to use the Joomla global sender address.</li>
        <li><strong>Email Template</strong> — the notification body. Use the placeholders <code>{name}</code>, <code>{email}</code>, <code>{phone}</code>, <code>{message}</code> to insert the submitter&rsquo;s details.</li>
    </ul>
    <p>Click <strong>Save</strong>, then use the <strong>Send Test Email</strong> button at the bottom of the Settings page to confirm delivery is working.</p>

    <hr />

    <!-- Step 3 -->
    <h3>Step 3 — Enable bot protection (optional)</h3>
    <p>The component supports <a href="https://www.cloudflare.com/products/turnstile/" target="_blank" rel="noopener noreferrer">Cloudflare Turnstile</a> to block spam submissions.</p>
    <ol>
        <li>Log in to the <a href="https://dash.cloudflare.com" target="_blank" rel="noopener noreferrer">Cloudflare dashboard</a> and go to <strong>Turnstile &rarr; Add site</strong>.</li>
        <li>Choose the <strong>Managed</strong> widget type and enter your production domain.</li>
        <li>Copy the <strong>Site Key</strong> and <strong>Secret Key</strong>.</li>
        <li>Paste them into the <em>Cloudflare Turnstile</em> section in <strong>Settings</strong> and click <strong>Save</strong>.</li>
    </ol>
    <p>Leave both fields blank to disable the challenge entirely. The form will still function — it just won&rsquo;t have bot protection.</p>
    <p><strong>Local / staging test keys (always pass on any domain):</strong></p>
    <table class="table table-bordered" style="width:auto;">
        <tr><th>Site Key</th><td><code>1x00000000000000000000AA</code></td></tr>
        <tr><th>Secret Key</th><td><code>1x0000000000000000000000000000000AA</code></td></tr>
    </table>

    <hr />

    <!-- Step 4 -->
    <h3>Step 4 — Customise the colour scheme (optional)</h3>
    <p>In <strong>Settings &rarr; Colour Scheme</strong> you can adjust ten colour tokens to match your brand without touching any CSS.</p>
    <table class="table table-bordered" style="width:auto;">
        <thead><tr><th>Colour</th><th>What it affects</th></tr></thead>
        <tbody>
            <tr><td>Primary</td><td>Title text, input focus ring, accent bar start</td></tr>
            <tr><td>Secondary</td><td>Button focus glow, accent bar end</td></tr>
            <tr><td>Background</td><td>Outer wrapper background</td></tr>
            <tr><td>Body Text</td><td>Input value colour</td></tr>
            <tr><td>Field Label</td><td>ALL-CAPS label colour</td></tr>
            <tr><td>Button Background</td><td>Submit button fill</td></tr>
            <tr><td>Button Text</td><td>Text and arrow icon inside the button</td></tr>
            <tr><td>Accent Bar Start / Middle / End</td><td>Three-stop gradient stripe at the top of the card</td></tr>
        </tbody>
    </table>
    <p>Colour changes take effect immediately on save — no cache clearing needed.</p>

    <hr />

    <!-- Step 5 -->
    <h3>Step 5 — Review submissions</h3>
    <p>Every form submission is stored in the database and can be reviewed under <strong>Components &rarr; Redeur Contact Form &rarr; Submissions</strong>.</p>
    <ul>
        <li><strong>Search</strong> by name or email using the filter bar at the top.</li>
        <li><strong>Sort</strong> by clicking a column header (Name, Email, or Date).</li>
        <li><strong>Delete</strong> by ticking one or more rows and clicking the <strong>Delete</strong> toolbar button.</li>
    </ul>

</div>

</div>
