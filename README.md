#Joomla Application Control Scripts (JACS)

##What Is JACS?
JACS provides a convenient command line method for running Joomla! CLI applications on your remote websites. Community-contributed scripts are made available in the scripts folder as they are submitted. You can then add those scripts to your websites as you desire.

##Setup Your Local Environment
The first thing necessary is to setup your local environment. This will be handled through two steps. First, you will need to download the `jacs` and `servers.sh` files from this repository. These files can be placed anywhere on your local machine. Once you have the files on your machine follow the simple two-step process below.

###Bash Installation / Implementation
The first thing you'll need to do (purely for convenience sake) is to add the `jacs` command to your local PATH. This will allow you to run `jacs` commands from your command prompt regardless of the location of your jacs file. Otherwise you will need to specify the full path each time you wish to run jacs. 

You should do this by editing your PATH variable stored in your profile and including the path to the jacs file. (Be sure to run `chmod u+x jacs` to make the file executable.)

Once you have done this step you'll need to configure your remote servers.

###Add Servers & Details
The remote server details are stored in the servers.sh file. This file should be kept in the same directory as the jacs.sh file. First, to add a server to this file simply add a new bash array like follows to the `SERVER` section of the file:

    #SERVERS
    sitealias=(
              'username@domain.com'
              '/path/to/script'
              );

Creating your servers in this format will allow you to reference a specific server simply by specifying the `sitealias`. The first string is the ssh method for your server, typically in the format of `username@domain.com` you can also add a port to this line if needed. The second string is the absolute path to where you have chosen to store your scripts. This location is typically best set to `/joomlaroot/libraries/scripts`. By using this location you will not need to modify the various CLI applications scripts submitted by the community.

Additionally, you can specify groups of servers in the `GROUPS` section of the servers.sh file. Specifying groups will allow you to run a command on a number of sites with one command.

###SSH Authentication
The SSH connection will require authentication before the commands will run. There are two ways to handle this. You can either authenticate directly each time you run a command (JACS will prompt you to enter the SSH password before it runs the commands); or you can setup your local machine with SSH authentication direct with your server through the configuration of public and private keys. 

####Configuring Direct SSH Authentication
If you want to authorize your computer to connect directly to your server without the need to enter your password each time you can do that by adding your local id_rsa.pub key to your server. Typically this can be done with this on a *Nix based machine.

        cat ~/.ssh/id_rsa.pub

Then copy the result and add it to your server's authorized-keys file. Login to your server through SSH, run the following command and paste the copied code into the file.

        nano ~/.ssh/authorized-keys
        
Your server should now allow remote SSH connections for your computer without a password being manually typed.

##Add Scripts to Remote Sites
Once you have configured your local settings you will next need to add the scripts you wish to use to the remote servers. As mentioned previously, you can place the scripts folder anywhere on the server convenient for you, however keep in mind, if choosing an alternate location (besides the one recommended above) you will most likely need to update each script to reference the correct base location for your server.

##How to Use
Using JACS is easy. Once you have setup and installed both the local and remote connections you can now run those commands on both single and groups of servers direct from your command line. Below are a few examples.

###Flags
The following are the flags available with JACS. 

*   -s = Site
*   -g = Group
*   -a = Action

###Example: Take Sites Offline
You can take a single site offline by entering the following command from your terminal

        jacs -s sitealias -a TakeOffline

You can take an entire group of sites offline by entering the following command from your terminal

        jacs -g myGroup -a TakeOffline
        
Other actions can be called just as easily. It is important to note that the action name is the same as the name of the script file located in the script directory, minus the file extension.
