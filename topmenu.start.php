<div id="header">

<p><a href="<?php print $Siteroot; ?>"><img src="<?php print $Siteroot; ?>/imgs/<?php print $Siteheader; ?>" width="200"></a></p>

</div>

<div class="main">

<div class="menu">

<ul id="topmenu">
  <li id="tm_home"><a href="<?php print $Siteroot; ?>">Home</a>
    <ul>
      <li><a href="<?php print $Editroot; ?>/mooks.php">Add Character</a></li>
    </ul>
  </li>
<?php
  if (check_userlevel($db, $AL_Johnson)) {
?>
  <li id="tm_mental"><a href="<?php print $Siteroot; ?>/index.mental.php">Mental</a>
    <ul>
      <li><a href="<?php print $Dataroot; ?>/add.active.php">Active Skills</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.contact.php">Contacts</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.knowledge.php">Knowledge Skills</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.language.php">Language Skills</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.lifestyle.php">Lifestyles</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.points.php">Lifestyle Points</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.metatypes.php">Metatypes</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.qualities.php">Qualities</a></li>
    </ul>
  <li id="tm_magic"><a href="<?php print $Siteroot; ?>/index.magic.php">Magic</a>
    <ul>
      <li><a href="<?php print $Dataroot; ?>/add.adept.php">Adept Powers</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.mentor.php">Mentor Spirits</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.metamagics.php">Metamagics</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.power.php">Powers</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.rituals.php">Rituals</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.spells.php">Spells</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.spirits.php">Spirits</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.tradition.php">Tradition</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.weakness.php">Weaknesses</a></li>
    </ul>
  <li id="tm_matrix"><a href="<?php print $Siteroot; ?>/index.matrix.php">Matrix</a>
    <ul>
      <li><a href="<?php print $Dataroot; ?>/add.agent.php">Agents</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.command.php">Command Consoles</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.commlink.php">Commlinks</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.complexform.php">Complex Forms</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.cyberdeck.php">Cyberdeck</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.cyberjack.php">Cyberjack</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.ic.php">Intrusion Countermeasures</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.actions.php">Matrix Actions</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.program.php">Programs</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.spritepower.php">Sprite Powers</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.sprites.php">Sprites</a></li>
    </ul>
  <li id="tm_meatspace"><a href="<?php print $Siteroot; ?>/index.meatspace.php">Meatspace</a>
    <ul>
      <li><a href="<?php print $Dataroot; ?>/add.accessory.php">Accessories</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.ammo.php">Ammunition</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.armor.php">Armor</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.grades.php">Bio/Cyberware Grades</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.bioware.php">Bioware</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.cyberware.php">Cyberware</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.firearm.php">Firearms</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.gear.php">Gear</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.melee.php">Melee</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.projectile.php">Projectile Weapons</a></li>
      <li><a href="<?php print $Dataroot; ?>/add.vehicles.php">Vehicles</a></li>
    </ul>
  <li id="tm_manage"><a href="<?php print $Siteroot; ?>/index.admin.php">Admins</a>
    <ul>
      <li><a href="<?php print $Adminroot; ?>/add.class.php">Classes</a></li>
      <li><a href="<?php print $Adminroot; ?>/add.books.php">Library</a></li>
      <li><a href="<?php print $Adminroot; ?>/add.orphan.php">Orphans</a></li>
      <li><a href="<?php print $Adminroot; ?>/add.subjects.php">Subjects</a></li>
    </ul>
<?php
  }
?>
