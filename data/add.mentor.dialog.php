<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Mentor Spirit <input type="text" name="mentor_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">All Advantages <input type="text" name="mentor_all" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Magicians Only <input type="text" name="mentor_mage" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Adepts Only <input type="text" name="mentor_adept" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Disadvantages <input type="text" name="mentor_disadvantage" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="mentor_book">
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
</select>: <input type="text" name="mentor_page" size="3"></td>
</tr>
</table>