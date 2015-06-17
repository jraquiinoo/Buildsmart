<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'LayoutController.php';
class Admin extends LayoutController {

	public function __construct(){
		parent::__construct();
		$this->load->model('product');
		$this->load->model('color');
		$this->load->model('application');
	}

	public function index()
	{
		$this->setPageTitle('Admin - Buildsmart')
			 ->setMainPageContent('admin/index')
			 ->addPageScript('jquery.min.js')
			 ->addPageScript('papaparse.min.js')
			 ->addPageScript('admin_upload.js')
			 ->loadPage();
	}

	public function insert_products(){
		$products = $_POST['products'];
		$applications = $_POST['applications'];
		$colors = $_POST['colors'];
		$this->application->insertApplications($applications);
		$this->color->insertColors($colors);
		$applications = $this->application->getApplications();
		$colors = $this->color->getColors();
		$insertionResult = $this->product->insertProducts($products, $colors, $applications);
		header('Content-Type: application/json');
		print json_encode($insertionResult);
	}
}
?>