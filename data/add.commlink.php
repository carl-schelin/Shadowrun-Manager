<?php
# Script: add.commlink.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.commlink.php";

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
<title>Manage Commlinks</title>

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
function delete_commlink( p_script_url ) {
  var question;
  var answer;

  question  = "The Commlink may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Commlink?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.commlink.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_commlink(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&link_brand="     + encode_URI(ac_form.link_brand.value);
  ac_url += "&link_model="     + encode_URI(ac_form.link_model.value);
  ac_url += "&link_rating="    + encode_URI(ac_form.link_rating.value);
  ac_url += "&link_response="  + encode_URI(ac_form.link_response.value);
  ac_url += "&link_signal="    + encode_URI(ac_form.link_signal.value);
  ac_url += "&link_data="      + encode_URI(ac_form.link_data.value);
  ac_url += "&link_firewall="  + encode_URI(ac_form.link_firewall.value);
  ac_url += "&link_avail="     + encode_URI(ac_form.link_avail.value);
  ac_url += "&link_perm="      + encode_URI(ac_form.link_perm.value);
  ac_url += "&link_access="    + encode_URI(ac_form.link_access.value);
  ac_url += "&link_cost="      + encode_URI(ac_form.link_cost.value);
  ac_url += "&link_book="      + encode_URI(ac_form.link_book.value);
  ac_url += "&link_page="      + encode_URI(ac_form.link_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.commlink.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.commlink.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickCommlink' ).click(function() {
    $( "#dialogCommlink" ).dialog('open');
  });

  $( "#dialogCommlink" ).dialog({
    autoOpen: false,

    modal: true,
    height: 400,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogCommlink" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_commlink('add.commlink.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Commlink",
        click: function() {
          attach_commlink('add.commlink.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Commlink",
        click: function() {
          attach_commlink('add.commlink.mysql.php', 0);
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

<form name="commlink">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Commlink Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('commlink-help');">Help</a></th>
</tr>
</table>

<div id="commlink-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Commlink Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addlink" id="clickCommlink" value="Add Commlink"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Commlinks...')?></span>

</div>


<div id="dialogCommlink" title="Commlink Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.commlink.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
