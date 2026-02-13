
var COMMENT_PLACEHOLDER = "Please enter your comments";


/* On first load: focus the first field and wire alias button */

function trimValue(v) {
   return v.trim();
}

function clearComments() {
   // Find the comments box
   var ta = document.getElementById("comments");
   // Only clear if it exactly equals the placeholder text
   if (ta.value === COMMENT_PLACEHOLDER) {
      ta.value = "";
   }
}

function restoreComments() {
   if (document.getElementById("comments").value.trim() === "")
      document.getElementById("comments").value = COMMENT_PLACEHOLDER;
}

function showAlias() {
   var first = document.getElementById("firstName").value;
   var last  = document.getElementById("lastName").value;
   var nick  = document.getElementById("nickname").value;
   alert(first + " " + last + " is " + nick);
}

/* Validation */
function validate(formObj) {
   // First Name
   if (trimValue(formObj.firstName.value) === "") {
      alert("You must enter a first name");
      formObj.firstName.focus();
            empty = true;
   }
   // Last Name
   if (trimValue(formObj.lastName.value) === "") {
      alert("You must enter a last name");
      formObj.lastName.focus();
         empty = true;
   }
   // Title
   if (trimValue(formObj.title.value) === "") {
      alert("You must enter a title");
      formObj.title.focus();
         empty = true;
   }
   // Organization
   if (trimValue(formObj.org.value) === "") {
      alert("You must enter an organization");
      formObj.org.focus();
         empty = true;
   }
   // Nickname 
   if (trimValue(formObj.nickname.value) === "") {
      alert("You must enter a nickname");
      formObj.pseudonym.focus();
         empty = true;
   }
   // Comments (not blank and not placeholder)
   var comments = trimValue(formObj.comments.value);
   if (comments === "" || comments === COMMENT_PLACEHOLDER) {
      alert("Please enter your comments");
      formObj.comments.focus();  
   }
   if (empty) {
      return false;
   }

   // Success
   alert("Success! Your form was submitted.");
   return true;
}
