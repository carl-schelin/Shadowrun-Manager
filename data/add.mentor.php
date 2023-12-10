<?php
# Script: add.mentor.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.mentor.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Mentor Spirits</title>

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
function delete_mentor( p_script_url ) {
  var question;
  var answer;

  question  = "The Mentor Spirit may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Mentor Spirit?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.mentor.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_mentor(p_script_url, update) {
  var am_form = document.dialog;
  var am_url;

  am_url  = '?update='   + update;
  am_url += "&id="       + am_form.id.value;

  am_url += "&mentor_name="         + encode_URI(am_form.mentor_name.value);
  am_url += "&mentor_all="          + encode_URI(am_form.mentor_all.value);
  am_url += "&mentor_mage="         + encode_URI(am_form.mentor_mage.value);
  am_url += "&mentor_adept="        + encode_URI(am_form.mentor_adept.value);
  am_url += "&mentor_disadvantage=" + encode_URI(am_form.mentor_disadvantage.value);
  am_url += "&mentor_book="         + encode_URI(am_form.mentor_book.value);
  am_url += "&mentor_page="         + encode_URI(am_form.mentor_page.value);

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.mentor.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickMentor' ).click(function() {
    $( "#dialogMentor" ).dialog('open');
  });

  $( "#dialogMentor" ).dialog({
    autoOpen: false,

    modal: true,
    height: 275,
    width:  700,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogMentor" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_mentor('add.mentor.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Mentor Spirit",
        click: function() {
          attach_mentor('add.mentor.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Mentor Spirit",
        click: function() {
          attach_mentor('add.mentor.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="mentor">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Mentor Spirit Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('mentor-help');">Help</a></th>
</tr>
</table>

<div id="mentor-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Language Form</strong>
  <ul>
    <li><strong>Name</strong> - The name of the Metatype.</li>
    <li><strong>Walk</strong> - The Metatype walking speed.</li>
    <li><strong>Run</strong> - The Metatype run speed.</li>
    <li><strong>Swim</strong> - The Metatype swim speed.</li>
    <li><strong>Book</strong> - Select the book where this table is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickMentor" value="Add Mentor Spirit"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Mentor Spirits...')?></span>

</div>


<div id="dialogMentor" title="Mentor Spirits">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.mentor.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
