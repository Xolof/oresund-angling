<?php

namespace Xolof\AnswerComment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\AnswerComment\AnswerComment;

/**
 * Form to delete an item.
 */
class DeleteForm extends FormModel
{
    use \Xolof\Item\Item;

    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->itemId = $id;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete an item",
            ],
            [
                "id" => [
                    "type"        => "number",
                    "label"       => "Item to delete:",
                    "value"     => $id,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete item",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Get all items as array suitable for display in select option dropdown.
     *
     * @return array with key value of all items.
     */
    protected function getAllItems() : array
    {
        $answerComment = new AnswerComment();
        $answerComment->setDb($this->di->get("dbqb"));

        $answerComments = ["-1" => "Select an item..."];
        foreach ($answerComment->findAll() as $obj) {
            $answerComments[$obj->id] = "{$obj->id} ({$obj->id})";
        }

        return $answerComments;
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

        if (!$this->isUsersItem(new AnswerComment(), $this->itemId, $uid)) {
            return false;
        };

        $answerComment = new AnswerComment();
        $answerComment->setDb($this->di->get("dbqb"));
        $answerComment->find("id", $this->form->value("id"));
        $answerComment->delete();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("answer-comment")->send();
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}
