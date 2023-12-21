<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostModification
{
    #region Function
    /**
     * Controller main function
     */
    public function execute(): void
    {
        #region Variables

        $postRepository = new PostRepository();
        $post = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

        if (UserActiveCheckValidity::check(array('Administrateur', 'Createur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $post = $postRepository->getPost($postId);

        //Check if the postId exists in the database
        if ($post->getId() === null) {
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage du post.",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        #endregion

        #region Function execution

        echo $twig->render('Admin\Post\AdminPost.html.twig', [
            'post'         => $post,
            'activeUser'   => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}
//end execute()
