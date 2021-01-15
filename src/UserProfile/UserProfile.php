<?php

namespace Xolof\UserProfile;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class UserProfile extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "UserProfile";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $uid;
    public $time;
    public $presentation;
}
