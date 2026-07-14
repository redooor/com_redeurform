<?php defined('_JEXEC') or die; ?>

<div id="redeurform-app">
  <div class="rf-container">

    <!-- Header -->
    <div class="rf-header">
      <h1 class="rf-title"><?php echo JText::_('COM_REDEURFORM_TITLE'); ?></h1>
      <p class="rf-subtitle"><?php echo JText::_('COM_REDEURFORM_SUBTITLE'); ?></p>
    </div>

    <!-- Card -->
    <div class="rf-card">
      <div class="rf-card-accent"></div>
      <div class="rf-card-body">

        <form
          id="redeurform-contact"
          action="<?php echo JRoute::_('index.php?option=com_redeurform&task=form.submit'); ?>"
          method="post"
          novalidate
          class="rf-form"
        >

          <!-- Name -->
          <div class="rf-field">
            <label for="rf-name" class="rf-label">
              <?php echo JText::_('COM_REDEURFORM_FIELD_NAME'); ?><span class="rf-required">*</span>
            </label>
            <input
              type="text"
              id="rf-name"
              name="name"
              maxlength="255"
              autocomplete="name"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_NAME_PLACEHOLDER'); ?>"
            />
            <span class="rf-error" data-field="name">
              <?php echo JText::_('COM_REDEURFORM_ERROR_NAME_REQUIRED'); ?>
            </span>
          </div>

          <!-- Email -->
          <div class="rf-field">
            <label for="rf-email" class="rf-label">
              <?php echo JText::_('COM_REDEURFORM_FIELD_EMAIL'); ?><span class="rf-required">*</span>
            </label>
            <input
              type="email"
              id="rf-email"
              name="email"
              autocomplete="email"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_EMAIL_PLACEHOLDER'); ?>"
            />
            <span class="rf-error" data-field="email">
              <?php echo JText::_('COM_REDEURFORM_ERROR_EMAIL_INVALID'); ?>
            </span>
          </div>

          <!-- Phone (optional) -->
          <div class="rf-field">
            <label for="rf-phone" class="rf-label">
              <?php echo JText::_('COM_REDEURFORM_FIELD_PHONE'); ?>
              <span class="rf-optional"><?php echo JText::_('COM_REDEURFORM_FIELD_OPTIONAL'); ?></span>
            </label>
            <input
              type="tel"
              id="rf-phone"
              name="phone"
              autocomplete="tel"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_PHONE_PLACEHOLDER'); ?>"
            />
          </div>

          <!-- Message -->
          <div class="rf-field">
            <label for="rf-message" class="rf-label">
              <?php echo JText::_('COM_REDEURFORM_FIELD_MESSAGE'); ?><span class="rf-required">*</span>
            </label>
            <textarea
              id="rf-message"
              name="message"
              rows="5"
              maxlength="255"
              class="rf-textarea"
              placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_MESSAGE_PLACEHOLDER'); ?>"
            ></textarea>
            <div class="rf-field-footer">
              <span class="rf-error" data-field="message">
                <?php echo JText::_('COM_REDEURFORM_ERROR_MESSAGE_REQUIRED'); ?>
              </span>
              <span class="rf-counter" id="rf-char-count">0/255</span>
            </div>
          </div>

          <!-- Cloudflare Turnstile widget -->
          <?php if (!empty($this->siteKey)): ?>
          <div class="rf-field">
            <div
              class="cf-turnstile"
              data-sitekey="<?php echo htmlspecialchars($this->siteKey); ?>"
              data-theme="light"
              data-size="normal"
            ></div>
            <span class="rf-error" data-field="turnstile" style="display:none;">
              <?php echo JText::_('COM_REDEURFORM_ERROR_TURNSTILE_REQUIRED'); ?>
            </span>
          </div>
          <?php endif; ?>

          <!-- Hidden fields -->
          <input type="hidden" name="<?php echo $this->token; ?>" value="1" />

          <!-- Submit -->
          <button type="submit" id="rf-submit" class="rf-submit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
            <?php echo JText::_('COM_REDEURFORM_BUTTON_SEND'); ?>
          </button>

        </form>
      </div>
    </div>

    <p class="rf-footer-note"><?php echo JText::_('COM_REDEURFORM_PRIVACY_NOTE'); ?></p>

  </div>
</div>

<script>
(function () {
  'use strict';

  var form    = document.getElementById('redeurform-contact');
  var msgArea = document.getElementById('rf-message');
  var counter = document.getElementById('rf-char-count');
  var hasTurnstile = <?php echo !empty($this->siteKey) ? 'true' : 'false'; ?>;

  if (msgArea && counter) {
    msgArea.addEventListener('input', function () {
      var len = msgArea.value.length;
      counter.textContent = len + '/255';
      counter.classList.toggle('rf-counter--limit', len >= 255);
    });
  }

  function showError(field, show) {
    var el = document.querySelector('.rf-error[data-field="' + field + '"]');
    if (!el) return;
    el.style.display = show ? 'block' : 'none';
    el.classList.toggle('rf-error--visible', show);
  }

  function setInputError(id, hasError) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('rf-input--error', hasError);
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validateForm() {
    var valid    = true;
    var nameVal  = document.getElementById('rf-name').value.trim();
    var emailVal = document.getElementById('rf-email').value.trim();
    var msgVal   = msgArea ? msgArea.value.trim() : '';

    var nameErr = !nameVal;
    showError('name', nameErr); setInputError('rf-name', nameErr);
    if (nameErr) valid = false;

    var emailErr = !emailVal || !validateEmail(emailVal);
    showError('email', emailErr); setInputError('rf-email', emailErr);
    if (emailErr) valid = false;

    var msgErrEl = document.querySelector('.rf-error[data-field="message"]');
    var msgErr   = false;
    if (!msgVal) {
      msgErr = true;
      if (msgErrEl) msgErrEl.textContent = <?php echo json_encode(JText::_('COM_REDEURFORM_ERROR_MESSAGE_REQUIRED')); ?>;
    } else if (msgVal.length > 255) {
      msgErr = true;
      if (msgErrEl) msgErrEl.textContent = <?php echo json_encode(JText::_('COM_REDEURFORM_ERROR_MESSAGE_TOO_LONG')); ?>;
    }
    showError('message', msgErr); setInputError('rf-message', msgErr);
    if (msgErr) valid = false;

    if (hasTurnstile) {
      var tokenInput = document.querySelector('input[name="cf-turnstile-response"]');
      var hasToken   = tokenInput && tokenInput.value.trim().length > 0;
      showError('turnstile', !hasToken);
      if (!hasToken) valid = false;
    }

    return valid;
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      if (!validateForm()) e.preventDefault();
    });

    [
      { id: 'rf-name',    field: 'name' },
      { id: 'rf-email',   field: 'email' },
      { id: 'rf-message', field: 'message' }
    ].forEach(function (item) {
      var el = document.getElementById(item.id);
      if (!el) return;
      el.addEventListener('blur',  validateForm);
      el.addEventListener('input', function () {
        showError(item.field, false);
        setInputError(item.id, false);
      });
    });
  }
}());
</script>
