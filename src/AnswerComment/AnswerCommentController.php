<?php

namespace Xolof\AnswerComment;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Xolof\AnswerComment\HTMLForm\CreateForm;
use Xolof\AnswerComment\HTMLForm\EditForm;
use Xolof\AnswerComment\HTMLForm\DeleteForm;
use Xolof\AnswerComment\HTMLForm\UpdateForm;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class AnswerCommentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    use \Xolof\Item\Item;

    /**
     * @var $data description
     */
    //private $data;



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }


    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction($aid): object
    {
        if (!$this->di->session->get("user_id")) {
            return $this->di->response->redirect("user/register");
        };

        $page = $this->di->get("page");
        $form = new CreateForm($this->di, $aid);
        $form->check();

        $page->add("answerComment/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Add comment",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction($id): object
    {
        // Check if the item with $id belongs to the user with $uid.
        $uid = $this->di->session->get("user_id");

        if (!$this->isUsersItem(new AnswerComment(), $id, $uid)) {
            $page = $this->di->get("page");

            $page->add("default/403");

            return $page->render([
                "title" => "403 Forbidden",
            ]);
        };

        $page = $this->di->get("page");
        $form = new DeleteForm($this->di, $id);
        $form->check();

        $page->add("answerComment/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Delete a comment",
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id): object
    {
        // Check if the item with $id belongs to the user with $uid.
        $uid = $this->di->session->get("user_id");

        if (!$this->isUsersItem(new AnswerComment(), $id, $uid)) {
            $page = $this->di->get("page");

            $page->add("default/403");

            return $page->render([
                "title" => "403 Forbidden",
            ]);
        };

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("answerComment/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update a comment",
        ]);
    }

    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return;
    }
}
