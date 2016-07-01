<style>
	.block {
		width: 200px;
		display: inline-block;
	}
</style>
<b>Automaton</b> A tool for automatizing FruityWiFi.
<br><br>
<b>Author</b>: xtr4nge [_AT_] gmail.com - @xtr4nge

<br><br>

<br><b>[OPTIONS]</b>
<br>
<br><b>OnStart</b>: This script is executed when automata is started.
<br><b>OnStop</b>: This script is executed when automata is stopped.
<br><b>OnBoot</b>: This script is executed on boot time when OnBoot is enabled.

<br><br>

<br><b>[COMMANDS]</b>
<br>
<br><b>START</b>: Start a module. (example: START ap)
<br><b>STOP</b>: Stop a module. (example: STOP ap)
<br><b>SET</b>: Set up an option using FruityWiFi api. (example: /config/core/hostapd_ssid/WiFi). All options can be obtained from <b>_info_.php</b> file on each module.
<br><b>SLEEP</b>: Suspends the execution for a specified time (in seconds). (example: SLEEP 1)
<br><b>EXEC</b>: Executes an OS command as root (Be Careful!). Note: This options is disabled by default. It needs to be enabled on the client script. [ client/<b>fruitywifi_client.py</b> : <b>os_command = True</b> ]

<br><br>

Example:
<div style="font-family: courier, monospace;">
SET /config/core/ap_mode/1
<br>SET /config/core/io_in_iface/wlan0
<br>SET /config/core/io_out_iface/eth0
<br>SET /config/core/hostapd_secure/0
<br>SET /config/core/hostapd_ssid/WiFi
<br>SLEEP 1
<br>START ap
</div>