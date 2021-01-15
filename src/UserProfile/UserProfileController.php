<?php

namespace Xolof\UserProfile;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Xolof\UserProfile\HTMLForm\CreateForm;
use Xolof\UserProfile\HTMLForm\EditForm;
use Xolof\UserProfile\HTMLForm\DeleteForm;
use Xolof\UserProfile\HTMLForm\UpdateForm;
use Anax\User\User;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserProfileController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    use \Xolof\Item\Item;

    /**
     * @var $data description
     */
    //private $data;


    //
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
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        // Check if the item with $id belongs to the user with $uid.
        $uid = $this->di->session->get("user_id");

        if (!$this->isUsersItem(new UserProfile(), $id, $uid)) {
            $page = $this->di->get("page");

            $page->add("default/403");

            return $page->render([
                "title" => "403 Forbidden",
            ]);
        };

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("user-profile/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update an item",
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
