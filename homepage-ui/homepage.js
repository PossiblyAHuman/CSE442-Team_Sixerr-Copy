function newestChecked() {
  if (document.getElementById("Oldest").checked) {
  document.getElementById("Oldest").checked = false;
  }
  if (document.getElementById("LowestRating").checked) {
    document.getElementById("LowestRating").checked = false;
  }
  if (document.getElementById("HighestRating").checked) {
    document.getElementById("HighestRating").checked = false;
  }
}

function oldestChecked() {
  if (document.getElementById("Newest").checked) {
  document.getElementById("Newest").checked = false;
  }
  if (document.getElementById("LowestRating").checked) {
    document.getElementById("LowestRating").checked = false;
  }
  if (document.getElementById("HighestRating").checked) {
    document.getElementById("HighestRating").checked = false;
  }
}

function HighestChecked() {
  if (document.getElementById("LowestRating").checked) {
  document.getElementById("LowestRating").checked = false;
  }
  if (document.getElementById("Oldest").checked) {
    document.getElementById("Oldest").checked = false;
  }
  if (document.getElementById("Newest").checked) {
    document.getElementById("Newest").checked = false;
  }
}

function LowestChecked() {
  if (document.getElementById("HighestRating").checked) {
  document.getElementById("HighestRating").checked = false;
  }
  if (document.getElementById("Oldest").checked) {
    document.getElementById("Oldest").checked = false;
  }
  if (document.getElementById("Newest").checked) {
    document.getElementById("Newest").checked = false;
  }
}

document.getElementById("Oldest").onchange = oldestChecked;
document.getElementById("Newest").onchange = newestChecked;
document.getElementById("HighestRating").onchange = HighestChecked;
document.getElementById("LowestRating").onchange = LowestChecked;





function openFilterMenu() {
    document.getElementById("FilterMenu").style.width = "252px";
  }
  
  function closeFilterMenu() {
    document.getElementById("FilterMenu").style.width = "0px";
  }


  function openOverFlowPosts(reqType) {
    if(reqType == 'YourRequests')
    {
        document.getElementById("OverFlowYourRequests").style.width = "100%";
    }
    else
    {
        document.getElementById("OverFlowAllRequests").style.width = "100%";
    }
  }

  function closeOverFlowPosts(reqType) {
    if(reqType == 'YourRequests')
    {
        document.getElementById("OverFlowYourRequests").style.width = "0px";
    }
    else
    {
        document.getElementById("OverFlowAllRequests").style.width = "0px";
    }
  }

  /*dropdown source: w3schools */
  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
