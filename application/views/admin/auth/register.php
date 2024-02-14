<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?= base_url("admin/dashboard"); ?>" class="h1"><b>Admin</b>LTE</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>
      <form action="<?= base_url("admin/auth/register"); ?>" method="post" autocomplete="off">
        <input type="hidden" name="<?= $content['csrf']['name']; ?>" value="<?= $content['csrf']['hash']; ?>">
        <div class="mb-3">
          <div class="input-group">
            <input type="text" id="username" name="username" value="<?= set_value('username') ?>" class="form-control" placeholder="Username" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <?= form_error('username', '<small id="username-error" class="text-danger">', '</small>'); ?>
        </div>
        <div class="mb-3">
          <div class="input-group">
            <input type="email" id="email" name="email" value="<?= set_value('email') ?>" class="form-control" placeholder="Email" autocomplete="off">
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
            <input type="password" id="password" name="password" value="<?= set_value('password') ?>" class="form-control" placeholder="Password" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <?= form_error('password', '<small id="password-error" class="text-danger">', '</small>'); ?>
        </div>
        <div class="mb-3">
          <div class="input-group">
            <input type="password" id="password_confirmation" name="password_confirmation" value="<?= set_value('password_confirmation') ?>" class="form-control" placeholder="Retype password" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <?= form_error('password_confirmation', '<small id="password-confirmation-error" class="text-danger">', '</small>'); ?>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="terms" name="terms" value="true">
              <label for="terms">
                I agree to the <a href="#">terms</a>
              </label>
            </div>
            <?= form_error('terms', '<small class="id="terms-error" text-danger">', '</small>'); ?>
          </div>

          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
        </div>
      </form>
      <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>
      <a href="<?= base_url("admin/auth/login"); ?>" class="text-center">I already have a membership</a>
    </div>

  </div>
</div>

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
      $('#password-confirmation-error').text('');
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
</script>