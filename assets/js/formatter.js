function parseStringToArray(str) {
  try {
    // Remove leading and trailing whitespace and single quotes
    str = str.trim().replace(/^'|'$/g, '');

    // Check if the string starts with '[' and ends with ']'
    if (str.startsWith('[') && str.endsWith(']')) {
      // Parse the string to JSON
      const arr = JSON.parse(str);

      // Check if the parsed value is an array
      if (Array.isArray(arr)) {
        return arr;
      } else {
        throw new Error('Not an array');
      }
    } else {
      throw new Error('Invalid format');
    }
  } catch (error) {
    // console.error('Error parsing string:', error.message);
    return null;
  }
}