<?php
# Script: add.ic.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.ic.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Intrusion Countermeasures</title>

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
function delete_ic( p_script_url ) {
  var question;
  var answer;

  question = "Delete this IC?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.ic.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_ic(p_script_url, update) {
  var ai_form = document.dialog;
  var ai_url;

  ai_url  = '?update='   + update;
  ai_url += "&id="       + ai_form.id.value;

  ai_url += "&ic_name="         + encode_URI(ai_form.ic_name.value);
  ai_url += "&ic_defense="      + encode_URI(ai_form.ic_defense.value);
  ai_url += "&ic_description="  + encode_URI(ai_form.ic_description.value);
  ai_url += "&ic_book="         + encode_URI(ai_form.ic_book.value);
  ai_url += "&ic_page="         + encode_URI(ai_form.ic_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ai_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.ic.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickIC' ).click(function() {
    $( "#dialogIC" ).dialog('open');
  });

  $( "#dialogIC" ).dialog({
    autoOpen: false,

    modal: true,
    height: 225,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogIC" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_ic('add.ic.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Countermeasure",
        click: function() {
          attach_ic('add.ic.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Countermeasure",
        click: function() {
          attach_ic('add.ic.mysql.php', 0);
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

<form name="ic">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Intrusion Countermeasure Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ic-help');">Help</a></th>
</tr>
</table>

<div id="ic-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickIC" value="Add Countermeasure"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Intrusion Countermeasures...')?></span>

</div>


<div id="dialogIC" title="Intrusion Countermeasure Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.ic.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
