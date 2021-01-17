<?php

namespace Xolof\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Question\Question;
use Xolof\Question\Tag;
use Xolof\Question\TagToQuestion;

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
    public function __construct(ContainerInterface $di)
    {
        $this->id = null;
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

                "tags" => [
                    "type" => "text",
                    "placeholder" => "Tags separated by comma"
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
        $uid = $this->di->session->get("user_id");

        if (!$uid) {
            return false;
        };

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->uid  = $uid;
        $question->text = $this->form->rawValue("text");
        $question->save();

        $this->id = $question->id;

        // Get the tags as comma separated values from the form.
        $tags = explode(",", $this->form->rawValue("tags"));

        foreach ($tags as $tagStr) {
            if ($tagStr === "") {
                continue;
            }
            $tagStr = preg_replace("/[^A-Za-zÅÄÖØÆåäöøæ0-9]/", '', trim($tagStr));

            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));

            if (!$tag->find("tag", $tagStr)->id) {
                // Save the tag.
                $tag = new Tag();
                $tag->setDb($this->di->get("dbqb"));
                $tag->tag = $tagStr;
                $tag->save();
            }

            // Write to help-table TagToQuestion.
            $tagToQuestion = new TagToQuestion();
            $tagToQuestion->setDb($this->di->get("dbqb"));
            $tagToQuestion->tagid = $tag->id;
            $tagToQuestion->qid = $this->id;
            $tagToQuestion->save();
        }

        return true;
    }




    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question/show/{$this->id}");
    }
}
