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

def staging():
    
    "Pushes current code to staging, hups Apache"
    # get the build number    
    local('svn up mealadvisor')
    
    config.svn_version   = svn_get_version()
    
    if not config.svn_version:
        abort()
    
    config.static_path   = '/a/static.mealadvisor.us'
    config.svn_path      = 'http://svn.reviewsby.us/trunk'
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
    run("ln -s %(static_path)s/menuitems_staging %(path)s/static/images/menuitems", fail=abort)
    
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
    
    
config.fab_hosts = ['wallace.mealadvisor.us']
config.fab_user = 'builder'
config.releases_path = '/a/mealadvisor.us'
config.path          = '%(releases_path)s/releases/$(svn_version)'
