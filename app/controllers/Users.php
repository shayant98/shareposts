<?php

    class Users extends Controller
    {
        public function __construct()
        {
            $this->userModel = $this->model('User');
        }

        public function index()
        {
            if (!isLoggedIn()) {
                redirect('users/login');
            } else {
                redirect('posts');
            }
        }


        public function register()
        {
            //check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // process the form

                //Sanitize Post Data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //init Data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    //error variables
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => '',
                ];

                //validate Email
                if (empty($data['email'])) {
                    $data['email_error'] = 'Please enter email';
                } else {
                    //check email
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_error'] = 'email is already taken';
                    }
                }
                //validate Name
                if (empty($data['name'])) {
                    $data['name_error'] = 'Please enter name';
                }

                //validate Password
                if (empty($data['password'])) {
                    $data['password_error'] = 'Please enter password';
                } elseif (strlen($data['password']) < 6) {
                    $data['password_error'] = 'Password should be atleast 6 char';
                }

                //validate Confirm Password
                if (empty($data['confirm_password'])) {
                    $data['confirm_password_error'] = 'Please enter password one more time';
                } else {
                    if ($data['password'] != $data['confirm_password']) {
                        $data['confirm_password_error'] = 'Passwords do not match';
                    }
                }

                //make sure error are empty

                if (empty($data['email_error']) && empty($data['name_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])) {
                    //validated
                    
                    //hash Password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    //Register User
                    if ($this->userModel->register($data)) {
                        flash('register_success', 'You are registered and can now Login');
                        redirect('users/login');
                    } else {
                        die('sth went wrong');
                    }
                } else {
                    //load View with error
                    $this->view('/users/register', $data);
                }
            } else {
                //init Data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    //error variables
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => '',
                ];

                //load View
                $this->view('/users/register', $data);
            };
        }

        public function login()
        {
            //check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // process the form

                //Sanitize Post Data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //init Data
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    //error variables
                    'email_error' => '',
                    'password_error' => '',
                ];

                //validate Email
                if (empty($data['email'])) {
                    $data['email_error'] = 'Please enter email';
                }
                //validate Name
                if (empty($data['name'])) {
                    $data['name_error'] = 'Please enter name';
                }

                //validate Password
                if (empty($data['password'])) {
                    $data['password_error'] = 'Please enter password';
                } elseif (strlen($data['password']) < 6) {
                    $data['password_error'] = 'Password should be atleast 6 char';
                }

                //check if user exists in DB
                if ($this->userModel->findUserByEmail($data['email'])) {
                    //User exists
                } else {
                    //false
                    $data['email_error'] = 'No User Found';
                }

                if (empty($data['email_error']) && empty($data['password_error'])) {
                    //validated
                    //Check and set Logged in user
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                    if ($loggedInUser) {
                        //create session

                        $this->createUserSession($loggedInUser);
                    } else {
                        //password error
                        $data['password_error'] = 'Password Incorrect';

                        //load view with error
                        $this->view('/users/login', $data);
                    }
                } else {
                    //load View with error
                    $this->view('/users/login', $data);
                }
            } else {
                //init Data
                $data = [
                    'email' => '',
                    'password' => '',
                    //error variables
                    'email_error' => '',
                    'password_error' => '',
                ];

                //load View
                $this->view('/users/login', $data);
            };
        }

        public function createUserSession($user)
        {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;

            redirect('posts');
        }


        public function logout()
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
        }
    }
