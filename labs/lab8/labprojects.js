
// $(document).ready(function() {
  
//   	$.ajax({
//    	 	type: "GET",
//    	 	url: "labprojects.json",
//    	 	dataType: "json",
//    	 	success: function(responseData, status){
//    	  	var output = "</ul>";  
//     	 	$.each(responseData.menuItem, function(i, item) {
//        		output += '<li><a href="' +item.link + '">' ;
//         	output += '<h1>' + item.title + '</h1>';  // Calling the Title of the lab page from my .json file
//         	output += '<h2>'+ item.subject + '<h2>';  // Calling the Subject of the lab page from my .json file
//         	output += '<a href="'+item.link + '"></li>' + item.link + '</a></li>';  // Calling the link of the lab page from my .json file
//       	});
//       	output += "</ul>";
//       	$('#labprojectOutput').html(output);
// 			// jQuery Button that bring you back to the older original projects page that doesnt use .json
// 			$(document).ready(function() {
//   $("#pButton").click(function() {
//     window.location.href = "/labs/labs_html_modern.html"; 
//   });
// });
//    }
// });
// })

$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "lab8/labprojects.json",
    dataType: "json",
    success: function (responseData, status) {

      var navHtml = "";
      var cardsHtml = "";

      $.each(responseData.menuItem, function (i, item) {
        // Extract lab number from title (e.g., "lab1" -> "1")
        var numOnly = item.title.replace(/\D/g, "");
        if (numOnly === "") {
          numOnly = (i + 1).toString();
        }
        var displayNum = numOnly.padStart(2, "0");    // "01"
        var displayTitle = "Lab " + numOnly;          // "Lab 1"

        // ----- Build nav item for top navigation -----
        navHtml +=
          '<li><a href="' +
          item.link +
          '" class="nav-link">' +
          displayTitle +
          "</a></li>";

        // ----- Build project card for "My Work" grid -----
        cardsHtml += '<a href="' + item.link + '" class="project-card">';
        cardsHtml += '  <div class="project-number">' + displayNum + "</div>";
        cardsHtml += "  <h4>" + displayTitle + "</h4>";
        cardsHtml += "  <p>" + item.subject + "</p>";
        cardsHtml += '  <div class="project-arrow">→</div>';
        cardsHtml += "</a>";
      });

      // Inject labs into navigation (after "Projects" link, before "About")
      // Find the "Projects" link and insert after it
      var projectsLink = $("#navMenu li").filter(function() {
        return $(this).find('a').text().trim() === 'Projects';
      });
      projectsLink.after(navHtml);

      // Inject cards into the "My Work" grid
      $("#projectsGrid").html(cardsHtml);
    },
    error: function (xhr, status, error) {
      console.error("Error loading labprojects.json:", status, error);
      $("#projectsGrid").html('<p style="text-align: center; color: #999;">Unable to load labs. Please check the console for errors.</p>');
    }
  });
});

