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
        $mostActiveUsers = $this->getMostActiveUsers(5);

        // Get the most popular tags.
        $tags = $this->getMostPopularTags(10);

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
    public function aboutActionGet(): object
    {
        $page = $this->di->get("page");

        $page->add("about/about");

        return $page->render([
            "title" => "About",
        ]);
    }


    /**
     * Show all tags.
     *
     * @return object as a response object
     */
    public function tagsActionGet(): object
    {
        $page = $this->di->get("page");

        $tags = $this->getMostPopularTags();

        $page->add("tag/tags", [
            "tags" => $tags
        ]);

        return $page->render([
            "title" => "Tags",
        ]);
    }


    /**
     * Show all users.
     *
     * @return object as a response object
     */
    public function usersActionGet(): object
    {
        $page = $this->di->get("page");

        $users = $this->getMostActiveUsers();

        $page->add("user/users", [
            "users" => $users
        ]);

        return $page->render([
            "title" => "Users",
        ]);
    }


    /**
    * Get the most active users.
    *
    * @return $mostActiveUsers, an array of objects with most active users.
    */
    private function getMostActiveUsers($limit = null)
    {
        // For every user,
        // Get all posts with that user's id.
        // Assign score.
        // Return the most active users.
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
                "gravatar" => $user->gravatar,
                "registered" => $user->time,
                "score" => $score
            ];
        }

        // Order by time, show newest first.
        usort($usersActivity, array($this, "sortByUserScore"));

        if ($limit) {
            return array_slice($usersActivity, 0, $limit);
        }

        return $usersActivity;
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
    private function getMostPopularTags($limit = null)
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

        if ($limit) {
            return array_slice($tagsWithNumRows, 0, $limit);
        }

        return $tagsWithNumRows;
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
