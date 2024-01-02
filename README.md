# blog
Installation instructions : 

1 - If git is not installed on your computer, clic the url to download and install it : https://git-scm.com/
2 - If composer is not installed on your computer, clic the url to download and install it : https://getcomposer.org/
2 - Start the git bash application
3 - In the directory of your choice on your web server, clone the github repository, 
    here is the command : git clone https://github.com/gitcmartel/blog.git
4 - Import the blog.sql script to create a mysql database named "blog"
5 - Edit the src\Lib\DatabaseConnexion.php file and complete the database connexion string with your own user and password account
6 - Launch the application
7 - You can log in as an admin user by using this account : 
    email : admintest@test.fr
    password : ?L$hEtmB&93GL38yN!