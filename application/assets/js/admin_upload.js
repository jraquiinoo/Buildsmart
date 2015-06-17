$(document).ready(function(){
	$("#product_file").change(function(e){
		var file = e.target.files[0];
		var products = [];
		var product_codes = [];
		var duplicateProducts = [];
		var colors = [];
		var applications = [];
		Papa.parse(file, {
			worker: true,
			header: true,
			skipEmptyLines: true,
			step: function(row){

				var product = row.data[0];
				var product_size = product.product_size.split('x');
				var product_width = product_size[0];
				var product_height = product_size[1];
				var currentProduct = {
					product_code: product.product_code,
					product_name: product.product_name,
					product_width: product_width,
					product_height: product_height,
					product_texture: product.product_texture,
					product_design: product.product_design,
					product_applications: product.product_application.split('/'),
					product_colors: product.product_color.split('/'),
					product_description: "hello",
					product_brand: product.product_brand,
					product_category: product.product_category
				}
				currentProduct.product_colors.forEach(function(element, index, array){
					if(colors.indexOf(element.toLowerCase()) == -1)
						colors.push(element.toLowerCase());
				});
				currentProduct.product_applications.forEach(function(element, index, array){
					if(applications.indexOf(element.toLowerCase()) == -1)
						applications.push(element.toLowerCase());
				});
				if(product_codes.indexOf(product.product_code) == -1){
					products.push(currentProduct);
					product_codes.push(product.product_code);
				} else duplicateProducts.push(currentProduct);
			},
			complete: function(){
				if(products.length > 0){
					if(duplicateProducts.length == 0)
						submitProducts(products, colors, applications);
					else alert('duplicate products ' + duplicateProducts.length);
				}
			}
		});
	});
});

function submitProducts(products, colors, applications){
	$.ajax({
		type: 'POST',
		dataType: 'json',
		data: {products: products, colors: colors, applications: applications},
		success: function(data){
			console.log(data);
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(textStatus + " " + errorThrown);
		}
	});
}