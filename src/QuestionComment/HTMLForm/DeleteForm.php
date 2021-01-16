<?php

namespace Xolof\QuestionComment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\QuestionComment\QuestionComment;

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
        $this->itemId = $id;
        parent::__construct($di);
        $this->questionComment = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Delete an item",
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "label"       => "Item to delete:",
                    "value"       => $this->questionComment->text,
                    "readonly"    => true
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete comment",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }


    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return QuestionComment
     */
    public function getItemDetails($id) : object
    {
        $questionComment = new QuestionComment();
        $questionComment->setDb($this->di->get("dbqb"));
        $questionComment->find("id", $id);
        return $questionComment;
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

        if (!$this->isUsersItem(new QuestionComment(), $this->itemId, $uid)) {
            return false;
        };

        $questionComment = new QuestionComment();
        $questionComment->setDb($this->di->get("dbqb"));
        $questionComment->find("id", $this->itemId);
        $questionComment->deleted = date("Y-m-d H:i:s", time());
        $questionComment->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question/show/{$this->questionComment->qid}")->send();
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
