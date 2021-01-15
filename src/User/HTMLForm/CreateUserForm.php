<?php

namespace Xolof\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\User\User;
use Xolof\UserProfile\UserProfile;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Create user",
            ],
            [
                "acronym" => [
                    "type"        => "text",
                    "validation"  => ["not_empty"],
                    "validation" => [
                        "custom_test" => [
                            "message" => "That username already exists.",
                            "test" => function ($acronym) {
                                // Check that username is not taken.
                                $user = new User();
                                $user->setDb($this->di->get("dbqb"));
                                $user->find("acronym", $acronym);
                                return $user->acronym != $acronym;
                            }
                        ]
                    ],
                ],

                "password" => [
                    "type"        => "password",
                    "validation"  => ["not_empty"]
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password",
                        "not_empty" => true
                    ],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create user",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
     public function callbackSubmit()
     {
        // Get values from the submitted form
        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password !== $passwordAgain ) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // Create a new user.
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->acronym = $acronym;
        $user->setPassword($password);
        $user->gravatar = $this->getGravatar($acronym);
        $user->save();

        // Create the user's profile.
        $userProfile = new UserProfile();
        $userProfile->setDb($this->di->get("dbqb"));
        $userProfile->uid = $user->id;
        $userProfile->presentation = null;
        $userProfile->save();

        $this->form->addOutput("User was created.");
        return true;
     }

     /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $size Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $imgSet Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    private function getGravatar($email, $size = 80, $imgSet = 'monsterid', $rating = 'g', $img = true, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$imgSet&r=$rating";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
