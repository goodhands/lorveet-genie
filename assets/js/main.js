function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
      return uri + separator + key + "=" + value;
    }
}

function setIndustry(value){
    const industryField = document.querySelector("#industry");

    industryField.value += value+"+";
}

function setFeedbackMood(mood, btnId){
    const moodField = document.querySelector("#mood");

    moodField.value = mood;

    console.log(moodField.value);
}

window.onload = function() {
    var url = window.location.href;

    //set mobile open button
    const closeMenu = document.querySelector('span[data-close-modal=menu]');
    const openMenu = document.querySelector('a[data-open=menu]');
    const menu = document.querySelector('.mobile-menu-pop');
    const searchBtnelem = document.querySelector('.btn-search');

    openMenu.addEventListener('click', function(event){
        if(window.location.href.indexOf("search") != "-1"){
            searchBtnelem.style.zIndex = "0";
            searchBtnelem.style.visibility = "hidden";
        }

        menu.style.display = "block";
        menu.style.transform = "translate(0)";
        document.querySelector("body").style.overflow = "hidden";
        event.preventDefault();
    });

    closeMenu.addEventListener('click', function(event){
        if(window.location.href.indexOf("search") != "-1"){
            searchBtnelem.style.zIndex = "1";
            searchBtnelem.style.visibility = "unset";
        }
        menu.style.transform = "translate(100%)";
        menu.style.display = "none";
        document.querySelector("body").style.overflow = "unset";
        event.preventDefault();
    });

    if(url.indexOf("search") != -1){
        //we are hiding the brand-area class on the search result page
        var brandArea = document.querySelector(".nav-area .brand-area");
        brandArea.style.display = "none";
        brandArea.style.visibility = "hidden";
    }

    //search results link {AD SETTINGS}
    const resultLinks = document.querySelectorAll("a[data-ad-result=true]");
    const adBanner = document.querySelector("div[data-banner]");
    const body = document.querySelector("body");
    const closeAd = document.querySelector(".ad_close");
    const countdownElem = document.querySelector("#countdown");
    const continueLink = document.querySelector("a[data-continue=true]");
    const searchBtn = document.querySelector(".btn-search");

    resultLinks.forEach(links => {
       links.addEventListener('click', function(event){

            searchBtn.style.zIndex = 1;
            searchBtn.style.visibility = "hidden";
            searchBtn.style.opacity = "0";

            adBanner.style.display = "block";
            body.style.overflow = "hidden";
            const resultURL = links.dataset.href;
            event.preventDefault();

            //start counter
            countdown = 5;
            var countDownHandler = setInterval(function(){
                countdown = countdown - 1;   
                countdownElem.innerHTML = countdown;
                if(countdown < 1){
                    countdownElem.innerHTML = 0;
                    return;
                }
            }, 1000);

            //target link to continue button
            continueLink.href = resultURL;

            var urlRedir = setTimeout(function(){
                window.location.href = "http://beta.lorveet.com/search"+resultURL;
            }, 5000);

            //close ad banner and stop redirect
            closeAd.addEventListener('click', function(event){
                adBanner.style.display = "none";
                body.style.overflowY = "scroll";
                searchBtn.style.display = "block";
                searchBtn.style.visibility = "unset";
                searchBtn.style.opacity = "1";
                event.preventDefault();
                clearTimeout(urlRedir);
                clearTimeout(countDownHandler);
                countdownElem.innerHTML = 5;
                return;
            });
        }) 
    });

    //feedback controller
    const feedbackBtn = document.querySelector("#feedback");
    const feedModal = document.querySelector("div[data-modal-for=feedback]");
    const closeModal = document.querySelector("span[data-modal-close=feedback]");
    const feedbackSubmit = document.querySelector("#submitFeedback");

    feedbackBtn.addEventListener('click', function(event){      
        event.preventDefault();
        feedModal.style.opacity = "1";
        feedModal.style.display = "block";
    });

    closeModal.addEventListener('click', function(){
        feedModal.style.opacity = "0";
        feedModal.style.display = "none";
    });

    feedbackSubmit.addEventListener('click', function(){
        var feed_email = document.querySelector("#email").value;
        var feed_mood = document.querySelector("#mood").value;
        var feedback_msg = document.querySelector("#feedbackText").value;
        const currURL = window.location.href;

        $.ajax({
            url: "controller/feedbackController.php", 
            type: "POST",
            data: "feedback=true&email="+feed_email+"&mood="+feed_mood+"&feedbackText="+feedback_msg+"&url="+currURL,
            success: function(result){
                $("#feedbackResponse").html(result);

                feed_email = "";
                feed_mood = "";
                feedback_msg = "";
                setTimeout(function(){
                    feedModal.style.opacity = "0";
                    feedModal.style.display = "none";
                }, 3000);
            }
        });
    });

    //suggestion buttons controller
    var suggestButtons = document.querySelectorAll('button[data-additional-search-item]');

    suggestButtons.forEach( 
    function performSearch(btn){
        btn.addEventListener('click', function(){
            var url = window.location.href; 
            btnType = btn.dataset.query.split("=");
            btnName = btnType[0].substr(1, btnType[0].length);
            btnURL = btnType[1];
            // console.log(btnType + " " + btnName);
            if(url.indexOf("page") != "-1"){
                url = url.substring(0, url.indexOf("&page"));
            }
            console.log(btnType + " " + btnName);            
            window.location.href = updateQueryStringParameter(url, btnName, btnURL);
            // console.log(url);
        });
    });

    if((window.location.href).indexOf("locale") != "-1" || (window.location.href).indexOf("company") != "-1"){
        const filterBtn = document.querySelector('#filter-clear');
    
        filterBtn.addEventListener('click', function(event){
            console.log("Filters cleared.");
            
            url = filterBtn.dataset.action;
    
            window.location.href = url;
    
            event.preventDefault();
        });
    }

    //switch logo on mobile (only show on search page)
    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    if(window.location.href.indexOf("search") != "-1" && width < 500){
        var navArea = document.querySelector(".nav-area");
            navArea.style.height = "5px";
    }
}


$(".top-sect").css("color", "red");