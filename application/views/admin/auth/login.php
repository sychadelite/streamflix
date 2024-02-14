<div class="login-box">

  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?= base_url("admin/dashboard"); ?>" class="h1"><b>Admin</b> <?= SITE_NAME ?></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <form action="<?= base_url("admin/auth/login"); ?>" method="post" autocomplete="off">
        <input type="hidden" name="<?= $content['csrf']['name']; ?>" value="<?= $content['csrf']['hash']; ?>">
        <div class="mb-3">
          <div class="input-group">
            <input id="email" type="email" name="email" value="<?= set_value('email'); ?>" class="form-control" placeholder="Email" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <?= form_error('email', '<small id="email-error" class="text-danger">', '</small>'); ?>
        </div>
        <div class="mb-3">
          <div class="input-group">
            <input id="password" type="password" name="password" value="<?= set_value('password'); ?>" class="form-control" placeholder="Password" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <?= form_error('password', '<small id="password-error" class="text-danger">', '</small>'); ?>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input id="remember" type="checkbox" name="remember" value="true">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>

          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>

        </div>
      </form>
      <div class="social-auth-links text-center mt-2 mb-3">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="<?= base_url("admin/auth/register"); ?>" class="text-center">Register a new membership</a>
      </p>
    </div>

  </div>

</div>

<script>
  $(document).ready(function() {
    $('#email').on('input', function() {
      $('#email-error').text('');
    });
    $('#password').on('input', function() {
      $('#password-error').text('');
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
    <?php if ($this->session->flashdata('message_login_error')) : ?>
      toastr.error('<?= $this->session->flashdata("message_login_error") ?>');
    <?php endif; ?>
    <?php if ($this->session->flashdata('message_register_success')) : ?>
      toastr.success('<?= $this->session->flashdata("message_register_success") ?>');
    <?php endif; ?>
    <?php if ($this->session->flashdata('message_login_success')) : ?>
      toastr.success('<?= $this->session->flashdata("message_login_success") ?>');
    <?php endif; ?>
  });
</script>