<?php
/*Plugin Name: Steam Profile Information
Plugin URI: http://stefki.com
Description: Widget that retrieves XML-based data from a non-private Steam profile.
Version: 0.1
Author: Stefanos Kiourkoulis
Author URI: http://stefki.com
*/

class mySteam extends WP_Widget
{	
	function mySteam()
	{
		$widget_ops = array( 'classname' => 'Steam Profile Information', 'description' => 'A Wordpress widget for displaying an individual\'s Steam statistics and account information.' );
		$control_ops = array( 'width' => 370, 'height' => 300, 'id_base' => 'steam-profile-widget' );
		$this->WP_Widget( 'steam-profile-widget', 'Steam Profile Information', $widget_ops, $control_ops );
	}
	
	function widget($args, $instance)
	{
		extract($args);	
		try
		{
			$data = simplexml_load_file($instance['url']. '?xml=1');
		}
		catch (Exception $e)
		{
			echo 'Steam Unavailable';
		}
		
        $title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ($title)
		{
			echo $before_title . $title . $after_title;
		}
		else
		{
			echo $before_title . $after_title;
		}
		
		if (!($data == false))
		{
			if (isset($instance['steamID']))
			{ 
				echo '<p><strong>SteamID:</strong> '. '<a href="' . $instance['url'] . '" target="_blank"> ' . $data->steamID . '</a><br />';
			}		
			if (isset($instance['RealName']))
			{ 
				echo '<strong>Real Name: </strong>' . $data->realname . '<br />';
			}
			if (isset($instance['avatar']))
			{ 
				echo '<strong>Avatar: </strong><br /><p style="text-align: center;"><img src="' . $data->avatarMedium . '"/></p>';
			}
			if (isset($instance['OnlineState']))
			{
				if ($data->onlineState == "online")
				{
					echo '<strong>Status:</strong> ' . '<span style="color: #4756D7;">' . $data->onlineState . '</span><br />';
				}
				else if ($data->onlineState == "offline")
				{
					echo '<strong>Status:</strong> ' . '<span style="color: #ff0000;">' . $data->onlineState . '</span><br />';
				}
				else
				{
					//in-game
					echo '<strong>Status:</strong> ' . '<span style="color: #14B517;">' . $data->onlineState . '</span><br />';
					if (isset($instance['StateMessage']))
					{ 
						echo '<strong>Playing: </strong><a href="'. $data->inGameInfo->gameLink . '" target="_blank">' .  $data->inGameInfo->gameName . '</a><br />';
					}
				}
			}
			if (isset($instance['Location']))
			{ 
				echo '<strong>Location: </strong>' . $data->location . '<br />';
			}
			if (isset($instance['MemberSince']))
			{ 
				echo '<strong>Member since:</strong> ' . $data->memberSince . '<br />';
			}
			if (isset($instance['SteamRating']))
			{ 
				echo '<Strong>Steam Rating:</strong> ' . $data->steamRating . '<br />';
			}
			if (isset($instance['hoursPlayed2Wk']))
			{ 
				echo '<Strong>Played in last 2 Weeks: </strong>' . $data->hoursPlayed2Wk . 'h<br />';
			}
			if (isset($instance['GroupName']))
			{ 
				echo '<strong>Group name:</strong> ' . '<a href="' . $data->groups->group[0]->groupURL . '" target="_blank">' . $data->groups->group[0]->groupName . '</a><br/>';
			}
			if (isset($instance['GroupHeadline']))
			{
				echo '<strong>Group\'s Headline:</strong> <br/><p style="text-align: center;">"<i>' . $data->groups->group[0]->headline . '"</i></p>';
			}
			if (isset($instance['GroupSummary']))
			{
				echo '<strong>Group\'s Summary:</strong><br/><p style="text-align: center;">"<i>' . $data->groups->group[0]->summary . '</i>"</p>';
			}
			if (isset($instance['PlayedGames']))
			{ 
				echo '<strong>Recently played Games:</strong> ';
				echo '<p style="text-align: center;"><a href="' . $data->mostPlayedGames->mostPlayedGame[0]->gameLink . '" target="_blank"><img width="150" height="56" src="' . $data->mostPlayedGames->mostPlayedGame[0]->gameLogo . '"  style="border-color: #462BD0; border-radius: 8px 8px 8px 8px; border-style: inset; border-width: 2px;" alt="game1" ></a> ' . ' ' .  '<br/><i>' . $data->mostPlayedGames->mostPlayedGame[0]->gameName .   '</i></p>';
				echo '<p style="text-align: center;"><a href="' . $data->mostPlayedGames->mostPlayedGame[1]->gameLink . '" target="_blank"><img width="150" height="56" src="' . $data->mostPlayedGames->mostPlayedGame[1]->gameLogo . '"  style="border-color: #462BD0; border-radius: 8px 8px 8px 8px; border-style: inset; border-width: 2px;" alt="game2" ></a> ' . ' ' .  '<br/><i>' . $data->mostPlayedGames->mostPlayedGame[1]->gameName .   '</i></p>';
			}			
			if (isset($instance['AddFriendsViewGames']))
			{
				echo '<p style="text-align:center;">|<a href="steam://friends/add/' . $data->customURL . '">Add to your friends list</a>|<br />';
				echo '|<a href="' . $instance['url'] . 'games?tab=all" target="_blank">View all games</a>|</p>';	
			}
		}
		else 
		{
			echo '<p style="color: red;">Steam is unavailable at the moment.</p>';
		}
		echo $after_widget;	
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['url'] = strip_tags($new_instance['url']);	
		$instance['steamID'] = $new_instance['steamID'];
		$instance['RealName'] = $new_instance['RealName'];
		$instance['avatar'] = $new_instance['avatar'];
		$instance['OnlineState'] = $new_instance['OnlineState'];
		$instance['StateMessage'] = $new_instance['StateMessage'];
		$instance['Location'] = $new_instance['Location'];
		$instance['CustomURL'] = $new_instance['CustomURL'];
		$instance['MemberSince'] = $new_instance['MemberSince'];
		$instance['SteamRating'] = $new_instance['SteamRating'];
		$instance['hoursPlayed2Wk'] = $new_instance['hoursPlayed2Wk'];
		$instance['GroupName'] = $new_instance['GroupName'];
		$instance['GroupHeadline'] = $new_instance['GroupHeadline'];
		$instance['GroupSummary'] = $new_instance['GroupSummary'];
		$instance['PlayedGames'] = $new_instance['PlayedGames'];
		$instance['AddFriendsViewGames'] = $new_instance['AddFriendsViewGames'];
		
        	return $instance;
	}
	
	function form($instance)
	{
		$defaults = array( 'title' => 'Steam Profile Information', 'url' => 'http://steamcommunity.com/id/eonaeternus/' );
		$instance = wp_parse_args((array) $instance, $defaults);
		
        ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>

				<label for="<?php echo $this->get_field_id( 'url' ); ?>">Profile URL:</label>
				<input id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" style="width:100%;" />
			</p>
			
			<table cellspacing="20">
				<tr>
					<td><label>Avatar: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>" <?php if (isset($instance['avatar'])){echo 'checked="1"';}?> /></td>
				</tr>
		
				<tr>
					<td><label>Steam ID: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'steam ID' ); ?>" name="<?php echo $this->get_field_name( 'steamID' ); ?>" <?php if (isset($instance['steamID'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Real Name: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'RealName' ); ?>" name="<?php echo $this->get_field_name( 'RealName' ); ?>" <?php if (isset($instance['RealName'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>		
					<td><label>Online State: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'OnlineState' ); ?>" name="<?php echo $this->get_field_name( 'OnlineState' ); ?>" <?php if (isset($instance['OnlineState'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Location: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'Location' ); ?>" name="<?php echo $this->get_field_name( 'Location' ); ?>" <?php if (isset($instance['Location'])){echo 'checked="1"';}?> /></td>
				</tr>
			
				<tr>
					<td><label>Member Since: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'MemberSince' ); ?>" name="<?php echo $this->get_field_name( 'MemberSince' ); ?>" <?php if (isset($instance['MemberSince'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Steam Rating: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'SteamRating' ); ?>" name="<?php echo $this->get_field_name( 'SteamRating' ); ?>" <?php if (isset($instance['SteamRating'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Hours Played in Last 2 Weeks: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'hoursPlayed2Wk' ); ?>" name="<?php echo $this->get_field_name( 'hoursPlayed2Wk' ); ?>" <?php if (isset($instance['hoursPlayed2Wk'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Group Name: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'GroupName' ); ?>" name="<?php echo $this->get_field_name( 'GroupName' ); ?>" <?php if (isset($instance['GroupName'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Group Headline: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'GroupHeadline' ); ?>" name="<?php echo $this->get_field_name( 'GroupHeadline' ); ?>" <?php if (isset($instance['GroupHeadline'])){echo 'checked="1"';}?> /></td>
				</tr>
				
				<tr>
					<td><label>Group Summary: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'GroupSummary' ); ?>" name="<?php echo $this->get_field_name( 'GroupSummary' ); ?>" <?php if (isset($instance['GroupSummary'])){echo 'checked="1"';}?> /></td>
				</tr>	
		
				<tr>
					<td><label>Played Games: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'PlayedGames' ); ?>" name="<?php echo $this->get_field_name( 'PlayedGames' ); ?>" <?php if (isset($instance['PlayedGames'])){echo 'checked="1"';}?> /></td>
				</tr>	
		
				<tr>
					<td><label>AddFriends, View Games: </label></td>
					<td><input type="checkbox" id="<?php echo $this->get_field_id( 'AddFriendsViewGames' ); ?>" name="<?php echo $this->get_field_name( 'AddFriendsViewGames' ); ?>" <?php if (isset($instance['AddFriendsViewGames'])){echo 'checked="1"';}?> /></td>
				</tr>		
		</table>
		<?php 
	}
}

add_action( 'widgets_init', 'initialize_widget' );

function initialize_widget()
{
	register_widget('mySteam');	
}

?>
