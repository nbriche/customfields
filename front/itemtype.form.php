<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2009 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Sponsor: Oregon Dept. of Administrative Services, State Data Center
// Original Author of file: Ryan Foster
// Contact: Matt Hoover <dev@opensourcegov.net>
// Project Website: http://www.opensourcegov.net
// Purpose of file: Perform update, activate, and delete actions
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT.'/inc/includes.php');

if (!isset($_GET['id'])) {
   $_GET['id'] = '';
}
if (!isset($_GET['withtemplate'])) {
   $_GET['withtemplate'] = '';
}

if (isset($_POST['delete'])) {
   $PluginItem = new PluginCustomfieldsItemtype($_REQUEST['itemtype']);
   $PluginItem->check($_POST['id'],'w');
   $PluginItem->delete($_POST);
   Html::redirect($_SERVER['HTTP_REFERER']);

} elseif (isset($_POST['update'])) {
   $PluginItem = new PluginCustomfieldsItemtype($_REQUEST['itemtype']);
   $PluginItem->update($_POST);
   Html::redirect($_SERVER['HTTP_REFERER']);

} elseif (isset($_GET['add']) && isset($_REQUEST['itemtype']) && isset($_GET['id'])) {
   $PluginItem = new PluginCustomfieldsItemtype($_REQUEST['itemtype']);
   $PluginItem->getRestricted($_REQUEST['itemtype']);
   $newID = $PluginItem->add($_GET, false);
   Html::redirect($_SERVER['HTTP_REFERER']);
}
?>
