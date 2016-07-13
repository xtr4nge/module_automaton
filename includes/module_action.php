<? 
/*
	Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
	regex_standard($_GET["hopping_conf"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];
$automaton_conf = $_GET['automaton_conf'];

if($service == "automaton") {
    
	if ($action == "run") {
		
		$exec = "python client/fruitywifi_client.py -f templates/$automaton_conf -t $api_token > /dev/null 2 &";
		exec_fruitywifi($exec);
		
	} else if ($action == "start") {

        $exec = "python client/fruitywifi_client.py -f templates/$mod_automaton_onstart -t $api_token > /dev/null 2 &";
		exec_fruitywifi($exec);

		$exec = "echo 'OnStart' > status.txt";
		exec_fruitywifi($exec);
		
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            //exec_fruitywifi($exec);
        }
    
    
    } else if ($action == "stop") {
        
        $exec = "python client/fruitywifi_client.py -f templates/$mod_automaton_onstop -t $api_token > /dev/null 2 &";
		exec_fruitywifi($exec);

		$exec = "echo 'OnStop' > status.txt";
		exec_fruitywifi($exec);
            
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            //exec_fruitywifi($exec);
        }

    } else if ($action == "onboot-start") {
        
        // INCLUDE rc.local
		$line_search = "fruitywifi_client.py";
		
        $exec = "grep '$line_search' /etc/rc.local";
        $isautostart = exec($exec);
        if ($isautostart  == "") {
			
			// Check if 'exit 0' exists in rc.local
			$exec = "grep '^exit 0' /etc/rc.local";
			$isexit = exec($exec);
			if ($isexit  == "") {
				$exec = "echo 'exit 0' >> /etc/rc.local";
                exec_fruitywifi($exec);
			} 
			
			// Insert OnBoot in rc.local
            $exec = "sed -i '/$line_search/d' /etc/rc.local";
            exec_fruitywifi($exec);
            
            $exec = "sed -i 's;^exit 0;python $mod_path/includes/client/fruitywifi_client.py -f $mod_path/includes/templates/$mod_automaton_onboot -t $api_token > /dev/null 2 \& \\nexit 0;g' /etc/rc.local";
            exec_fruitywifi($exec);
            
        }

    } else if ($action == "onboot-stop") {
        // REMOVE from rc.local
		$line_search = "fruitywifi_client.py";
		
        $exec = "sed -i '/$line_search/d' /etc/rc.local";
        exec_fruitywifi($exec);
    }

}

if ($install == "install_autostart") {

    $exec = "chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module=autostart');
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name);
}

?>