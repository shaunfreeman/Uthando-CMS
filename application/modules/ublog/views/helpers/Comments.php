<?php

/*
 * Comments.php
 *
 * Copyright (c) 2011 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of Uthando-CMS.
 *
 * Uthando-CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Uthando-CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Uthando-CMS.  If not, see <http ://www.gnu.org/licenses/>.
 */

/**
 * Description of Zend_View_Helper_Comments
 *
 * @author Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Zend_View_Helper_Comments extends Zend_View_Helper_Abstract
{
    /**
     * @var Ublog_Model_Mapper_Comments
     */
    protected $_comments;

    protected $_id;

    public function comments($blogId)
    {
        $this->_comments = new Ublog_Model_Mapper_Comments();
        $this->_id = $blogId;
        return $this;
    }

    public function getComments()
    {
        return $this->_comments->getComments($this->_id);
    }

    public function count()
    {
        return $this->_comments->numComments($this->_id);
    }
}

?>