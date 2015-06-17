<?php
	class Color extends CI_Model {

		public function __construct(){
			$this->load->database();
		}

		public function getColors(){
			$query = $this->db->get('colors');
			$colors = array();
			foreach($query->result() as $row){
				$colors[strtolower($row->color_name)] = $row->color_id;
			}
			return $colors;
		}

		public function insertColors($colors){
			$colorsToBeInserted = $this->formatColorsForInsertion($colors);
			$uniqueColors = $this->filterColorsToBeInserted($colorsToBeInserted);
			$this->insertColorsWithUniqueNames($uniqueColors);
			return $uniqueColors;
		}

		private function formatColorsForInsertion($colors){
			$colorsToBeInserted = array();
			foreach($colors as $color){
				$colorsToBeInserted[$color] = array(
					'color_name' => $color
				);
			}
			return $colorsToBeInserted;
		}

		private function filterColorsToBeInserted($colors){
			$colorsWithDuplicateNames = $this->getColorsByColorName(array_keys($colors));
			$duplicateColors = array();
			foreach($colorsWithDuplicateNames as $duplicateColor){
				$duplicateColors[] = $duplicateColor->color_name;
			}
			$uniqueColors = $this->removeElementsFromArrayWithKeys($colors, $duplicateColors);
			return $uniqueColors;
		}

		private function getColorsByColorName($colorNames){
			$this->db->where_in('color_name', $colorNames);
			$query = $this->db->get('colors');
			return $query->result();
		}

		private function insertColorsWithUniqueNames($uniqueColors){
			if(count($uniqueColors) > 0){
				$this->db->insert_batch('colors', $uniqueColors);
			}
		}

		private function removeElementsFromArrayWithKeys($array, $keys){
			foreach($keys as $key){
				unset($array[$key]);
			}
			return $array;
		}
	}
?>