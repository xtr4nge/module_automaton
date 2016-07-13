<?
$mod_name="automaton";
$mod_version="1.1";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_logs_panel="disabled";
$mod_panel="show";
$mod_isup="grep 'OnStart' $mod_path/includes/status.txt";
$mod_alias="Automaton";

# OPTIONS
$mod_automaton_onstart="ap-start.conf";
$mod_automaton_onstop="ap-stop.conf";
$mod_automaton_onboot="ap-start.conf";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_grep = "/usr/bin/ngrep";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_dos2unix = "/usr/bin/dos2unix";
$bin_touch = "/usr/bin/touch";
$bin_mv = "/bin/mv";
?>
