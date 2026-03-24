<?php defined('_JEXEC') or die; ?>

<div class="redeuform-wrapper min-h-screen bg-gradient-to-br from-slate-50 to-stone-100 py-12 px-4 sm:px-6 lg:px-8" id="redeuform-app">

  <div class="max-w-xl mx-auto">

    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-stone-800 mb-4 shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-stone-800 tracking-tight" style="font-family:'Georgia',serif;">
        <?php echo JText::_('COM_REDEUFORM_TITLE'); ?>
      </h1>
      <p class="mt-2 text-stone-500 text-sm">
        <?php echo JText::_('COM_REDEUFORM_SUBTITLE'); ?>
      </p>
    </div>

    <!-- System messages -->
    <?php $messages = JFactory::getApplication()->getMessageQueue(); ?>
    <?php if (!empty($messages)): ?>
      <div class="mb-6 space-y-2">
        <?php foreach ($messages as $msg): ?>
          <?php
            $cls = 'bg-blue-50 border-blue-200 text-blue-700';
            if ($msg['type'] === 'error')   $cls = 'bg-red-50 border-red-200 text-red-700';
            if ($msg['type'] === 'message') $cls = 'bg-emerald-50 border-emerald-200 text-emerald-700';
            if ($msg['type'] === 'warning') $cls = 'bg-amber-50 border-amber-200 text-amber-700';
          ?>
          <div class="border rounded-xl px-4 py-3 text-sm font-medium <?php echo $cls; ?>">
            <?php echo htmlspecialchars($msg['message']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-stone-200/60 border border-stone-100 overflow-hidden">

      <!-- Card accent bar -->
      <div class="h-1.5 bg-gradient-to-r from-amber-400 via-orange-400 to-rose-400"></div>

      <div class="p-8 sm:p-10">
        <form
          id="redeuform-contact"
          action="<?php echo JRoute::_('index.php?option=com_redeuform&task=form.submit'); ?>"
          method="post"
          novalidate
          class="space-y-6"
        >

          <!-- Name -->
          <div class="redeu-field">
            <label for="rf-name" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_NAME'); ?> <span class="text-rose-500">*</span>
            </label>
            <input
              type="text"
              id="rf-name"
              name="name"
              maxlength="255"
              autocomplete="name"
              class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 placeholder-stone-300 text-sm transition focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_NAME_PLACEHOLDER'); ?>"
            />
            <p class="redeu-error hidden mt-1.5 text-xs text-rose-500 font-medium" data-field="name">
              <?php echo JText::_('COM_REDEUFORM_ERROR_NAME_REQUIRED'); ?>
            </p>
          </div>

          <!-- Email -->
          <div class="redeu-field">
            <label for="rf-email" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL'); ?> <span class="text-rose-500">*</span>
            </label>
            <input
              type="email"
              id="rf-email"
              name="email"
              autocomplete="email"
              class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 placeholder-stone-300 text-sm transition focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_EMAIL_PLACEHOLDER'); ?>"
            />
            <p class="redeu-error hidden mt-1.5 text-xs text-rose-500 font-medium" data-field="email">
              <?php echo JText::_('COM_REDEUFORM_ERROR_EMAIL_INVALID'); ?>
            </p>
          </div>

          <!-- Phone (optional) -->
          <div class="redeu-field">
            <label for="rf-phone" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_PHONE'); ?>
              <span class="text-stone-300 font-normal normal-case tracking-normal ml-1"><?php echo JText::_('COM_REDEUFORM_FIELD_OPTIONAL'); ?></span>
            </label>
            <input
              type="tel"
              id="rf-phone"
              name="phone"
              autocomplete="tel"
              class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 placeholder-stone-300 text-sm transition focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_PHONE_PLACEHOLDER'); ?>"
            />
          </div>

          <!-- Message -->
          <div class="redeu-field">
            <label for="rf-message" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-1.5">
              <?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE'); ?> <span class="text-rose-500">*</span>
            </label>
            <textarea
              id="rf-message"
              name="message"
              rows="5"
              maxlength="255"
              class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 placeholder-stone-300 text-sm transition focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white resize-none"
              placeholder="<?php echo JText::_('COM_REDEUFORM_FIELD_MESSAGE_PLACEHOLDER'); ?>"
            ></textarea>
            <div class="flex justify-between items-center mt-1.5">
              <p class="redeu-error hidden text-xs text-rose-500 font-medium" data-field="message">
                <?php echo JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED'); ?>
              </p>
              <p class="text-xs text-stone-400 ml-auto">
                <span id="rf-char-count">0</span>/255
              </p>
            </div>
          </div>

          <!-- reCAPTCHA -->
          <?php if (!empty($this->siteKey)): ?>
          <div class="redeu-field">
            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($this->siteKey); ?>"></div>
            <p class="redeu-error hidden mt-1.5 text-xs text-rose-500 font-medium" data-field="recaptcha">
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
            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-stone-800 hover:bg-stone-700 active:bg-stone-900 text-white text-sm font-semibold tracking-wide transition-all duration-200 shadow-lg shadow-stone-800/20 hover:shadow-xl hover:shadow-stone-800/30 hover:-translate-y-0.5"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
            <?php echo JText::_('COM_REDEUFORM_BUTTON_SEND'); ?>
          </button>

        </form>
      </div>
    </div>

    <!-- Footer note -->
    <p class="text-center text-xs text-stone-400 mt-6">
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
        counter.classList.add('text-rose-500');
      } else {
        counter.classList.remove('text-rose-500');
      }
    });
  }

  function showError(field, show) {
    var el = document.querySelector('.redeu-error[data-field="' + field + '"]');
    if (!el) return;
    if (show) {
      el.classList.remove('hidden');
    } else {
      el.classList.add('hidden');
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

    showError('name',    !name);
    if (!name) valid = false;

    showError('email',   !email || !validateEmail(email));
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

    // reCAPTCHA check
    <?php if (!empty($this->siteKey)): ?>
    var captchaEl = document.querySelector('.redeu-error[data-field="recaptcha"]');
    if (typeof grecaptcha !== 'undefined') {
      var response = grecaptcha.getResponse();
      if (!response) {
        if (captchaEl) captchaEl.classList.remove('hidden');
        valid = false;
      } else {
        if (captchaEl) captchaEl.classList.add('hidden');
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

    // Inline validation on blur
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
