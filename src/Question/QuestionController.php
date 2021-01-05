<?php

namespace Xolof\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Xolof\Question\HTMLForm\CreateForm;
use Xolof\Question\HTMLForm\EditForm;
use Xolof\Question\HTMLForm\DeleteForm;
use Xolof\Question\HTMLForm\UpdateForm;
use Xolof\QuestionComment\QuestionComment;
use Xolof\Answer\Answer;
use Xolof\AnswerComment\AnswerComment;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
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
     * This sample method action takes one argument:
     * GET mountpoint/argument/<value>
     *
     * @param mixed $value
     *
     * @return string
     */
    public function showActionGet($id) // : object
    {
        $page = $this->di->get("page");

        if (!is_numeric($id)) {
            $page->add("default/404");

            return $page->render([
                "title" => "404 - not found",
            ]);
        }
        // Try to find questions with $id
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $q = $question->find("id", $id);

        // Try to find comments for the question
        $questionComment = new QuestionComment();
        $questionComment->setDb($this->di->get("dbqb"));
        $qComments = $questionComment->findAllWhere("qid = ?", $question->id);

        // Try to find answers
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->findAllWhere("qid = ?", $question->id);

        $aComments = [];
        // Try to find comments for answers
        foreach ($answers as $item) {
            $answerComment = new AnswerComment();
            $answerComment->setDb($this->di->get("dbqb"));
            $aComments["answer $item->id"] = $answerComment->findAllWhere("aid = ?", $item->id);
        }


        $qCommentForm = new \Xolof\QuestionComment\HTMLForm\CreateForm($this->di);
        $qCommentForm->check();

        if (!$q->id) {
            $page->add("default/404");

            return $page->render([
                "title" => "404 - not found",
            ]);
        }
        $page->add("question/crud/view-one", [
            "data" => [
                "question" => $q,
                "qComments" => $qComments,
                "answers" => $answers,
                "aComments" => $aComments,
                "qCommentForm" => $qCommentForm->getHTML(),
            ]
        ]);

        return $page->render([
            "title" => "Question $q->id",
        ]);
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $page->add("question/crud/view-all", [
            "items" => $question->findAll(),
        ]);

        return $page->render([
            "title" => "A collection of items",
        ]);
    }


    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateForm($this->di);
        $form->check();

        $page->add("question/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a item",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $page = $this->di->get("page");
        $form = new DeleteForm($this->di);
        $form->check();

        $page->add("question/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Delete an item",
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("question/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update an item",
        ]);
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
