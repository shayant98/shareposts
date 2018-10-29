<?php


     class Posts extends Controller
     {
         public function __construct()
         {
             if (!isLoggedIn()) {
                 redirect('users/login');
             }

             $this->postModel = $this->model('Post');
             $this->userModel = $this->model('User');
         }
         public function index()
         {

             //get Posts
             $posts = $this->postModel->getPosts();
             $data = [
                 'posts' => $posts
             ];
             $this->view('posts/index', $data);
         }


         public function add()
         {
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                 $data = [
                    'title' => trim($_POST['title']),
                    'body' => trim($_POST['body']),
                    'user_id' => $_SESSION['user_id'],
                    //error variable
                    'title_error' => '',
                    'body_error' => ''
                ];
                 //validate title
                 if (empty($data['title'])) {
                     $data['title_error'] = 'please insert a title';
                 }
                 //validate body
                 if (empty($data['body'])) {
                     $data['body_error'] = 'please insert a body';
                 }


                 //make sure no errors
                 if (empty($data['title_error']) && empty($data['body_error'])) {
                     //validated
                     if ($this->postModel->addPost($data)) {
                         flash('post_message', 'Post Added');
                         redirect('posts');
                     } else {
                         die('sth went wrong');
                     }
                 } else {
                     //load view with error
                     $this->view('posts/add', $data);
                 }
             } else {
                 $data = [
                    'title' => '',
                    'body' => '',
                    //error variable
                    'title_error' => '',
                    'body_error' => ''
                ];
    
                 $this->view('posts/add', $data);
             }
         }

         public function edit($id)
         {
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                 $data = [
                     'id' => $id,
                     'title' => trim($_POST['title']),
                     'body' => trim($_POST['body']),
                     'user_id' => $_SESSION['user_id'],
                     //error variable
                     'title_error' => '',
                     'body_error' => ''
                ];
                 //validate title
                 if (empty($data['title'])) {
                     $data['title_error'] = 'please insert a title';
                 }
                 //validate body
                 if (empty($data['body'])) {
                     $data['body_error'] = 'please insert a body';
                 }


                 //make sure no errors
                 if (empty($data['title_error']) && empty($data['body_error'])) {
                     //validated
                     if ($this->postModel->updatePost($data)) {
                         flash('post_message', 'Post Updated');
                         redirect('posts');
                     } else {
                         die('sth went wrong');
                     }
                 } else {
                     //load view with error
                     $this->view('posts/edit', $data);
                 }
             } else {

                //get existing post from
                 $post = $this->postModel->getPostById($id);

                 //check for owner
                 if ($post->user_id != $_SESSION['user_id']) {
                     redirect('posts');
                 }
                
                 $data = [
                    'id' => $id,
                    'title' => $post->title,
                    'body' => $post->body,
                    //error variable
                    'title_error' => '',
                    'body_error' => ''
                ];
    
                 $this->view('posts/edit', $data);
             }
         }

         public function show($id)
         {
             $post = $this->postModel->getPostById($id);
             $user = $this->userModel->getUserById($post->user_id);
             $data = [
                 'post' => $post,
                 'user' => $user
             ];
             $this->view('posts/show', $data);
         }

         public function delete($id)
         {
             //get existing post from
             $post = $this->postModel->getPostById($id);

             //check for owner
             if ($post->user_id != $_SESSION['user_id']) {
                 redirect('posts');
             }
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 if ($this->postModel->deletePost($id)) {
                     flash('post_message', 'Post Deleted');
                     redirect('posts');
                 } else {
                     die('STH went wrong');
                 }
             } else {
                 redirect('posts');
             }
         }
     }
