<?php
# Script: add.subjects.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.subjects.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Subjects</title>

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
function delete_subject( p_script_url ) {
  var question;
  var answer;

  question  = "Many items are likely associated with this subject. Only delete if you're sure it's unused.\n";
  question += "Delete this Subject?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.subjects.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_subject(p_script_url, update) {
  var as_form = document.dialog;
  var as_url;

  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.id.value;

  as_url += "&sub_name="     + encode_URI(as_form.sub_name.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.subjects.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.subjects.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickSubject' ).click(function() {
    $( "#dialogSubject" ).dialog('open');
  });

  $( "#dialogSubject" ).dialog({
    autoOpen: false,
    modal: true,
    height: 180,
    width:  620,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogSubject" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_subject('add.subjects.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Subject",
        click: function() {
          attach_subject('add.subjects.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Subject",
        click: function() {
          attach_subject('add.subjects.mysql.php', 0);
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

<form name="books">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Subject Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('subject-help');">Help</a></th>
</tr>
</table>

<div id="subject-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addsubject" id="clickSubject" value="Add Subject"></td>
</tr>
</table>

</form>


<span id="subject_table"><?php print wait_Process('Loading Subjects...')?></span>

</div>


<div id="dialogSubject" title="Subjects">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Subject Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Subject <input type="text" name="sub_name" size="40"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
