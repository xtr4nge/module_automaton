#!/usr/bin/python
'''
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
'''

# GLOBAL VARIABLES
gVersion = "1.1"
server = "https://127.0.0.1:8443";
token = "e5dab9a69988dd65e578041416773149ea57a054"
# NOTE: Enabling this option allows Automata module (script) to execute OS commands as ROOT (Be Careful!) [True|False]
os_command = False
log_file = "/usr/share/fruitywifi/logs/automaton.log"
exclude_param = ["$", "version", "regex", "regex_extra", "codename", "root_path", "root_web", "log_path", "core_name", "core_alias", "api_token",
                    "bin_", "mod_name", "mod_version", "mod_path", "mod_logs", "mod_logs_history", "mod_logs_panel", "mod_panel", "mod_isup", "mod_alias"]

import os, sys, getopt
import urllib2
import json
import requests
from requests import session
import time

requests.packages.urllib3.disable_warnings() # DISABLE SSL CHECK WARNINGS

import logging

logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)

# create a file handler
handler = logging.FileHandler(log_file)
handler.setLevel(logging.INFO)

# create a logging format
#formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
formatter = logging.Formatter('%(asctime)s - %(message)s')
handler.setFormatter(formatter)

# add the handlers to the logger
logger.addHandler(handler)


def usage():
    print "\nFruityWiFi API " + gVersion + " by @xtr4nge"
    
    print "Usage: ./client <options>\n"
    print "Options:"
    print "-x <command>, --execute=<commnd>      exec the command passed as parameter."
    print "-f <file>,    --file=<file>           sequence of commands to be executed"
    print "-t <token>,   --token=<token>         authentication token."
    print "-s <server>,  --server=<server>       FruityWiFi server [http{s}://ip:port]."
    print "-h                                    Print this help message."
    print ""
    print "FruityWiFi: http://www.fruitywifi.com"
    print ""

def parseOptions(argv):
    
    v_execute = ""
    v_token = token
    v_server = server
    v_file = ""
    
    try:                                
        opts, args = getopt.getopt(argv, "hx:t:s:f:", 
                                   ["help","execute=","token=","server=","file="])
        
        for opt, arg in opts:
            if opt in ("-h", "--help"):
                usage()
                sys.exit()
            elif opt in ("-x", "--execute"):
                v_execute = arg
            elif opt in ("-t", "--token"):
                v_token = arg
            elif opt in ("-s", "--server"):
                v_server = arg
            elif opt in ("-f", "--file"):
                v_file = arg
                
        return (v_execute, v_token, v_server, v_file)
                    
    except getopt.GetoptError:
        usage()
        sys.exit(2)

(execute, token, server, FILE) = parseOptions(sys.argv[1:])

class webclient:

    def __init__(self, server, token):
        
        self.global_webserver = server
        self.path = "/modules/api/includes/ws_action.php"
        self.s = requests.session()
        self.token = token

    def login(self):

        payload = {
            'action': 'login',
            'token': self.token
        }
        
        self.s = requests.session()
        self.s.get(self.global_webserver, verify=False) # DISABLE SSL CHECK
        self.s.post(self.global_webserver + '/login.php', data=payload)

    def loginCheck(self):
                
        response = self.s.get(self.global_webserver + '/login_check.php')
        
        if response.text != "":
            self.login()
        
        if response.text != "":
            print "Ah, Ah, Ah! You didn't say the magic word!"
            sys.exit()
        
        return True
        
    def submitPost(self, data):
        response = self.s.post(self.global_webserver + data)
        return response.json
    
        if response.text == "":
            return True
        else:
            return False
    
    def submitGet(self, data):
        response = self.s.get(self.global_webserver + self.path + "?" + data)
        return response

def automaton(w, FILE):
    pass
    global os_command
    global exclude_param

    with open(FILE, "r") as f:
        for line in f:
            line = line.strip()
            
            if line.startswith("EXEC "):
                _exec = line.replace("EXEC ", "")
                print _exec
                logger.info(line)
                # NOTE: Enabling this option allows Automata module (script) to execute OS commands as ROOT (Be Careful!)
                if os_command:
                    os.system(_exec)
            
            if line.startswith("SLEEP "):
                _exec = line.replace("SLEEP ", "")
                print _exec
                logger.info(line)
                time.sleep(int(_exec))
            
            if line.startswith("SET "):
                _exec = line.replace("SET ", "")
                
                # EXCLUDE PARAM
                # -------------------->
                _exclude = _exec.split("/")
                for value in exclude_param:
                    if _exclude[3].startswith(value):
                        print "Parameter not allowed: " + str(_exclude[3])
                        logger.info("Parameter not allowed: " + str(_exclude[3]))
                        sys.exit(1)
                # <--------------------
                
                print _exec
                logger.info(line)
                out =  w.submitGet("api=" + str(_exec))
                print out.json()
                
            if line.startswith("START "):
                MODULE = line.replace("START ", "")
                _exec = "/module/"+MODULE+"/start"
                print _exec
                logger.info(line)
                out =  w.submitGet("api=" + str(_exec))
                print out.json()
                
            if line.startswith("STOP "):
                MODULE = line.replace("STOP ", "")
                _exec = "/module/"+MODULE+"/stop"
                print _exec
                logger.info(line)
                out =  w.submitGet("api=" + str(_exec))
                print out.json()
            
try: 
    w = webclient(server, token)
    w.login()
    w.loginCheck()
    
    if FILE != "":
        automaton(w, FILE)
    
    if execute != "":
        out =  w.submitGet("api=" + str(execute))
        print out.json()
    
except requests.exceptions.ConnectionError:
    print "check server connection [http{s}://ip:port]"
    logger.info("check server connection [http{s}://ip:port]")
except SystemExit:
    print "Bye."
    logger.info("Bye.")
except:
    pass
    print "Error: " + str(sys.exc_info()[0])
    logger.info("Error: " + str(sys.exc_info()[0]))
