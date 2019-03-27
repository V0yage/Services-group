<?php
	class Links_model extends CI_Model {
		public function __construct() {
			$this->load->database();
		}
		
		public function get_short_link($raw_link) {
			if (!$this->exists_link($raw_link)) {
				$this->add_link($raw_link);
			}
			
			$link_data = $this->get_by_column('present_link', $raw_link);
			if (empty($link_data)) { return null; }
			$link_data[0]->replace_link = "http://" . base_url() . "url/" . $link_data[0]->link_label;
			
			return $link_data[0];
		}
		
		public function get_link_by_label($link_label) {
			$link_data = $this->get_by_column('link_label', $link_label);
			return empty($link_data) ? null : $link_data[0];
		}
		
		private function add_link($raw_link) {
			$link_data = $this->create_short_link($raw_link);
			$this->db->insert('links', $link_data);
		}
		
		private function create_short_link($raw_link) {
			$links_num = $this->db->count_all('links');
			
			$init_num = $links_num + 5432;
			$link_label = "";
		    $dict = "abcdefghjkmnpqrstuvwxyz0123456789ABCDEFGHJKMNPQRSTUVWXYZ";
		
		    while ($init_num > 55) {
		        $key    = $init_num % 56;
		        $init_num = (int)floor($init_num / 56) - 1;
		        $link_label = $dict{$key} . $link_label;
		    }
		
		    $link_label = $dict{$init_num} . $link_label;
			
			return array("present_link" => $raw_link, "link_label" => $link_label);
		}
		
		private function get_by_column($column, $value) {
			$query = $this->db->get_where('links', array($column => $value));
			return $query->result();
		}
		
		private function exists_link($raw_link) {
			return !empty($this->get_by_column('present_link', $raw_link));
		}
		
		private function transform_label_to_link($link_label) {
			return "http://" . base_url() . "url/" . $link_label;
		}
	}
