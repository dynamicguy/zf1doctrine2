import os
from fabric.api import *

abspath = lambda filename: os.path.join(os.path.abspath(os.path.dirname(__file__)), filename)

env.user = 'nurul'
env.password = 'c0mm0n'
env.key_filename = '/Users/ferdous/Documents/ssh-ubuntu/id_rsa'

def prepare():
    env.hosts = ['tms-micro', ]
    env.serverpath = '/var/www'
    env.graceful = True
    return

def deploy():
    run("sudo rm -rf %s/*" % env.serverpath)
    local('zip -r code.zip ./ -x "*.pyc" ".git/*" ".svn/*" "library/Zend/*"')
    put("code.zip", "%s/" % env.serverpath)
    run("cd %s; unzip -o code.zip" % env.serverpath)
    run("cd %s; rm -f code.zip" % env.serverpath)
    local("rm -f code.zip")
    restart_apache()

def restart_apache():
    if env.graceful:
        run("sudo /etc/init.d/apache2 graceful")
    else:
        run("service httpd restart")