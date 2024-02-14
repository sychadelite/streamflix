<div>
  <?php if (isset($content['component']['modal']['add'])) : echo $content['component']['modal']['add'];
  endif; ?>
  <?php if (isset($content['component']['modal']['edit'])) : echo $content['component']['modal']['edit'];
  endif; ?>
  <?php if (isset($content['component']['modal']['delete'])) : echo $content['component']['modal']['delete'];
  endif; ?>
</div>

<script>
  $(document).ready(function() {
    // ------------- MODAL FIRST RENDER
    const current_modal = '<?= isset($content['modal']['container']) ? $content['modal']['container'] : NULL; ?>';
    const current_endpoint = '<?= isset($content['modal']['endpoint']) ? $content['modal']['endpoint'] : NULL; ?>';

    if (current_modal) {
      const container = $(current_modal);
      container.modal('show');

      if (current_endpoint) {
        const endpoint = window.location.origin + current_endpoint;

        updateFormAction(container)
        performAjaxRequest('GET', endpoint, container);
      }
    }

    // ------------- MODAL BEHAVIOR
    $(function() {
      // clear error feedback on input
      setupErrorClearance('#modal-<?= $context ?>-add form[id="modal-<?= $context ?>-add-form"]');
      setupErrorClearance('#modal-<?= $context ?>-edit form[id="modal-<?= $context ?>-edit-form"]');
      setupErrorClearance('#modal-<?= $context ?>-delete form[id="modal-<?= $context ?>-delete-form"]');

      // File Binding
      const available_files = <?= json_encode(isset($content['files']) ? $content['files'] : []) ?>;
      available_files.forEach(file_name => {
        handleFileChange("#modal-<?= $context ?>-add-form #add_" + file_name, "#modal-<?= $context ?>-add-form .preview." + file_name);
        handleFileChange("#modal-<?= $context ?>-edit-form #edit_" + file_name, "#modal-<?= $context ?>-edit-form .preview." + file_name);
      });

      $('#modal-<?= $context ?>-add').on('show.bs.modal', function(e) {
        // Do something when the modal is shown
        // clearance
        clearFormHelperText($(e.target))
      });
      $('#modal-<?= $context ?>-edit, #modal-<?= $context ?>-delete').on('show.bs.modal', function(e) {
        // Do something when the modal is shown
        // clearance
        clearFormInputValue($(e.target))
        clearFormHelperText($(e.target))
      });
      $('#modal-<?= $context ?>-add, #modal-<?= $context ?>-edit, #modal-<?= $context ?>-delete').on('hidden.bs.modal', function(e) {
        // Do something when the modal is dismissed
        updatePathname(getSegmentPath('<?= $path_prefix . $context ?>'))
        deleteQueryParam('err_val');
      });
    });
  });

  function setupErrorClearance(formSelector) {
    const form = $(formSelector);
    form.find('[name]').each(function() {
      const name = $(this).attr('name');
      const escapedName = name.replace(/\[/g, "\\[").replace(/\]/g, "\\]");
      $(this).on('input', function() {
        $(`#add_${escapedName}-error`).text('');
        $(`#edit_${escapedName}-error`).text('');
        $(`#delete_${escapedName}-error`).text('');
      });
    });
  }

  function handleFileChange(inputId, previewClass) {
    $(inputId).change(function() {
      let file = this.files[0];
      if (file) {
        let reader = new FileReader();

        reader.onload = function(event) {
          $(previewClass).css({
            display: "block"
          });
          $(`${previewClass} img`).attr("src", event.target.result);
        };
        reader.readAsDataURL(file);

        let fileSizeKB = (file.size / 1024).toFixed(2);
        $($(this).parent()).children("label").html(`${file.name} | ${fileSizeKB} KB`);
      }
    });
  }
</script>