<?php

namespace Xolof\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Question\Question;
use Xolof\QuestionComment\QuestionComment;
use Xolof\Answer\Answer;
use Xolof\AnswerComment\AnswerComment;
use Xolof\Question\TagToQuestion;

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
        $question = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Delete an item",
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "label"       => "Question to delete:",
                    "value"       => $question->text,
                    "readonly"    => true,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete question",
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
     * @return Question
     */
    public function getItemDetails($id): object
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
    public function callbackSubmit(): bool
    {
        // Check if the item with $id belongs to the user with $uid.
        $uid = $this->di->session->get("user_id");

        if (!$this->isUsersItem(new Question(), $this->itemId, $uid)) {
            return false;
        };

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $this->itemId);
        $question->deleted = date("Y-m-d H:i:s", time());
        $question->save();

        //  Soft delete question comments
        $questionComment = new QuestionComment();
        $questionComment->setDb($this->di->get("dbqb"));
        $qComments = $questionComment->findAllWhere("qid = ?", $this->itemId);
        foreach ($qComments as $qComment) {
            $qComment->setDb($this->di->get("dbqb"));
            $qComment->deleted = date("Y-m-d H:i:s", time());
            $qComment->save();
        }

        // Answers
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->findAllWhere("qid = ?", $this->itemId);
        foreach ($answers as $answer) {
            $answer->setDb($this->di->get("dbqb"));
            $answer->deleted = date("Y-m-d H:i:s", time());
            $answer->save();
        }

        // For each answer, the answer comments
        foreach ($answers as $item) {
            $answerComment = new AnswerComment();
            $answerComment->setDb($this->di->get("dbqb"));
            $aComments = $answerComment->findAllWhere("aid = ?", $item->id);
            foreach ($aComments as $aComment) {
                $aComment->setDb($this->di->get("dbqb"));
                $aComment->deleted = date("Y-m-d H:i:s", time());
                $aComment->save();
            }
        }

        $tagToQuestion = new TagToQuestion();
        $tagToQuestion->setDb($this->di->get("dbqb"));
        $tagToQuestion->deleteWhere("qid = ?", $this->itemId);

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question")->send();
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
