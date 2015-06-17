<?php
	class Product extends CI_Model {

		public function __construct(){
			$this->load->database();
		}

		public function hasCategory($category){
			$this->db->from('products')->where('product_category', $category);
			return $this->db->count_all_results() > 0;
		}

		public function hasBrand($brand){
			$this->db->from('products')->where('product_brand', $brand);
			return $this->db->count_all_results() > 0;
		}

		public function getTextures(){
			$query = $this->db
				->distinct()
				->select('product_texture')
			    ->get('products');
			$textures = array();
			foreach($query->result() as $row){
				$textures[] = $row->product_texture;
			}
			return $textures;
		}

		public function getProducts($productSpec){
			$this->addSelectClause();
			$this->addWhereClause($productSpec);
			$this->addJoinClause();
			$query = $this->db->get('products');
			$products = $this->parseQueryResult($query->result());
			return $products;
		}

		private function addSelectClause(){
			$this->db->select("products.product_id, products.product_code, " .
				"products.product_name, CONCAT(products.product_width, 'x', products.product_height) " .
				"AS product_size, products.product_texture, products.product_design, " .
				"products.product_brand, products.product_category, colors.color_name, " .
				"applications.application_name", false);
		}

		private function addWhereClause($productSpec){
			if(isset($productSpec)){
				if(isset($productSpec->category))
					$this->db->where('product_category', $productSpec->category);
				if(isset($productSpec->brand))
					$this->db->where('product_brand', $productSpec->brand);
				if(isset($productSpec->colors))
					$this->db->where_in('colors.color_name', $productSpec->colors);
				if(isset($productSpec->applications))
					$this->db->where_in('applications.application_name', $productSpec->applications);
				if(isset($productSpec->textures))
					$this->db->where_in('products.product_texture', $productSpec->textures);
			}
		}

		private function addJoinClause(){
			$this->db->join('product_colors', 'products.product_id = product_colors.product_id', 'inner');
			$this->db->join('product_applications', 'products.product_id = product_applications.product_id', 'inner');
			$this->db->join('colors', 'product_colors.color_id = colors.color_id', 'inner');
			$this->db->join('applications', 'product_applications.application_id = applications.application_id', 'inner');
			$this->db->order_by('products.product_id', 'asc');
		}

		private function parseQueryResult($result){
			$products = array();
			foreach($result as $row){
				if(isset($products[$row->product_id])){
					$currentProduct = $products[$row->product_id];
					if(!in_array($row->color_name, $currentProduct['colors'], false)){
						$products[$row->product_id]['colors'][] = $row->color_name;
					}
					if(!in_array($row->application_name, $currentProduct['applications'], false)){
						$products[$row->product_id]['applications'][] = $row->application_name;
					}
				}
				else{
					$products[$row->product_id] = array(
						'product_id' => $row->product_id,
						'product_code' => $row->product_code,
						'product_name' => $row->product_name,
						'product_size' => $row->product_size,
						'product_texture' => $row->product_texture,
						'product_brand' => $row->product_brand,
						'product_category' => $row->product_category,
						'colors' => array($row->color_name),
						'applications' => array($row->application_name),
						'images' => array()
					);
					$dataProductDirectory = 'data/Products/' . 
						$row->product_category . '/' . 
						$row->product_brand . '/' .
						$row->product_code . '/Image';
					$productDirectory = APPPATH . $dataProductDirectory;
					$images = array();
					if(file_exists($productDirectory)){
						$filenames = get_filenames($productDirectory);
						foreach($filenames as $filename){
							if(strpos(get_mime_by_extension($filename), "image") !== false){
								$images[] = base_url("application/" . $dataProductDirectory . '/' . $filename);
							}
						}
					}
					if(count($images) > 0)
						$products[$row->product_id]['images'] = $images;
				}
			}
			return $products;
		}

		public function insertProducts($products, $colors, $applications){
			$productsToBeInserted = $this->formatProductsForInsertion($products, $colors, $applications);
			$filteredProducts = $this->filterProductsToBeInserted($productsToBeInserted);
			$productsWithUniqueCodes = $filteredProducts['unique'];
			$productsWithDuplicateCodes = $filteredProducts['duplicates'];
			$generatedProductIds = $this->insertProductsWithUniqueCodes($productsWithUniqueCodes);
			return array(
				'total_products' => count($products),
				'successful_inserts' => count($generatedProductIds), 
				'duplicates' => $productsWithDuplicateCodes,
				'ids' => $generatedProductIds
			);
		}

		private function formatProductsForInsertion($products, $colors, $applications){
			$productsToBeInserted = array();
			foreach($products as $product){
				$currentProductCode = $product['product_code'];
				$applicationsOfCurrentProduct = array();
        		foreach($product['product_applications'] as $currentProductApplication){
        			$applicationsOfCurrentProduct[] = $applications[strtolower($currentProductApplication)];
        		}
        		$colorsOfCurrentProduct = array();
        		foreach($product['product_colors'] as $currentProductColor){
        			$colorsOfCurrentProduct[] = $colors[strtolower($currentProductColor)];
        		}
				$productsToBeInserted[$currentProductCode] = array(
					'product_details' => array(
	                	'product_code' => $product['product_code'],
						'product_name' => $product['product_name'],
						'product_width' => $product['product_width'],
						'product_height' => $product['product_height'],
						'product_texture' => $product['product_texture'],
						'product_design' => $product['product_design'],
						'product_description' => $product['product_description'],
						'product_brand' => $product['product_brand'],
						'product_category' => $product['product_category']
						),
					'product_applications' => $applicationsOfCurrentProduct,
					'product_colors' => $colorsOfCurrentProduct
        		);
			}
			return $productsToBeInserted;
		}

		private function filterProductsToBeInserted($productsToBeInserted){
			$productsWithDuplicateCodes = $this->getProductsByProductCodes(array_keys($productsToBeInserted));
			$duplicateProductCodes = array();
			foreach($productsWithDuplicateCodes as $duplicateProduct){
				$duplicateProductCodes[] = $duplicateProduct->product_code;
			}
			$productsWithUniqueCodes = $this->removeElementsFromArrayWithKeys($productsToBeInserted, 
				$duplicateProductCodes);
			return array(
				'unique' => $productsWithUniqueCodes,
				'duplicates' => $productsWithDuplicateCodes
			);
		}

		private function getProductsByProductCodes($productCodes){
			$this->db->where_in('product_code', $productCodes);
			$query = $this->db->get('products');
			return $query->result();
		}

		private function removeElementsFromArrayWithKeys($array, $keys){
			foreach($keys as $key){
				unset($array[$key]);
			}
			return $array;
		}

		//returns array containing auto generated ids of products 
		//in order of insertion 
		private function insertProductsWithUniqueCodes($products){
			$productColorsToBeInserted = array();
			$productApplicationsToBeInserted = array();
			$generatedProductIds = array();
			if(count($products) > 0){
				foreach($products as $product){
					$this->db->insert('products', $product['product_details']);
					$currentProductId = $this->db->insert_id();
					foreach($product['product_colors'] as $productColor){
						$productColorsToBeInserted[] = array(
							'product_id' => $currentProductId,
							'color_id' => $productColor
						);
					}
					foreach($product['product_applications'] as $productApplication){
						$productApplicationsToBeInserted[] = array(
							'product_id' => $currentProductId,
							'application_id' => $productApplication
						);
					}
					$generatedProductIds[] = $currentProductId;
				}
				$this->db->insert_batch('product_colors', $productColorsToBeInserted);
				$this->db->insert_batch('product_applications', $productApplicationsToBeInserted);
			}
			return $generatedProductIds;
		}
	}
?>