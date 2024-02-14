<section id="register">
  <div class="hero pb-0">
    <div class="overlay"></div>
    <div class="header">
      <a href="<?= base_url("client/home") ?>" class="d-flex h-100" style="max-width: 200px;">
        <img src="<?= base_url("assets/icons/logo.svg") ?>" alt="app-logo" width="100%" style="z-index: 1;">
      </a>
    </div>
    <div class="container m-auto">
      <div class="content">
        <div>
          <div class="form-wrapper">
            <div class="form-container">
              <form action="<?= base_url("client/auth/register") ?>" method="post" autocomplete="off">
                <input type="hidden" name="<?= $content['csrf']['name']; ?>" value="<?= $content['csrf']['hash']; ?>">
                <h1>Sign up</h1>
                <div class="form-element">
                  <div class="position-relative">
                    <input id="username" type="text" name="username" value="<?= set_value('username'); ?>" class="<?= form_error('username') ? 'error' : '' ?>" required />
                    <label class="floating-label" for="username">Username</label>
                  </div>
                  <?= form_error('username', '<small id="username-error">', '</small>'); ?>
                </div>
                <div class="form-element">
                  <div class="position-relative">
                    <input id="email" type="email" name="email" value="<?= set_value('email'); ?>" class="<?= form_error('email') ? 'error' : '' ?>" required />
                    <label class="floating-label" for="email">Email</label>
                  </div>
                  <?= form_error('email', '<small id="email-error">', '</small>'); ?>
                </div>
                <div class="form-element">
                  <div class="position-relative">
                    <input id="password" type="password" name="password" value="<?= set_value('password'); ?>" class="<?= form_error('password') ? 'error' : '' ?>" required />
                    <label class="floating-label" for="password">Password</label>
                    <i class="far fa-eye-slash append-icon" onclick="togglePassword(this)"></i>
                  </div>
                  <?= form_error('password', '<small id="password-error">', '</small>'); ?>
                </div>
                <div class="form-element">
                  <div class="position-relative">
                    <input id="password_confirmation" type="password_confirmation" name="password_confirmation" value="<?= set_value('password_confirmation'); ?>" class="<?= form_error('password_confirmation') ? 'error' : '' ?>" required />
                    <label class="floating-label" for="password_confirmation">Password Confirmation</label>
                    <i class="far fa-eye-slash append-icon" onclick="togglePassword(this)"></i>
                  </div>
                  <?= form_error('password_confirmation', '<small id="password_confirmation-error">', '</small>'); ?>
                </div>

                <button type="submit" class="btn-register">
                  Sign In
                </button>

                <div class="form-element mt-3">
                  <label for="terms" class="option">
                    I agree terms
                    <input type="checkbox" id="terms" name="terms" value="true" />
                    <span class="checkbox checkbox3"></span>
                  </label>
                  <?= form_error('terms', '<small id="terms-error">', '</small>'); ?>
                </div>
              </form>
              <div class="info">
                <h5>
                  New to <?= SITE_NAME ?>?
                  <a class="text-white" href="<?= base_url("client/auth/login") ?>">
                    <span class="link text-white">Sign in now.</span>
                  </a>
                </h5>
                <p>
                  This page is protected by Google reCAPTCHA to ensure you're not a bot.
                  <a href="https://developers.google.com/recaptcha/docs/v3" target="_blank">
                    <span class="link">Learn more.</span>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#username').on('input', function() {
      $('#username-error').text('');
    });
    $('#email').on('input', function() {
      $('#email-error').text('');
    });
    $('#password').on('input', function() {
      $('#password-error').text('');
    });
    $('#password_confirmation').on('input', function() {
      $('#password_confirmation-error').text('');
    });
    $('#terms').on('input', function() {
      $('#terms-error').text('');
    });

    <?php if (isset($validation_errors) && !empty($validation_errors)) : ?>
      var validationErrors = <?= json_encode($validation_errors) ?>;
      // Split the validation errors by line
      var errorsArray = validationErrors.split("\n");

      // Loop through each error and display toastr message
      for (var i = 0; i < errorsArray.length; i++) {
        if (errorsArray[i].trim() !== "") {
          toastr.warning(errorsArray[i].trim());
        }
      }
    <?php endif; ?>

    <?php if ($this->session->flashdata('message_validation_error')) : ?>
      toastr.error('<?= $this->session->flashdata("message_validation_error") ?>');
    <?php endif; ?>
    <?php if ($this->session->flashdata('message_register_error')) : ?>
      toastr.error('<?= $this->session->flashdata("message_register_error") ?>');
    <?php endif; ?>
    <?php if ($this->session->flashdata('message_register_success')) : ?>
      toastr.success('<?= $this->session->flashdata("message_register_success") ?>');
    <?php endif; ?>
  });

  function togglePassword(e) {
    const el = $(e);
    const input = el.parent().find("input");
    if (input.attr("type") != "text") {
      input.attr("type", "text");
      el.removeClass("fa-eye-slash");
      el.addClass("fa-eye");
    } else {
      input.attr("type", "password");
      el.removeClass("fa-eye");
      el.addClass("fa-eye-slash");
    }
  }
</script>