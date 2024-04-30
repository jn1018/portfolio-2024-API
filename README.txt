Portfolio 2024 API

Setup Instructions:


1. Extract the files to your server directory.

2. Create a database, name should be "portfolio_2024_db".

3. Import portfolio_2024_db.sql to your database. portfolio_2024_db.sql file is located on the "dev" folder.

4. Open "/api/config/database.php" file and change variables values to your database credentials.

5. If you are not setting this up in localhost, change the $home_url variable value in "/config/core.php" file.

6. I'm using a Chrome extension called "JSONView" to make the JSON data readable in the browser.
Get the extension here: https://chrome.google.com/webstore/detail/jsonview/chklaanhfefbnpoihckbnefhakgolnmc

7. I assume you work on localhost. Open your browser, put the following URL on the address bar http://localhost/api/product/read.php and hit the "enter" key.

8. If you can see the JSON data, it means you set up the API correctly.

