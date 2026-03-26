<?php defined('_JEXEC') or die; ?>

<div id="redeuform-app">
  <div class="rf-container">

    <!-- Header -->
    <div class="rf-header">
      <div class="rf-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
      </div>
      <h1 class="rf-title"><?php echo JText::_('COM_REDEUFORM_TITLE'); ?></h1>
      <p class="rf-subtitle"><?php echo JText::_('COM_REDEUFORM_SUBTITLE'); ?></p>
    </div>

    <!-- System messages -->
    <?php $messages = JFactory::getApplication()->getMessageQueue(); ?>
    <?php if (!empty($messages)): ?>
      <div class="rf-messages">
        <?php foreach ($messages as $msg): ?>
          <?php
            $cls = 'rf-message--info';
            if ($msg['type'] === 'error')   $cls = 'rf-message--error';
            if ($msg['type'] === 'message') $cls = 'rf-message--success';
            if ($msg['type'] === 'warning') $cls = 'rf-message--warning';
          ?>
          <div class="rf-message <?php echo $cls; ?>">
            <?php echo htmlspecialchars($msg['message']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="rf-card">
      <div class="rf-card-accent"></div>
      <div class="rf-card-body">

        <form
          id="redeuform-contact"
          action="<?php echo JRoute::_('index.php?option=com_redeuform&task=form.submit'); ?>"
          method="post"
          novalidate
          class="rf-form"
        >

          <!-- Name -->
          <div class="rf-field">
            <label for="rf-name" class="rf-label">
              <?php echo JText::_('COM_REDEUFORM_FIELD_NAME'); ?><span class="rf-required">*</span>
            </label>
            <input
              type="text"
              id="rf-name"
              name="name"
              maxlength="255"
              autocomplete="name"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_NAME_PLACEHOLDER'); ?>"
            />
            <span class="rf-error" data-field="name">
              <?php echo JText::_('COM_REDEUFORM_ERROR_NAME_REQUIRED'); ?>
            </span>
          </div>

          <!-- Email -->
          <div class="rf-field">
            <label for="rf-email" class="rf-label">
              <?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL'); ?><span class="rf-required">*</span>
            </label>
            <input
              type="email"
              id="rf-email"
              name="email"
              autocomplete="email"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL_PLACEHOLDER'); ?>"
            />
            <span class="rf-error" data-field="email">
              <?php echo JText::_('COM_REDEUFORM_ERROR_EMAIL_INVALID'); ?>
            </span>
          </div>

          <!-- Phone (optional) -->
          <div class="rf-field">
            <label for="rf-phone" class="rf-label">
              <?php echo JText::_('COM_REDEUFORM_FIELD_PHONE'); ?>
              <span class="rf-optional"><?php echo JText::_('COM_REDEUFORM_FIELD_OPTIONAL'); ?></span>
            </label>
            <input
              type="tel"
              id="rf-phone"
              name="phone"
              autocomplete="tel"
              class="rf-input"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_PHONE_PLACEHOLDER'); ?>"
            />
          </div>

          <!-- Message -->
          <div class="rf-field">
            <label for="rf-message" class="rf-label">
              <?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE'); ?><span class="rf-required">*</span>
            </label>
            <textarea
              id="rf-message"
              name="message"
              rows="5"
              maxlength="255"
              class="rf-textarea"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE_PLACEHOLDER'); ?>"
            ></textarea>
            <div class="rf-field-footer">
              <span class="rf-error" data-field="message">
                <?php echo JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED'); ?>
              </span>
              <span class="rf-counter" id="rf-char-count">0/255</span>
            </div>
          </div>

          <!-- reCAPTCHA v3: hidden token field, populated by JS before submit -->
          <?php if (!empty($this->siteKey)): ?>
          <input type="hidden" name="g-recaptcha-response" id="rf-recaptcha-token" value="" />
          <span class="rf-error" data-field="recaptcha" style="display:none;">
            <?php echo JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_REQUIRED'); ?>
          </span>
          <?php endif; ?>

          <!-- reCAPTCHA v3 badge note -->
          <?php if (!empty($this->siteKey)): ?>
          <p class="rf-recaptcha-note">
            <?php echo JText::_('COM_REDEUFORM_RECAPTCHA_V3_NOTE'); ?>
          </p>
          <?php endif; ?>

          <!-- Hidden fields -->
          <input type="hidden" name="<?php echo $this->token; ?>" value="1" />

          <!-- Submit -->
          <button type="submit" id="rf-submit" class="rf-submit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
            <span id="rf-submit-label"><?php echo JText::_('COM_REDEUFORM_BUTTON_SEND'); ?></span>
          </button>

        </form>
      </div>
    </div>

    <!-- Footer note -->
    <p class="rf-footer-note"><?php echo JText::_('COM_REDEUFORM_PRIVACY_NOTE'); ?></p>

  </div>
</div>

<script>
(function () {
  'use strict';

  var form      = document.getElementById('redeuform-contact');
  var msgArea   = document.getElementById('rf-message');
  var counter   = document.getElementById('rf-char-count');
  var submitBtn = document.getElementById('rf-submit');
  var submitLbl = document.getElementById('rf-submit-label');
  var siteKey   = <?php echo json_encode($this->siteKey); ?>;

  // ── Character counter ──────────────────────────────────────────────────────
  if (msgArea && counter) {
    msgArea.addEventListener('input', function () {
      var len = msgArea.value.length;
      counter.textContent = len + '/255';
      if (len >= 255) {
        counter.classList.add('rf-counter--limit');
      } else {
        counter.classList.remove('rf-counter--limit');
      }
    });
  }

  // ── Error helpers ──────────────────────────────────────────────────────────
  function showError(field, show) {
    var el = document.querySelector('.rf-error[data-field="' + field + '"]');
    if (!el) return;
    el.style.display = show ? 'block' : 'none';
    if (show) {
      el.classList.add('rf-error--visible');
    } else {
      el.classList.remove('rf-error--visible');
    }
  }

  function setInputError(id, hasError) {
    var el = document.getElementById(id);
    if (!el) return;
    if (hasError) {
      el.classList.add('rf-input--error');
    } else {
      el.classList.remove('rf-input--error');
    }
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  // ── Field validation (without reCAPTCHA) ──────────────────────────────────
  function validateFields() {
    var valid = true;

    var nameVal  = document.getElementById('rf-name').value.trim();
    var emailVal = document.getElementById('rf-email').value.trim();
    var msgVal   = msgArea ? msgArea.value.trim() : '';

    var nameErr = !nameVal;
    showError('name', nameErr);
    setInputError('rf-name', nameErr);
    if (nameErr) valid = false;

    var emailErr = !emailVal || !validateEmail(emailVal);
    showError('email', emailErr);
    setInputError('rf-email', emailErr);
    if (emailErr) valid = false;

    var msgErrEl = document.querySelector('.rf-error[data-field="message"]');
    var msgErr   = false;
    if (!msgVal) {
      msgErr = true;
      if (msgErrEl) msgErrEl.textContent = <?php echo json_encode(JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED')); ?>;
    } else if (msgVal.length > 255) {
      msgErr = true;
      if (msgErrEl) msgErrEl.textContent = <?php echo json_encode(JText::_('COM_REDEUFORM_ERROR_MESSAGE_TOO_LONG')); ?>;
    }
    showError('message', msgErr);
    setInputError('rf-message', msgErr);
    if (msgErr) valid = false;

    return valid;
  }

  // ── Submit handler ─────────────────────────────────────────────────────────
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      if (!validateFields()) {
        return;
      }

      // If no reCAPTCHA configured, submit immediately
      if (!siteKey) {
        form.submit();
        return;
      }

      // reCAPTCHA v3: execute silently and inject token before submitting
      if (typeof grecaptcha === 'undefined') {
        // Script not yet loaded — submit anyway (server will handle gracefully)
        form.submit();
        return;
      }

      // Disable button and show loading state
      submitBtn.disabled = true;
      if (submitLbl) submitLbl.textContent = <?php echo json_encode(JText::_('COM_REDEUFORM_BUTTON_SENDING')); ?>;

      grecaptcha.ready(function () {
        grecaptcha.execute(siteKey, { action: 'contact_form' }).then(function (token) {
          var tokenField = document.getElementById('rf-recaptcha-token');
          if (tokenField) {
            tokenField.value = token;
          }
          form.submit();
        }).catch(function () {
          // Token fetch failed — re-enable button and show error
          submitBtn.disabled = false;
          if (submitLbl) submitLbl.textContent = <?php echo json_encode(JText::_('COM_REDEUFORM_BUTTON_SEND')); ?>;
          showError('recaptcha', true);
        });
      });
    });

    // Clear errors on input
    [
      { id: 'rf-name',    field: 'name' },
      { id: 'rf-email',   field: 'email' },
      { id: 'rf-message', field: 'message' }
    ].forEach(function (item) {
      var el = document.getElementById(item.id);
      if (!el) return;
      el.addEventListener('blur',  function () { validateFields(); });
      el.addEventListener('input', function () {
        showError(item.field, false);
        setInputError(item.id, false);
      });
    });
  }
}());
</script>
