# yj_AALB2AAWP
convert Amazon Associates Link Builder shortcodes to Amazon Affiliate for WordPress (AAQP, https://aawp.de/) shortcodes in wordpress SQL db
This was necessary due to the shift from Amazon’s Product Advertising API (PA API) 4 and the discontinuing of their Affiliate Plugin for WordPress as of March 31, 2020.

- It consists of simple php code to be run via console. 
- AAWP (https://aawp.de/) is required to be installed in your WordPress instance, configured and licenced
- assumes you use wordpress with a mySQL or MariaDB SQL database
- Two separate php files in order to have more control (and be more lazy):
  - convert.php changes [amazon_link ...] shortcodes to [amazon box="... /] preserving the ASIN(s). Hence your wordpress articles will from then onwards use the AAWP shortcode. The frontent will change as AAWP uses different templates. But you may condigure them as you like, see aawp.de for more details.
  - convert2.php changes [amazon_textlink ...] shortcodes to [amazon link="... /] preserving ASIN and link text. You won't see a change in the frontend, only the source will be different
- dumps CSV files with the converted lines so you still have a log of what has happened.
- by default, only 100 appearances of the corresponding short codes are converted by one run. You may change that within the PHP onbviously if you feel comfortable. I decided 100 would be fair enough to be able to closely watch the conversion and check for mistakes or false positives. So far, I saw none when converting about 16k amazon short codes on our wordpress installation at www.pocketpc.ch  

# == NO WARRANTY ==
obviously, but still to mention: This code comes with no warranty what so ever. I saw no issues and have not run into any problems, but you are solely responsible for what you do on your wordpress. Don't blame me if you messed it up!

# == Usage Instructions ==
- MAKE A BACKUP OF YOUR WORDPRESS, ESPECIALLY THE DB!
- upload to your server (or a place that has access to the sql server). but NOT in a folder accessible from the outside world (e.g. htdocs). It is solely for you to use them, not via browser!
- change the infos in the php files (db host, username, password, db name)
- connect to the server via ssh (or telnet or whatever) or use your console if you run it locally
- run #php -f convert.php for the amazon product display boxes 
  - check the output on your terminal. check the yjsqlbackup.csv if everything looks ok
  - repeat as long as there are still hits
- run #php -f convert2.php for the amazon textlinks as long as there are still hits
  - check the output on your terminal. check the yjsqlbackup2.csv if everything looks ok
  - repeat as long as there are still hits
- remove the convert.php and convert2.php file from your server. They contain your credentilas in plain text and therefore are a security threat! I would remove the csv-files, too.
