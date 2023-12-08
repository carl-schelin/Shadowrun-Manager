<?php
# Script: add.class.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.class.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Classes</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel(1)) {
?>
function delete_class( p_script_url ) {
  var question;
  var answer;

  question  = "Many items are likely associated with this class. Only delete if you're sure it's unused.\n";
  question += "Delete this Class?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.class.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_class(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&class_subjectid=" + encode_URI(ac_form.class_subjectid.value);
  ac_url += "&class_name="      + encode_URI(ac_form.class_name.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.class.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.class.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickClass' ).click(function() {
    $( "#dialogClass" ).dialog('open');
  });

  $( "#dialogClass" ).dialog({
    autoOpen: false,
    modal: true,
    height: 180,
    width:  620,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogClass" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_class('add.class.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Class",
        click: function() {
          attach_class('add.class.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Class",
        click: function() {
          attach_class('add.class.mysql.php', 0);
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

<form name="class">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Class Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('class-help');">Help</a></th>
</tr>
</table>

<div id="class-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addclass" id="clickClass" value="Add Class"></td>
</tr>
</table>

</form>


<span id="class_table"><?php print wait_Process('Loading Classes...')?></span>

</div>

</div>


<div id="dialogClass" title="Class">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="2">Class Form</th>
</tr>
<tr>
  <td class="ui-widget-content"><select name="class_subjectid">
<?php
  $q_string  = "select sub_id,sub_name ";
  $q_string .= "from subjects ";
  $q_string .= "order by sub_name ";
  $q_subjects = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_subjects = mysqli_fetch_array($q_subjects)) {
    print "<option value=\"" . $a_subjects['sub_id'] . "\">" . $a_subjects['sub_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Class <input type="text" name="class_name" size="40"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
