<section id="welcome" class="h-100">
	<div class="overlay"></div>

	<div class="container content pt-2 pb-4" style="min-height: 95vh;">

		<div class="py-3">
			<div class="m-auto" style="max-width: 360px;">
				<a href="<?= base_url("client/home") ?>" class="d-flex h-100">
					<img src="<?= base_url("assets/icons/logo.svg") ?>" alt="app-logo" width="100%">
				</a>
			</div>
		</div>

		<h1 class="fs-1 fw-bold">Unlimited movies, TV shows, and more</h1>
		<small>We welcome you to join us!</small>
		<p class="fs-6">
			Ready to watch? Enter your email to create or restart your membership.
		</p>
		<h1>Get Started!</h1>

		<div class="buttons">
			<form action="<?= base_url("client/auth/login") ?>" method="post">
				<input type="text" name="email" placeholder="Email address" value="<?= $auth["current_user"]->email ?? '' ?>" <?= $auth["current_user"] ? "disabled" : "" ?>>
			</form>

			<!-- The Glow Button -->
			<!-- https://stacksorted.com/ -->
			<a href="<?= base_url("client/home") ?>" class="GlowingButton">

				<!-- Glow -->
				<div class="GlowingButton__glowWrap l"><span class="GlowingButton__glow"></span></div>
				<div class="GlowingButton__glowWrap r"><span class="GlowingButton__glow"></span></div>
				<!-- /Glow -->

				<!-- Overlay -->
				<span class="GlowingButton__overlay"></span>
				<!-- Overlay -->

				<!-- Content -->
				<div class="GlowingButton__content">
					<span>Visit Streamflix</span>
					<span>Visit Streamflix</span>
				</div>
				<!-- /Content -->

			</a>
			<!-- /The Glow Button -->
		</div>

	</div>

</section>

<script>
	<?php if ($this->session->flashdata('message_logout_success')) : ?>
		toastr.info('<?= $this->session->flashdata("message_logout_success") ?>');
	<?php endif; ?>
</script>

<script type="importmap">
	{
    "imports": {
      "three": "https://unpkg.com/three@0.158.0/build/three.module.js",
      "three/addons/": "https://unpkg.com/three@0.158.0/examples/jsm/"
    }
  }
</script>

<script type="module" src="<?= base_url("assets/js/threejs/rock.js"); ?>"></script>