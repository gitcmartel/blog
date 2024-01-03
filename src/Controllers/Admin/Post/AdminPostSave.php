<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\File;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Constants;
use Exception;

class AdminPostSave
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region Variables

        $postRepository = new PostRepository();
        $userRepository = new UserRepository();
        $warningImage = '';
        $twig = TwigLoader::getEnvironment();

        //endregion

        //region Conditions tests

        if (UserActiveCheckValidity::check(array('Administrateur', 'Createur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $postId = filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT);
        $postTitle = filter_input(INPUT_POST, 'postTitle', FILTER_SANITIZE_SPECIAL_CHARS);
        $postSummary = filter_input(INPUT_POST, 'postSummary', FILTER_SANITIZE_SPECIAL_CHARS);
        $postContent = filter_input(INPUT_POST, 'postContent', FILTER_SANITIZE_SPECIAL_CHARS);
        $resetImage = filter_input(INPUT_POST, 'resetImage', FILTER_VALIDATE_BOOLEAN);
        $validation = filter_input(INPUT_POST, 'validation', FILTER_VALIDATE_BOOLEAN);

        if (
            $postId === false || $postId === null || $postTitle === false || $postTitle === null ||
            $postSummary === false || $postSummary === null || $postContent === false || $postContent === null ||
            $resetImage === null
        ) {
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement du post.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        $file = new File($_FILES);

        if ($file->validateFile()) {
            $warningImage = $file->ckeckFile();
        }

        $post = new Post();

        $post->hydrate([
            'id'              => (int) $postId,
            'title'           => trim($postTitle),
            'summary'         => trim($postSummary),
            'content'         => trim($postContent),
            'publicationDate' => $validation === true ? date('Y-m-d H:i:s') : '',
            'user'            => $userRepository->getUser(Session::getActiveUserId()),
            'modifier'        => $userRepository->getUser(Session::getActiveUserId())
        ]);

        $fieldsWarnings = [
            'title'   => 'Vous devez renseigner un titre',
            'summary' => 'Vous devez renseigner un résumé',
            'content' => 'Vous devez renseigner un contenu',
            'image'   => $warningImage,
        ];

        if ($post->getTitle() === "" || $post->getSummary() === "" || $post->getContent() === "") {
            echo $twig->render('Admin\Post\AdminPost.html.twig', [
                'warningTitle' => $post->getTitle() === '' ? $fieldsWarnings['title'] : '',
                'warningSummary' => $post->getSummary() === '' ? $fieldsWarnings['summary'] : '',
                'warningContent' => $post->getContent() === '' ? $fieldsWarnings['content'] : '',
                'warningImage' => $fieldsWarnings['image'],
                'activeUser' => Session::getActiveUser(),
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        $postDatabase = $postRepository->getPost($post->getId());

        //endregion

        //region Function execution

        //If there is a Post Id then we have to make an update
        if ($post->getId() !== 0) {
            //Fetching creationDate and imagePath from the database
            $post->setCreationDate($postDatabase->getCreationDate());
            $post->setImagePath($postDatabase->getImagePath());
            $post->getPublicationDate() === '' ? $post->setPublicationDate($postDatabase->getPublicationDate()) : $post->setPublicationDate($post->getPublicationDate());
            $postRepository->updatePost($post);
        } else {
            //Else we have to create a new post
            $post->setImagePath(Constants::DEFAULT_IMAGE_POST_PATH);
            $postRepository->createPost($post);
        }

        //Image management function (deletes, update, move physical tmp image etc...)
        try {
            //If we have to reset the image
            if ($resetImage === true) {
                $postRepository->resetImage($post);
            }

            //If there is a new image we update it
            if ($file->getfilePathTmpName() !== '') {
                $postRepository->updateImage($post, $file->getfilePathTmpName(), $file->getfilePathName());
            }

        } catch (Exception $exception) {
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement de l'image. \n 
                Desription de l'erreur : " . $exception->getMessage(),
                "index.php?action=Admin\Post\AdminPostList",
                "Retour à la liste des posts");
            return;
        }

        //We display the updated post list
        header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");

        //endregion
    }
    //endregion
}
//end execute()
