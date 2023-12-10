<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="action_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Marks Required <input type="text" name="action_marks" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Legal <input type="radio" value="0" checked="true" name="action_type"> Illegal <input type="radio" value="1" name="action_type"></td>
</tr>
<tr>
  <td class="ui-widget-content">Simple/Minor Action <input type="radio" value="0" checked="true" name="action_level"> Complex/Major Action <input type="radio" value="1" name="action_level"></td>
</tr>
<tr>
  <td class="ui-widget-content">Attack <input type="text" name="action_attack" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Defense <input type="text" name="action_defense" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Outsider <input type="checkbox" name="action_outsider"> User <input type="checkbox" name="action_user"> Admin <input type="checkbox" name="action_admin"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="action_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="action_page" size="3"></td>
</tr>
</table>
