<?php
$return = TRUE;


require("../configuration.php");
require("./include.php");
include("../libs/lgsl/lgsl_protocol.php");


if (isset($_POST['task']))
{
	$task = mysqli_real_escape_string($conn, $_POST['task']);
}
else if (isset($_GET['task']))
{
	$task = mysqli_real_escape_string($conn, $_GET['task']);
}


switch (@$task)
{
	case 'configgameadd':
		$gameName = mysqli_real_escape_string($conn, $_POST['gameName']);
		$maxSlots = mysqli_real_escape_string($conn, $_POST['maxSlots']);
		$defaultPort = mysqli_real_escape_string($conn, $_POST['defaultPort']);
		$cfg1Name = mysqli_real_escape_string($conn, $_POST['cfg1Name']);
		$cfg1 = mysqli_real_escape_string($conn, $_POST['cfg1']);
		$cfg2Name = mysqli_real_escape_string($conn, $_POST['cfg2Name']);
		$cfg2 = mysqli_real_escape_string($conn, $_POST['cfg2']);
		$cfg3Name = mysqli_real_escape_string($conn, $_POST['cfg3Name']);
		$cfg3 = mysqli_real_escape_string($conn, $_POST['cfg3']);
		$cfg4Name = mysqli_real_escape_string($conn, $_POST['cfg4Name']);
		$cfg4 = mysqli_real_escape_string($conn, $_POST['cfg4']);
		$cfg5Name = mysqli_real_escape_string($conn, $_POST['cfg5Name']);
		$cfg5 = mysqli_real_escape_string($conn, $_POST['cfg5']);
		$cfg6Name = mysqli_real_escape_string($conn, $_POST['cfg6Name']);
		$cfg6 = mysqli_real_escape_string($conn, $_POST['cfg6']);
		$cfg7Name = mysqli_real_escape_string($conn, $_POST['cfg7Name']);
		$cfg7 = mysqli_real_escape_string($conn, $_POST['cfg7']);
		$cfg8Name = mysqli_real_escape_string($conn, $_POST['cfg8Name']);
		$cfg8 = mysqli_real_escape_string($conn, $_POST['cfg8']);
		$cfg9Name = mysqli_real_escape_string($conn, $_POST['cfg9Name']);
		$cfg9 = mysqli_real_escape_string($conn, $_POST['cfg9']);
		$startLine = mysqli_real_escape_string($conn, $_POST['startLine']);
		$queryType = mysqli_real_escape_string($conn, $_POST['queryType']);
		$queryPort = mysqli_real_escape_string($conn, $_POST['queryPort']);
		$cacheDir = mysqli_real_escape_string($conn, $_POST['cacheDir']);
		###
		//Used to fill in the blanks of the form
		$_SESSION['gameName'] = $gameName;
		$_SESSION['maxSlots'] = $maxSlots;
		$_SESSION['defaultPort'] = $defaultPort;
		$_SESSION['cfg1Name'] = $cfg1Name;
		$_SESSION['cfg1'] = $cfg1;
		$_SESSION['cfg2Name'] = $cfg2Name;
		$_SESSION['cfg2'] = $cfg2;
		$_SESSION['cfg3Name'] = $cfg3Name;
		$_SESSION['cfg3'] = $cfg3;
		$_SESSION['cfg4Name'] = $cfg4Name;
		$_SESSION['cfg4'] = $cfg4;
		$_SESSION['cfg5Name'] = $cfg5Name;
		$_SESSION['cfg5'] = $cfg5;
		$_SESSION['cfg6Name'] = $cfg6Name;
		$_SESSION['cfg6'] = $cfg6;
		$_SESSION['cfg7Name'] = $cfg7Name;
		$_SESSION['cfg7'] = $cfg7;
		$_SESSION['cfg8Name'] = $cfg8Name;
		$_SESSION['cfg8'] = $cfg8;
		$_SESSION['cfg9Name'] = $cfg9Name;
		$_SESSION['cfg9'] = $cfg9;
		$_SESSION['startLine'] = $startLine;
		$_SESSION['queryType'] = $queryType;
		$_SESSION['queryPort'] = $queryPort;
		$_SESSION['cacheDir'] = $cacheDir;
		###
		//Check the inputs. Output an error if the validation failed
		$gameLength = strlen($gameName);
		###
		$error = '';
		###
		if ($gameLength < 2)
		{
			$error .= T_('Game Name is too short (2 Chars min.). ');
		}
		if (empty($maxSlots))
		{
			$error .= T_('Max Slots is not set ! ');
		}
		else if (!is_numeric($maxSlots))
		{
			$error .= T_('Max Slots is not a numeric value ! ');
		}
		if (empty($defaultPort))
		{
			$error .= T_('Default Server Port is not set ! ');
		}
		else if (!is_numeric($defaultPort))
		{
			$error .= T_('Default Server Port is not a numeric value ! ');
		}
		if (empty($startLine))
		{
			$error .= T_('Start Command is not set ! ');
		}
		if (!array_key_exists($queryType, lgsl_type_list()))
		{
			$error .= T_('Unknown Query Type ! ');
		}
		if (!empty($queryPort))
		{
			if (!is_numeric($queryPort))
			{
				$error .= T_('Query Port is not a numeric value ! ');
			}
		}
		else
		{
			$queryPort = $defaultPort;
		}
		/*
		if(!validatePath($cacheDir))
		{
			$error .= 'Invalid Cache Directory. ';
		}
		*/
		###
		if (!empty($error))
		{
			$_SESSION['msg1'] = 'Validation Error!';
			$_SESSION['msg2'] = $error;
			$_SESSION['msg-type'] = 'error';
			unset($error);
			header( "Location: configgameadd.php" );
			die();
		}
		###
		//As the form has been validated, vars are useless
		unset($_SESSION['gameName']);
		unset($_SESSION['maxSlots']);
		unset($_SESSION['defaultPort']);
		unset($_SESSION['cfg1Name']);
		unset($_SESSION['cfg1']);
		unset($_SESSION['cfg2Name']);
		unset($_SESSION['cfg2']);
		unset($_SESSION['cfg3Name']);
		unset($_SESSION['cfg3']);
		unset($_SESSION['cfg4Name']);
		unset($_SESSION['cfg4']);
		unset($_SESSION['cfg5Name']);
		unset($_SESSION['cfg5']);
		unset($_SESSION['cfg6Name']);
		unset($_SESSION['cfg6']);
		unset($_SESSION['cfg7Name']);
		unset($_SESSION['cfg7']);
		unset($_SESSION['cfg8Name']);
		unset($_SESSION['cfg8']);
		unset($_SESSION['cfg9Name']);
		unset($_SESSION['cfg9']);
		unset($_SESSION['startLine']);
		unset($_SESSION['queryType']);
		unset($_SESSION['queryPort']);
		unset($_SESSION['cacheDir']);
		###
		//Security
		$maxSlots = abs($maxSlots);
		$defaultPort = abs($defaultPort);
		$queryPort = abs($queryPort);
		###
		//Adding game to the database
		query_basic( "INSERT INTO `".DBPREFIX."game` SET
			`game` = '".$gameName."',
			`status` = 'Active',
			`maxslots` = '".$maxSlots."',
			`defaultport` = '".$defaultPort."',
			`cfg1name` = '".$cfg1Name."',
			`cfg1` = '".$cfg1."',
			`cfg2name` = '".$cfg2Name."',
			`cfg2` = '".$cfg2."',
			`cfg3name` = '".$cfg3Name."',
			`cfg3` = '".$cfg3."',
			`cfg4name` = '".$cfg4Name."',
			`cfg4` = '".$cfg4."',
			`cfg5name` = '".$cfg5Name."',
			`cfg5` = '".$cfg5."',
			`cfg6name` = '".$cfg6Name."',
			`cfg6` = '".$cfg6."',
			`cfg7name` = '".$cfg7Name."',
			`cfg7` = '".$cfg7."',
			`cfg8name` = '".$cfg8Name."',
			`cfg8` = '".$cfg8."',
			`cfg9name` = '".$cfg9Name."',
			`cfg9` = '".$cfg9."',
			`startline` = '".$startLine."',
			`querytype` = '".$queryType."',
			`queryport` = '".$queryPort."',
			`cachedir` = '".$cacheDir."'" );
		###
		$_SESSION['msg1'] = T_('Game Added Successfully!');
		$_SESSION['msg2'] = T_('The new game has been added and is ready for use.');
		$_SESSION['msg-type'] = 'success';
		header( "Location: configgame.php" );
		die();
		break;

	case 'configgameedit':
		$gameid = mysqli_real_escape_string($conn, $_POST['gameid']);
		$gameName = mysqli_real_escape_string($conn, $_POST['gameName']);
		$status = mysqli_real_escape_string($conn, $_POST['status']);
		$maxSlots = mysqli_real_escape_string($conn, $_POST['maxSlots']);
		$defaultPort = mysqli_real_escape_string($conn, $_POST['defaultPort']);
		$cfg1Name = mysqli_real_escape_string($conn, $_POST['cfg1Name']);
		$cfg1 = mysqli_real_escape_string($conn, $_POST['cfg1']);
		$cfg2Name = mysqli_real_escape_string($conn, $_POST['cfg2Name']);
		$cfg2 = mysqli_real_escape_string($conn, $_POST['cfg2']);
		$cfg3Name = mysqli_real_escape_string($conn, $_POST['cfg3Name']);
		$cfg3 = mysqli_real_escape_string($conn, $_POST['cfg3']);
		$cfg4Name = mysqli_real_escape_string($conn, $_POST['cfg4Name']);
		$cfg4 = mysqli_real_escape_string($conn, $_POST['cfg4']);
		$cfg5Name = mysqli_real_escape_string($conn, $_POST['cfg5Name']);
		$cfg5 = mysqli_real_escape_string($conn, $_POST['cfg5']);
		$cfg6Name = mysqli_real_escape_string($conn, $_POST['cfg6Name']);
		$cfg6 = mysqli_real_escape_string($conn, $_POST['cfg6']);
		$cfg7Name = mysqli_real_escape_string($conn, $_POST['cfg7Name']);
		$cfg7 = mysqli_real_escape_string($conn, $_POST['cfg7']);
		$cfg8Name = mysqli_real_escape_string($conn, $_POST['cfg8Name']);
		$cfg8 = mysqli_real_escape_string($conn, $_POST['cfg8']);
		$cfg9Name = mysqli_real_escape_string($conn, $_POST['cfg9Name']);
		$cfg9 = mysqli_real_escape_string($conn, $_POST['cfg9']);
        $startLine = mysqli_real_escape_string($conn, $_POST['startLine'] );
		$queryType = mysqli_real_escape_string($conn, $_POST['queryType']);
		$queryPort = mysqli_real_escape_string($conn, $_POST['queryPort']);
		$cacheDir = mysqli_real_escape_string($conn, $_POST['cacheDir']);
		###
		//Check the inputs. Output an error if the validation failed
		$gameLength = strlen($gameName);
		###
		$error = '';
		###
		if (!is_numeric($gameid))
		{
			$error .= T_('Invalid GameID. ');
		}
		else if (query_numrows( "SELECT `game` FROM `".DBPREFIX."game` WHERE `gameid` = '".$gameid."'" ) == 0)
		{
			$error .= T_('Invalid GameID. ');
		}
		###
		if ($gameLength < 2)
		{
			$error .= T_('Game Name is too short (2 Chars min.). ');
		}
		if (empty($maxSlots))
		{
			$error .= T_('Max Slots is not set ! ');
		}
		else if (!is_numeric($maxSlots))
		{
			$error .= T_('Max Slots is not a numeric value ! ');
		}
		if (empty($defaultPort))
		{
			$error .= T_('Default Server Port is not set ! ');
		}
		else if (!is_numeric($defaultPort))
		{
			$error .= T_('Default Server Port is not a numeric value ! ');
		}
		if (empty($startLine))
		{
			$error .= T_('Start Command is not set ! ');
		}
		if (!array_key_exists($queryType, lgsl_type_list()))
		{
			$error .= T_('Unknown Query Type ! ');
		}
		if (!empty($queryPort))
		{
			if (!is_numeric($queryPort))
			{
				$error .= T_('Query Port is not a numeric value ! ');
			}
		}
		else
		{
			$queryPort = $defaultPort;
		}
		/*
		if(!validatePath($cacheDir))
		{
			$error .= T_('Invalid Cache Directory. ');
		}
		*/
		###
		if (!empty($error))
		{
			$_SESSION['msg1'] = T_('Validation Error! Form has been reset!');
			$_SESSION['msg2'] = $error;
			$_SESSION['msg-type'] = 'error';
			unset($error);
			header( "Location: configgameedit.php?id=".urlencode($gameid) );
			die();
		}
		###
		//Security
		$maxSlots = abs($maxSlots);
		$defaultPort = abs($defaultPort);
		$queryPort = abs($queryPort);
		###
		//Update
		query_basic( "UPDATE `".DBPREFIX."game` SET
			`game` = '".$gameName."',
			`status` = '".$status."',
			`maxslots` = '".$maxSlots."',
			`defaultport` = '".$defaultPort."',
			`cfg1name` = '".$cfg1Name."',
			`cfg1` = '".$cfg1."',
			`cfg2name` = '".$cfg2Name."',
			`cfg2` = '".$cfg2."',
			`cfg3name` = '".$cfg3Name."',
			`cfg3` = '".$cfg3."',
			`cfg4name` = '".$cfg4Name."',
			`cfg4` = '".$cfg4."',
			`cfg5name` = '".$cfg5Name."',
			`cfg5` = '".$cfg5."',
			`cfg6name` = '".$cfg6Name."',
			`cfg6` = '".$cfg6."',
			`cfg7name` = '".$cfg7Name."',
			`cfg7` = '".$cfg7."',
			`cfg8name` = '".$cfg8Name."',
			`cfg8` = '".$cfg8."',
			`cfg9name` = '".$cfg9Name."',
			`cfg9` = '".$cfg9."',
			`startline` = '".$startLine."',
			`querytype` = '".$queryType."',
			`queryport` = '".$queryPort."',
			`cachedir` = '".$cacheDir."' WHERE `gameid` = '".$gameid."'" );
		###
		//Update LGSL and servers
		$servers = query_fetch_assoc( "SELECT `serverid` FROM `".DBPREFIX."server` WHERE `gameid` = '".$gameid."'" );
		###
		query_basic( "UPDATE `".DBPREFIX."server` SET
			`game` = '".$gameName."' WHERE `serverid` = '".$servers['serverid']."'" );
		###
		query_basic( "UPDATE `".DBPREFIX."lgsl` SET `type` = '".$queryType."' WHERE `id` = '".$servers['serverid']."'" );
		###
		unset($servers);
		###
		$_SESSION['msg1'] = T_('Game Updated Successfully!');
		$_SESSION['msg2'] = T_('Your changes to the game have been saved.');
		$_SESSION['msg-type'] = 'success';
		header( "Location: configgame.php" );
		die();
		break;

	case 'configgamedelete':
		$gameid = $_GET['id'];
		###
		$error = '';
		###
		if (!is_numeric($gameid))
		{
			$error .= T_('Invalid GameID. ');
		}
		else if (query_numrows( "SELECT `game` FROM `".DBPREFIX."game` WHERE `gameid` = '".$gameid."'" ) == 0)
		{
			$error .= T_('Invalid GameID. ');
		}
		###
		if (!empty($error))
		{
			$_SESSION['msg1'] = T_('Validation Error!');
			$_SESSION['msg2'] = $error;
			$_SESSION['msg-type'] = 'error';
			unset($error);
			header( "Location: index.php" );
			die();
		}
		###
		if (query_numrows( "SELECT `serverid` FROM `".DBPREFIX."server` WHERE `gameid` = '".$gameid."'" ) != 0)
		{
			$_SESSION['msg1'] = T_('Error!');
			$_SESSION['msg2'] = T_('The selected game cannot be deleted as it is currently in use by a game server. The server must be deleted first.');
			$_SESSION['msg-type'] = 'error';
			header( "Location: configgame.php" );
			die();
		}
		###
		query_basic( "DELETE FROM `".DBPREFIX."game` WHERE `gameid` = '".$gameid."' LIMIT 1" );
		$_SESSION['msg1'] = T_('Game Deleted Successfully!');
		$_SESSION['msg2'] = T_('The selected game has been removed.');
		$_SESSION['msg-type'] = 'success';
		header( "Location: configgame.php" );
		die();
		break;

	default:
		exit('<h1><b>Error</b></h1>');
}

exit('<h1><b>403 Forbidden</b></h1>'); //If the task is incorrect or unspecified, we drop the user.
?>