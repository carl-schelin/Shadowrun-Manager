<?php
# Script: add.foci.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.foci.php";

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
<title>Manage Power Focuses</title>

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
function delete_foci( p_script_url ) {
  var question;
  var answer;

  question  = "The Foci may be in use. Only delete if you're sure it's unused.\n";
  question += "Delete this Foci?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.foci.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_foci(p_script_url, update) {
  var af_form = document.dialog;
  var af_url;

  af_url  = '?update='   + update;
  af_url += "&id="       + af_form.id.value;

  af_url += "&foci_name="         + encode_URI(af_form.foci_name.value);
  af_url += "&foci_karma="        + encode_URI(af_form.foci_karma.value);
  af_url += "&foci_avail="        + encode_URI(af_form.foci_avail.value);
  af_url += "&foci_perm="         + encode_URI(af_form.foci_perm.value);
  af_url += "&foci_cost="         + encode_URI(af_form.foci_cost.value);
  af_url += "&foci_book="         + encode_URI(af_form.foci_book.value);
  af_url += "&foci_page="         + encode_URI(af_form.foci_page.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.foci.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickFoci' ).click(function() {
    $( "#dialogFoci" ).dialog('open');
  });

  $( "#dialogFoci" ).dialog({
    autoOpen: false,

    modal: true,
    height: 250,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogFoci" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_foci('add.foci.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Foci",
        click: function() {
          attach_foci('add.foci.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Foci",
        click: function() {
          attach_foci('add.foci.mysql.php', 0);
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

<form name="foci">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Foci Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('foci-help');">Help</a></th>
</tr>
</table>

<div id="foci-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickFoci" value="Add Foci"></td>
</tr>
</table>

</form>

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Foci Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('foci-listing-help');">Help</a></th>
</tr>
</table>

<div id="foci-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>


<span id="mysql_table"><?php print wait_Process('Loading Foci...')?></span>

</div>


<div id="dialogFoci" title="Foci">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.foci.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
