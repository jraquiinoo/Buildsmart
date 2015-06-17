<form method='post'>
<p>Color</p>
<?php foreach($colors as $color): ?>
	<input type='checkbox' name='colors[]' value='<?php echo $color['color_id']; ?>'><?php echo ucfirst($color['color_name']); ?></input>
<?php endforeach ?>
<p>Application</p>
<?php foreach($applications as $application): ?>
	<input type='checkbox' name='applications[]' value='<?php echo $application['application_id'] ?>'><?php echo ucfirst($application['application_name']); ?></input>
<?php endforeach ?>
<p>Texture</p>
<?php foreach($textures as $texture): ?>
	<input type='checkbox' name='textures[]' value='<?php echo ucfirst($texture); ?>'><?php echo $texture; ?></input>
<?php endforeach ?>
<input type='submit'/>
</form>