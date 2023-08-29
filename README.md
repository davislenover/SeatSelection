# SeatSelection
A web-based multi-purpose seat selection tool. The platform can be adpated such that users can quickly reserve seats for anything using unique codes

# How to Install
- Note this guide assumes you are using some hosting proivder for a website
- You will need accsess to some sort of a cPanel and FTP program *(FileZilla is used in this guide)*
  
1. Log into cPanel (or equivalent)
2. Goto MySQL Databases
3. Create a new Database (if not done already). *Take note of the database, or rather schema, name!*
4. Create however many users you desire with special ID's *(note that a prefix may be applied, this can be set to be dealt with in php files later)*. Give them all the same password
5. Add each user to the database *(including main account)*. Give all created users accsess to ```CREATE, CREATE TEMPORARY TABLES, INDEX, LOCK TABLES, SELECT, INSERT, SHOW VIEW, UPDATE```
6. If the host IP is not known, for cPanel, go back to main homepage, then goto Remote MySQL, make note of the accsess host IP
7. Log into your FTP of choice, typically, host is the shared IP address (in General Information on cPanel homepage), Username is Current User (On cPanel homepage), password and port are typically sent to you from your hosting provider via email
8. Copy/Paste all .php files into public_html (or wherever your files for your website are stored). From the images folder, place images in the root images folder (wherever images are stored on your website)
9. In cPanel, from homepage, navigate to phpMyAdmin
10. In phpMyAdmin, on the left-hand side containner, select the created database, then in the window that appears, near the bottom left, name the table to store the seat reservation info (like seatData), select the number of columns (seats) and then select go
11. Fill out all column names (seat names), set them all to VARCHAR and a length of 45, check the null box for each
12. Apply when finished, the table should now be created within the database, make note of the name
13. Open DatabaseInfo.php in FTP *(Right-Click -> View/Edit in FileZilla)*
14. When the file opens, modify the servername with the host IP in step 6, modify the default user to be the main account in step 5, if a prefix existed on user created accounts in step 4 then specify it in userPrefix, password from step 4, database is the name from step 3, tableName is from step 12, the other parameters can be left for now. Remember to re-upload/update file in FTP.
15. Using a web-broswer, navigate to ```www.<yoursite>.<com/me/ca...>/Selection.php```, verify the webpage works (i.e., try IDs, etc)
16. That's it!

# How to use (User)
1. Visit the specified website
2. Make your seat selection from the dropdown
3. Enter your unique ID provided where specified
4. Select the button to reserve your seat *(don't worry if the button does not react to your mouse click, as long as the webpage is reloading you are good!)*
5. If your seat was successfully reserved, a confirmation will appear, if not, a message will appear telling you to select a different seat

# How to use (Admin)
## Timed release
- This will disable seat selections until the specified time (24h) during the given day
1. Within the ```DatabaseInfo.php``` file *(within the ```public_html``` directory)* change ```$disableResponseUntil``` to a specified ```HH:MM:SS```
2. Save the file, this will update the live site to not accept responses until the given time
- Note that the specified time is based on the server time hosting your website
- IF ANOTHER ROUND OF RESERVATIONS ARE NEEDED, IT IS RECOMMENDED TO SET THIS TIME BEFORE CLEARING RESERVATION DATA
## Disable/Enable Service At Will
- In case one might want to outright disable selections
1. Within the ```DatabaseInfo.php``` file *(within the ```public_html``` directory)*, change ```$acceptingReservations``` to a boolean value
- ```true``` - enables reservations
- ```false``` - disables reservations
## Multiple Reservations
- Allows multiple reservations under the same ID
1. Within the ```DatabaseInfo.php``` file *(within the ```public_html``` directory)*, change ```$allowMultipleResponses``` to a boolean value
- ```true``` - enables multiple reservations
- ```false``` - disables multiple reservations

## General Notes for Admins
- To view reservations, select your created table *(under the given database)* in the left containner in phpMyAdmin, select the Browse tab in the main window.
- To clear seat reservations, select your created table *(under the given database)* in the left containner in phpMyAdmin, then select the SQL tab in the main window. From here, run the following query: ```DELETE FROM <table name here, exclude ">">;``` This will clear all reservations. *NOTE: You can also run all kinds of queries from here!*
