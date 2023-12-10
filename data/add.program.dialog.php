<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Type <select name="pgm_type">
<option value="0">Common</option>
<option value="1">Hacking</option>
<option value="2">Rigger</option>
<option value="3">Rigger Hacking</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="pgm_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="pgm_desc" size="70"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="pgm_avail" size="10"><td class="ui-widget-content">Perm <input type="text" name="pgm_perm" size="4"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="pgm_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="pgm_book">
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
</select>: <input type="text" name="pgm_page" size="10"></td>
</tr>
</table>
