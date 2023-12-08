<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="know_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Category <select name="know_attribute">
<?php
  $q_string  = "select s_know_id,s_know_name ";
  $q_string .= "from s_knowledge ";
  $q_string .= "order by s_know_name ";
  $q_s_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_knowledge = mysqli_fetch_array($q_s_knowledge)) {
    print "<option value=\"" . $a_s_knowledge['s_know_id'] . "\">" . $a_s_knowledge['s_know_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
</table>
