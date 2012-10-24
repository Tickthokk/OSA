<?php

class User extends OSA_Controller
{
		
	public function main()
	{
		if ( ! $this->user->is_logged)
			redirect('/user/login');
		else
			redirect('/user/' . $this->user->username);
	}

	public function view($username)
	{
		$this->_data['username'] = $this->user->username;

		$this->_data['success'] = $this->session->flashdata('success');

		# Page Load
		$this->_load_wrapper('user/view');
	}

	public function login()
	{
		# Models, Helpers
		$this->load->helper(array('form'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');

		if ($this->input->post())
		{
			# Attempt to Log in!
			if ( ! $this->user->login($this->input->post('username'), $this->input->post('password')))
				$this->_data['error'] = 'Login Failed.  Wrong username or password.';
		}

		if ( ! $this->session->userdata('redirect_after_login'))
			$this->session->set_userdata('redirect_after_login', $this->input->server('HTTP_REFERER'));

		if ($this->form_validation->run() == FALSE || ! $this->user->is_logged)
		{
			# Page Data
			$this->set_title('Login');
			#$js = array('login');
			#$this->set_more_data(compact(
			#	'js'
			#));

			$this->_data['username'] = $this->input->post('username');

			# Page Load
			$this->_load_wrapper('user/login');
		}
		else
		{
			$this->session->set_flashdata('success', 'You have successfully logged in!');
			
			// Redirect to the session variable or root
			$redirect_after_login = $this->session->userdata('redirect_after_login');
			$this->session->unset_userdata('redirect_after_login');

			redirect($redirect_after_login ?: '/');
		}
	}

	public function logout()
	{
		if ($this->user->is_logged)
		{
			$this->user->logout();
			$this->session->set_flashdata('success', 'You have successfully logged out.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Wut?');
		}
		redirect('/user/login');
	}

	public function register()
	{
		# Models, Helpers
		$this->load->helper(array('form'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|alpha|required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');

		if ($this->form_validation->run() == FALSE)
		{
			# Page Data
			$this->set_title('Register');
			#$js = array('register');
			#$this->set_more_data(compact(
			#	'js'
			#));

			$this->_data['email'] = $this->input->post('email');
			$this->_data['username'] = $this->input->post('username');

			# Page Load
			$this->_load_wrapper('user/register');
		}
		else
		{
			# Register them
			$this->user->register($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

			# Success message
			$this->session->set_flashdata('success', 'You have successfully created an account!');

			redirect('/');
		}
	}

	public function forgotten_password()
	{
		# Models, Helpers
		$this->load->helper(array('form'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email_or_username', 'Email or Username', 'trim|xss_clean|required');

		if ($this->form_validation->run() == FALSE)
		{
			# Page Data
			$this->set_title('Forgot Your Password?');
			#$js = array('forgot');
			#$this->set_more_data(compact(
			#	'js'
			#));

			$this->_data['email_or_username'] = $this->input->post('email_or_username');

			# Page Load
			$this->_load_wrapper('user/forgot');
		}
		else
		{
			# Register them
			if ($this->user->auto_reset_password($this->input->post('email_or_username')) == TRUE)
			{
				# Warning message
				$this->session->set_flashdata('warning', 'Your new password has been emailed to you.');
			}
			else
			{
				# Error message - Something went wrong...
				# TODO: Fix this message, it's cryptic
				$this->session->set_flashdata('error', 'Sorry, we could not reset your password...');
			}

			redirect('/user/login');
		}
	}

}