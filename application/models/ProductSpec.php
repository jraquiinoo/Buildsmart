<?php
	class ProductSpec extends CI_Model{
		public $brand;
		public $category;
		public $textures;
		public $width;
		public $height;
		public $code;
		public $name;
		public $design;
		public $colors;
		public $applications;

		public function brand($brand){
			$this->brand = $brand;
			return $this;
		}

		public function category($category){
			$this->category = $category;
			return $this;
		}

		public function textures($textures){
			$this->textures = $textures;
			return $this;
		}

		public function width($width){
			$this->width = $width;
			return $this;
		}

		public function height($height){
			$this->height = $height;
			return $this;
		}

		public function code($code){
			$this->code = $code;
			return $this;
		}

		public function name($name){
			$this->name = $name;
			return $this;
		}

		public function design($design){
			$this->design = $design;
			return $this;
		}

		public function colors($colors){
			$this->colors = $colors;
			return $this;
		}

		public function applications($applications){
			$this->applications = $applications;
			return $this;
		}
	} 
?>