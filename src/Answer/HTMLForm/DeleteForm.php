<?php

namespace Xolof\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Answer\Answer;

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
        $this->answer = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Delete an item",
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "label"       => "Answer to delete:",
                    "value"       => $this->answer->text,
                    "readonly"    => true,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete answer",
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
     * @return Answer
     */
    public function getItemDetails($id) : object
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);
        return $answer;
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

        if (!$this->isUsersItem(new Answer(), $this->itemId, $uid)) {
            return false;
        };

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $this->itemId);
        $answer->delete();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $qid = $this->answer->qid;
        $this->di->get("response")->redirect("question/show/{$qid}");
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
