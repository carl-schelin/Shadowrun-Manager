<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="con_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Archetype <input type="text" name="con_archetype" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Character <select name="con_character">
<option value="0">None</option>
<?php
  $q_string  = "select runr_id,runr_archetype,runr_name ";
  $q_string .= "from runners ";
  $q_string .= "order by runr_archetype,runr_name ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_runners = mysqli_fetch_array($q_runners)) {
    print "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_archetype'] . " (" . $a_runners['runr_name'] . ")</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="con_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="con_page" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Owner <select name="con_owner">
<option value="0">None</option>
<?php
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from users ";
  $q_string .= "order by usr_last,usr_first ";
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_users = mysqli_fetch_array($q_users)) {
    print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
</tr>
</table>
