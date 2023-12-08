<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Brand <input type="text" name="cmd_brand" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Model <input type="text" name="cmd_model" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="cmd_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="cmd_data" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Firewall <input type="text" name="cmd_firewall" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Programs <input type="text" name="cmd_programs" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="cmd_avail" size="3"><input type="text" name="cmd_perm" size="2"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="cmd_cost" size="6"></td>
</tr>
<tr>
  <td class="ui-widget-content">Manufacturer's Code <input type="text" name="cmd_access" value="<?php print $cmd_access; ?>" size="15"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="cmd_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="cmd_page" size="3"></td>
</tr>
</table>
