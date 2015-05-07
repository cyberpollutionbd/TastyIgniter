<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Login extends Main_Controller {

	public function index() {
		$this->load->library('customer');
				$this->load->model('Pages_model');
		$this->lang->load('login_register');

		if ($this->customer->islogged()) { 														// checks if customer is logged in then redirect to account page.
  			redirect('account');
		}

		$this->template->setBreadcrumb('<i class="fa fa-home"></i>', '/');
		$this->template->setBreadcrumb($this->lang->line('text_heading'), 'login');

		// START of retrieving lines from language file to pass to view.
		$this->template->setTitle($this->lang->line('text_heading'));
		//$this->template->setHeading($this->lang->line('text_heading'));
		$data['text_heading'] 			= $this->lang->line('text_heading');
		$data['text_login'] 			= $this->lang->line('text_login');
		$data['text_register'] 			= $this->lang->line('text_register');
		$data['text_forgot'] 			= $this->lang->line('text_forgot');
		$data['entry_email'] 			= $this->lang->line('entry_email');
		$data['entry_password'] 		= $this->lang->line('entry_password');
		$data['button_login'] 			= $this->lang->line('button_login');
		$data['button_register'] 		= $this->lang->line('button_register');
		$data['text_login_register'] 	= $this->lang->line('text_login_register');
		// END of retrieving lines from language file to send to view.

		$data['reset_url'] 				= site_url('password_reset');
		$data['register_url'] 			= site_url('register');

		if ($this->input->post()) {																// checks if $_POST data is set
			if ($this->validateForm() === TRUE) {
				$email = $this->input->post('email');											// retrieves email value from $_POST data if set
				$password = $this->input->post('password');										// retrieves password value from $_POST data if set

				if ($this->customer->login($email, $password) === FALSE) {						// invoke login method in customer library with email and password $_POST data value then check if login was unsuccessful
					$this->alert->set('alert', $this->lang->line('alert_invalid_login'));	// display error message and redirect to account login page
  					redirect('login');
    			} else {																		// else if login was successful redirect to account page
 					redirect('account');
  				}
    		}
		}

		$this->template->setPartials(array('header', 'footer'));
		$this->template->render('login', $data);
	}

	public function validateForm() {
		// START of form validation rules
		$this->form_validation->set_rules('email', 'Email Address', 'xss_clean|trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'xss_clean|trim|required|min_length[6]|max_length[32]');
		// END of form validation rules

		if ($this->form_validation->run() === TRUE) {										// checks if form validation routines ran successfully
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file login.php */
/* Location: ./main/controllers//login.php */