<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim', [
			'min_length' => 'Password too short!'
		]);

		if($this->form_validation->run() == false){
			$data['title'] = 'Login Account';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login' );
			$this->load->view('templates/auth_footer');
		} else{
			$this->_login('');
		}
	}

	private function _login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('users', [
			'email' => $email
		])->row_array();

		if($user){
			if($user['is_active'] == 1){
				if(password_verify($password, $user['password'])){

					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id'],
					];
					$this->session->set_userdata($data);
					redirect('user');
				} else {
					$this->session->set_flashdata('message', '
					<div class="alert alert-danger" role="alert">
						Wrong Password!
					</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '
				<div class="alert alert-danger" role="alert">
					Email not
				</div>');
				redirect('auth');
			}
		}else{
			$this->session->set_flashdata('message', '
			<div class="alert alert-danger" role="alert">
				Email not Registered!
			</div>');
			redirect('auth');
		}
	}

	public function register()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
			'is_unique' => 'This email has already registered!'
		]);
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[3]', [
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('passwordconf', 'Passwordconf', 'required|trim|matches[password]', [
			'required' => 'Password didnt matches!',
			'matches' => 'Password didnt matches!',
		]);

		if($this->form_validation->run() == false){
			$data['title'] = 'Register Account';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/register');
			$this->load->view('templates/auth_footer');
		}
		else{
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'image' => $this->input->post('image'),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time(),
			];

			$this->db->insert('users', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
				Your account has already ben created!
			</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
				Your account has already ben logout!
			</div>');
		redirect('auth');
	}
}
