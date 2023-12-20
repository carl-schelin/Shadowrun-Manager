<?php
# Script: add.metamagics.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.metamagics.php";

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
<title>Manage Metamagics</title>

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
function delete_metamagics( p_script_url ) {
  var question;
  var answer;

  question  = "The Metamagic may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Metamagic?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.metamagics.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_metamagics(p_script_url, update) {
  var am_form = document.dialog;
  var am_url;

  am_url  = '?update='   + update;
  am_url += "&id="       + am_form.id.value;

  am_url += "&meta_name="          + encode_URI(am_form.meta_name.value);
  am_url += "&meta_description="   + encode_URI(am_form.meta_description.value);
  am_url += "&meta_book="          + encode_URI(am_form.meta_book.value);
  am_url += "&meta_page="          + encode_URI(am_form.meta_page.value);

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.metamagics.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickMetamagics' ).click(function() {
    $( "#dialogMetamagics" ).dialog('open');
  });

  $( "#dialogMetamagics" ).dialog({
    autoOpen: false,
    modal: true,
    height: 200,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogMetamagics" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_metamagics('add.metamagics.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Metamagic",
        click: function() {
          attach_metamagics('add.metamagics.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Metamagics",
        click: function() {
          attach_metamagics('add.metamagics.mysql.php', 0);
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

<form name="metamagics">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Metamagics Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('metamagics-help');">Help</a></th>
</tr>
</table>

<div id="metamagics-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Metamagics Listing</strong>
  <ul>
    <li><strong>Name</strong> - The name of the spell.</li>
    <li><strong>Class</strong> - Spell class.</li>
    <li><strong>Type</strong> - Mana or Physical Spell.</li>
    <li><strong>Test</strong> - Test type; Opposed, which is resisted by attribute listed. S(OR) must beat the Object Resistance threshold.</li>
    <li><strong>Range</strong> - Select the book where this table is located.</li>
    <li><strong>Damage</strong> - Either Physical or Stun.</li>
    <li><strong>Duration</strong> - Spell duration. Instant, Sustained, or Permanent.</li>
    <li><strong>Drain</strong> - Drain Value.</li>
    <li><strong>Book/Page</strong> - Book and page number where you can find the spell. Core book (sr5) or Street Grimoire (sg).</li>
  </ul></li>
</ul>

<p>The Use Force and Use Force / 2 checkboxes are when the Drain Value is based on the strength of the cast spell in force. Some are half force, 
some are full force, and some are a hard value.</p>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addmetamagics" id="clickMetamagics" value="Add Metamagics"></td>
</tr>
</table>

</form>

<span id="metamagics_table"><?php print wait_Process('Loading Metamagics...')?></span>

</div>


<div id="dialogMetamagics" title="Metamagics List">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.metamagics.dialog.php');
?>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
