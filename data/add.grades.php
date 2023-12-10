<?php
# Script: add.grades.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.grades.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Bio/Cyberware Grades</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_grade( p_script_url ) {
  var question;
  var answer;

  question  = "Many items are likely associated with this bio/cyberware grade. Only delete if you're sure it's unused.\n";
  question += "Delete this ware Grade?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.grades.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_grade(p_script_url, update) {
  var ag_form = document.dialog;
  var ag_url;

  ag_url  = '?update='   + update;
  ag_url += "&id="       + ag_form.id.value;

  ag_url += "&grade_name="     + encode_URI(ag_form.grade_name.value);
  ag_url += "&grade_essence="  + encode_URI(ag_form.grade_essence.value);
  ag_url += "&grade_avail="    + encode_URI(ag_form.grade_avail.value);
  ag_url += "&grade_cost="     + encode_URI(ag_form.grade_cost.value);
  ag_url += "&grade_book="     + encode_URI(ag_form.grade_book.value);
  ag_url += "&grade_page="     + encode_URI(ag_form.grade_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ag_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.grades.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.grades.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickGrade' ).click(function() {
    $( "#dialogGrade" ).dialog('open');
  });

  $( "#dialogGrade" ).dialog({
    autoOpen: false,
    modal: true,
    height: 180,
    width:  620,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogGrade" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_grade('add.grades.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Grade",
        click: function() {
          attach_grade('add.grades.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Grade",
        click: function() {
          attach_grade('add.grades.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

$("#button-update").button("disable");

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="grades">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Bio/Cyberware Grade Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('grade-help');">Help</a></th>
</tr>
</table>

<div id="grade-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Active Skill Form</strong>
  <ul>
    <li><strong>Type</strong> - Tyep type of the active skill.</li>
    <li><strong>Name</strong> - The name of the skill.</li>
    <li><strong>Group</strong> - The group of the skill.</li>
    <li><strong>Attribute</strong> - The linked attribute for the skill.</li>
    <li><strong>Default</strong> - Whether the skill can be defaulted (-1).</li>
    <li><strong>Book</strong> - Select the book where this skill is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addgrade" id="clickGrade" value="Add Grade"></td>
</tr>
</table>

</form>


<span id="grades_table"><?php print wait_Process('Loading Grades...')?></span>

</div>


<div id="dialogGrade" title="Bio/Cyberware Grades">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="5">Bio/Cyberware Grade Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="5">Grade <input type="text" name="grade_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence Modifier <input type="text" name="grade_essence" size="5"></td>
  <td class="ui-widget-content">Availability Modifier <input type="text" name="grade_avail" size="5"></td>
  <td class="ui-widget-content">Cost Modifier <input type="text" name="grade_cost" size="5"></td>
  <td class="ui-widget-content">Book <select name="grade_book">
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
</select></td>
  <td class="ui-widget-content">Page <input type="text" name="grade_page" size="5"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
