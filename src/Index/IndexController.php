<?php

namespace Xolof\Index;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Xolof\Question\Question;
use Xolof\Question\Tag;
use Xolof\Question\TagToQuestion;
use Xolof\QuestionComment\QuestionComment;
use Xolof\Answer\Answer;
use Xolof\AnswerComment\AnswerComment;
use Anax\User\User;
use Michelf\Markdown;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * Index controller.
 */
class IndexController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var $data description
     */
    //private $data;


    //
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
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet(): object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions = $question->findAll();

        // Order by time, show newest first.
        usort($questions, array($this, "dateSort"));

        // Get the usernames.
        $questions = $this->addUserData($questions);

        $notDeletedQuestions = [];

        foreach ($questions as $question) {
            // Parse to markdown
            $question->text = $this->markdown($question->text);

            // Filter out the deleted questions
            if (!$question->deleted) {
                $notDeletedQuestions[] = $question;
            }
        }

        // Get the most active users.
        $mostActiveUsers = $this->getMostActiveUsers();

        // Get the most popular tags.
        $tags = $this->getMostPopularTags();

        $page->add("home/home", [
            "questions" => array_slice($notDeletedQuestions, 0, 3),
            "mostPopularTags" => $tags,
            "mostActiveUsers" => $mostActiveUsers
        ]);

        return $page->render([
            "title" => "Index",
        ]);
    }


    /**
     * Show the about page.
     *
     * @return object as a response object
     */
    public function aboutActionGet()//: object
    {
        $page = $this->di->get("page");

        $page->add("about/about");

        return $page->render([
            "title" => "About",
        ]);    }



    /**
    * Get the most active users.
    *
    * @return $mostActiveUsers, an array of objects with most active users.
    */
    private function getMostActiveUsers()
    {
        // För varje användare,
        // Hämta alla inlägg med den användarens id.
        // Tilldela poäng.
        // Visa de mest aktiva.
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAll();

        $usersActivity = [];

        foreach ($users as $user) {
            $score = 0;

            // Get all Questions made by the user.
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $usersQuestions = $question->findAllWhere("uid = ?", $user->id);

            $score += count($usersQuestions);

            // Get all QuestionComments made by the user.
            $questionComment = new QuestionComment();
            $questionComment->setDb($this->di->get("dbqb"));
            $usersQComments = $questionComment->findAllWhere("uid = ?", $user->id);
            $score += count($usersQComments);

            // Get all Answers made by the user.
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answers = $answer->findAllWhere("uid = ?", $user->id);
            $score += count($answers);

            // Get all AnswerComments made by the user.
            $answerComment = new AnswerComment();
            $answerComment->setDb($this->di->get("dbqb"));
            $answerComments = $answerComment->findAllWhere("uid = ?", $user->id);
            $score += count($answerComments);

            $usersActivity[] = [
                "id" => $user->id,
                "acronym" => $user->acronym,
                "score" => $score
            ];
        }

        // Order by time, show newest first.
        usort($usersActivity, array($this, "sortByUserScore"));

        return array_slice($usersActivity, 0, 5);
    }


    private function sortByUserScore($alpha, $bravo)
    {
        return $bravo["score"] - $alpha["score"];
    }


    /**
    * Get the most popular tags.
    *
    * @return $tags, an array of objects with most popular tags.
    */
    private function getMostPopularTags()
    {
        // Hämta alla taggar.
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAll();

        // Array to hold each tag and it's number of rows.
        $tagsWithNumRows = [];

        foreach ($tags as $tag) {
            $tagToQuestion = new TagToQuestion();
            $tagToQuestion->setDb($this->di->get("dbqb"));
            $tTQs = $tagToQuestion->findAllWhere("tagid = ?", $tag->id);

            $tagsWithNumRows[] = [
                "tag" => $tag->tag,
                "id" => $tag->id,
                "numRows" => count($tTQs)
            ];
        }

        usort($tagsWithNumRows, array($this, "numRowsSort"));

        return array_slice($tagsWithNumRows, 0, 10);
    }

    private function numRowsSort($alpha, $bravo)
    {
        return $bravo["numRows"] - $alpha["numRows"];
    }

    /**
     * Add the users acronym to each item.
     *
     * @return $items, an array of items.
     */
    private function addUserData($items)
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        if (gettype($items) === "object") {
            $user->findWhere("id = ?", $items->uid);
            $item = $items;
            $item->acronym = $user->acronym;
            $item->gravatar = $user->gravatar;
            return $item;
        }

        foreach ($items as $item) {
            $user->findWhere("id = ?", $item->uid);
            $item->acronym = $user->acronym;
            $item->gravatar = $user->gravatar;
        }
        return $items;
    }

    /**
     * Format text according to Markdown syntax.
     *
     * @param string $text The text that should be formatted.
     *
     * @return string as the formatted html text.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function markdown($text)
    {
        return Markdown::defaultTransform(htmlentities($text));
    }


    private function dateSort($alpha, $bravo)
    {
        return strtotime($bravo->time) - strtotime($alpha->time);
    }
}
