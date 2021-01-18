<?php

/**
* Check if this item belongs to the current user.
*/

namespace Xolof\Item;

trait Item
{
    private function isUsersItem($item, $itemId, $userId)
    {
        $item->setDb($this->di->get("dbqb"));
        $usersItem = $item->find("id", $itemId);

        if ($usersItem->uid != $userId) {
            return false;
        };

        return true;
    }
}
