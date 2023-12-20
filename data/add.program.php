<?php
# Script: add.program.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.program.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Programs</title>

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
function delete_program( p_script_url ) {
  var question;
  var answer;

  question  = "The Program may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Program?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.program.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_program(p_script_url, update) {
  var ap_form = document.dialog;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.id.value;

  ap_url += "&pgm_name="      + encode_URI(ap_form.pgm_name.value);
  ap_url += "&pgm_type="      + encode_URI(ap_form.pgm_type.value);
  ap_url += "&pgm_desc="      + encode_URI(ap_form.pgm_desc.value);
  ap_url += "&pgm_avail="     + encode_URI(ap_form.pgm_avail.value);
  ap_url += "&pgm_perm="      + encode_URI(ap_form.pgm_perm.value);
  ap_url += "&pgm_cost="      + encode_URI(ap_form.pgm_cost.value);
  ap_url += "&pgm_book="      + encode_URI(ap_form.pgm_book.value);
  ap_url += "&pgm_page="      + encode_URI(ap_form.pgm_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.program.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickProgram' ).click(function() {
    $( "#dialogProgram" ).dialog('open');
  });

  $( "#dialogProgram" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogProgram" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_program('add.program.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Program",
        click: function() {
          attach_program('add.program.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Program",
        click: function() {
          attach_program('add.program.mysql.php', 0);
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

<form name="program">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Program Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('program-help');">Help</a></th>
</tr>
</table>

<div id="program-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Program Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addpgm" id="clickProgram" value="Add Program"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#common">Common Programs</a></li>
  <li><a href="#hacking">Hacking Programs</a></li>
  <li><a href="#rigger">Rigger Programs</a></li>
  <li><a href="#righack">Rigger Hacking Programs</a></li>
</ul>

<div id="common">

<span id="common_table"><?php print wait_Process('Loading Common Programs...')?></span>

</div>


<div id="hacking">

<span id="hacking_table"><?php print wait_Process('Loading Hacking Programs...')?></span>

</div>


<div id="rigger">

<span id="rigger_table"><?php print wait_Process('Loading Rigger Programs...')?></span>

</div>


<div id="righack">

<span id="righack_table"><?php print wait_Process('Loading Rigger Hacking Programs...')?></span>

</div>


</div>

</div>


<div id="dialogProgram" title="Computer Programs">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.program.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
