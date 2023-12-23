<?php
# Script: add.firearm.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.firearm.php";

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
<title>Manage Weapons</title>

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
function delete_firearm( p_script_url ) {
  var question;
  var answer;

  question  = "The Firearm may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Firearm?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.firearm.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_firearm(p_script_url, update) {
  var af_form = document.dialog;
  var af_url;

  af_url  = '?update='   + update;
  af_url += "&id="       + af_form.id.value;

  af_url += "&fa_class="        + encode_URI(af_form.fa_class.value);
  af_url += "&fa_name="         + encode_URI(af_form.fa_name.value);
  af_url += "&fa_acc="          + encode_URI(af_form.fa_acc.value);
  af_url += "&fa_damage="       + encode_URI(af_form.fa_damage.value);
  af_url += "&fa_weight="       + encode_URI(af_form.fa_weight.value);
  af_url += "&fa_type="         + encode_URI(af_form.fa_type.value);
  af_url += "&fa_flag="         + encode_URI(af_form.fa_flag.value);
  af_url += "&fa_conceal="      + encode_URI(af_form.fa_conceal.value);
  af_url += "&fa_ap="           + encode_URI(af_form.fa_ap.value);
  af_url += "&fa_mode1="        + encode_URI(af_form.fa_mode1.value);
  af_url += "&fa_mode2="        + encode_URI(af_form.fa_mode2.value);
  af_url += "&fa_mode3="        + encode_URI(af_form.fa_mode3.value);
  af_url += "&fa_ar1="          + encode_URI(af_form.fa_ar1.value);
  af_url += "&fa_ar2="          + encode_URI(af_form.fa_ar2.value);
  af_url += "&fa_ar3="          + encode_URI(af_form.fa_ar3.value);
  af_url += "&fa_ar4="          + encode_URI(af_form.fa_ar4.value);
  af_url += "&fa_ar5="          + encode_URI(af_form.fa_ar5.value);
  af_url += "&fa_rc="           + encode_URI(af_form.fa_rc.value);
  af_url += "&fa_fullrc="       + encode_URI(af_form.fa_fullrc.value);
  af_url += "&fa_ammo1="        + encode_URI(af_form.fa_ammo1.value);
  af_url += "&fa_clip1="        + encode_URI(af_form.fa_clip1.value);
  af_url += "&fa_ammo2="        + encode_URI(af_form.fa_ammo2.value);
  af_url += "&fa_clip2="        + encode_URI(af_form.fa_clip2.value);
  af_url += "&fa_avail="        + encode_URI(af_form.fa_avail.value);
  af_url += "&fa_perm="         + encode_URI(af_form.fa_perm.value);
  af_url += "&fa_basetime="     + encode_URI(af_form.fa_basetime.value);
  af_url += "&fa_duration="     + encode_URI(af_form.fa_duration.value);
  af_url += "&fa_index="        + encode_URI(af_form.fa_index.value);
  af_url += "&fa_cost="         + encode_URI(af_form.fa_cost.value);
  af_url += "&fa_book="         + encode_URI(af_form.fa_book.value);
  af_url += "&fa_page="         + encode_URI(af_form.fa_page.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.firearm.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickFirearm' ).click(function() {
    $( "#dialogFirearm" ).dialog('open');
  });

  $( "#dialogFirearm" ).dialog({
    autoOpen: false,

    modal: true,
    height: 500,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogFirearm" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_firearm('add.firearm.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Firearm",
        click: function() {
          attach_firearm('add.firearm.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Firearm",
        click: function() {
          attach_firearm('add.firearm.mysql.php', 0);
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

<form name="firearm">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Firearm Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('firearm-help');">Help</a></th>
</tr>
</table>

<div id="firearm-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickFirearm" value="Add New Firearm"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#tasers">Tasers</a></li>
  <li><a href="#hold-outs">Hold-Outs</a></li>
  <li><a href="#pistols">Pistols</a></li>
  <li><a href="#machineguns">Machine Guns</a></li>
  <li><a href="#rifles">Rifles</a></li>
  <li><a href="#shotguns">Shotguns</a></li>
  <li><a href="#special">Special Weapons</a></li>
  <li><a href="#launchers">Launchers</a></li>
  <li><a href="#cyberguns">Cyberguns</a></li>
  <li><a href="#errors">Mislabeled Firearms</a></li>
</ul>


<div id="tasers">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Taser Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('taser-listing-help');">Help</a></th>
</tr>
</table>

<div id="taser-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="tasers_table"><?php print wait_Process('Loading Tasers...')?></span>

</div>


<div id="hold-outs">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Hold-Out Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('holdout-listing-help');">Help</a></th>
</tr>
</table>

<div id="holdout-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="hold-outs_table"><?php print wait_Process('Loading Hold-Outs...')?></span>

</div>


<div id="pistols">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Pistol Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('pistol-listing-help');">Help</a></th>
</tr>
</table>

<div id="pistol-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="pistols_table"><?php print wait_Process('Loading Pistols...')?></span>

</div>


<div id="machineguns">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Machinegun Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('machinegun-listing-help');">Help</a></th>
</tr>
</table>

<div id="machinegun-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="mg_table"><?php print wait_Process('Loading Machine Guns...')?></span>

</div>


<div id="rifles">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Rifles Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('rifles-listing-help');">Help</a></th>
</tr>
</table>

<div id="rifles-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="rifles_table"><?php print wait_Process('Loading Rifles...')?></span>

</div>


<div id="shotguns">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Shotgun Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('shotgun-listing-help');">Help</a></th>
</tr>
</table>

<div id="shotgun-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="shotguns_table"><?php print wait_Process('Loading Shotguns...')?></span>

</div>


<div id="special">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Special Weapon Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('special-listing-help');">Help</a></th>
</tr>
</table>

<div id="special-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="special_table"><?php print wait_Process('Loading Special Weapons...')?></span>

</div>


<div id="launchers">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Launcher Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('launcher-listing-help');">Help</a></th>
</tr>
</table>

<div id="launcher-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="launchers_table"><?php print wait_Process('Loading Launchers...')?></span>

</div>


<div id="cyberguns">

o<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Cybergun Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cybergun-listing-help');">Help</a></th>
</tr>
</table>

<div id="cybergun-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="cyberguns_table"><?php print wait_Process('Loading Cyberguns...')?></span>

</div>


<div id="errors">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Missing Items Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('missing-listing-help');">Help</a></th>
</tr>
</table>

<div id="missing-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="error_table"><?php print wait_Process('Loading Missing...')?></span>

</div>

</div>

</div>


<div id="dialogFirearm" title="Firearm Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.firearm.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
