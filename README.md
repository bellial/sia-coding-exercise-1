# Sports Interaction WordPress Exercise
1. [Environment Setup](#environment)
2. [WordPress Configuration](#wordpress)
3. [The Exercise](#exercise)
<a name="environment"></a>
## Environment Setup
To setup your environment:
1. Download and install Docker from [https://www.docker.com/](https://www.docker.com/)
2. Fork [https://github.com/koolkrazy/sia-coding-exercise-1](https://github.com/koolkrazy/sia-coding-exercise-1hh) to your own GitHub repo
3. Clone your new forked repo to a local folder on your computer
4. Go to the root of the cloned repo in Terminal/Command Prompt/Power Shell (you should see docker-compose.yml in the list of files)
5. Run the following command: ```docker-compose up```
6. Open a browser and go to [http://localhost:7000](http://localhost:7000) and follow the instructions to complete the WordPress database installation

**NOTE: You can use any enviroment/server setup you wish like LAMP/WAMP etc. Docker is provided as one possible solution.**

<a name="wordpress"></a>
## WordPress Configuration
1. Go to [http://localhost:7000/wp-admin/](http://localhost:7000/wp-admin/) and log-in as the administrator
2. Enable ```Sia Theme``` in the appearance settings
<a name="exercise"></a>

## The Exercise
Modify the 'Sia Theme' to add the following functionality:
* Add a custom post type called "menus". This post type will ONLY have the following fields
    * Title (default WordPress field)
    * Cuisine
    * Recipie
* Create a widget which will display "Hello World"
* Create some sample posts in the CPT
* Call Widget inside the post using shortcode.
* Create a Widget Area for the Post and Add the Widget in that
* Final Output:
    * "Hello World" Should print twice. 1st via Short Code and 2nd via Widget Inclusion

## Instructions
* Please use OOP where necessary
* Please use best security & sanitization practices where necessary
* Please use Git workflow best practices 
* Submit the url to your forked repo with the completed work for review (please ensure that the repo is public)
* Submit a backup of your working database in an .sql format
* **Please do not push anything to this repo**

