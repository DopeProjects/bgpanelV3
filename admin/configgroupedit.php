<?php
$page = 'configgroupedit';
$tab = 5;
$isSummary = TRUE;
###
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$groupid = $_GET['id'];
}
else
{
	exit('Error: GroupID error.');
}
###
$return = 'configgroupedit.php?id='.urlencode($groupid);


require("../configuration.php");
require("./include.php");

$title = T_('Edit Group');

if (query_numrows( "SELECT `name` FROM `".DBPREFIX."group` WHERE `groupid` = '".$groupid."'" ) == 0)
{
	exit('Error: GroupID is invalid.');
}


$rows = query_fetch_assoc( "SELECT `name`, `description` FROM `".DBPREFIX."group` WHERE `groupid` = '".$groupid."' LIMIT 1" );

$clients = getGroupClients($groupid);

if ($clients == FALSE)
{
	$error = T_("This group doesn't have clients.");
}


include("./bootstrap/header.php");


/**
 * Notifications
 */
include("./bootstrap/notifications.php");


?>
			<div class="well">
				<form method="post" action="configgroupprocess.php">
					<input type="hidden" name="task" value="configgroupedit" />
					<input type="hidden" name="groupid" value="<?php echo $groupid; ?>" />
					<label><?php echo T_('Group Name'); ?></label>
						<input type="text" name="name" class="span4" value="<?php echo htmlspecialchars($rows['name'], ENT_QUOTES); ?>">
					<label><?php echo T_('Group Description'); ?></label>
						<textarea name="notes" class="textarea span10"><?php echo htmlspecialchars($rows['description'], ENT_QUOTES); ?></textarea>
					<div class="row">
						<div class="span5">
							<div style="text-align: center; margin-bottom: 5px;">
								<span class="label"><?php echo T_('Group Configuration'); ?></span>
							</div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo T_('First Name'); ?></th>
										<th><?php echo T_('Last Name'); ?></th>
										<th><?php echo T_('Username'); ?></th>
										<th><?php echo T_('Actions'); ?></th>
									</tr>
								</thead>
								<tbody>
<?php

if (!isset($error))
{
	foreach($clients as $key => $value)
	{
		$client = query_fetch_assoc( "SELECT `firstname`, `lastname`, `username` FROM `".DBPREFIX."client` WHERE `clientid` = '".$value."'" );
?>
									<tr>
										<td><?php echo ($key + 1); ?></td>
										<td><?php echo htmlspecialchars($client['firstname'], ENT_QUOTES); ?></td>
										<td><?php echo htmlspecialchars($client['lastname'], ENT_QUOTES); ?></td>
										<td><?php echo htmlspecialchars($client['username'], ENT_QUOTES); ?></td>
										<td>
											<label class="checkbox">
												<input type="checkbox" name="removeid<?php echo $key; ?>"><i class="icon-remove-sign <?php echo formatIcon(); ?>"></i>
											</label>
										</td>
									</tr>
<?php
	}
	unset($clients);
}
else
{
?>
									<tr>
										<td colspan="5"><div style="text-align: center;"><span class="label label-warning"><?php echo $error; ?></span></div></td>
									</tr>
<?php
}

?>
									<tr>
										<td>#</td>
										<td>~</td>
										<td>~</td>
										<td>
											<select class="span3" name="newClient">
												<option>-Select-</option>
<?php

$clients = mysqli_query($conn, "SELECT `clientid`, `username` FROM `".DBPREFIX."client` WHERE `status` = 'Active'" );

while ($rowsClients = mysqli_fetch_assoc($clients))
{
	if (!checkClientGroup($groupid, $rowsClients['clientid']))
	{
?>
												<option value="<?php echo htmlspecialchars($rowsClients['username'], ENT_QUOTES); ?>"><?php echo htmlspecialchars($rowsClients['username'], ENT_QUOTES); ?></option>
<?php
	}
}
unset($clients);

?>
											</select>
										</td>
										<td><button type="submit" class="btn btn-primary btn-small" href=""><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo T_('Add'); ?></button></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div style="text-align: center; margin-top: 19px;">
						<button type="submit" class="btn btn-primary"><?php echo T_('Save Changes'); ?></button>
						<button type="reset" class="btn"><?php echo T_('Cancel Changes'); ?></button>
					</div>
					<div style="text-align: center; margin-top: 19px;">
						<ul class="pager">
							<li>
								<a href="configgroup.php"><?php echo T_('Back to Groups'); ?></a>
							</li>
						</ul>
					</div>
				</form>
			</div>
<?php


include("./bootstrap/footer.php");
?>