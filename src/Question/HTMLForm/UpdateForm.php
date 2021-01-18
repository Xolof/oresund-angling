<?php

namespace Xolof\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Xolof\Question\Question;
use Xolof\Question\Tag;
use Xolof\Question\TagToQuestion;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    use \Xolof\Item\Item;

    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->itemId = $id;
        $question = $this->getItemDetails($id);
        $tags = $this->getTags($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                // "legend" => "Update details of the item",
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "value" => $question->text,
                    "validation" => ["not_empty"],
                ],

                "tags" => [
                    "type" => "text",
                    "value" => $tags,
                    "placeholder" => "Tags separated by comma"
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
     * Get the tags for the item.
     *
     * @param integer $id get tags for item with id.
     *
     * @return string
     */
    public function getTags($id): string
    {
        $tagToQuestion = new TagToQuestion();
        $tagToQuestion->setDb($this->di->get("dbqb"));
        $tagRows = $tagToQuestion->findAllWhere("qid = ?", $id);

        $tags = "";

        foreach ($tagRows as $row) {
            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));
            $tag->find("id", $row->tagid);
            $tags .= $tag->tag . ", ";
        }

        return rtrim($tags, ", ");
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

        // Save the question.
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $this->itemId);
        $question->uid  = $uid;
        $question->text = $this->form->rawValue("text");
        $question->updated = date("Y-m-d H:i:s", time());
        $question->save();

        // Get the tags as comma separated values from the form.
        $rawNewTags = explode(",", $this->form->rawValue("tags"));
        $newTags = [];

        foreach ($rawNewTags as $tagStr) {
            if ($tagStr === "") {
                continue;
            }
            $trimmed = trim($tagStr);
            $newTags[] = preg_replace("/[^A-Za-zÅÄÖØÆåäöøæ0-9]/", '', $trimmed);
        }

        $this->addTags($newTags);

        $this->removeOldTags($newTags);

        return true;
    }


    /**
    * Add the new tags
    */
    private function addTags($newTags)
    {
        foreach ($newTags as $tagStr) {
            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));

            // If the tag doesn't exist, save it.
            if (!$tag->find("tag", $tagStr)->id) {
                $tag = new Tag();
                $tag->setDb($this->di->get("dbqb"));
                $tag->tag = $tagStr;
                $tag->save();
            }

            $tagId = $tag->find("tag", $tagStr)->id;
            $tagToQuestion = new TagToQuestion();
            $tagToQuestion->setDb($this->di->get("dbqb"));

            // If there's not a row for this tag and this question,
            // insert a row in  the table TagToQuestion.
            if (!$tagToQuestion->findWhere("tagid = ? AND qid = ?", [$tagId, $this->itemId])->id) {
                $newTTQ = new TagToQuestion();
                $newTTQ->setDb($this->di->get("dbqb"));
                $newTTQ->tagid = $tag->id;
                $newTTQ->qid = $this->itemId;
                $newTTQ->save();
            }
        }
    }


    /**
    * Remove tags if they are not in the form field.
    *
    */
    private function removeOldTags($newTags)
    {
        // Get the old tags.
        $tagToQuestion = new TagToQuestion();
        $tagToQuestion->setDb($this->di->get("dbqb"));
        $tagToQuestions = $tagToQuestion->findAllWhere("qid = ?", $this->itemId);

        $oldTagArr = [];
        foreach ($tagToQuestions as $tTQ) {
            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));
            $oldTagArr[] = $tag->find("id", $tTQ->tagid)->tag;
        }

        foreach ($oldTagArr as $oldTag) {
            // Check if any tag rows should be removed.
            if (!in_array($oldTag, $newTags)) {
                $tag = new Tag();
                $tag->setDb($this->di->get("dbqb"));
                $tag->find("tag", $oldTag);

                $tagToQuestion = new TagToQuestion();
                $tagToQuestion->setDb($this->di->get("dbqb"));
                $tagToQuestion->find("tagid", $tag->id);
                $tagToQuestion->delete();
            }
        }
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        // $this->di->get("response")->redirect("question")->send();
        $this->di->get("response")->redirect("question/show/{$this->itemId}");
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     echo "fail";
    //     // $this->di->get("response")->redirectSelf()->send();
    // }
}
