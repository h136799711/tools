<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    protected $env = 'prod';

    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('root@www.hebidu.cn:22')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('/home/repo/help')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@h1367github:h136799711/tools.git')
            // the repository branch to deploy
            ->repositoryBranch('master')
            ->symfonyEnvironment($this->env)
            ->sharedFilesAndDirs();
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {

//         $this->runLocal('./bin/phpunit');
    }

    public function beforePreparing()
    {
        $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }}');
        $this->runRemote('cp {{ deploy_dir }}/env/.env.'.$this->env.'.local {{ project_dir }}');
    }

    public function beforePublishing()
    {
        $this->runRemote('chown www:www {{ deploy_dir }}/shared/public/uploads');
        $this->runRemote('chmod 777 {{ deploy_dir }}/shared/public/uploads');
        $this->runRemote('chown -R www:www  {{ project_dir }}/var/cache');
        $this->runRemote('chown -R www:www  {{ project_dir }}/var/log');
        $this->runRemote('chmod -R 777 {{ project_dir }}/var/cache');
        $this->runRemote('chmod -R 777 {{ project_dir }}/var/log');
        $this->runRemote('composer dump-env '.$this->env);
    }

//    public function beforeUpdating()
//    {
//        $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }}');
//        parent::beforeUpdating();
//    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        $this->runRemote('/bin/lnmp php-fpm reload');
    }
};
