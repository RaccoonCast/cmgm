<?php
if (!isset($_POST['new'])) {
  if (!empty($value)) {
    if (str_contains($key, 'PCI')) {
      $pci_updated_note = "true";
    } else {
      if (!empty(@${@$key})) @$vals .= "$" . $key . ' value changed from "' . preg_replace( "/\r|\n/", " ", @${@$key}) . '" to "' . preg_replace( "/\r|\n/", " ", $value ) . '" '. PHP_EOL.'';
      if (empty(@${@$key})) @$vals .= "$" . $key . ' value set to "' . preg_replace( "/\r|\n/", " ", $value ) . '" '. PHP_EOL.'';
    }
  }
  if (empty($value)) {
    if (str_contains($key, 'PCI')) {
      $pci_removed_note = "true";
    } else {
      @$vals .= "$" . $key . ' value removed, $' . $key . ' formerly "' . preg_replace( "/\r|\n/", " ", @${@$key}) . '" '. PHP_EOL.'';
    }
  }
} else {
  @$vals .= "$" . $key . ' value set to "' . preg_replace( "/\r|\n/", " ", $value ) . '" '. PHP_EOL.'';
}
 ?>
