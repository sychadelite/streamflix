<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <?php if (!$this->uri->segment(3)) { ?>
          <h1 class="m-0"><?= formatColumnName($this->uri->segment(2)) ?></h1>
        <?php } else { ?>
          <h1 class="m-0"><?= formatColumnName(str_replace('_', ' ', $this->uri->segment(3))) ?></h1>
        <?php } ?>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="#">
              Home
            </a>
          </li>
          <li class="breadcrumb-item <?php !$this->uri->segment(3) ? 'active' : '' ?>">
            <a href="<?= base_url($this->uri->segment(1) . '/' . $this->uri->segment(2)) ?>">
              <?= formatColumnName($this->uri->segment(2)) ?>
            </a>
          </li>
          <?php if ($this->uri->segment(3)) { ?>
            <li class="breadcrumb-item active">
              <a href="#">
                <?= formatColumnName($this->uri->segment(3)) ?>
              </a>
            </li>
          <?php } ?>
        </ol>
      </div>
    </div>
  </div>
</div>