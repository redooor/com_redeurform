<?php defined('_JEXEC') or die; ?>

<div class="tw-min-h-screen tw-bg-gradient-to-br tw-from-slate-50 tw-to-stone-100 tw-py-12 tw-px-4 sm:tw-px-6 lg:tw-px-8" id="redeuform-app">

  <div class="tw-max-w-xl tw-mx-auto">

    <!-- Header -->
    <div class="tw-text-center tw-mb-10">
      <div class="tw-inline-flex tw-items-center tw-justify-center tw-w-16 tw-h-16 tw-rounded-2xl tw-bg-stone-800 tw-mb-4 tw-shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-8 tw-h-8 tw-text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
      </div>
      <h1 class="tw-text-3xl tw-font-bold tw-text-stone-800 tw-tracking-tight" style="font-family:'Georgia',serif;">
        <?php echo JText::_('COM_REDEUFORM_TITLE'); ?>
      </h1>
      <p class="tw-mt-2 tw-text-stone-500 tw-text-sm">
        <?php echo JText::_('COM_REDEUFORM_SUBTITLE'); ?>
      </p>
    </div>

    <!-- System messages -->
    <?php $messages = JFactory::getApplication()->getMessageQueue(); ?>
    <?php if (!empty($messages)): ?>
      <div class="tw-mb-6 tw-space-y-2">
        <?php foreach ($messages as $msg): ?>
          <?php
            $cls = 'tw-bg-blue-50 tw-border-blue-200 tw-text-blue-700';
            if ($msg['type'] === 'error')   $cls = 'tw-bg-red-50 tw-border-red-200 tw-text-red-700';
            if ($msg['type'] === 'message') $cls = 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-700';
            if ($msg['type'] === 'warning') $cls = 'tw-bg-amber-50 tw-border-amber-200 tw-text-amber-700';
          ?>
          <div class="tw-border tw-rounded-xl tw-px-4 tw-py-3 tw-text-sm tw-font-medium <?php echo $cls; ?>">
            <?php echo htmlspecialchars($msg['message']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="tw-bg-white tw-rounded-3xl tw-shadow-xl tw-border tw-border-stone-100 tw-overflow-hidden">

      <!-- Accent bar -->
      <div class="tw-h-1.5 tw-bg-gradient-to-r tw-from-amber-400 tw-via-orange-400 tw-to-rose-400"></div>

      <div class="tw-p-8 sm:tw-p-10">
        <form
          id="redeuform-contact"
          action="<?php echo JRoute::_('index.php?option=com_redeuform&task=form.submit'); ?>"
          method="post"
          novalidate
          class="tw-space-y-6"
        >

          <!-- Name -->
          <div>
            <label for="rf-name" class="tw-block tw-text-xs tw-font-semibold tw-text-stone-500 tw-uppercase tw-tracking-widest tw-mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_NAME'); ?> <span class="tw-text-rose-500">*</span>
            </label>
            <input
              type="text"
              id="rf-name"
              name="name"
              maxlength="255"
              autocomplete="name"
              class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-stone-200 tw-bg-stone-50 tw-text-stone-800 tw-text-sm tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-400 focus:tw-border-transparent focus:tw-bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_NAME_PLACEHOLDER'); ?>"
            />
            <p class="redeu-error tw-hidden tw-mt-1.5 tw-text-xs tw-text-rose-500 tw-font-medium" data-field="name">
              <?php echo JText::_('COM_REDEUFORM_ERROR_NAME_REQUIRED'); ?>
            </p>
          </div>

          <!-- Email -->
          <div>
            <label for="rf-email" class="tw-block tw-text-xs tw-font-semibold tw-text-stone-500 tw-uppercase tw-tracking-widest tw-mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL'); ?> <span class="tw-text-rose-500">*</span>
            </label>
            <input
              type="email"
              id="rf-email"
              name="email"
              autocomplete="email"
              class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-stone-200 tw-bg-stone-50 tw-text-stone-800 tw-text-sm tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-400 focus:tw-border-transparent focus:tw-bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL_PLACEHOLDER'); ?>"
            />
            <p class="redeu-error tw-hidden tw-mt-1.5 tw-text-xs tw-text-rose-500 tw-font-medium" data-field="email">
              <?php echo JText::_('COM_REDEUFORM_ERROR_EMAIL_INVALID'); ?>
            </p>
          </div>

          <!-- Phone (optional) -->
          <div>
            <label for="rf-phone" class="tw-block tw-text-xs tw-font-semibold tw-text-stone-500 tw-uppercase tw-tracking-widest tw-mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_PHONE'); ?>
              <span class="tw-text-stone-300 tw-font-normal tw-normal-case tw-tracking-normal tw-ml-1"><?php echo JText::_('COM_REDEUFORM_FIELD_OPTIONAL'); ?></span>
            </label>
            <input
              type="tel"
              id="rf-phone"
              name="phone"
              autocomplete="tel"
              class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-stone-200 tw-bg-stone-50 tw-text-stone-800 tw-text-sm tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-400 focus:tw-border-transparent focus:tw-bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_PHONE_PLACEHOLDER'); ?>"
            />
          </div>

          <!-- Message -->
          <div>
            <label for="rf-message" class="tw-block tw-text-xs tw-font-semibold tw-text-stone-500 tw-uppercase tw-tracking-widest tw-mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE'); ?> <span class="tw-text-rose-500">*</span>
            </label>
            <textarea
              id="rf-message"
              name="message"
              rows="5"
              maxlength="255"
              class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-stone-200 tw-bg-stone-50 tw-text-stone-800 tw-text-sm tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-amber-400 focus:tw-border-transparent focus:tw-bg-white tw-resize-none"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE_PLACEHOLDER'); ?>"
            ></textarea>
            <div class="tw-flex tw-justify-between tw-items-center tw-mt-1.5">
              <p class="redeu-error tw-hidden tw-text-xs tw-text-rose-500 tw-font-medium" data-field="message">
                <?php echo JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED'); ?>
              </p>
              <p class="tw-text-xs tw-text-stone-400 tw-ml-auto">
                <span id="rf-char-count">0</span>/255
              </p>
            </div>
          </div>

          <!-- reCAPTCHA -->
          <?php if (!empty($this->siteKey)): ?>
          <div>
            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($this->siteKey); ?>"></div>
            <p class="redeu-error tw-hidden tw-mt-1.5 tw-text-xs tw-text-rose-500 tw-font-medium" data-field="recaptcha">
              <?php echo JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_REQUIRED'); ?>
            </p>
          </div>
          <?php endif; ?>

          <!-- Hidden fields -->
          <input type="hidden" name="<?php echo $this->token; ?>" value="1" />

          <!-- Submit -->
          <button
            type="submit"
            id="rf-submit"
            class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-6 tw-py-3.5 tw-rounded-xl tw-bg-stone-800 hover:tw-bg-stone-700 active:tw-bg-stone-900 tw-text-white tw-text-sm tw-font-semibold tw-tracking-wide tw-transition-all tw-duration-200 tw-shadow-lg hover:tw-shadow-xl hover:tw--translate-y-0.5"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
            <?php echo JText::_('COM_REDEUFORM_BUTTON_SEND'); ?>
          </button>

        </form>
      </div>
    </div>

    <!-- Footer note -->
    <p class="tw-text-center tw-text-xs tw-text-stone-400 tw-mt-6">
      <?php echo JText::_('COM_REDEUFORM_PRIVACY_NOTE'); ?>
    </p>

  </div>
</div>

<script>
(function () {
  'use strict';

  var form    = document.getElementById('redeuform-contact');
  var msgArea = document.getElementById('rf-message');
  var counter = document.getElementById('rf-char-count');

  // Live character counter
  if (msgArea && counter) {
    msgArea.addEventListener('input', function () {
      counter.textContent = msgArea.value.length;
      if (msgArea.value.length >= 255) {
        counter.classList.add('tw-text-rose-500');
        counter.classList.remove('tw-text-stone-400');
      } else {
        counter.classList.remove('tw-text-rose-500');
        counter.classList.add('tw-text-stone-400');
      }
    });
  }

  function showError(field, show) {
    var el = document.querySelector('.redeu-error[data-field="' + field + '"]');
    if (!el) return;
    if (show) {
      el.classList.remove('tw-hidden');
    } else {
      el.classList.add('tw-hidden');
    }
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validateForm() {
    var valid = true;

    var name    = document.getElementById('rf-name').value.trim();
    var email   = document.getElementById('rf-email').value.trim();
    var message = msgArea ? msgArea.value.trim() : '';

    showError('name', !name);
    if (!name) valid = false;

    showError('email', !email || !validateEmail(email));
    if (!email || !validateEmail(email)) valid = false;

    var msgError = !message || message.length > 255;
    if (!message) {
      document.querySelector('.redeu-error[data-field="message"]').textContent =
        <?php echo json_encode(JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED')); ?>;
    } else if (message.length > 255) {
      document.querySelector('.redeu-error[data-field="message"]').textContent =
        <?php echo json_encode(JText::_('COM_REDEUFORM_ERROR_MESSAGE_TOO_LONG')); ?>;
    }
    showError('message', msgError);
    if (msgError) valid = false;

    <?php if (!empty($this->siteKey)): ?>
    var captchaEl = document.querySelector('.redeu-error[data-field="recaptcha"]');
    if (typeof grecaptcha !== 'undefined') {
      var response = grecaptcha.getResponse();
      if (!response) {
        if (captchaEl) captchaEl.classList.remove('tw-hidden');
        valid = false;
      } else {
        if (captchaEl) captchaEl.classList.add('tw-hidden');
      }
    }
    <?php endif; ?>

    return valid;
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });

    ['rf-name', 'rf-email', 'rf-message'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) {
        el.addEventListener('blur', function () { validateForm(); });
        el.addEventListener('input', function () {
          var field = id === 'rf-name' ? 'name' : id === 'rf-email' ? 'email' : 'message';
          showError(field, false);
        });
      }
    });
  }
}());
</script>
