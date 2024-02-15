$(document).ready(function () {
  onSidebarEffect()
});


/* ----------------------------------------- FUNCTIONAL ----------------------------------------- */

// Function to handle the effect of sidebar toggle
function onSidebarEffect() {
  // Sidebar behavior
  const sidebar_status = window.localStorage.getItem('sidebar_status')

  if (sidebar_status == 'open') {
    $('body').addClass('sidebar-open');
  }

  if (sidebar_status == 'collapse') {
    $('body').addClass('sidebar-collapse');
  }

  // Event delegation 
  $(document).on('click', '[data-widget="pushmenu"]', function () {
    window.localStorage.setItem('sidebar_status', $('body').hasClass('sidebar-collapse') ? 'collapse' : $('body').hasClass('sidebar-closed') ? 'closed' : 'open');
  });

  $(document).on('click', '#sidebar-overlay', function () {
    window.localStorage.setItem('sidebar_status', $('body').hasClass('sidebar-closed') || $('body').hasClass('sidebar-collapse') ? 'closed' : 'collapse');
  });
}

function openModal(modal, url_segments, path) {
  updatePathname(url_segments);
  if (path) {
    const container = $(modal)
    const endpoint = `${window.location.origin}/${path}`;

    updateFormAction(container);
    performAjaxRequest('GET', endpoint, container);
  }
}

// Function to clear the form input value
function clearFormInputValue(container) {
  const inputElements = container.find('form .card-body [name]')
  inputElements.each(function () {
    const inputElement = $(this);
    inputElement.val('');
  });
}

// Function to clear the form helper (error) text
function clearFormHelperText(container) {
  const inputElements = container.find('form .card-body [name]');
  inputElements.each(function () {
    const inputElement = $(this);
    const name = inputElement.attr('name');

    const escapedName = name.replace(/\[/g, "\\[").replace(/\]/g, "\\]");
    const helperElements = container.find(`[id*="${escapedName}-error"]`);
    helperElements.each(function () {
      const helperElement = $(this);
      helperElement.text('');
    });
  });
}

// Function to set a new form action link
function updateFormAction(container) {
  const form = container.find('form[action]');
  if (form.length > 0) {
    const actionLink = form.attr('action');
    const segments = actionLink.split('/');

    const baseUrl = segments.splice(0, 3).join('/');
    const newActionLink = baseUrl + window.location.pathname;

    // Set the new action URL to the form
    form.attr('action', newActionLink);
  } else {
    console.error('modal / form is not detected')
  }
}

// AJAX
function performAjaxRequest(method, endpoint, container) {
  $.ajax({
    url: endpoint,
    type: method,
    dataType: 'json',
    success: function (response) {
      $.each(response, function (name, value) {
        bindValueToContainer(container, name, value);
      });
    },
    error: logErrorResponse
  });
}

function bindValueToContainer(container, name, value) {
  const labelElement = container.find(`.${name}-label`);
  const inputElement = container.find(`[name^="${name}"]:not([name$="_delete"])`);

  if (labelElement.length > 0) {
    labelElement.text(value);
  }

  if (inputElement.length > 0) {
    const isSelect = inputElement.is('select');
    const isFile = inputElement.is('[type="file"]');
    const isPassword = inputElement.is('[type="password"]');
    const isCheckbox = inputElement.is(':checkbox');

    if (isPassword) return;

    if (isCheckbox) {
      inputElement.val('1');
      if (getQueryParam('err_val') != 1) inputElement.prop('checked', value == 1);
    } else if (isFile) {
      if (getQueryParam('err_val') != 1) {
        if (value) {
          container.find(`.preview.${name}`).css({ 'display': 'block' });
        } else {
          container.find(`.preview.${name}`).css({ 'display': 'none' });
        }

        container.find(`.preview.${name} img`).each(function () {
          $(this).attr('src', value ?? '#');
        });
      }
    } else if (isSelect) {
      // on edit should be set if user just save the changes but get an error *later
      if (getQueryParam('err_val') != 1) {
        // for multiple select option set
        if (inputElement.hasClass("select2")) {
          const arrVal = parseStringToArray(value);
          if (arrVal) {
            inputElement.find('option').each(function () {
              const optionValue = $(this).val();

              if (arrVal.includes(optionValue)) {
                // If the value is selected, set the selected attribute
                $(this).prop('selected', true);
              } else {
                // If the value is not selected, remove the selected attribute
                $(this).prop('selected', false);
              }
            })
          }
          // Refresh the select2 component if needed
          inputElement.trigger('change.select2');
        } else {
          inputElement.val(value);
        }
      }
    } else {
      if (!inputElement.val()) inputElement.val(value);
    }
  }
}

function logErrorResponse(xhr) {
  console.error(xhr.responseText);
  if (xhr.responseText) alert(xhr.responseText);
}