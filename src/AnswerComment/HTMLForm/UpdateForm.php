<?php

namespace Xolof\AnswerComment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\AnswerComment\AnswerComment;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $answerComment = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update details of the item",
            ],
            [
                "id" => [
                    "type" => "number",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $answerComment->id,
                ],

                "aid" => [
                    "type" => "number",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $answerComment->aid,
                ],

                "uid" => [
                    "type" => "number",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $answerComment->uid,
                ],

                "text" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $answerComment->text,
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
     * @return AnswerComment
     */
    public function getItemDetails($id) : object
    {
        $answerComment = new AnswerComment();
        $answerComment->setDb($this->di->get("dbqb"));
        $answerComment->find("id", $id);
        return $answerComment;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $answerComment = new AnswerComment();
        $answerComment->setDb($this->di->get("dbqb"));
        $answerComment->find("id", $this->form->value("id"));
        $answerComment->text = $this->form->value("text");
        $answerComment->save();
        return true;
    }



    // /**
    //  * Callback what to do if the form was successfully submitted, this
    //  * happen when the submit callback method returns true. This method
    //  * can/should be implemented by the subclass for a different behaviour.
    //  */
    // public function callbackSuccess()
    // {
    //     $this->di->get("response")->redirect("answer-comment")->send();
    //     //$this->di->get("response")->redirect("answer-comment/update/{$answerComment->id}");
    // }



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
