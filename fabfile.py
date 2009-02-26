from subprocess import Popen, PIPE
config.fab_hosts = ['mealadvisor.us']
config.fab_user = 'builder'

def staging():
    "Pushes current code to staging, hups Apache"
    # svn up the local mealadvisor code
    info = local("svn up mealadvisor", fail='abort')
    # get the build number
    config.svn_version   = svn_get_version()

    if not config.svn_version:
        abort()
    
    config.releases_path = '/var/www_apps/mealadvisor.us'
    config.path          = '%(releases_path)s/releases/%(svn_version)s'
    config.static_path   = '/var/www/static.mealadvisor.us'
    config.svn_path      = 'http://svn.reviewsby.us/trunk'
    config.svn_export    = 'svn export -r %(svn_version)s --username davedash --password c3p0'
    
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
    run('%(svn_export)s %(svn_path)s/static %(static_path)s/releases/%(svn_version)s', fail='abort')
    
    # copy images from /var/www/static-staging.mealadvisor/staging/images/menuitems/* new release dir
    run("cp  %(static_path)s/staging/images/menuitems/* %(static_path)s/releases/%(svn_version)s/images/menuitems/", fail='warn')

    # rm "staging" symlinks
    run('rm %(releases_path)s/staging', fail='warn')
    run('rm %(static_path)s/staging', fail='warn')

    # staging sym to new destination
    run('ln -s %(path)s %(releases_path)s/staging', fail='abort')
    run('ln -s %(static_path)s/releases/%(svn_version)s %(static_path)s/staging', fail='abort')
    
    # server is hup'd
    invoke(hup)

def hup():
    sudo('/etc/init.d/apache2 restart')
    sudo('/etc/init.d/nginx restart')
    
    
def svn_get_version():
    output = Popen(["svn", "info", "mealadvisor"], stdout=PIPE).communicate()[0]
    return output.partition('Revision: ')[2].partition('\n')[0]
