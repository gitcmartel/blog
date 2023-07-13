<?php

require_once('src/controllers/home.php');
require_once('src/controllers/post.php');
require_once('src/controllers/connexion.php');
require_once('src/controllers/subscription.php');
require_once('src/controllers/user.php');
require_once('src/controllers/comment.php');

use Application\Controllers\Home\Home;
use Application\Controllers\Post\Post;
use Application\Controllers\Post\Connexion;
use Application\Controllers\Post\Subscription;
use Application\Controllers\Post\User;
use Application\Controllers\Post\Comment;

try {
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        if ($_GET['action'] === 'post') {
            if(isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new Post())->execute($identifier);
            } else {
                (new Post())->execute();
            }
        }elseif ($_GET['action'] === 'connexion') {
            (new Connexion())->execute();
        }elseif ($_GET['action'] === 'subscription') {
            (new Subscription())->execute();
        }elseif ($_GET['action'] === 'user') {
            if(isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new User())->execute($identifier);
            } else {
                (new User())->execute();
            }
        }elseif ($_GET['action'] === 'comment') {
            if(isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new Comment())->execute($identifier);
            } else {
                (new Comment())->execute();
            }
        } else {
            throw new Exception("La page que vous demandez n'existe pas.");
        }
    } else {
        (new Home())->execute();
    }
} catch (Execption $exception) {
    $erreorMessage = $exception->getMessage();

    require('templates/error.php');
}
