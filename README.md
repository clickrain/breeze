# Click Rain Breeze #

A PHP CLI tool for managing LAMP projects within a Vagrant environment

# Introduction

Breeze is a toolset for quickly spinning up websites in a virtual development environment. Inspired by early versions of [Laravel Homestead](https://github.com/laravel/homestead), Breeze seeks to eliminate or simplify the steps needed to start development on a PHP project. It provides a Vagrant environment with pre-configured software (Ubuntu 14.04, Apache, MySQL, PHP, Git, etc) as well as single configuration file for managing all of your sites, so creating a development site is as simple as copy/pasting and running a few simple commands.

Breeze is especially helpful for maintaining websites that currently exist on your servers. Setting up an existing website in a development environment can be a pain, but Breeze automates common tasks like creating virtual hosts, replicating databases, and pulling down fresh versions of site assets. Tasks that used to take several minutes now take seconds, which means you can spend more time writing code and solving problems.

# Prerequisites

Before launching your Breeze environment, you will need to install the following software on your system:

 * [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
 * [Vagrant](https://www.vagrantup.com/downloads.html)
 * [PHP](php.net)
 * [Composer](https://getcomposer.org)

## Remote Environments

Breeze provides some tooling for "pulling" databases and files from a server into your local development environment. Breeze is opinionated about how it expects to interact with your remote environments. To use Breeze's features for syncing databases and remote files, you will need the ability to access your remote servers over SSH using an identity file for authentication. Common tools such as `rsync` and `mysqldump` are also required in your remote environments for some operations.

# Installation and Setup

Before launching your Breeze environment, you will need to install VirtualBox, Vagrant, PHP, and Composer on your system. If you are using Windows, you will need to use [Git BASH](https://git-for-windows.github.io/) or an equivalent terminal. Also, if you're using Windows, there are a few caveats to using Breeze that are [ outlined below](#appendix-windows-setup-and-notes).

### Install the Ubuntu Box

Breeze uses an Ubuntu box as it's virtualized environment. Use Vagrant to add the box:

    vagrant box add ubuntu/trusty64

### Install Breeze

Install Breeze globally using composer:

    composer global require clickrain/breeze dev-master

Make sure that composer `bin` directory is in your path:

    export PATH=~/.composer/vendor/bin:$PATH

Typing `breeze` in a terminal and hitting `ENTER` should show a list of Breeze commands. Now to initialize the Breeze configuration, run the following command:

    breeze init

This will install a directory called `.breeze` in your home directory. This is where your main `Breeze.yaml` configuration file will reside, along with some other files that Breeze will use.

### Setup Autocompletion

It's highly recommended that you setup autocompletion for Breeze. Add the following to your `.bash_profile`:

    eval $(breeze _completion --generate-hook --program breeze)

Source your `.bash_profile` file to begin using immediately:

    source ~/.bash_profile

Now typing ` breeze ` followed by the `TAB` key should provide a list of available commands and options.

> **Mac OSX Users:** You may need to configure bash completion on OSX if you haven't already. Follow instructions at https://github.com/bobthecow/git-flow-completion/wiki/Install-Bash-git-completion to get autocompletion configured correctly.

### Start your Breeze Machine

You can start your Breeze machine with `breeze up`. The first time you run this command, Breeze will provision your machine and get it ready for you to start developing!

## Configuring Breeze

When you initialize Breeze using `breeze init`, a starter configuration file `~/.breeze/Breeze.yaml` is created. You can edit this file to make changes to your Breeze machine.

Breeze also allows you to quickly edit your `Breeze.yaml` configuration file just by typing `breeze edit` in your terminal. (You may need to setup your default editor in order for this to work.)

### Example Configuration File

Below is an example breeze config file. This config file is explained in detail below:

    ip: "192.168.10.10"
    memory: 2048
    cpus: 1
    provider: virtualbox
    authorize: ~/.ssh/id_rsa.pub
    keys:
        - ~/.ssh/id_rsa

    folders:
        - map: ~/workspace
          to: /var/www/vhosts

    ports:
      - send: 9999
        to: 9999

    servers:
        - id: web1
          user: james
          host: web1.example.net
          port: 22

    sites:
        - id: example.com
          aliases: [example.dev]
          path: /var/www/vhosts/example.com
          document_root: httpdocs
          server: web1
          server_path: /var/www/vhosts/example.com
          uploads_path: public/uploads

    databases:
        - id: example
          local_name: example
          local_user: example
          local_password: secret
          remote_name: example_prod
          remote_user: example_prod
          remote_password: prodsecret
          remote_host: localhost
          remote_port: 3306
          server: web1

### VM Configuration

The following configuration items are available to configure your virtual machine.

    ip: "192.168.10.10"
    memory: 2048
    cpus: 1
    provider: virtualbox
    authorize: ~/.ssh/id_rsa.pub
    keys:
        - ~/.ssh/id_rsa

Here's a brief description of each item:

| Key           | Description                                                                         |
|---------------|:------------------------------------------------------------------------------------|
| *ip*          | Private network IP address of the machine                                           |
| *memory*      | Machine memory in MB                                                                |
| *cpus*        | Processors assigned to this machine                                                 |
| *provider*    | Vagrant provider - Breeze is currently only tested using `virtualbox`               |
| *authorize*   | This file is copied to the Breeze guest's authorized_keys for simplified SSH access |
| *keys*        | A list of keys to copy to the Breeze guest machine                                  |

### Folders

The folders config section describes how folders on the host machine are mapped to the guest (Breeze) machine.

    folders:
        - map: ~/workspace
          to: /var/www/vhosts

In the above example, the `/home/user/workspace` directory on the host machine is mapped to `/var/www/vhosts` in Breeze.

### Ports

By default, the following ports are automatically forwarded from the Breeze guest machine to your host machine:

 * **HTTP:** port 8000 &#8594; port 80
 * **HTTPS:** port 44300 &#8594; port 443
 * **MySQL:** port 33060 &#8594; port 3306

The ports config section allows you to forward additional ports from your host machine to the Breeze guest machine.

    ports:
      - send: 9999
        to: 9999

### Servers

The following is an example entry in the folders configuration section:

    servers:
        - id: web1
          user: james
          host: web1.example.net
          port: 4561

Here's a breakdown of what each item means:

| Key           | Description                                                                                                          |
|---------------|:---------------------------------------------------------------------------------------------------------------------|
| *id*          | Unique identifier for the server. You'll use this to reference this server in your sites and database configurations |
| *user*        | Name of the user that you use to SSH into this server                                                                |
| *host*        | Hostname of this server (can also be an IP address)                                                                  |
| *port*        | SSH port for this server                                                                                             |

> **Hint:** Consider using similar naming conventions for your Breeze servers as you do in your host machine's SSH config file. For example,  when searching using `breeze search <phrase>`, the generated `git clone` commands will use the Breeze server id as host identifier. If that matches your host machine's SSH configuration, cloning the repo will be a simple copy-paste from the search output.

### Sites

The following is an example sites configuration:

    sites:
        - id: example.com
          aliases: [example.dev]
          path: /var/www/vhosts/example.com
          document_root: httpdocs
          server: web1
          server_path: /var/www/vhosts/example.com
          uploads_path: public/uploads

Here's a breakdown of what each item means:

| Key             | Description                                                                                                          |
|-----------------|:---------------------------------------------------------------------------------------------------------------------|
| *id*            | Unique identifier for the site. You'll use this to reference this site from the Breeze CLI tool                      |
| *aliases*       | An array of virtual host aliases that can be used to access this site. Used to generate a virtual host file          |
| *path*          | Path on the Breeze to the site project files. Path is on the Breeze *guest machine*                                  |
| *document_root* | Document root of the site. Leave blank if the document root is the same as the *path*                                |
| *server*        | *id* of the server that this site is hosted on (if this site isn't hosted in production anywhere, this can be left alone) |
| *server_path*   | Path of the site on the the *server*                                                                                 |
| *uploads_path*  | Directory in this site that contains assets that are uploaded (and typically not version controlled)                 |

### Databases

The following is an example database configuration:

    databases:
        - id: example
          local_name: example
          local_user: example
          local_password: secret
          remote_name: example_prod
          remote_user: example_prod
          remote_password: prodsecret
          remote_host: localhost
          remote_port: 3306
          server: web1

Here's a breakdown of each configuration item:

| Key               | Description                                                                                                               |
|-------------------|:--------------------------------------------------------------------------------------------------------------------------|
| *id*              | Unique identifier for the database. You'll use this to reference this database from the Breeze CLI tool                   |
| *local_name*      | The name of the database on the Breeze guest VM                                                                           |
| *local_user*      | The username for this database on the Breeze guest VM                                                                     |
| *local_password*  | The password used to access the database on the Breeze guest VM                                                           |
| *remote_name*     | The name of the database on a remote server                                                                               |
| *remote_user*     | The username for this database on the remote server                                                                       |
| *remote_password* | The password used to access the database on the remote server                                                             |
| *remote_port*     | The port used to access the database on the remote server                                                                 |
| *server*          | *id* of the server that access to the database. Breeze will SSH into this server in order to dump the database            |

### SSH Configuration

To simplify work that may require SSH (ssh, scp, rsync, etc), you may add the following to your `~/.ssh/config` file:

    Host breeze
        HostName 127.0.0.1
            Port 2222
            KeepAlive yes
            ServerAliveInterval 60
            User vagrant

This will allow you to type `ssh breeze` and SSH directly into your machine (as long as SSH keys are setup correctly).

# Breeze Commands

The following is a list of available Breeze commands.

## Breeze Utility Commands

### init

Initialize the Breeze configuration files in `~/.breeze`

    breeze init

### edit

Edit the Breeze config file in your default editor

    breeze edit

### config:ssh

Generate an SSH config file within the Breeze guest machine based on configured servers

    breeze config:ssh

## Vagrant Commands

The following commands act as helpers to run Vagrant commands against the Breeze VM with some slight alterations.

### up

Start the Breeze machine (proxies to `vagrant up`). Optionally provision by passing the `--provision` flag.

    breeze up [--provision]

### status

Display the status of the Breeze machine (proxies to `vagrant status`)

    breeze status

### provision

Provision the Breeze machine (proxies to `vagrant provision`)

    breeze provision

### reload

Reboot the Breeze machine (proxies to `vagrant reload`)

    breeze reload

### halt

Halt the Breeze machine (proxies to `vagrant halt`)

    breeze halt

### reload

Restart the Breeze machine (proxies to `vagrant reload`)

    breeze reload

### suspend

Suspend the Breeze machine (proxies to `vagrant suspend`)

    breeze suspend

### resume

Resume a suspended Breeze machine (proxies to `vagrant resume`)

    breeze resume

### ssh

Login to the Breeze machine via SSH (proxies to `vagrant ssh`)

    breeze ssh

### destroy

This command will destroy the Breeze machine

    breeze destroy

## Site Commands

### site:create

Create and enable a virtual host for the given `<site>`.

    breeze site:create [--force] [--] <site>

If you add the option `--force`, this command will override any existing configuration file for this `<site>`.

### site:delete

Delete the virtual host file for the given `<site>`.

    breeze site:create [--force] [--] <site>

If you add the option `--purge`, this command will completely delete all files for this site <site>, both on the guest and the host, so use with caution!

### site:list

List sites that are in the Breeze config

    breeze site:list

### site:pull-rsync

Pull site files from the remote server via rsync for the given `<site>`

    breeze site:pull-git <site>

### site:sync-uploads

Pull uploads from the remote server via rsync for the given `<site>`

    breeze site:sync-uploads <site>

## Database Commands

### db:create

Create the given `<database>` on the Breeze guest VM

    breeze db:create <database>

### db:drop

Drop the given `<database>` from the Breeze guest VM

    breeze db:drop <database>

### db:dump

Dump the given `<database>` on the Breeze guest VM to output

    breeze db:dump <database>

Pass the `--remote` option to dump the remote database

### db:pull

Pull the given `<database>` from the remote server into the database on the Breeze guest VM

    breeze db:pull <database>

> Note: this will overwrite the existing database on the Breeze VM

## Web Commands

###  web:reload

Reload the Breeze Apache server

    breeze web:reload

###  web:restart

Restart the Breeze Apache server

    breeze web:restart

# Appendix: Windows Setup and Notes

## SSH Issues

There are currently some outstanding issues with Breeze on Windows that prevent it from functioning correctly. However, you can use nearly all of Breeze's features by SSH'ing directly into Breeze (either with `vagrant ssh` from the Breeze project directory or by [setting up your SSH configuration file](#ssh-configuration) and running `ssh breeze`) and then running all your Breeze commands from within that terminal.

## Composer Installation

In order to install Breeze, you will need to get composer up and running. This can be a little tricky, but it's made easier by this tool:

https://github.com/composer/windows-setup

# License

Breeze is open-source software and licensed under the MIT license.
