<?php

function hasNonEmptyPostValues($postData)
{
  foreach ($postData as $value) {
    if (!empty($value)) {
      return true;
    }
  }
  return false;
}
