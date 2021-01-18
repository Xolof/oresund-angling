<?php

namespace Xolof\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Xolof\User\HTMLForm\UserLoginForm;
use Xolof\User\HTMLForm\CreateUserForm;
use Xolof\User\HTMLForm\UserForm;
use Xolof\User\HTMLForm\UpdateForm;
use Xolof\UserProfile\UserProfile;
use Anax\User\User;
use Michelf\Markdown;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

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
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet(): object
    {
        $page = $this->di->get("page");

        $page->add("user/user");

        return $page->render([
            "title" => "User",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction(): object
    {
        $activeUser = $this->di->session->get("user_id");

        if ($activeUser) {
            return $this->di->response->redirect("user");
        }

        $page = $this->di->get("page");
        $form = new UserLoginForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A login page",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction(): object
    {
        $activeUser = $this->di->session->get("user_id");

        if ($activeUser) {
            $this->di->session->set("error", ["You need to log out before you can create a user."]);
            return $this->di->response->redirect("user");
        }

        $page = $this->di->get("page");
        $form = new CreateUserForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A create user page",
        ]);
    }


    /**
     * This method action takes one argument:
     * GET mountpoint/argument/<value>
     *
     * @param mixed $value
     *
     * @return string
     */
    public function showActionGet($id) // : object
    {
        $page = $this->di->get("page");

        // Try to find user with $id
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $id);

        if (!is_numeric($id) || !$user->id) {
            $page->add("default/404");

            return $page->render([
                "title" => "404 - not found",
            ]);
        }

        $userProfile = new UserProfile();
        $userProfile->setDb($this->di->get("dbqb"));
        $userProfile->find("id", $id);

        $presentation = $userProfile->presentation ? $this->markdown($userProfile->presentation) : null;

        $page->add("user/show-one", [
            "data" => [
                "uid"       => $user->id,
                "acronym"       => $user->acronym,
                "presentation"  => $presentation,
                "gravatar"      => $user->gravatar
            ]
        ]);

        return $page->render([
            "title" => "User $user->acronym",
        ]);
    }


    /**
     * Format text according to Markdown syntax.
     *
     * @param string $text The text that should be formatted.
     *
     * @return string as the formatted html text.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function markdown($text)
    {
        return Markdown::defaultTransform(htmlentities($text));
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
