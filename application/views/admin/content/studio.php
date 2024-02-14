<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
            <h3 class="card-title">Manage <?= strtolower(formatColumnName($context)) ?> with data table</h3>
            <button class="btn btn-sm btn-primary ml-auto" onclick="updatePathname([...getSegmentPath('<?= $path_prefix . $context ?>'), 'add'])" data-toggle="modal" data-target="#modal-<?= $context ?>-add"><i class="fas fa-plus mr-2"></i>Add New</button>
          </div>

          <div class="card-body">
            <table id="<?= $context ?>_data_table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <?php foreach ($content["table"][$context]["columns"] as $key => $value) : ?>
                    <th><?= formatColumnName($value) ?></th>
                  <?php endforeach ?>
                  <th data-orderable="false">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>


<?php $this->load->view('admin/_partials/script-dt') ?>

<script>
  $(document).ready(function() {
    const csrf_token = '<?= $this->security->get_csrf_hash(); ?>';

    /* DATA TABLE */
    $(function() {
      var dt = $("#<?= $context ?>_data_table").DataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "autoWidth": false,
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": '<?= base_url($path_prefix . $context . "/ajax_list"); ?>',
          "type": "POST",
          "data": function(d) {
            d.<?= $this->security->get_csrf_token_name(); ?> = csrf_token;
          }
        },
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "order": [],
        "initComplete": function(settings, json) {
          // Append Buttons after initialization
          dt.buttons().container().appendTo('#<?= $context ?>_data_table_wrapper .col-md-6:eq(0)');
        },
      });

      $(window).on('resize', function() {
        dt.columns.adjust();
      });
    })

    /* VALIDATION */
    $(function() {
      <?php if (isset($validation_errors) && !empty($validation_errors)) { ?>
        const validationErrors = <?= json_encode($validation_errors) ?>;
        // Split the validation errors by line
        const errorsArray = validationErrors.split("\n");

        // Loop through each error and display toastr message
        for (let i = 0; i < errorsArray.length; i++) {
          if (errorsArray[i].trim() !== "") {
            toastr.warning(errorsArray[i].trim());
          }
        }

        setQueryParam('err_val', 1);
      <?php } else { ?>
        deleteQueryParam('err_val');
      <?php } ?>

      <?php if ($this->session->flashdata("message_validation_error")) : ?>
        toastr.error('<?= $this->session->flashdata("message_validation_error") ?>');
      <?php endif; ?>
      <?php if ($this->session->flashdata("message_add_" . $context . "_success")) : ?>
        toastr.success('<?= $this->session->flashdata("message_add_" . $context . "_success") ?>');
      <?php endif; ?>
      <?php if ($this->session->flashdata("message_edit_" . $context . "_error")) : ?>
        toastr.error('<?= $this->session->flashdata("message_edit_" . $context . "_error") ?>');
      <?php endif; ?>
      <?php if ($this->session->flashdata("message_edit_" . $context . "_success")) : ?>
        toastr.success('<?= $this->session->flashdata("message_edit_" . $context . "_success") ?>');
      <?php endif; ?>
      <?php if ($this->session->flashdata("message_delete_" . $context . "_error")) : ?>
        toastr.error('<?= $this->session->flashdata("message_delete_" . $context . "_error") ?>');
      <?php endif; ?>
      <?php if ($this->session->flashdata("message_delete_" . $context . "_success")) : ?>
        toastr.success('<?= $this->session->flashdata("message_delete_" . $context . "_success") ?>');
      <?php endif; ?>
    })

  });
</script>