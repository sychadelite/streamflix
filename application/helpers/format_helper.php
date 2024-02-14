<?php

function formatColumnName($columnName)
{
  // Remove prefix and split by "."
  $parts = explode('.', $columnName);

  // Get last part and replace underscores with spaces
  $lastPart = str_replace('_', ' ', end($parts));

  // Return title case
  return ucwords($lastPart);
}
