<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class LayoutController extends CI_Controller {

		protected $layout = 'layout/default_layout';
		protected $footer = 'layout/default_footer';
		protected $header = 'layout/default_header';
		protected $data = array('title' => 'Buildsmart');
		protected $scripts = array();

		protected function setPageLayout($layout){
			$this->layout = $layout;
			return $this;
		}

		protected function setPageHeader($header){
			$this->header = $header;
			return $this;
		}

		protected function setPageFooter($footer){
			$this->footer = $footer;
			return $this;
		}

		protected function setPageTitle($title){
			$this->data['title'] = $title;
			return $this;
		}

		protected function setMainPageContent($main_content){
			$this->data['main_content'] = $main_content;
			return $this;
		}

		protected function addPageData($key, $value){
			$this->data[$key] = $value;
			return $this;
		}

		protected function addPageScript($script){
			$this->scripts[] = $script;
			return $this;
		}

		protected function loadPage(){
			$this->data['footer'] = $this->footer;
			$this->data['header'] = $this->header;
			$this->data['scripts'] = $this->scripts;
			$this->load->view($this->layout, $this->data);
		}
	}
?>
