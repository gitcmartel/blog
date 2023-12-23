<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\Constants;
use Application\Lib\TwigWarning;
use Application\Lib\Alert;

class AdminPostList
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region Variables

        $postRepository = new PostRepository();
        $totalPages = $postRepository->getTotalPageNumber(Constants::NUMBER_OF_POSTS_PER_PAGE, false);
        ;
        $posts = "";
        $twig = TwigLoader::getEnvironment();

        //endregion

        //region Conditions tests

        $alert = filter_input(INPUT_GET, 'alert', FILTER_SANITIZE_SPECIAL_CHARS);
        $alertType = filter_input(INPUT_GET, 'alertType', FILTER_SANITIZE_SPECIAL_CHARS);
        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);

        if (UserActiveCheckValidity::check(array('Administrateur', 'Createur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        if ($pageNumber === false || $pageNumber === null || $pageNumber === '0') {
            $pageNumber = 1;
        }

        //endregion

        //region Function execution

        $posts = $postRepository->getPosts($pageNumber, Constants::NUMBER_OF_POSTS_PER_PAGE, false);

        echo $twig->render('Admin\Post\AdminPostList.html.twig', [
            'actualPage'   => $pageNumber,
            'totalPages'   => $totalPages,
            'posts'        => $posts,
            'alert'        => ($alert !== false && $alert !== null) ? $alert : '',
            'alertMessage' => ($alertType !== false && $alertType !== null) ? Alert::getMessage($alertType) : '',
            'activeUser'   => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);
        return;

        //endregion
    }
    //endregion
}
//end execute()
