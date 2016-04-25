# News Portal

DEMO: http://petrica.nanca.ro/~petrica/advanced/frontend/web/



News Portal application written in Yii2

-Allows registering of users

-Users are registered only if they confirm their email address

-Any user can read the news

-Only registered users can post news

-Has RSS feeds with news (latest 10 ones)

-Lists in frontend latest 10 news also

-News can be saved/viewed as PDF

-Anonymous users cannot post news






Create a txt file with the following information

1)  Steps to create and initialize the database
    
    As 'root' user (MySQL administrator) you need to run this :
    
    
    MariaDB [(none)]> create database newsportal;
    Query OK, 1 row affected (0.01 sec)


    This will create the database.

    MariaDB [(none)]> grant all privileges on newsportal.* to newsportal@localhost identified by 'yourpassword';
    Query OK, 0 rows affected (0.00 sec)
    
    The above command grants user privileges(db connectors are already set in the source code)

    MariaDB [(none)]> flush privileges;
    
    Don't forget to run this so the above would work.
    
    
    Import the database from the sql dump provided in the root of the structure. 
    
    MariaDB [(none)]>\. newsportal.sql
    
    
    
    At this stage the DB is imported.
    
    You can now remove the sql dump from the root of your structure.
    
    
    
2)  Steps to prepare the source code to run properly

    Apache is used as a web server. Suppose you are having a user account named 'username' and you already configured the public_html directory to be visible for the username
    'username' in the Apache cofigs. That means the structure from /home/username/public_html can be accessed from the browser in http://localhost/~username
    
    
    $git clone https://github.com/petrican/newsportal
    
    
    After this step you should have the 'newsportal' folder in the /home/username/public_html with its structure
    
    $cd /home/username/public_html/newsportal/advanced
    
    Install the deps with composer
    
    $composer install
    
    Wait for that to complete...
    
    
    VERY IMPORTANT: Set ownership of the assets and upload such that the apache can write to the assets and upload folders
    
    You need to do this as root
    $ su -
    Password: 
    Last login: Wed Apr 20 18:10:02 EEST 2016 on pts/11
    [root@dev ~]# chown apache:apache  /home/username/public_html/newsportal/advanced/frontend/web/assets
    [root@dev ~]# chown apache:apache  /home/username/public_html/newsportal/advanced/frontend/web/uploads
    [root@dev ~]# 

    
    
    At this stage you can access the application in browser at the following url:
    
    (default route)
    http://localhost/~username/newsportal/advanced/frontend/web/index.php?r=site%2Findex
    
    or at 
    
    http://localhost/~username/newsportal/advanced/frontend/web/
    

3)  Any assumptions made and missing requirements that are not covered in the specifications
    
    - of course any other custom setups can be made within the apache if you want to run the app from the root of url or as a vhosting but this is beyond the scope of this readme and I assume you as
    a great coder you already are familliar with.
    
    You can also instruct in .htaccess to route all requests to Yii with rewrite conditions like
    
    RewriteEngine on

    # If a directory or a file exists, use it directly
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # Otherwise forward it to index.php
    RewriteRule . index.php    
    

    Other configs: /home/username/public_html/newsportal/advanced/frontend/config/params.php
    <?php
return [

    'adminEmail' => 'petrica_nanca@yahoo.com',

    'baseUrl' => 'http://localhost',

    'appName'=>'News Portal',

    'servPath' => '/home/username/public_html'
   // NOTE: update path according to your local path
];


    // PHP Emailer settings(created a gmail account specially for the testing purpose) /home/username/public_html/advanced/frontend/config/main.php:
    // You need to create your own account and set here the username and password !!!
    // PHPMailer coniguration - Petrica Nanca <petrica_nanca@yahoo.com>

        'mail' => [

            'class'            => 'zyx\phpmailer\Mailer',

            'viewPath'         => '@common/mail',

            'useFileTransport' => false,

            'config'           => [

                'mailer'     => 'smtp',

                'host'       => 'smtp.gmail.com',

                'port'       => '465',

                'smtpsecure' => 'ssl',

                'smtpauth'   => true,

                'username'   => 'yourgmailaccountcreatedforsendingemail@gmail.com',

                'password'   => 'yourgmailpassword',

            ],

        ],
    
    








    Note: the app is set for development so if you are planning to put app into production you should set the configs for prod(disable debug aso).
    
4)  Things that can be added(TODOs):
    
    - in Article model set min number of input characters to higher value (in my tests I used it small because it allows me to test quick if it works).
    - In order to share the news on the Social Networking platforms I could add code to interract with these near the articles (in article view).
    FBLike, Facebook Share button, Google plus, share via email...aso
    
    - User should be allow to comment on articles
    
    - In article view also comments could be displayed
    
    - Article should be first pe reviewed by an administrator and only after it was reviewed to be published. I am sanitizing the output with HTMLPurifier but that doesn't also assure that 
    the quality of the article is fine so the need of human review in this kind of sites I find to be mandatory.
    
    - Emails could be sent to the user with the link to the newly created article once it was posted
    
    There a probably many more but I will just stop here.
    
    Hope you will find my application easy to use.
    
    
    
    Enjoy using it!
    
    Petrica Nanca <petrica_nanca@yahoo.com>    
    
    
    
    



