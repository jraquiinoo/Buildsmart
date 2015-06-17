<form method='post'>
<p>Color</p>
<?php foreach($colors as $color): ?>
	<input type='checkbox' name='colors[]' value='<?php echo ucfirst($color); ?>'><?php echo $color; ?></input>
<?php endforeach ?>
<p>Application</p>
<?php foreach($applications as $application): ?>
	<input type='checkbox' name='applications[]' value='<?php echo ucfirst($application); ?>'><?php echo $application; ?></input>
<?php endforeach ?>
<p>Texture</p>
<?php foreach($textures as $texture): ?>
	<input type='checkbox' name='textures[]' value='<?php echo ucfirst($texture); ?>'><?php echo $texture; ?></input>
<?php endforeach ?>
<input type='submit'/>
</form>