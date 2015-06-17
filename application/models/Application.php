<?php
	class Application extends CI_Model {

		public function __construct(){
			$this->load->database();
		}

		public function getApplications(){
			$query = $this->db->get('applications');
			$applications = array();
			foreach($query->result() as $row){
				$applications[strtolower($row->application_name)] = $row->application_id;
			}
			return $applications;
		}

		public function insertApplications($applications){
			$applicationsToBeInserted = $this->formatApplicationsForInsertion($applications);
			$uniqueApplications = $this->filterApplicationsToBeInserted($applicationsToBeInserted);
			$this->insertApplicationsWithUniqueNames($uniqueApplications);
			return $uniqueApplications;
		}

		private function formatApplicationsForInsertion($applications){
			$applicationsToBeInserted = array();
			foreach($applications as $application){
				$applicationsToBeInserted[$application] = array(
					'application_name' => $application
				);
			}
			return $applicationsToBeInserted;
		}

		private function filterApplicationsToBeInserted($applications){
			$applicationsWithDuplicateNames = $this->getApplicationsByApplicationName(array_keys($applications));
			$duplicateApplications = array();
			foreach($applicationsWithDuplicateNames as $duplicateApplication){
				$duplicateApplications[] = $duplicateApplication->application_name;
			}
			$uniqueApplications = $this->removeElementsFromArrayWithKeys($applications, $duplicateApplications);
			return $uniqueApplications;
		}

		private function getApplicationsByApplicationName($applicationNames){
			$this->db->where_in('application_name', $applicationNames);
			$query = $this->db->get('applications');
			return $query->result();
		}

		private function insertApplicationsWithUniqueNames($uniqueApplications){
			if(count($uniqueApplications) > 0){
				$this->db->insert_batch('applications', $uniqueApplications);
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