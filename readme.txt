The website is used for clients which are interested for parent coaching.

Clients can search in the search box for adhd, family or school issues located in table named articles

Clients can use the contact us tab to leave a message and this message and all details, mobile, email etc are saved in the submit table.

Clients can register as well by providing an email and a password this will be stored in the db and its hashed.

Once the client logs in, a dashboard is provided where the client can change the password, leave a message or update an existing one which was left before or 
delete the account completely from the database (CRUD)

The login page is equipped with login lockout to mitigate brute force attacks, the user is locked out after 5 failed attempts of inserting wrong credentials 

The search box is found in the Home, about and Register page. the search box also provides autocomplete for the categories stored in the articles table which mainly are ADD/ADHD , family dynamics and school challenges. Once the client clicks on the autocomplete this will be directed to article.php and than he can choose the categories for the respective articles. 

the changes are committed on GitHub by using the gitbash terminal 

Once the user logs in a cookie is set and its tracking when the user logs in to the dashboard and a welcome back message to the user by his email is generated. 

users

email: dlc.cachia@gmail.com 





password !AB1234ed5620/1 for all accounts




git add .
git commit -m "set cookies"
git push


