<?php
	class Pages extends CI_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('links_model');
		}
		
		public function view($param = null) {
			if (!empty($param)) {
				show_404();
			}
			
			$this->load->view('templates/header');
			$this->load->view('pages/home');
			$this->load->view('templates/footer');
		}
		
		public function transform() {
			$raw_link = $_POST['raw_link'];
			$data['received_link'] = $this->links_model->get_short_link($raw_link);
			
			if (empty($data['received_link'])) {
				show_404();
			}
		
			$this->load->view('templates/header', $data);
			$this->load->view('pages/short', $data);
			$this->load->view('templates/footer', $data);
		}
		
		public function redirect($short_link_label) {
			$redirect_link = $this->links_model->get_link_by_label($short_link_label);
			if (empty($redirect_link)) {
				show_404();
			} else {
				header('Location: ' . $redirect_link->present_link);
			}
		}
	}