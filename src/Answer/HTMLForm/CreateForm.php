<?php

namespace Xolof\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Answer\Answer;

/**
 * Form to create an item.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $qid)
    {
        $this->qid = $qid;
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Details of the item",
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Submit",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        if (!$this->di->session->get("user_id")) {
            return false;
        };

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->qid  = $this->qid;
        $answer->uid = $this->di->session->get("user_id");
        $answer->text = $this->form->rawValue("text");
        $answer->save();

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question/show/$this->qid")->send();
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
