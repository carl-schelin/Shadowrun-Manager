<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content" colspan="2">Name <input type="text" name="jack_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="jack_rating" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="jack_data" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Firewall <input type="text" name="jack_firewall" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">VR Matrix Init Dice Bonus <input type="text" name="jack_matrix" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence <input type="text" name="jack_essence" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Avail <input type="text" name="jack_avail" size="3"><input type="text" name="jack_perm" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="jack_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="jack_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="jack_page" size="3"></td>
</tr>
</table>