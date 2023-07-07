<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="adp_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="adp_desc" size="70"></td>
</tr>
<tr>
  <td class="ui-widget-content">Level <input type="text" name="adp_level" size="5"> (0 for Limited By Magic)</td>
</tr>
<tr>
  <td class="ui-widget-content">Power Points <input type="text" name="adp_power" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="adp_book">
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
</select>: <input type="text" name="adp_page" size="5"></td>
</tr>
</table>
