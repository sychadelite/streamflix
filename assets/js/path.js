// Function to get a segments value with condition
function getPageSegmentValue(pos = 1, list = []) {
  const currentUrl = window.location.pathname;
  const segments = currentUrl.split('/').filter(function (segment) {
    return segment !== '';
  });

  // Check if the path matches the specified list
  const matchesList = list.every(function (value, index) {
    return segments[index] === value;
  });

  if (matchesList) {
    return segments[pos];
  }

  return null;
}

function getCumulativePathSegments(path) {
  return Array.from(path.matchAll(/(?<=(.+(\/|$)))/g), (match) => match[1])
}

// Function to achieve the segment of url
function getSegmentPath(path) {
  const segments = path.split('/');
  return segments;
}

// Function to set the new url
function updatePathname(segmentsToAdd) {
  const currentUrl = window.location.href;
  const url = new URL(currentUrl);

  // Set the new pathname directly
  const newPathname = '/' + segmentsToAdd.join('/');
  url.pathname = newPathname.replace(/\/{2,}/g, '/');

  // Update the browser URL
  window.history.replaceState({}, document.title, url.toString());
}