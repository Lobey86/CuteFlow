<?php
echo "<b>" . $sf_user->getCulture() . "</b><br>";
?>
<input type="hidden" name="url" id="url" value="<?php echo url_for('layout/Index')?>">