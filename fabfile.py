"""
a
|-mealadvisor.us
  |-releases
  | |-number
  |-staging
  | |-mealadvisor
  | |-static
  |   |- menutiems -> /a/static.mealadvisor.us/menutiems_static
  | |-config
  | |-scripts
  |-prod
  |-staging.rollback
|-static.mealadvisor.us
 |-staging -> /a/mealadvisor.us/staging/static
 |-menuitems_static

"""

config.releases_path = '/a/mealadvisor.us'
config.path          = '%(releases_path)s/releases/$(svn_version)'
config.static_path   = '/a/static.mealadvisor.us'
config.svn_path      = 'http://svn.reviewsby.us/trunk'


def staging():
    "Setup staging info"
    config.environment = staging
    config.fab_hosts   = ['wallace.mealadvisor.us']
    config.fab_user    = 'builder'
    
def prod():
    "setup production info"
    config.fab_hosts = ['67.23.9.127']
    config.fab_user  = 'builder'
    
def setup_ubuntu():
    config.fab_user = 'root'
    users = {'davedash': "Dave Dash", 'builder': "Build Script", 'mealadvisor': "Meal Advisor"}
    
    for user, fullname in users.iteritems():
        sudo('useradd -G users,sudo -m -c "%s" -s /bin/bash %s'% (fullname, user))
    
    # visudo # Uncomment %sudo ALL=NOPASSWD: ALL
    # su - davedash
    # mkdir ~/.ssh
    # chmod 700 ~/.ssh
    # vim ~/.ssh/authorized_keys
    # # PASTE:
    # ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEAzHDofB7grcL09XY8/kWhh3zCHZdc057d/2rChWiHXOeBEIKKxpAaB0V0m1sIcXnOgRGRl/Y7ENEC1xnyXPOqCdgS3MODQBJmQDPPfJLNXgkzz1qBVgN+eVj4VffLd3Gwo13Q/HOwORG3l/sTj7xYzCq6iErR9iFQzZJRNsE0WvyX+zs5aOA6+nB74TmTPqY8PtGpK4yy96JjvUfdfleZ5u6zanZ8GZVKqY5Yser4Mzgsfy54DPTqDirX7a6RxYsoCP4yOIkX3/QLsrq3uJYDD1iFY2ctdHkPU/K9Bx+GCBgFSnM4H3IeXsKtdeZmu+UPxLTSz+xyzR/DDrtQprQP4w== dash@awesomepants
    # 
    # ssh davedash@domain # should work without a password
    # sudo whoami # should print 'root'
    # sudo vim /etc/ssh/sshd_config
    # sudo /etc/init.d/ssh reload
    # sudo passwd -d root
    # sudo apt-get update
    # sudo apt-get dist-upgrade -y
    # 
    # sudo apt-get install -y bash-completion command-not-found \                          emacs-snapshot-nox exuberant-ctags vim-nox
    # sudo apt-get install -y postfix procmail
    # sudo apt-get install -y dnsutils file info logrotate lsof \                          mailx mlocate openssl rsync screen unzip
    # sudo apt-get install -y autoconf build-essential cdecl colordiff \                          git-core git-svn libtool make patch subversion
    # sudo dpkg-reconfigure postfix
    # sudo vim /etc/postfix/main.cf
    # 
    # $ sudo vim /etc/postfix/virtual# Add one USER@VIRTUAL_DOMAIN EMAIL_TO_FORWARD_TO per line, e.g.:user@example.com user@gmail.com$ sudo postmap /etc/postfix/virtual$ sudo vim /etc/aliases# Append the following line so that root mail goes to USER@DOMAINroot: USER$ sudo newaliases# Restart postfix for changes to take effect$ sudo /etc/init.d/postfix restart
    # pass

    
def setup():
    sudo("mkdir -p %(static_path)s/menuitems")
    sudo("chmod a+w %(static_path)s/menuitems")
    sudo("mkdir -p %(releases_path)s/releases")
    if config.get('environment') == 'staging':
        sudo("ln -s /a/mealadvisor.us/staging/config/wallace.mealadvisor.us.apache /etc/apache2/sites-enabled/wallace.mealadvisor.us ")
        sudo("ln -s /a/mealadvisor.us/staging/config/wallace.mealadvisor.us.nginx /etc/nginx/sites-enabled/wallace.mealadvisor.us")
        sudo("mkdir -p /var/log/apache2/wallace.mealadvisor.us")
    else:
        sudo("ln -s /a/mealadvisor.us/staging/config/mealadvisor.us.apache /etc/apache2/sites-enabled/mealadvisor.us ")
        sudo("ln -s /a/mealadvisor.us/staging/config/mealadvisor.us.nginx /etc/nginx/sites-enabled/mealadvisor.us")
        sudo("mkdir -p /var/log/apache2/mealadvisor.us")
    # /a/mealadvisor.us/bin/easy_install django
    
def push():
    "Pushes current code to staging, hups Apache"
    # get the build number    
    local('svn up mealadvisor')
    
    config.svn_version   = svn_get_version()
    
    if not config.svn_version:
        abort()
    
    config.svn_export    = 'svn export -q -r %(svn_version)s --username davedash --password c3p0'
    
    run('mkdir %(path)s', fail='abort')
    
    # svn export mealadvisor to path 
    run('%(svn_export)s %(svn_path)s/mealadvisor %(path)s/mealadvisor', fail='abort')
    
    # svn export site-packages to site-packages
    # run('%(svn_export)s %(svn_path)s/site-packages %(path)s/site-packages', fail='abort')
    
    # svn export mealadvisor to path 
    run('%(svn_export)s %(svn_path)s/scripts %(path)s/scripts', fail='warn')
    
    # svn export configs
    run('%(svn_export)s %(svn_path)s/config %(path)s/config', fail='abort')
    
    # export /var/www/static-staging.mealadvisor.us/releases/%(svn_version) 
    run('%(svn_export)s %(svn_path)s/static %(path)s/static', fail='abort')
    
    # symlink to images from /var/www/static-staging.mealadvisor/staging/images/menuitems/* new release dir
    run("rm -r %(path)s/static/images/menuitems", fail=abort)
    run("ln -s %(static_path)s/menuitems %(path)s/static/images/menuitems", fail=abort)
    
    # upload a compressed js file to the server
    invoke(concat_minify_js)
    put('static/js/ma-min.js', '%(path)s/static/js')

    # upload a compressed css file to the server
    invoke(concat_minify_css)
    put('static/css/ma-min.css', '%(path)s/static/css')
    
    # rotate "staging" symlinks
    run('rm %(releases_path)s/staging.rollback', fail='warn')
    run('mv %(releases_path)s/staging  %(releases_path)s/staging.rollback', fail='warn')

    # staging sym to new destination
    run('ln -s %(path)s %(releases_path)s/staging', fail='abort')
        
    # server is hup'd
    invoke(hup)

def rm_cur_rev():
    config.svn_version   = svn_get_version()
    run('rm -rf %(path)s', fail='abort')

def hup():
    sudo('/etc/init.d/apache2 restart')
    invoke(hup_nginx)
    
def hup_nginx():
    sudo('/etc/init.d/nginx restart')
    
def svn_get_version():
    from subprocess import Popen, PIPE
    output = Popen(["svn", "info", "mealadvisor"], stdout=PIPE).communicate()[0]
    return output.partition('Revision: ')[2].partition('\n')[0]

def concat_minify_js():
    # concat js
    local('cat static/js/main.js static/js/home.js static/js/restaurant.js > static/js/ma-all.js', abort='fail')
    # run compressor
    # save output to ma-min.js
    local('java -jar bin/yuicompressor-2.4.2.jar -v static/js/ma-all.js -o static/js/ma-min.js', abort='fail')
    
def concat_minify_css():
    # concat js
    local('cat static/css/main.css static/css/home.css static/css/restaurant.css static/css/search.css > static/css/ma-all.css', abort='fail')
    # run compressor
    # save output to ma-min.js
    local('java -jar bin/yuicompressor-2.4.2.jar -v static/css/ma-all.css -o static/css/ma-min.css', abort='fail')

def setup_nginx():
    sudo("ln -s %(releases_path)s/staging/config/wallace.mealadvisor.us.nginx /etc/nginx/sites-enabled/wallace.mealadvisor.us")
    sudo("mkdir -p /var/log/nginx/wallace.mealadvisor.us/")
    invoke(hup_nginx)

def setup_staging():
    sudo("sudo chmod a+w menuitems ")
    sudo("svn co http://django-tagging.googlecode.com/svn/trunk/tagging /a/mealadvisor.us/lib/python2.5/site-packages/tagging")
    
# install environment
# django easy
# cmemcache apt libmemcache-dev and download and setup
# mysql    apt
# geopy easy
# markdown ei
# registration ei
# django-registration ei
# install spindrop.* svn
# django-debug-toolbar
# python openid from apt-get

# setup dbs
# ma_staging user has access to ma_staging db via:
# create user 'ma_staging' identified by 'f3nne7'
# DATABASE_PASSWORD = 'f3nne7'         
#   GRANT CREATE, ALTER, INDEX, SELECT, INSERT, UPDATE, DELETE, LOCK TABLES ON `ma_staging`.* TO 'ma_staging'@'%' 
# create the static directory

# ma_prod
# create user 'ma_prod' identified by 'y@rmul3'
# DATABASE_PASSWORD = 'y@rmul3'         
#   GRANT CREATE, ALTER, INDEX, SELECT, INSERT, UPDATE, DELETE, LOCK TABLES ON `ma_prod`.* TO 'ma_prod'@'%' 

def install_deps_local():
    local("pip install django-tagging")
