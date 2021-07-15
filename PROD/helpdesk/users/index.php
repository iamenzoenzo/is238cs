<?php
/*
PLEMA Digital 5
An Open Source PHP User Management System
by the PLEMA Digital Team at http://PLEMA.digital

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
require_once '../users/init.php';
// require_once 'init.php';
if(isset($user) && $user->isLoggedIn()){
  Redirect::to($us_url_root.'users/account.php');
}else{
  Redirect::to($us_url_root.'users/login.php');
}
die();
?>