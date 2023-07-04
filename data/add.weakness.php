<?php
# Script: add.weakness.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.weakness.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Creature Weaknesses</title>

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
function delete_weakness( p_script_url ) {
  var question;
  var answer;

  question  = "The Weakness may be in use by existing spirits. Only delete if you're sure it's unused.\n";
  question += "Delete this Weakness?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.weakness.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_weakness(p_script_url, update) {
  var aw_form = document.dialog;
  var aw_url;

  aw_url  = '?update='   + update;
  aw_url += "&id="       + aw_form.id.value;

  aw_url += "&weak_name="         + encode_URI(aw_form.weak_name.value);
  aw_url += "&weak_description="  + encode_URI(aw_form.weak_description.value);
  aw_url += "&weak_book="         + encode_URI(aw_form.weak_book.value);
  aw_url += "&weak_page="         + encode_URI(aw_form.weak_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aw_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.weakness.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickWeakness' ).click(function() {
    $( "#dialogWeakness" ).dialog('open');
  });

  $( "#dialogWeakness" ).dialog({
    autoOpen: false,

    modal: true,
    height: 200,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogWeakness" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_weakness('add.weakness.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Weakness",
        click: function() {
          attach_weakness('add.weakness.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Weakness",
        click: function() {
          attach_weakness('add.weakness.mysql.php', 0);
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

<form name="weakness">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Creature Weakness Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('weakness-help');">Help</a></th>
</tr>
</table>

<div id="weakness-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>sprite Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickWeakness" value="Add Weakness"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Creature Weaknesses...')?></span>

</div>


<div id="dialogWeakness" title="Weaknesses">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.weakness.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
