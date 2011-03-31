<?php
/* 
 * Edit.php
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
 * Description of Edit
 *
 * @author Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Core_Form_User_Edit extends Core_Form_User_Base
{
    public function init()
    {
        parent::init();
        
        $this->getElement('password')->setRequired(false);
        $this->getElement('passwordVerify')->setRequired(false);
        $this->removeElement('roleId');
        $this->removeElement('captcha');
        $this->removeElement('csrf');
        $this->removeDisplayGroup('SiteCaptcha');

        $this->addSubmit(_('Save'));
    }
}
?>
