<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Model_login extends CI_Model {

	public function __construct() {
		$this->load->library('email');
		parent::__construct();
	}

	public function login($data)
	{
		$condition = "email =" . "'" . $data['email'] . "' AND " . "password =" . "'" . md5($data['password']) . "'";
		$this->db->select('*');
		$this->db->from('Usuario');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
		return true;
		} else {
		return false;
		}
	}

	public function registration_insert($data) 
	{
		// Query to check whether username already exist or not
		$condition = "email =" . "'" . $data['email'] . "'";
		$this->db->select('*');
		$this->db->from('Usuario');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {

		// Query to insert data in database
		$this->db->insert('Usuario', $data);
		if ($this->db->affected_rows() > 0) {
		return true;
		}
		} else {
		return false;
		}
	}

	public function forgot($data) // La parte interesante de comentar el codigo es que el siguiente que haga modificaciones le entienda
	{
		$email = $data['email'];
		$condition = "email =" . "'" . $email . "'";
		$this->db->select('*');
		$this->db->from('Usuario');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result();
		if ($query->num_rows() > 0) 
		{

		$selector = bin2hex(random_bytes(8));
		$token = random_bytes(32);

		$url = sprintf('%sreset_password?%s', base_url().'login/', http_build_query(['selector' => $selector, 'validator' => bin2hex($token)]));

		// Token expiration
		$expires = new DateTime('NOW');
		$expires->add(new DateInterval('PT10M')); // 10 Minutos = PT10M, 10 Segundos = PT10S, 1 Hora = PT01H

		// Delete any existing tokens for this user
		$this->db->delete('password_reset', array('email' => $email));

		// Insert reset token into database
		$insert = $this->db->insert('password_reset', 
		    array(
		        'email'     =>  $email,
		        'selector'  =>  $selector, 
		        'token'     =>  hash('sha256', $token),
		        'expires'   =>  $expires->format('U'),
		    )
		);
 		$this->email->set_header('MIME-Version', '1.0; charset=utf-8');
		$this->email->set_header('Content-type', 'text/html');
       //SMTP & mail configuration
		$config = array(
		   'smtp_crypto' => 'tls',
		   'smtp_timeout'=>'30',
		   'protocol' => 'smtp',
		   'mailpath' => 'C:\xampp\sendmail',
		   'smtp_host' => 'smtp-mail.outlook.com',
		   //'smtp_port' => '587', // Outlook
		   'smtp_port' => '465', // Google
		   'smtp_user' => 'sirlalito.hl@gmail.com',
		   'smtp_pass' => 'hvnkpwcqkndkdugf',
		   'mailtype' => 'html',
		   'wordwrap' => FALSE,
		   'charset' => 'utf-8'
		);
		$this->email->set_newline("\r\n");
		$this->email->initialize($config);
		$this->email->clear();
		$this->email->set_crlf( "\r\n" );

		//Email content
		$this->email->from('funsoftware@outlook.com', 'Admin');
		$this->email->to($email);
		$this->email->subject('SALAB Password reset request');//En el mensaje que llega a tu correo tiene un problema no te lo envia con simbolos = tiene UF-8
		$mail_message = '
		<center>
			<h1>SALAB</h1>
			<h4>Reset your Password</h4>
			<p>Hola '.$result[0]->nombre_usr.',</p>
			<p>Has solicitado un reinicio de contraseña.</p>
			<a href="'.base_url().'login/reset_password?selector='.$selector.'&validator='.bin2hex($token).'">Clic Aqui</a>
			'.sprintf('<p><a href="%s">Clic Aqui</a></p>', $url, $url).'
			<footer>Sistema de Administracion del Laboratorio de Computo</footer>
		</center>
		';
		$this->email->message($mail_message);

		//Send email
		$link = sprintf('<center><h1><a href="%s">Clic Aqui</a></h1></center>', $url, $url);
		// $this->email->send(); // Esto lo envia directo a correo no tiene un validador si falla la conexion
		$debug = $this->email->print_debugger(); 
		// print_r($debug);
		// echo $this->email->print_debugger();
		// echo $validator;
		// echo $selector;
		// print_r($_POST);
		// var_dump($_POST);
		//echo $mail_message;
		//exit; 

		if($this->email->send()){
		   //Success email Sent
		   echo $this->email->print_debugger();
		   echo '<script>alert("Email sent successfully")</script>';
		   redirect('login','refresh');
		}else{
		   //Email Failed To Send
		   echo $this->email->print_debugger();
		}
		return true;
		} else {
		return false;
		}
	}
	public function valid_token($data)
	{
		$condition = "selector = "."'".$data['selector']."' AND "."expires >="."'".time(). "'";
		$this->db->select('*');
		$this->db->from('password_reset');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
			if ($query->num_rows() > 0) 
			{
				$result = $query->result();
				$auth_token = $result[0];
				$calc = hash('sha256', hex2bin($data['validator']));

			if ( hash_equals( $calc, $auth_token->token ) )  
			{
				$this->db->where('email', $auth_token->email);
				$this->db->update('usuario', array(
					'password' => md5($data['password'])
				));
			}
		return true;
		} else {
			return false;
		}
	}

	// Read data from database to show data in admin page
	public function read_user_information($data) 
	{
		$condition = "email =" . "'" . $data['email'] . "'";
		$this->db->select('*');
		$this->db->from('Usuario');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) 
		{
		return $query->result();
		} else {
		return false;
		}
	}

}    
?>