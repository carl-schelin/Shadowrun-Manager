<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Brand <input type="text" name="link_brand" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Model <input type="text" name="link_model" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="link_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Response <input type="text" name="link_response" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Signal <input type="text" name="link_signal" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="link_data" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Firewall <input type="text" name="link_firewall" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="link_cost" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="link_avail" size="5"><input type="text" name="link_perm" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Manufacturer's Code <input type="text" name="link_access" size="15"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="link_book">
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
</select>: <input type="text" name="link_page" size="3"></td>
</tr>
</table>
