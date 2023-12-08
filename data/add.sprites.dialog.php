<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="sprite_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Attack <input type="text" name="sprite_attack" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Sleaze <input type="text" name="sprite_sleaze" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="sprite_data" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Firewall <input type="text" name="sprite_firewall" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Initiative <input type="text" name="sprite_initiative" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="sprite_book">
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
</select>: <input type="text" name="sprite_page" size="3"></td>
</tr>
</table>
