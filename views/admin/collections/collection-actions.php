<form action="<?php echo uri('/bag-it/collections/' . $id . '/add'); ?>" method="post" class="button-form bagit-inline-form">
  <input type="submit" name="addfiles-collection-<?php echo $id; ?>" id="addfiles-collection-<?php echo $id; ?>" value="Add Files" class="bagit-inline-button">
</form>

<form action="<?php echo uri('/bag-it/collections/' . $id . '/export'); ?>" method="post" class="button-form bagit-inline-form">
  <input type="submit" name="addfiles-collection-<?php echo $id; ?>" id="addfiles-collection-<?php echo $id; ?>" value="Create Bag" class="bagit-inline-button bagit-create-bag">
</form>

<form action="<?php echo uri('/bag-it/collections/' . $id . '/delete'); ?>" method="post" class="button-form bagit-inline-form">
  <input type="submit" name="addfiles-collection-<?php echo $id; ?>" id="addfiles-collection-<?php echo $id; ?>" value="Delete" class="bagit-inline-button bagit-delete">
</form>
