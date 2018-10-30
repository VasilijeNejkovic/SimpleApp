<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Post.php';

  //Logging
      require '../../vendor/autoload.php';
      use Monolog\Logger;
      use Monolog\Handler\StreamHandler;

      $log = new Logger('Vasilije');
      $log->pushHandler(new StreamHandler('../../log.txt', Logger::INFO));



  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $post = new Post($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $post->title = $data->title;
  $post->body = $data->body;
  $post->author = $data->author;
  $post->category_id = $data->category_id;

  // Create post
  if($post->create()) {
    echo json_encode(
      array('message' => 'Post Created')
    );
    $log->info('Post created');
  } else {
    echo json_encode(
      array('message' => 'Post Not Created')
    );
    $log->info('Posts not created');
  }
