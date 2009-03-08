def staging():
    
    "Pushes current code to staging, hups Apache"
    # get the build number    
    local('svn up mealadvisor')
    
    config.svn_version   = svn_get_version()
    
    if not config.svn_version:
        abort()
    
    config.static_path   = '/var/www/static.mealadvisor.us'
    config.svn_path      = 'http://svn.reviewsby.us/trunk'
    config.svn_export    = 'svn export -q -r %(svn_version)s --username davedash --password c3p0'
    
    run('mkdir %(path)s', fail='abort')
    
    # svn export mealadvisor to path 
    run('%(svn_export)s %(svn_path)s/mealadvisor %(path)s/mealadvisor', fail='abort')
    
    # svn export site-packages to site-packages
    run('%(svn_export)s %(svn_path)s/site-packages %(path)s/site-packages', fail='abort')
    
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
    # # grab  google analytics js
    # local('curl http://www.google-analytics.com/ga.js >> static/js/ma-min.js', abort='fail')
    # # verify the code is there
    # local('grep -q _gat static/js/ma-min.js')
    
def concat_minify_css():
    # concat js
    local('cat static/css/main.css static/css/home.css static/css/restaurant.css static/css/search.css > static/css/ma-all.css', abort='fail')
    # run compressor
    # save output to ma-min.js
    local('java -jar bin/yuicompressor-2.4.2.jar -v static/css/ma-all.css -o static/css/ma-min.css', abort='fail')

config.fab_hosts = ['mealadvisor.us']
config.fab_user = 'builder'
config.releases_path = '/var/www_apps/mealadvisor.us'
config.path          = '%(releases_path)s/releases/$(svn_version)'
