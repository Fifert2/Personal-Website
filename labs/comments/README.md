Database Table Structure for comments:

CREATE TABLE `siteComments` (
   `commentid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
   `visitor_name` VARCHAR(100) NOT NULL,
   `email` VARCHAR(200) NOT NULL,
   `comment_text` TEXT NOT NULL,
   `feature_suggestion` TEXT,
   `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `status` VARCHAR(20) NOT NULL DEFAULT 'approved',
   PRIMARY KEY (`commentid`)
);

Has a commentid for indivusal comments,
visitor_name which is the *required name section in the form
email which is another *required section on the form
comment_text which is the last *required section on the form
There is a feature_suggestion that is not required
created_at which is the timestamp at the topright of the comments when it is succeesfully submitted on the website.
status Whicih is the rating for if the comment is "approved" meaning if all the required fileds are succesfully filled out before being submitted. If they are not the comment will not bw submited but the users input will be saved until all rewuired fields are fullfilled so they can then succesfully submit there comment.


How to test the comment section:
   As listed above is the structure of my comment system for my website.
Comment Location: The comment button is located on my homepage under the News Feed section the button is called "View & Leave Comments"
How to Test It: After you click the button it will redirect you to a page that is not otherwise accesiabl on my website. As you scroll down you can see other visitors comments first. This was done also so that you can make sure you comment or suggestion (if wanted) is uniqe for example if you have a feature suggestion that was already commented there wouldnt be a reason to repeat the same suggestion.
When you finaly get to the "Your Feedback" section You will be asked to enter a Name, Email, Your Comment, and a Feature Suggestion (optional). Then finnally a "submit comment" button when you are done.

The Name Email and Your Comment all are required and have a "*" next to all of them. If you do not fill out anyone of these 3 section in any missing order you will be prompted on the right side in Red text that the missing field is required.

Specifically in the Email section you must enter an "@" in your input followed by another input after the @. IE: need a real email and not random text. 

Then Lastly you can submit and optional feature suggestion that is not required that will be dispalyed under you comment if you chose to make one.

AI Assisted Sections:

I have listed the sections that use AI already as comments in the comments.php code.

I heavily used aspects from lab 8 and more from lab 9 while also grabbing HTML ascpects like a headers, footers, etc from their respctive HTML pages.

AI assisted Sections; (Chatgpt 5.1) and brielfy used (claude) to solve a none code linking issue.
Server Side validation
Simple navigation
Comments + Form Section
jQuery Validation and Prepared. Statements




