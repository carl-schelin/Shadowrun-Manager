<?php
# Script: add.cyberdeck.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.cyberdeck.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['sort'] = '';
  if (isset($_GET['sort'])) {
    $formVars['sort'] = clean($_GET['sort'], 50);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage cyberdecks</title>

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
function delete_cyberdeck( p_script_url ) {
  var question;
  var answer;

  question  = "The Cyberdeck may be in use by existing characters. Only delete if you're sure it's unused.\n\n";
  question += "Delete this Cyberdeck?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.cyberdeck.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_cyberdeck(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&deck_brand="     + encode_URI(ac_form.deck_brand.value);
  ac_url += "&deck_model="     + encode_URI(ac_form.deck_model.value);
  ac_url += "&deck_rating="    + encode_URI(ac_form.deck_rating.value);
  ac_url += "&deck_attack="    + encode_URI(ac_form.deck_attack.value);
  ac_url += "&deck_sleaze="    + encode_URI(ac_form.deck_sleaze.value);
  ac_url += "&deck_data="      + encode_URI(ac_form.deck_data.value);
  ac_url += "&deck_firewall="  + encode_URI(ac_form.deck_firewall.value);
  ac_url += "&deck_persona="   + encode_URI(ac_form.deck_persona.value);
  ac_url += "&deck_hardening=" + encode_URI(ac_form.deck_hardening.value);
  ac_url += "&deck_memory="    + encode_URI(ac_form.deck_memory.value);
  ac_url += "&deck_storage="   + encode_URI(ac_form.deck_storage.value);
  ac_url += "&deck_load="      + encode_URI(ac_form.deck_load.value);
  ac_url += "&deck_io="        + encode_URI(ac_form.deck_io.value);
  ac_url += "&deck_response="  + encode_URI(ac_form.deck_response.value);
  ac_url += "&deck_programs="  + encode_URI(ac_form.deck_programs.value);
  ac_url += "&deck_access="    + encode_URI(ac_form.deck_access.value);
  ac_url += "&deck_avail="     + encode_URI(ac_form.deck_avail.value);
  ac_url += "&deck_perm="      + encode_URI(ac_form.deck_perm.value);
  ac_url += "&deck_basetime="  + encode_URI(ac_form.deck_basetime.value);
  ac_url += "&deck_duration="  + encode_URI(ac_form.deck_duration.value);
  ac_url += "&deck_index="     + encode_URI(ac_form.deck_index.value);
  ac_url += "&deck_cost="      + encode_URI(ac_form.deck_cost.value);
  ac_url += "&deck_book="      + encode_URI(ac_form.deck_book.value);
  ac_url += "&deck_page="      + encode_URI(ac_form.deck_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.cyberdeck.mysql.php?update=-1&sort=<?php print $formVars['sort']; ?>');
}

$(document).ready( function() {
  $( '#clickCyberdeck' ).click(function() {
    $( "#dialogCyberdeck" ).dialog('open');
  });

  $( "#dialogCyberdeck" ).dialog({
    autoOpen: false,
    modal: true,
    height: 550,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogCyberdeck" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_cyberdeck('add.cyberdeck.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Cyberdeck",
        click: function() {
          attach_cyberdeck('add.cyberdeck.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Cyberdeck",
        click: function() {
          attach_cyberdeck('add.cyberdeck.mysql.php', 0);
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

<form name="cyberdeck">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Cyberdeck Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberdeck-help');">Help</a></th>
</tr>
</table>

<div id="cyberdeck-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Cyberdeck Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickCyberdeck" value="Add New Cyberdeck"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Cyberdeck...')?></span>

</div>


<div id="dialogCyberdeck" title="Cyberdeck Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.cyberdeck.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
