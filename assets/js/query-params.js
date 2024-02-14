// Function to set a new query parameter to the URL
function setQueryParam(key, value) {
  clearAllQueryParams();

  var url = new URL(window.location.href);
  url.searchParams.set(key, value);
  window.history.replaceState({}, document.title, url.toString());
}

function getQueryParam(key) {
  const url = new URL(window.location.href);
  return url.searchParams.get(key);
}

// Function to update a query parameter in the URL
function updateQueryParam(key, value) {
  var url = new URL(window.location.href);
  url.searchParams.set(key, value);
  window.history.replaceState({}, document.title, url.toString());
}

// Function to delete a query parameter from the URL
function deleteQueryParam(key) {
  clearAllQueryParams();

  var url = new URL(window.location.href);
  url.searchParams.delete(key);
  window.history.replaceState({}, document.title, url.toString());
}

// Function to clear all query parameters from the URL
function clearAllQueryParams() {
  var url = new URL(window.location.href);
  url.search = '';
  window.history.replaceState({}, document.title, url.toString());
}