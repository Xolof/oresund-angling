<?php

namespace Xolof\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Question\Question;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    use \Xolof\Item\Item;

    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->itemId = $id;
        $question = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Update details of the item",
            ],
            [
                "text" => [
                    "type" => "text",
                    "value" => $question->text,
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return Question
     */
    public function getItemDetails($id) : object
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        return $question;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        // Check if the item with $id belongs to the user with $uid.
        $uid = $this->di->session->get("user_id");

        if (!$this->isUsersItem(new Question(), $this->itemId, $uid)) {
            return false;
        };

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $this->itemId);
        $question->uid  = $uid;
        $question->text = $this->form->rawValue("text");
        $question->save();

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        // $this->di->get("response")->redirect("question")->send();
        $this->di->get("response")->redirect("question/show/{$this->itemId}");
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     echo "fail";
    //     // $this->di->get("response")->redirectSelf()->send();
    // }
}
