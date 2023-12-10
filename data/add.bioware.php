<?php
# Script: add.bioware.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.bioware.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Bioware</title>

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
function delete_bioware( p_script_url ) {
  var question;
  var answer;

  question  = "The Bioware may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Bioware?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.bioware.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_bioware(p_script_url, update) {
  var ab_form = document.dialog;
  var ab_url;

  ab_url  = '?update='   + update;
  ab_url += "&id="       + ab_form.id.value;

  ab_url += "&bio_class="     + encode_URI(ab_form.bio_class.value);
  ab_url += "&bio_name="      + encode_URI(ab_form.bio_name.value);
  ab_url += "&bio_rating="    + encode_URI(ab_form.bio_rating.value);
  ab_url += "&bio_essence="   + encode_URI(ab_form.bio_essence.value);
  ab_url += "&bio_avail="     + encode_URI(ab_form.bio_avail.value);
  ab_url += "&bio_perm="      + encode_URI(ab_form.bio_perm.value);
  ab_url += "&bio_basetime="  + encode_URI(ab_form.bio_basetime.value);
  ab_url += "&bio_duration="  + encode_URI(ab_form.bio_duration.value);
  ab_url += "&bio_index="     + encode_URI(ab_form.bio_index.value);
  ab_url += "&bio_cost="      + encode_URI(ab_form.bio_cost.value);
  ab_url += "&bio_book="      + encode_URI(ab_form.bio_book.value);
  ab_url += "&bio_page="      + encode_URI(ab_form.bio_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ab_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.bioware.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.bioware.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickBioWare' ).click(function() {
    $( "#dialogBioWare" ).dialog('open');
  });

  $( "#dialogBioWare" ).dialog({
    autoOpen: false,

    modal: true,
    height: 325,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogBioWare" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_bioware('add.bioware.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update BioWare",
        click: function() {
          attach_bioware('add.bioware.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add BioWare",
        click: function() {
          attach_bioware('add.bioware.mysql.php', 0);
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

<form name="bioware">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">BioWare Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('bioware-help');">Help</a></th>
</tr>
</table>

<div id="bioware-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Bioware Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addbio" id="clickBioWare" value="Add BioWare"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#basic">Basic Bioware</a></li>
  <li><a href="#biosculpting">Biosculpting</a></li>
  <li><a href="#cosmetic">Cosmetic</a></li>
  <li><a href="#cultured">Cultured Bioware</a></li>
  <li><a href="#endosymbiont">Endosymbiont</a></li>
  <li><a href="#leech">Leech Bioware</a></li>
</ul>


<div id="basic">

<span id="basic_table"><?php print wait_Process('Loading Basic BioWare...')?></span>

</div>


<div id="biosculpting">

<span id="biosculpting_table"><?php print wait_Process('Loading Biosculpting...')?></span>

</div>


<div id="cosmetic">

<span id="cosmetic_table"><?php print wait_Process('Loading Cosmetic...')?></span>

</div>


<div id="cultured">

<span id="cultured_table"><?php print wait_Process('Loading Cultured BioWare...')?></span>

</div>


<div id="endosymbiont">

<span id="endosymbiont_table"><?php print wait_Process('Loading Endosymbiont...')?></span>

</div>


<div id="leech">

<span id="leech_table"><?php print wait_Process('Loading Leech BioWare...')?></span>

</div>


</div>

</div>


<div id="dialogBioWare" title="BioWare Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.bioware.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
