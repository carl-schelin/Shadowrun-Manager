<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="pow_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Type <input type="text" name="pow_type" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Range <input type="text" name="pow_range" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Action <input type="text" name="pow_action" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Duration <input type="text" name="pow_duration" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="pow_description" size="70"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="pow_book">
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
</select>: <input type="text" name="pow_page" size="3"></td>
</tr>
</table>
