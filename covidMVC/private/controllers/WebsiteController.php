<?php

namespace Website\Controllers;

/**
 * Class HomeController
 *
 * Deze handelt de logica van de homepage af
 * Haalt gegevens uit de "model" laag van de website (de gegevens)
 * Geeft de gegevens aan de "view" laag (HTML template) om weer te geven
 *
 */
class WebsiteController
{



	public function registerHandle()
	{
		$errors = [];

		$email		= filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		$password	=trim($_POST['password']);

		if ( $email === false ) {
			$errors['email'] = 'There is no email';
		}

		if (strlen($password) < 6 ){
			$errors['password'] = 'no valid password (min 6 characters)';
		}	

		if (count($errors) === 0){
			$connection = dbConnect();
			$sql = "SELECT * FROM `users` WHERE `email` = :email";
			$statement = $connection->prepare($sql);
			$statement->execute( ['email' => $email]);
		
		if ($statement->rowCount() === 0){
			$sql = "INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)";
			$statement = $connection->prepare($sql);
			$safe_password = password_hash($password, PASSWORD_DEFAULT);
			$params	= [
				'email' => $email,
				'password' => $safe_password
			];
			$statement->execute($params);
			$doneUrl = url('login');
			redirect($doneUrl);
		}
	}
		else{
			$errors['email'] = 'this account already exists';
		}

		$template_engine = get_template_engine();
		echo $template_engine->render('registration', ['errors' => $errors]);
	}

	public function loginHandle()
	{
		$result = validateRegistrationData($_POST);


		$template_engine = get_template_engine();
		echo $template_engine->render('login', ['errors' => $result['errors']]);
	}

	public function login()
	{
		$template_engine = get_template_engine();
		echo $template_engine->render('login');
	}

	public function register()
	{
		$template_engine = get_template_engine();
		echo $template_engine->render('registration');
	}
	
	public function geinlogdepagina()
	{
		$template_engine = get_template_engine();
		echo $template_engine->render('geinlogdepagina');
	}
}
// $result = validateRegistrationData( $_POST);
// if( userNotRegistered( $result['data']['email'])) {
// 	$result['errors']['email'] = 'Deze gebruiker is niet bekend';
// } else{
// 	$user = getUserByEmail( $result['data']['email']);
// }
// if(password_verify($result['data']['password'], $user['password'])) 