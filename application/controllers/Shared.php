<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require 'LayoutController.php';

	class Shared extends LayoutController {

		public function __construct(){
			parent::__construct();
			$this->load->model('product');
			$this->load->model('color');
			$this->load->model('application');
			$this->load->model('productspec');
		}

		public function index()
		{
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$this->applyProductFilter();
			}
			$products = $this->product->getProducts($this->productspec);
			$colors = array_keys($this->color->getColors());
			$applications = array_keys($this->application->getApplications());
			$textures = $this->product->getTextures();
			$this->setPageTitle('Products - Buildsmart')
				 ->setMainPageContent('default/index')
				 ->addPageData('colors', $colors)
				 ->addPageData('product_filter_form', 'layout/product_filter_form')
				 ->addPageData('products', $products)
				 ->addPageData('applications', $applications)
				 ->addPageData('textures', $textures)
				 ->loadPage();
		}

		public function product_by_category($category){
			$category = urldecode($category);
			$this->productspec
				 ->category($category);
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$this->applyProductFilter();
			}
			if(isset($category) && $this->product->hasCategory($category)){
				$products = $this->product->getProducts($this->productspec);
				$colors = array_keys($this->color->getColors());
				$applications = array_keys($this->application->getApplications());
				$textures = $this->product->getTextures();
				$this->setPageTitle(ucfirst($category) . ' - Buildsmart')
					 ->setMainPageContent('default/index')
					 ->addPageData('colors', $colors)
					 ->addPageData('product_filter_form', 'layout/product_filter_form')
					 ->addPageData('products', $products)
					 ->addPageData('applications', $applications)
					 ->addPageData('textures', $textures)
					 ->loadPage();
			}
		}

		public function product_by_brand($brand){
			$brand = urldecode($brand);
			$this->productspec
				 ->brand($brand);
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$this->applyProductFilter();
			}
			if(isset($brand) && $this->product->hasBrand($brand)){
				$products = $this->product->getProducts($this->productspec);
				$colors = array_keys($this->color->getColors());
				$applications = array_keys($this->application->getApplications());
				$textures = $this->product->getTextures();
				$this->setPageTitle(ucfirst($brand) . ' - Buildsmart')
					 ->setMainPageContent('default/index')
					 ->addPageData('colors', $colors)
					 ->addPageData('product_filter_form', 'layout/product_filter_form')
					 ->addPageData('products', $products)
					 ->addPageData('applications', $applications)
					 ->addPageData('textures', $textures)
					 ->loadPage();
			}
		}

		private function applyProductFilter(){
			if(array_key_exists('colors', $_POST))
				$this->productspec->colors($_POST['colors']);
			if(array_key_exists('applications', $_POST))
				$this->productspec->applications($_POST['applications']);
			if(array_key_exists('textures', $_POST))
				$this->productspec->textures($_POST['textures']);
		}
	}
?>
