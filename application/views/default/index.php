<?php $this->load->view($product_filter_form); ?>
<table>
	<tr>
		<th>product code</th>
		<th>product name</th>
		<th>product size</th>
		<th>product texture</th>
		<th>product brand</th>
		<th>product category</th>
		<th>color</th>
		<th>application</th>
		<th>image</th>
	</tr>
	<?php foreach ($products as $product): ?>
	<tr>
		<td><?php echo $product['product_code']?></td>
		<td><?php echo $product['product_name']?></td>
		<td><?php echo $product['product_size']?></td>
		<td><?php echo $product['product_texture']?></td>
		<td><?php echo $product['product_brand']?></td>
		<td><?php echo $product['product_category']?></td>
		<td>
			<?php foreach($product['colors'] as $color){
				echo $color;
				if($color !== end($product['colors']))
					echo ', ';
			}?>
		</td>
		<td>
			<?php foreach($product['applications'] as $application){
				echo $application;
				if($application !== end($product['applications']))
					echo ', ';
			}?>
		</td>
		<td>
		<?php foreach($product['images'] as $image): ?>
			<img style='height: 30px; width: 30px;' src='<?php echo $image; ?>'/>
		<?php endforeach ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>