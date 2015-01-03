last_click_time = new Date().getTime();
document.addEventListener('click', function (e) {
    click_time = e['timeStamp'];
    if (click_time && (click_time - last_click_time) < 1000) {
        e.stopImmediatePropagation();
        e.preventDefault();
        return false;
    }
    last_click_time = click_time;
}, true);


function fnHome(){
    wrapAnimation = _noAnimation;
    if(!MaraMentor.isAjaxCall)
    DashManager.GetDashboardData(1)
    NotificationManager.Notification("");
}

function fnMessages(){
	wrapAnimation = _noAnimation;
    if(!MaraMentor.isAjaxCall)
    InboxPrivateMessageManager.InboxPrivateMessage(1)

    NotificationManager.Notification("");
}

function fnMentors(){
	wrapAnimation = _noAnimation;
    if(!MaraMentor.isAjaxCall)
    MentorListManager.MentorList(1,'0')

    NotificationManager.Notification("");
}

function fnConnections(){
	wrapAnimation = _noAnimation;
    if(!MaraMentor.isAjaxCall)
    UserConnectionManager.UserConnection(1)

    NotificationManager.Notification("");
}

function fnMore(){
	wrapAnimation = _noAnimation;
    if(!MaraMentor.isAjaxCall)
    MoreScreenManager.GetMoreScreenHtml()

    NotificationManager.Notification("");
}

var pushNotification;
myScroll="";
var isPopupUp= false;
var wrapAnimation = 0;
var _backPageClicked = -1, _noAnimation = 0, _nextPageClicked = 1;
var _isAnimationRunning = false;
 
$(document).ready(function () {
    var size = 13;
    $('body').css("font-size", size + "px");
    MaraMentor.Initialize();
    document.addEventListener("showkeyboard", function() {
        focusedElement = $(document.activeElement);
        elementTagName = document.activeElement.tagName;
        /*var rect = focusedElement .getBoundingClientRect();
        //alert(rect.top);
        console.log(rect.top, rect.right, rect.bottom, rect.left);
        */
        if(MaraMentor.isLogin == "true"){
                        $("#footer").css("display", "none");
                        $("#wrapper").css("bottom", "1px");
                    }

                    $('#header').css('position', 'absolute');

                    if(elementTagName=="INPUT"){
            if (myScroll !== "") {
                setTimeout(function() {
                    myScroll.refresh();
                    myScroll.scrollToElement(focusedElement, 100);
                }, 100);
            }
        }

    }, false);
    document.addEventListener("hidekeyboard", function() {
        if(isPopupUp==true){
            $("#commentspopupwindow").css("margin-top","40px");
            isPopupUp=false;
        }
        if(MaraMentor.isLogin == "true"){
                        $("#footer").css("display", "block");
                        $("#wrapper").css("bottom", "45px");
                    }
    }, false);
});
/********** START : Popup zoomIn Function : RANA ***********/
/**
 * Function to open div as popup in ZoomIn style
 */


function scrollToTop(){
    //alert(1);
   if(myScroll!=="")
      myScroll.scrollTo(0,0,0);
}

function pullDownAction() {
    DashManager.RefreshDashboardData();
   //myScroll.refresh();
}

function appRatingPromptHandlerFunction(buttonIndex) {
    if (buttonIndex == 1) {
        //openUrl("https://play.google.com/store/apps/details?id=com.mara.maramentor", "_system");
        window.open("https://play.google.com/store/apps/details?id=com.mara.maramentor", '_system', 'location=yes');
        window.localStorage.setItem("neverRemindRatingPrompt", "true");
    }
    if (buttonIndex == 3) {
        window.localStorage.setItem("neverRemindRatingPrompt", "true");
    }
}

function onExitConfirm(button) {
    if(button==2){//If User selected No, then we just do nothing
        return;
    }else{
        navigator.app.exitApp();// Otherwise we quit the app.
    }
}

function alertDismissed() {
    // do something
}

function Logout(buttonIndex) {
    //var r = confirm("Logout from application?");
    if (buttonIndex == 2) {
        return false;
    }

    MaraMentor.backPages.length = 0;
    MaraMentor.ChangeHeaderText("Mara Mentor");
    $(".editIcon,.searchIcon").css("visibility", "hidden");
    MaraMentor.HideFooter();
    MaraMentor.displayName = "";
    MaraMentor.userImage = "";
    MaraMentor.userRole = "";
    MaraMentor.userIndustry = "";
    MaraMentor.userCountry = "";
    MaraMentor.isLogin = "false";
      NotificationManager.notificationArray= "";
    NotificationManager.notifiTotalArray=0;
    try {
        window.localStorage.setItem("isLogin", "false");
        window.localStorage.setItem("displayName", "");
        window.localStorage.setItem("sessionIdNew", "");
        window.localStorage.setItem("niceName", "");
        window.localStorage.setItem("userImage", "");
        window.localStorage.setItem("userRole", "");
        window.localStorage.setItem("userIndustry", "");
        window.localStorage.setItem("userCountry", "");
    } catch (err) {

    }
    LoginManager.SetMobileLoginHTML();
}

function openUrl(url, elem) {
    window.open(url, '_blank', 'location=yes');
}


/********** START : Popup zoomIn Function : RANA ***********/
/**
 * Function to open div as popup in ZoomIn style
 */
$.fn.zoomIn = function (duration) {
    MaraMentor.popupOpen = "true";
    var myWidth = 0,
        myHeight = 0;
    if (typeof (window.innerWidth) == 'number') {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    //window.alert( 'Width = ' + myWidth );
    //window.alert( 'Height = ' + myHeight );

    // get the element position to restore it then
    var position = this.css('position');

    // show element if it is hidden
    this.show();

    // place it so it displays as usually but hidden
    this.css({
        position: 'absolute',
        visibility: 'hidden'
    });

    // get naturally height, margin, padding
    var marginTop = this.css('margin-top');
    var marginBottom = this.css('margin-bottom');
    var paddingTop = this.css('padding-top');
    var paddingBottom = this.css('padding-bottom');
    var height = this.css('height');
    var top = this.css('top');
    var width = this.css('width');
    var left = this.css('left');


    // set initial css for animation
    this.css({
        position: position,
        visibility: 'visible',
        overflow: 'hidden',
        top: ((myHeight - (myHeight * 20) / 100)) / 2, //20 is the difference of Height required 80%-20% = 100% screensize
        left: ((myWidth - (myWidth * 20) / 100)) / 2, //20 is the difference of Width required 80%-20% = 100% screensize
        width: 0,
        height: 0,
        marginTop: 0,
        marginBottom: 0,
        paddingTop: 0,
        paddingBottom: 0
    });

    // animate to gotten height, margin and padding

    this.animate({
        top: top,
        left: left,
        width: width,
        height: height,
        marginTop: marginTop,
        marginBottom: marginBottom,
        paddingTop: paddingTop,
        paddingBottom: paddingBottom
    }, duration);

}

//Get width of screen
$.fn.width = function (duration) {
    var myWidth = 0;
    if (typeof (window.innerWidth) == 'number') {
        //Non-IE
        myWidth = window.innerWidth;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
    }

    return myWidth;
}

//Fade in Effect in Zempto
$.fn.fadeIn = function () {
    if (typeof (ms) === 'undefined') {
        ms = 500;
    }
    $(this).css({
        display: 'block',
        opacity: 0
    }).animate({
        opacity: 1
    }, ms);
    return this;
}

$.fn.popupclose = function () {
    $(this).css({
        display: 'none'
    });
    return this;
}

/********** END : Popup zoomIn Function : RANA ***********/

var MaraMentor = {
    androidVersion: 2,
    isAjaxCall:false,
    countryIsoCode:"",
    userMobileNo:"",
    isScrollPostion:false,
    scrollXposition:0,
    scrollYposition:0,
    ShowRatingPopup:'false',
    neverRemindRatingPrompt:'false',
    UserClicks:0,
    IsLoader:"true",
    IsLoadMore:'false',
    scrolltop: 1,
    popupOpen: "false",
    appRatingPromptCount: 0,
    sessionId: '',
    isLogin: 'false',
    isDashboard: 'false',
    isBack: 'false',
    serverURL: 'https://mentorapp.mara.com/wp-content/plugins/buddypress/bp-themes/bp-default/mobile_api/RequestHandler1.php',
//    serverURL: 'http://192.168.0.22/wp-content/plugins/buddypress/bp-themes/bp-default/mobile_api/RequestHandler.php',
    //serverURL: 'https://dmentor.mara.com/wp-content/plugins/buddypress/bp-themes/bp-default/mobile_api/RequestHandler.php',
    activeMenu: "",
    displayName: "",
    userImage: "",
    userRole: "",
    userCountry: "",
    userIndustry: "",
    backPages: [],
    savedSessionId: "",
    tim: "",
    popupOpen: "false",
    mentor_country_arr: "",
    mentor_industry_arr: "",
    mentor_additional_industry_arr: "",
    Initialize: function () {
        this.bindEvents();
    },
    // Bind Event Listeners
    bindEvents: function () {
        document.addEventListener('deviceready', this.onDeviceReady, false);
        document.addEventListener("online", this.onOnline, false);
        document.addEventListener("offline", this.onOffline, false);
        document.addEventListener("pause", this.onPause, false);
        document.addEventListener("resume", this.onResume, false);
        //alert("bindEvents");
    },
    // deviceready Event Handler

    onDeviceReady: function () {
        document.addEventListener("backbutton", MaraMentor.HandleBack, false);
        
        try{
            if(device.version != null)
                MaraMentor.androidVersion = device.version;
        }
        catch(err){
             MaraMentor.androidVersion = 2;
        }
        pictureSource = navigator.camera.PictureSourceType;
        destinationType = navigator.camera.DestinationType;
        
        var telephoneNumber = cordova.require("cordova/plugin/telephonenumber");
        telephoneNumber.get(function(result) {
                //console.log("result = " + result);
                //alert("result = " + result);
                MaraMentor.countryIsoCode= result;
            }, function() {
                //console.log("error");
                //alert("error");
        });
        
        telephoneNumber.getMobileNo(function(result) {
        		MaraMentor.userMobileNo= result;
       			//console.log("result = " + result);
                //alert("result = " + result);
            }, function() {
                //console.log("error");
                //alert("error");
        });

         try {
            this.savedSessionId = window.localStorage.getItem("sessionIdNew");
           
            var savedLastNotificationId = window.localStorage.getItem("lastNotificaionId");

            if(savedLastNotificationId ==null){
                NotificationManager.lastViewedNotificationId = 0;
            }
            else{
                NotificationManager.lastViewedNotificationId= parseInt(savedLastNotificationId);
            }


            var savedAppRatingPromptCount = window.localStorage.getItem("appRatingPromptCount");

            var neverRemindRatingPrompt = window.localStorage.getItem("neverRemindRatingPrompt");
			
            if (neverRemindRatingPrompt == null) {
                window.localStorage.setItem("neverRemindRatingPrompt", "false");
                neverRemindRatingPrompt = "false";
            }
            else if(neverRemindRatingPrompt != ""){
                neverRemindRatingPrompt == "false";
            }
            else{
                neverRemindRatingPrompt = savedNeverRemindRatingPrompt;
            }

            if (savedAppRatingPromptCount != null) {
                if (savedAppRatingPromptCount != "" && neverRemindRatingPrompt == "false") {
                    this.appRatingPromptCount = parseInt(window.localStorage.getItem("appRatingPromptCount"));
                    this.appRatingPromptCount = this.appRatingPromptCount + 1;
                    if (this.appRatingPromptCount > 4){
                        MaraMentor.ShowRatingPopup="true";
                        this.appRatingPromptCount = 5;
                    }
                } else {
                    this.appRatingPromptCount = 0;
                }
            } else {
                MaraMentor.ShowRatingPopup="true";
            }
        } catch (err) {
            this.savedSessionId = "";
            this.appRatingPromptCount = 0;
        }
        window.localStorage.setItem("appRatingPromptCount", this.appRatingPromptCount);
        MaraMentor.sessionId = this.savedSessionId;

        MaraMentor.sessionId = this.savedSessionId;
        MaraMentor.GetAllCountriesIndustries();
        if (this.savedSessionId != null) {
            if (this.savedSessionId != "") {
                MaraMentor.isLogin = "true";
                var isPushActivatedOnDevice=window.localStorage.getItem("pushNotification_"+this.savedSessionId);
                if(isPushActivatedOnDevice ==null || isPushActivatedOnDevice==""){

                    pushNotification = window.plugins.pushNotification;

                    pushNotification.register(
                                          pushSuccessHandler,
                                          pushErrorHandler, {
                                          "senderID":"377223944618",
                                          "ecb":"onNotificationGCM"
                                          });
                }
                document.getElementById("wrapContent").innerHTML="";
                try {
                    MaraMentor.displayName = window.localStorage.getItem("displayName");
                    MaraMentor.userImage = window.localStorage.getItem("userImage");
                    MaraMentor.userRole = window.localStorage.getItem("userRole");
                    MaraMentor.userIndustry = window.localStorage.getItem("userIndustry");
                    MaraMentor.userCountry = window.localStorage.getItem("userCountry");
                } catch (err) {

                }
                if (window.navigator.onLine) {
                    $("#loading2").show();
                    DashManager.GetDashboardData(1);
                } else {
                    try {
                        var dbDashboardContent = JSON.parse(window.localStorage.getItem("dashboardData"));
                        var dbDashboardTotal = JSON.parse(window.localStorage.getItem("dashboardTotal"));

                        if (dbDashboardTotal != "" && dbDashboardContent != "") {
                            DashManager.dashboardDataArray = dbDashboardContent;
                            DashManager.dashboardTotalArray = dbDashboardTotal;
                            DashManager.SetDashboardHTML();
                        }
                    } catch (err) {

                    }
                }
                MaraMentor.ShowHeaderFooter();
            }
            else{
               var isVerificationScreen = window.localStorage.getItem("isVerification");
            if(isVerificationScreen != null){
                if(isVerificationScreen =="true"){
                    var vLoginId = window.localStorage.getItem("vLoginId");
                    var verify = window.localStorage.getItem("verify");
                    var urole =  window.localStorage.getItem("urole");
                    MaraMentor.PushPageRecord("loginMobile");
                    MaraMentor.PushPageRecord("mobileVerify");
                    MaraMentor.ChangeHeaderText("Verify Mobile");
                    LoginManager.Bck = "signup";
                    LoginManager.confirmMobileUserPage(vLoginId,verify,urole);
                }
            }
            MaraMentor.PushPageRecord("loginMobile");
            $("#wrapContent").show();
            
            }
        } else {
           var isVerificationScreen = window.localStorage.getItem("isVerification");
            if(isVerificationScreen != null){
                if(isVerificationScreen =="true"){
                    var vLoginId = window.localStorage.getItem("vLoginId");
                    var verify = window.localStorage.getItem("verify");
                    var urole =  window.localStorage.getItem("urole");
                    MaraMentor.PushPageRecord("loginMobile");
                    MaraMentor.PushPageRecord("mobileVerify");
                    MaraMentor.ChangeHeaderText("Verify Mobile");
                    LoginManager.Bck = "signup";
                    LoginManager.confirmMobileUserPage(vLoginId,verify,urole);
                }
            }
            MaraMentor.PushPageRecord("loginMobile");
            $("#wrapContent").show();
            //MaraMentor.setupUserCountryAndMobile();
        }

    },
    setupUserCountryAndMobile:function(){
    
    	var lastLoggedInCountryCode = window.localStorage.getItem("lastLoggedInCountryCode");	
		var lastLoggedInCountryName = window.localStorage.getItem("lastLoggedInCountryName");
		
		
    
    
    	if(MaraMentor.userMobileNo!=null || MaraMentor.userMobileNo !=undefined)
    		$("#userMobile, #forget_mobile").val(MaraMentor.userMobileNo);
    	
    	var countryCode="";
    	var countryText="";
    	
    	for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
    		if(MaraMentor.mentor_country_arr[c]['iso'].toLowerCase()== MaraMentor.countryIsoCode.toLowerCase()){
    			 countryCode= MaraMentor.mentor_country_arr[c]['c_phone_prefix'];
    			 countryText= MaraMentor.mentor_country_arr[c]['country_nm'] + " (+" + MaraMentor.mentor_country_arr[c]['c_phone_prefix'] + ")";
    		}
        }
        
        if(lastLoggedInCountryCode!=null && lastLoggedInCountryCode!=undefined){
		 	if(lastLoggedInCountryCode!=""){
		 		countryCode = lastLoggedInCountryCode;
		 	}
		}
		if(lastLoggedInCountryName!=null && lastLoggedInCountryName!=undefined){
		 	if(lastLoggedInCountryName!=""){
		 		countryText = lastLoggedInCountryName;
		 	}
		}
        
        
        if(countryCode!="" && countryText !="" ){
        	$("#countrycode").val(countryCode);
        	$("#countrytext").val(countryText);
        	
        }	
       	
    },
    onOnline: function () {
        //Internet Connects Again
        //alert("Internet Connects");
        //MaraChat.connectChat();

    },
    onOffline: function () {
        //Internet Disconnects
        //alert("Internet Disnnects");
    },

    onPause: function () {
        //App Goes Background
        //console.log("pause");

    },
    onResume: function () {
        //App Again On Foreground
        //MaraChat.connectChat();
        //alert("Resume");
        //console.log("resume");

    },

    ShowLoader: function () {
        var wrapContentDiv = document.getElementById("wrapContent");
        if(MaraMentor.IsLoadMore == "false" ){
                if(wrapAnimation != _noAnimation && $("#wrapContent").css('display') != 'none'){
                    _isAnimationRunning = true;
                    wrapContentDiv.style.position = 'absolute';
                    wrapContentDiv.style.left = '0%';
                    wrapContentDiv.style.display='block';
                            
                    if (wrapAnimation ==  _backPageClicked){
                        $("#wrapContent").animate({ left:'100%'}, 400,'linear',function(){
                            wrapContentDiv.style.left = '0%';
                            wrapContentDiv.style.position = 'static';
                            wrapContentDiv.style.display='none';
                            document.getElementById("loading").style.display="block";
                            _isAnimationRunning = false;
                        });
                    }
                    else{
                        $("#wrapContent").animate({ left:'-100%'},400,'linear',function(){
                            wrapContentDiv.style.left = '0%';
                            wrapContentDiv.style.position = 'static';
                            wrapContentDiv.style.display='none';
                            document.getElementById("loading").style.display="block";
                            _isAnimationRunning = false;
                        });
                    }
                }
                else{
                   _isAnimationRunning = false;
                   wrapContentDiv.style.display='none';
                   document.getElementById("loading").style.display="block";
                }
        }
        MaraMentor.IsLoadMore ="false";
    },
    HideLoader: function () {
       document.getElementById("loading").style.display="none";
       //document.getElementById("wrapContent").style.display="block";
       if(MaraMentor.isLogin == "true"){
                $("#footer").css("display", "block");
        }
    },
    ChangePageContent: function (content, pageSource) {
    	var wrapContentDiv = document.getElementById("wrapContent");
        wrapContentDiv.innerHTML = content;
        wrapContentDiv.style.left="0%";
        wrapContentDiv.style.position="static";
        MaraMentor.RefreshScrollBar();

        if(_isAnimationRunning){
                window.setTimeout(function() {
                    if (MaraMentor.scrolltop == 1) {
                        if(myScroll!=="")
                            myScroll.scrollTo(0,0,0);
                    }
                    if(MaraMentor.isScrollPostion){
                        if(myScroll!==""){
                            setTimeout(function () {
                                myScroll.scrollTo(0,MaraMentor.scrollYposition,0);
                            }, 0);
                        }

                    }
                    wrapContentDiv.style.display="block";
                    _isAnimationRunning =false;
                    document.getElementById("loading").style.display="none";
               },400);
        }
        else{
            if (MaraMentor.scrolltop == 1) {
            if(myScroll!=="")
                myScroll.scrollTo(0,0,0);
            }
            if(MaraMentor.isScrollPostion){
                if(myScroll!==""){
                    setTimeout(function () {
                        myScroll.scrollTo(0,MaraMentor.scrollYposition,0);
                    }, 0);
                }
            }

            wrapContentDiv.style.display="block";
            _isAnimationRunning =false;
            document.getElementById("loading").style.display="none";
        }

        
        wrapAnimation = _nextPageClicked;
        
        if(MaraMentor.ShowRatingPopup=="true" && MaraMentor.isLogin == "true" && MaraMentor.UserClicks >2){
            MaraMentor.ShowAppRating();
            MaraMentor.appRatingPromptCount = 0;
            MaraMentor.ShowRatingPopup="false";
            MaraMentor.UserClicks = 0;
        }
        if(MaraMentor.isLogin=="true"){
            MaraMentor.UserClicks++;
        }
        
        DashManager.PopupClose();
        DashManager.PopupImageClose("close");
        
        MaraMentor.isScrollPostion = false;
        MaraMentor.scrolltop = 1;
    },
    showToastMsg: function (msg) {
    if(msg==""){
            msg="Sorry could not process your request, please try again later.";
     }
     navigator.notification.alert(
            msg, // message
            alertDismissed, // callback
            'Message', // title
            'Ok' // buttonName
        );

    },
    ShowAppRating: function () {
        navigator.notification.confirm(
            'Rate MaraMentor', // message
            appRatingPromptHandlerFunction, // callback to invoke
            'Help us out and rate us on app store.', // title
            ['Rate Now', 'Remind Me Later', 'No Thanks'] // buttonLabels
        );
    },
    showAlert: function (msg, header) {
        navigator.notification.alert(
            msg, // message
            alertDismissed, // callback
            'Message', // title
            'Ok' // buttonName
        );
    },
    ShowAlert: function (msg) {
        navigator.notification.alert(
            msg, // message
            alertDismissed, // callback
            'Message', // title
            'Ok' // buttonName
        );
    },
    MakeAjaxCall: function (requestUrl, requestData, requestType, successFunction, failureFunction) {
        if(MaraMentor.isAjaxCall)
            return;

        MaraMentor.isAjaxCall = true;
        this.ShowLoader();
        MaraMentor.isDashboard = "false";
        if (!requestUrl) {
            return;
        }
        if (!requestData) {
            requestData = "";
        }
       /* if (!requestType) {
            requestType = "GET";
        }*/
         requestType = "POST";

        if (!successFunction) {
            successFunction = function () {};
        }
        if (!failureFunction) {
            failureFunction = function () {};
        }
        $.ajax({
            type: requestType,
            url: requestUrl,
            data: requestData,
            cache:false,
            dataType: "text/html",
            timeout:30000,
            success: function (data, status, xhr) {
                MaraMentor.isAjaxCall = false;
                MaraMentor.HideLoader();
                successFunction(data);
            },
            error: function (xhr, errorType, error) {
                MaraMentor.HideLoader();
                MaraMentor.isAjaxCall = false;
                MaraMentor.ShowAlert("Sorry could not process your request now.", "Message");
                failureFunction(error);
                MaraMentor.IsLoadMore = "false";
            },
            complete: function (xhr, status) {
                MaraMentor.isAjaxCall = false;
                //MaraMentor.HideLoader();
                $("#loading2").hide();
            }
        });
    },
    MakeAjaxCallHTML: function (requestUrl, requestData, requestType, successFunction, failureFunction) {
        
        if(wrapAnimation != _backPageClicked)
        	wrapAnimation = _noAnimation;
        
        this.ShowLoader();
        MaraMentor.isDashboard = "false";
        if (!requestUrl) {
            return;
        }
        if (!requestData) {
            requestData = "";
        }
        //if (!requestType) {
            requestType = "GET";
        //}
          requestType = "POST";
        if (!successFunction) {
            successFunction = function () {};
        }
        if (!failureFunction) {
            failureFunction = function () {};
        }

        $.ajax({
            type: requestType,
            url: requestUrl,
            data: requestData,
            cache:false,
            dataType: "text/html",
            timeout:15000,
            success: function (data, status, xhr) {
                MaraMentor.HideLoader();
                successFunction(data);
            },
            error: function (xhr, errorType, error) {
                MaraMentor.HideLoader();
                $("#loading2").hide();
                MaraMentor.ShowAlert("Sorry could not process your request now.", "Message");
                failureFunction(error);
                MaraMentor.IsLoadMore = "false";
            },
            complete: function (xhr, status) {
               //MaraMentor.HideLoader();
            }
        });
    },
    MakeAjaxCall2: function (requestUrl, requestData, requestType, successFunction, failureFunction) {
        MaraMentor.isDashboard = "false";
        if (!window.navigator.onLine) {
            //MaraMentor.showAlert("No Internet Connection.", "Message")
            // return false;
        }
        if (!requestUrl) {
            return;
        }
        if (!requestData) {
            requestData = "";
        }
        /*if (!requestType) {
            requestType = "Get";
        }*/
        requestType = "POST";
        if (!successFunction) {
            successFunction = function () {};
        }
        if (!failureFunction) {
            failureFunction = function () {};
        }
        if (navigator.appName == "Microsoft Internet Explorer") {
            asyncString = true;
        } else {
            asyncString = true;
        }

        if (true) {

            var xmlhttp;
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    successFunction(xmlhttp.responseText);
                } else if (xmlhttp.readyState == 4 && xmlhttp.status == 0) {

                    MaraMentor.ShowAlert("Sorry could not process your request now.", "Message");
                }
            }
            xmlhttp.open("POST", requestUrl, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
            xmlhttp.send(requestData);
        }
    },
    AcitivateMenu: function (menuItem) {

        var imagePath = "assets/images/";
        var activeMenu = this.activeMenu;

        if (activeMenu != "") {
            var oldActiveImagePath = $("#" + activeMenu).attr("data-img");
            $("#" + activeMenu).attr("src", imagePath + oldActiveImagePath);
            $("#text_" + activeMenu).removeClass("active");
        }

        var newActiveImagePath = $("#" + menuItem).attr("data-img");
        $("#" + menuItem).attr("src", imagePath + "active_" + newActiveImagePath);
        $("#text_" + menuItem).addClass("active");

        this.activeMenu = menuItem;
    },
    ShowHeaderFooter: function () {
        $("#header, #footer").show();
        $(".editIcon,.searchIcon").css("visibility", "visible");
    },
    HideHeaderFooter: function () {
        $("#header, #footer").hide();
    },
    HideFooter: function () {
        $("#footer").hide();
    },
    ShowContent: function () {
        $("#wrapContent").show();
    },
    HideContent: function () {
        $("#wrapContent").hide();
    },
    
    RefreshScrollBar:function(){
       if (myScroll == "") {
            /*document.getElementById('wrapContent').addEventListener('touchmove', function (e) {
                e.preventDefault();
            }, false);*/
            document.getElementById("pullDown").style.display = "block";
            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;

            var _userTransform = false;

            if(MaraMentor.androidVersion >= 4)
                _userTransform = true;

            myScroll = new iScroll('wrapper', {
                scrollbars: false,
                useTransform: _userTransform,
                topOffset: pullDownOffset,
                onRefresh: function () {
                    if (pullDownEl.className.match('loading')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
                    }
                },
                onScrollMove: function () {
                    if (MaraMentor.isDashboard == "true") {
                        document.getElementById("pullDown").style.visibility = "visible";
                        if (this.y > 5 && !pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'flip';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Release to refresh...';
                            this.minScrollY = 0;
                        } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                            pullDownEl.className = '';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
                            this.minScrollY = -pullDownOffset;
                        }
                    } else {
                        document.getElementById("pullDown").style.visibility = "hidden";
                    }
                    //window.scrollTo(0,0);
                },
                onScrollEnd: function () {
                    if (this.y == this.maxScrollY && MaraMentor.IsLoadMore == "false") {
                           if(document.getElementById('loadMoreButton')){
                               	wrapAnimation = _noAnimation;
                                MaraMentor.scrolltop = 0;
                                MaraMentor.IsLoadMore = "true";
                                MaraMentor.IsLoader = "false";
                                document.getElementById('loadMoreButton').style.visibility="visible";
                                $('#loadMoreButton').text("");
                                $('#loadMoreButton').click();
                            }
                    }
                    else if(MaraMentor.IsLoadMore == "true"){
                        document.getElementById("pullDown").style.visibility = "hidden";
                    }

                    if (MaraMentor.isDashboard == "true") {

                        document.getElementById("pullDown").style.visibility = "visible";
                        if (pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'loading';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '';
                            pullDownAction(); // Execute custom function (ajax call?)
                        }
                    } else {
                        document.getElementById("pullDown").style.visibility = "hidden";
                    }
                }
            });
            myScroll.options.onBeforeScrollStart = function(e) {
                     var nodeType = e.explicitOriginalTarget ? e.explicitOriginalTarget.nodeName.toLowerCase():(e.target ? e.target.nodeName.toLowerCase():'');
                    if(nodeType !='select' && nodeType !='option' && nodeType !='input' && nodeType!='textarea')
                        e.preventDefault();
            }
        } else {
            setTimeout(function () {
                myScroll.refresh()
            }, 0);
        }
        $("#loading2").hide();
        MoreScreenManager.SetUpExternalLinks();
    },
    ChangeHeaderText: function (newText) {
        if (newText != "") {
            $("#headerText").text(newText);
        }

        if (MaraMentor.backPages.length > 1) {

            $("#header .searchIcon img").attr('src', 'assets/images/back.png');
            $("#header .searchIcon").attr('onclick', 'MaraMentor.HandleBack()');
            $("#header .searchIcon").css('visibility', 'visible');
        } else {

            $("#header .searchIcon img").attr('src', 'assets/images/search.png');
            $("#header .searchIcon").attr('onclick', 'GlobalSearchManager.GetGlobalSearchData(1)');
        }
    },
    confirmLogout: function () {
        navigator.notification.confirm(
            'Logout from application?', // message
            Logout, // callback to invoke with index of button pressed
            'Message', // title
            ['Yes', 'No'] // buttonLabels
        );

    },
    PushPageRecord: function (pushPage) {
        if (pushPage == "") {
            return;
        }

        var t = MaraMentor.backPages.length;
        var lastPage = MaraMentor.backPages[t - 1];
        if (t > 0) {
            if (lastPage != pushPage) {
                MaraMentor.backPages.push(pushPage);
            }
        } else {
            MaraMentor.backPages.push(pushPage);
        }
    },
    HandleBack: function () {

        if(MaraMentor.ShowRatingPopup=="true" && MaraMentor.isLogin == "true"  && MaraMentor.UserClicks >2){
            MaraMentor.ShowAppRating();
            MaraMentor.appRatingPromptCount = 0;
            MaraMentor.ShowRatingPopup="false";
        }

        var t = (MaraMentor.backPages.length - 1);
        if (t > 0) {
            MaraMentor.isBack='true';
            wrapAnimation = _backPageClicked;
            MaraMentor.backPages.pop();
            var newPage = MaraMentor.backPages[t - 1];
            switch (newPage) {
            case "dashboard":
                DashManager.SetDashboardHTML();
                break;
            case "loginMobile":
            	wrapAnimation = _noAnimation;
                LoginManager.SetMobileLoginHTML();
                break;
            case "loginEmail":
            	wrapAnimation = _noAnimation;
                LoginManager.SetMobileLoginHTML();
                wrapAnimation = _noAnimation;	
                LoginManager.SetEmailLoginHTML();
                break;
            case "forgotPassword":
            	wrapAnimation = _noAnimation;
                LoginManager.SetForgotPassHTML();
                break;
            case "signupMentor":
                wrapAnimation = _noAnimation;
                LoginManager.ShowSignUpForm(1);
                break;
            case "signupMente":
            	wrapAnimation = _noAnimation;
                LoginManager.ShowSignUpForm(2);
                break;
            case "connection":
                UserConnectionManager.UserConnection(1);
                break;
            case "profile":
                ProfileManager.SetProfileHTML();
                break;
            case "inbox":
                InboxPrivateMessageManager.InboxPrivateMessage(1);
                break;
            case "sentbox":
                SentboxPrivateMessageManager.SentboxPrivateMessage(1);
                break;
            case "composeNew":
                PrivateMessageUserListManager.PrivateMessageUserList();
                break;
            case "viewSingleMessage":
                ViewSinglePrivateMessageManager.ViewSinglePrivateMessage(ViewSinglePrivateMessageManager.var1, ViewSinglePrivateMessageManager.var2);
                break;
            case "mentorList":
                MentorListManager.MentorList(1, '0');
                break;
            case "globalSearch":
                GlobalSearchManager.GetGlobalSearchData(1);
                break;
            case "more":
                MoreScreenManager.GetMoreScreenHtml();
                break;
            case "notifications":
                NotificationManager.SetNotificationHTML();
                break;
            case "debateRoomForum":
                DebateRoom.debateRoomForums();
                break;
            case "debateRoomSingleForum":
                DebateRoom.debateRoomSingleFourm(DebateRoom.mForumID, DebateRoom.morderBy, DebateRoom.mpageNo, DebateRoom.mcurrentPage);
                break;
            case "debateRoomSingleTopic":
                DebateRoom.DebateRoomSingleTopicPage(DebateRoom.mTopicId, DebateRoom.mPageNo);
                break;
            case "globalSearch":
	            wrapAnimation = _noAnimation;
                GlobalSearchManager.GetGlobalSearchData();
                break;
            case "advMentorSearch":
	            wrapAnimation = _noAnimation;
                MentorListManager.SetAdvSrchMentorPageHTML();
                break;
            case "advMenteeSearchpage":
	            wrapAnimation = _noAnimation;
                MenteeListManager.SetAdvSrchMenteePageHTML();
                break;
            case "advMenteeSearchpageResult":
	            wrapAnimation = _noAnimation;
                MenteeListManager.SetAdvMenteeSrhRunHTML();
                break;
            case "showEditProfile":
                ShowEditProfileManager.ShowEditProfileForm();
                break;
            case "profilePicture":
                UploadProfileImageManager.UploadProfileImage();
                break;
            case "activity":
            SingleActivityManager.SetSingleActivityHTML();
            break;
            case "singleActiviyLikes":
                DashManager.SetPopupLikeUserListHTML();
                break;
            case "singleActiviyLikes_profile":
                ProfileManager.SetPopupLikeUserListHTML();
                break;
            default:
               if(MaraMentor.isLogin=="true"){
                     DashManager.SetDashboardHTML();
                }
                else{
                    LoginManager.SetMobileLoginHTML();
                }
            }
        }
        else{
            //navigator.notification.confirm("Are you sure you want to exit ?", onExitConfirm, "Confirmation", "Yes,No");
        }
    },
    GetAllCountriesIndustries: function () {

        if (MaraMentor.savedSessionId == "") {
            MaraMentor.ShowLoader();
        }
        var loginURL = this.serverURL;
        var data = "func=allCountryAndIndustry&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall2(loginURL, data, "GET", MaraMentor.AllCountriesIndustriesSuccess, MaraMentor.AllCountriesIndustriesError);
    },
    AllCountriesIndustriesSuccess: function (arg) {

        var arg = JSON.parse(arg);

        if (arg.IsSuccess == "true") {

            MaraMentor.mentor_country_arr = arg.countryContent;
            MaraMentor.mentor_industry_arr = arg.industryContent;
            MaraMentor.mentor_additional_industry_arr = arg.additionalIndustryContent;

            if (MaraMentor.savedSessionId == "") {
                MaraMentor.HideLoader();
                MaraMentor.SetupAllCountries();
            }

        } else {
            MaraMentor.showToastMsg(arg.ErrorMessage);
        }
     },
    AllCountriesIndustriesError: function (result) {
        MaraMentor.showToastMsg(arg.ErrorMessage);
    },
    SetupAllCountries: function () {
        var countries = [];
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            countries[c] = {
                id: MaraMentor.mentor_country_arr[c]['c_phone_prefix'],
                title: MaraMentor.mentor_country_arr[c]['country_nm'] + " (+" + MaraMentor.mentor_country_arr[c]['c_phone_prefix'] + ")"
            };
        }

        $('#countrytext').tinyAutocomplete({
            data: countries,
            onEscape: function (val) {
              //  alert(val);
            },
            onSelect: function (el, val) {
                if (val == null) {
                    $(this).focus();
                } else {
                    $("#countrycode").val(val.id);
                    $(this).val(val.title);
                }
            }
        });
        MaraMentor.setupUserCountryAndMobile();
    },
    SetupSignUpCountryIndustr: function () {
      var mCountries = [];
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            mCountries[c] = {
                id: MaraMentor.mentor_country_arr[c]['c_phone_prefix'],
                title: MaraMentor.mentor_country_arr[c]['country_nm'] + " (+" + MaraMentor.mentor_country_arr[c]['c_phone_prefix'] + ")"
            };
        }

        $('#countrytext').tinyAutocomplete({
            data: mCountries,
            onEscape: function (val) {
              //  alert(val);
            },
            onSelect: function (el, val) {
                if (val == null) {
                    $(this).focus();
                } else {
                    $("#countrycode").val(val.id);
                    $(this).val(val.title);
                }
            }
        });

        var aCountries = [];
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            aCountries[c] = {
                id: MaraMentor.mentor_country_arr[c]['country_nm'],
                title: MaraMentor.mentor_country_arr[c]['country_nm'],
            };
        }

        $('#acountrytext').tinyAutocomplete({
            data: aCountries,
            onEscape: function (val) {
               // alert(val);
            },
            onSelect: function (el, val) {
                if (val == null) {
                    $(this).focus();

                } else {
                    $("#country").val(val.id);
                    $(this).val(val.title);
                }
            }
        });
       /* $('#countrycode option').remove();
        var countriesCodeList = "<option value=''>Select Country</option>";
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            countriesCodeList += '<option value="' + MaraMentor.mentor_country_arr[c]['c_phone_prefix'] + '">' + MaraMentor.mentor_country_arr[c]['country_nm'] + ' ' + MaraMentor.mentor_country_arr[c]['c_phone_prefix'] + '</option>';
        }
        $("#countrycode").html($(countriesCodeList));


        $('#country option').remove();
        var countriesList = "<option value=''>Select Country</option>";
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            countriesList += '<option value="' + MaraMentor.mentor_country_arr[c]['country_nm'] + '">' + MaraMentor.mentor_country_arr[c]['country_nm'] + '</option>';
        }
        $("#country").html($(countriesList));
        */

        $('#industry option').remove();
        var industriesList = "<option value=''>Select Industry</option>";
        for (var i = 0; i < MaraMentor.mentor_industry_arr.length; i++) {
            industriesList += '<option value="' + MaraMentor.mentor_industry_arr[i]['ind_name'] + '">' + MaraMentor.mentor_industry_arr[i]['ind_label'] + '</option>';
        }
        $("#industry").html($(industriesList));
        $('#additional_industry option').remove();
        var addIndustriesList = "";
        for (var j = 0; j < MaraMentor.mentor_additional_industry_arr.length; j++) {
            addIndustriesList += '<div style="width:100%;float:left"><input style="margin-left:10px;top:-10px;position: relative;" type="checkbox" id="'+j+'" name="chk_additi_ind[]" onChange="LoginManager.signup_additional_validation(this)"  class="additional_ind" value="' + MaraMentor.mentor_additional_industry_arr[j]['addit_indus_ID'] + '" /><label for="'+j+'" style="font-size:12px;display:block;">' + MaraMentor.mentor_additional_industry_arr[j]['addit_ind_label'] + '</label></div>';
        }
        $("#replaceIndustry").html($(addIndustriesList));
    },
    setupCountriesOnly: function () {
        var aCountries = [];
        for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
            aCountries[c] = {
                id: MaraMentor.mentor_country_arr[c]['country_nm'],
                title: MaraMentor.mentor_country_arr[c]['country_nm'],
            };
        }
        $('#acountrytext').tinyAutocomplete({
            data: aCountries,
            onEscape: function (val) {
                // alert(val);
            },
            onSelect: function (el, val) {
                if (val == null) {
                    $(this).focus();

                } else {
                    $("#country").val(val.id);
                    $(this).val(val.title);
                }
            }
        });
    },
   InviteFriends: function () {
            var number ="";
            var message = "I am on Mara Mentor, an online tool to help young and ambitious entrepreneurs connect with experienced business people and peers in their industry. You can download the app from http://goo.gl/BlON9l";
            var intent = "INTENT"; //leave empty for sending sms using default intent
            var success = function () { MaraMentor.showToastMsg('Message sent successfully'); };
            var error = function (e) { MaraMentor.showToastMsg('Message Failed:' + e); };
            sms.send(number, message, intent, success, error);
    },
}




/******************** Login Data start **********************/
var LoginManager = {
    //userName:'',
    //password:'',
    Login: function (login_page) {
        //If login_page = 2 then login with mobile, else login with email
        LoginManager.userName = $("#userName").val();
        LoginManager.userMobile = $("#userMobile").val();
        LoginManager.password = $("#password").val();
        LoginManager.countrycode = $("#countrycode").val();
        LoginManager.countryname = $("#countrytext").val();
        // validation started
        if (login_page == 2) {
            if (LoginManager.countrycode == "") {
                MaraMentor.showToastMsg("Please select country code.");
                return false;
            }
            if (LoginManager.userMobile == "") {
                MaraMentor.showToastMsg("Please enter mobile number");
                return false;
            } else if (isNaN(LoginManager.userMobile) || LoginManager.userMobile.indexOf(" ") != -1) {
                MaraMentor.showToastMsg("Please enter numeric value in mobile no.");
                return false;
            } else if ((LoginManager.userMobile.length < 7) || (LoginManager.userMobile.length > 15)) {
                MaraMentor.showToastMsg("Please enter a vaild mobile no.");
                return false;
            }
        }
        if (login_page == 1) {
            if (LoginManager.userName == "") {
                MaraMentor.showToastMsg("Please enter Email ID");
                return false;
            }
        }
        if (LoginManager.password == "") {
            MaraMentor.showToastMsg("Please enter password");
            return false;
        }
        //validation end
		
		
		
		var requestData = "user=" + LoginManager.userName + "&countrycode=" + LoginManager.countrycode + "&userMobile=" + LoginManager.userMobile + "&pass=" + LoginManager.password + "&dis_page=" + login_page + "&func=login&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "GET", LoginManager.LoginSuccess, LoginManager.LoginFailure)
    },

    LoginSuccess: function (resultData) {
        var myObject = JSON.parse(resultData);
        MaraMentor.sessionId = myObject.SessionId;
        LoginManager.IsSuccess = myObject.IsSuccess;
        if (LoginManager.IsSuccess == "true") {
            MaraMentor.displayName = myObject.display_name;
            MaraMentor.userImage = myObject.loginUserImage;
            MaraMentor.userRole = myObject.loginUserRole;
            MaraMentor.userIndustry = myObject.loginUserIndustry;
            MaraMentor.userCountry = myObject.loginUserCountry;

            if (myObject.userType == "1" && myObject.loginRes == "4") {
                u_id = myObject.SessionId;
                LoginManager.SetRegisterMobileHTML(u_id, "registerMobile");
            } else {

                MaraMentor.isLogin = "true";
	


                MaraMentor.sessionId = myObject.SessionId;

                niceName = $.trim(myObject.user_nicename);

                //Save the session id in local storage
                try {
                	
                	window.localStorage.setItem("lastLoggedInCountryCode",LoginManager.countrycode);	
					window.localStorage.setItem("lastLoggedInCountryName",LoginManager.countryname);
					
                    window.localStorage.setItem("sessionIdNew", MaraMentor.sessionId);
                    window.localStorage.setItem("niceName", myObject.user_nicename);
                    window.localStorage.setItem("userImage", MaraMentor.userImage);
                    window.localStorage.setItem("displayName", MaraMentor.displayName);
                    window.localStorage.setItem("userRole", MaraMentor.userRole);
                    window.localStorage.setItem("userIndustry", MaraMentor.userIndustry);
                    window.localStorage.setItem("userCountry", MaraMentor.userCountry);
                    window.localStorage.setItem("isLogin", "true");
                    window.localStorage.setItem("dashboardData", JSON.stringify(myObject.dashboardContent));
                    window.localStorage.setItem("dashboardTotal", JSON.stringify(myObject.dashboardTotal));
                } catch (err) {

                }

                MaraMentor.ShowHeaderFooter();

                DashManager.dashboardDataArray = myObject.dashboardContent;
                DashManager.dashboardTotalArray = myObject.dashboardTotal;
                DashManager.dashboardTotalPages = parseInt(myObject.dashtotalpages);
                DashManager.SetDashboardHTML();
                var isPushActivatedOnDevice=window.localStorage.getItem("pushNotification_"+this.savedSessionId);
                if(isPushActivatedOnDevice ==null || isPushActivatedOnDevice==""){

                    pushNotification = window.plugins.pushNotification;

                    pushNotification.register(
                                          pushSuccessHandler,
                                          pushErrorHandler, {
                                          "senderID":"377223944618",
                                          "ecb":"onNotificationGCM"
                                          });
                }
            }
        } else {
            MaraMentor.showToastMsg(myObject.ErrorMessage);

            if (myObject.verfied === "0") {
                LoginManager.Bck = "signup";
                LoginManager.confirmMobileUserPage(myObject.login_id, "", myObject.urole);
            } else if (myObject.verfied === "86") {
                LoginManager.Bck = "registerMobile";
                LoginManager.confirmMobileUserPage(myObject.login_id, "", myObject.urole);
            }
        }
    },

    LoginFailure: function (resultData) {

        if (resultData.success == "true") {

        }
    },
    /************** Login with Email *****************/

    SetEmailLoginHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/loginWithEmail.html", "", "GET", LoginManager.SetEmailLoginHTMLSuccess, LoginManager.SetEmailLoginHTMLFailure);
    },

    SetEmailLoginHTMLSuccess: function (resultData) {
        MaraMentor.PushPageRecord("loginEmail");
        MaraMentor.ChangeHeaderText("Mara Mentor");
        $("#loginsection").html(resultData);
    },

    SetEmailLoginHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },
    /************** Login with Mobile *****************/

    SetMobileLoginHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/loginWithMobile.html", "", "GET", LoginManager.SetMobileLoginHTMLSuccess, LoginManager.SetMobileLoginHTMLFailure);
    },

    SetMobileLoginHTMLSuccess: function (resultData) {
        MaraMentor.backPages.length = 0;
        MaraMentor.PushPageRecord('loginMobile');
        MaraMentor.ChangeHeaderText("Mara Mentor");
        $("#header .searchIcon").css('visibility', 'hidden');
        MobileTemplate = $(resultData);//.filter("#wrapContent").html();
        MaraMentor.ChangePageContent(MobileTemplate, "mobileLogin");
        MaraMentor.SetupAllCountries();



    },

    SetMobileLoginHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },

    /************** Register Mobile No. for existing user HTML *****************/

    SetRegisterMobileHTML: function (uId, Bck) {
        MaraMentor.sessionId = uId;
        LoginManager.Bck = Bck;
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/registerMobile.html", "", "GET", LoginManager.SetRegisterMobileHTMLSuccess, LoginManager.SetRegisterMobileHTMLFailure);
    },

    SetRegisterMobileHTMLSuccess: function (resultData) {
        var myHtml = "";
        myHtml = resultData.replace(/\{\{uId\}\}/, MaraMentor.sessionId)
            .replace(/\{\{bkpage\}\}/, LoginManager.Bck)
            .replace(/\{\{login_id\}\}/, MaraMentor.sessionId);
        if (LoginManager.Bck == "editMobile") {
            MaraMentor.PushPageRecord("editMobile");
            MaraMentor.ChangeHeaderText("Edit Mobile");
        } else if (LoginManager.Bck == "registerMobile") {

            MaraMentor.PushPageRecord("registerMobile");
            MaraMentor.ChangeHeaderText("Register Mobile");
        }
        MaraMentor.ChangePageContent(myHtml);
        MaraMentor.SetupAllCountries();
        $("#wrapContent").scrollTop(0);



    },

    SetRegisterMobileHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },


    /************** Submit Register Mobile No. for existing user *****************/
    RegisterMobileSubmit: function () {
        //var isLoginPage = $("#isLogin").val("true");
        var emcountryCode = $("#countrycode").val();
        var emMobile = $("#mobile").val();
        var em_user = $("#uId").val();
        var bkpage = $("#bkpage").val();

        if (emcountryCode == "") {
            $("#countrycode").css('border', '2px solid #ff0000');
            MaraMentor.showToastMsg("Please select the country code.");
            return false;
        } else {
            $("#countrycode").css('border', '');
        }
        if (emMobile == "") {
            MaraMentor.showToastMsg("Please enter mobile no.");
            $("#mobile").css('border', '2px solid #ff0000');
            return false;
        } else if (isNaN(emMobile) || emMobile.indexOf(" ") != -1) {
            $("#mobile").css('border', '2px solid #ff0000');
            MaraMentor.showToastMsg("Please enter numeric value in mobile no.");
            return false;
        } else if ((emMobile.length < 7) || (emMobile.length > 15)) {
            MaraMentor.showToastMsg("Please enter a vaild mobile no.");
            $("#mobile").css('border', '2px solid #ff0000');
            return false;
        } else {
            $("#mobile").css('border', '');
        }
        var apiFunction = "";
        if (LoginManager.Bck == "registerMobile") {
            apiFunction = "editLoginMobile";
        }
        if (LoginManager.Bck == "editMobile") {
            apiFunction = "editLoginMobile";
        }
        var data = "emcountryCode=" + emcountryCode + "&emMobile=" + emMobile + "&em_user=" + em_user + "&bkpage=" + bkpage + "&func=" + apiFunction + "&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "GET", LoginManager.RegisterMobileSubmitSuccess, LoginManager.RegisterMobileSubmitFailure);
    },
    RegisterMobileSubmitSuccess: function (arg) {
        var arg = JSON.parse(arg);

        if (arg.IsSuccess == "true") {
            var em_user1 = arg.em_user_id;
            var em_user_key1 = arg.em_user_key;
            //var bkapge1 = arg.bkpage;
            // LoginManager.Bck = "registerMobile";
            LoginManager.confirmMobileUserPage(em_user1, em_user_key1, "");
            //olduserConfirmMobileUserPage(em_user1,em_user_key1,"",bkapge1);
            var isLoginPage = $("#isLogin").val("true");
        } else {
            MaraMentor.showToastMsg(arg.ErrorMessage);
        }
    },
    RegisterMobileSubmitFailure: function (arg) {
        MaraMentor.showToastMsg(arg.ErrorMessage);
    },
    /************** Sign Up form *****************/

    SetSignUpHTML: function (role) {
        LoginManager.role = role;
        // If role = 1 then Sign up as a Mentor, If role = 2 then Sign up as a Mentee
        //call to get the html template
        if(role == "1") {
         MaraMentor.MakeAjaxCall("views/signUp.html", "", "GET", LoginManager.SetSignUpHTMLSuccess, LoginManager.SetSignUpHTMLFailure);
        }else{
         MaraMentor.MakeAjaxCall("views/signUpMentee.html", "", "GET", LoginManager.SetSignUpHTMLSuccess, LoginManager.SetSignUpHTMLFailure);
        }
     },

    SetSignUpHTMLSuccess: function (resultData) {
        if (LoginManager.role == 1) {
            MaraMentor.PushPageRecord('signupMentor');
            MaraMentor.ChangeHeaderText("Register as Mentor");

        } else {
            MaraMentor.PushPageRecord('signupMente');
            MaraMentor.ChangeHeaderText("Register as Mentee");

        }




        MaraMentor.ChangePageContent(resultData, "signUp");
        MaraMentor.SetupSignUpCountryIndustr();


    },

    SetSignUpHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },

    SetSignupAddtionalIndustryHTML: function () {
        myScroll.scrollTo(0,0,0);
        document.getElementById("signUpFormDiv").style.display = "none";
        document.getElementById("signupSecondPage").style.display = "none";
        document.getElementById("industries").style.display = "block";
        MaraMentor.PushPageRecord('signupAdditionalIndustry');
        MaraMentor.RefreshScrollBar();
    },
    SetSignUpPageHTML: function (PageName) {
        LoginManager.pageName = PageName;
        var data = "page_type=" + PageName + "&func=StaticPageContent&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "GET", LoginManager.SetSignUpPageHTMLSuccess, LoginManager.SetSignUpPageHTMLFailure);
    },
    SetSignUpPageHTMLSuccess: function (resultData) {
        var myPage = JSON.parse(resultData);
        if (myPage.IsSuccess == "true") {
            //MaraMentor.ChangePageContent(resultData.Content);
            $('#signupSecondPage').html(myPage.Content);
            $('#signUpFormDiv').hide();
            $('#signupSecondPage').show();
            MaraMentor.PushPageRecord("staticPages");
            MaraMentor.ChangeHeaderText("Terms and conditions");
            MaraMentor.RefreshScrollBar();
        }
    },
    SetSignUpPageHTMLFailure: function (resultData) {

    },
    ShowSignUpForm:function(){

        if (LoginManager.role == 1) {
            MaraMentor.PushPageRecord('signupMentor');
            MaraMentor.ChangeHeaderText("Register as Mentor");

        } else {
            MaraMentor.PushPageRecord('signupMente');
            MaraMentor.ChangeHeaderText("Register as Mentee");

        }
        document.getElementById("signUpFormDiv").style.display = "block";
        document.getElementById("signupSecondPage").style.display = "none";
        document.getElementById("industries").style.display = "none";
        MaraMentor.RefreshScrollBar();
    },
    /************** Sign Up form Submit with validation*****************/
    SignUpSubmit: function () {
        var myfieldNameSign = new Array("fname", "lname", "user_email", "user_pass", "user_pass1", "industry", "mobile", "sex", "company", "desig", "country", "state", "twitterhan", "edu", "aboutme", "exper", "webdetail", "bloglink", "countrycode", "sex1","term","additional_ind");
        var resSign = LoginManager.signup_empty_field(myfieldNameSign);
        var resSign1 = LoginManager.signup_additional_validation(myfieldNameSign);
        if (resSign == false || resSign1 == false) {
            return;
        }
        // additional industry code start here..
          var additional_ind = $(".additional_ind");
          var additi_ind_vals = "";
          for (var i=0, n=additional_ind.length;i<n;i++) {
            if (additional_ind[i].checked)
            {
            additi_ind_vals += additional_ind[i].value+",";
            }
          }
          var additional_value = additi_ind_vals.replace(/,(?=[^,]*$)/, '');

          //end
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var user_email = $("#user_email").val();
        var user_pass = $("#user_pass").val();
        var industry = $("#industry").val();
        var mobile = $("#mobile").val();
        var sexm = $("#sex:checked").val();
        if (sexm) {
            var sex = sexm;
        }
        var sexf = $("#sex1:checked").val();
        if (sexf) {
            var sex = sexf;
        }
        var userRole1 = $("#userRole").val();
        if (LoginManager.role == "2") {
            var userRole = "mentee";
        } else {
            var userRole = "mentor";
        }
        var company1 = $("#company").val();
        if (company1) {
            var company = company1;
        } else {
            var company = "";
        }
       // var countrycode = $("#countrycode").val();
        var desig1 = $("#desig").val();
        if (desig1) {
            var desig = desig1;
        } else {
            var desig = "";
        }
        var country = $("#country").val();
       // if (LoginManager.role == "2") {
         for (var c = 0; c < MaraMentor.mentor_country_arr.length; c++) {
          if(MaraMentor.mentor_country_arr[c]['country_nm'] == country )
          {
            var countrycode = MaraMentor.mentor_country_arr[c]['c_phone_prefix'];
          }
                
         }
    // }
        var state1 = $("#state").val();
        if (state1) {
            var state = state1;
        } else {
            var state = "";
        }
        var twitterhan1 = $("#twitterhan").val();
        if (twitterhan1) {
            var twitterhan = twitterhan1;
        } else {
            var twitterhan = "";
        }
        var edu1 = $("#edu").val();
        if (edu1) {
            var edu = edu1;
        } else {
            var edu = "";
        }
        var aboutme1 = $("#aboutme").val();
        if (aboutme1) {
            var aboutme = aboutme1;
        } else {
            var aboutme = "";
        }
        var exper1 = $("#exper").val();
        if (exper1) {
            var exper = exper1;
        } else {
            var exper = "";
        }
        var webdetail1 = encodeURIComponent($("#webdetail").val());
        if (webdetail1 && webdetail1 != 'undefined') {
            var webdetail = webdetail1;
        } else {
            var webdetail = "";
        }
        var bloglink1 = $("#bloglink").val();
        if (bloglink1) {
            var bloglink = bloglink1;
        } else {
            var bloglink = "";
        }
         if (LoginManager.role == "2") {
         var data = "fname=" + fname + "&lname=" + '' + "&industry=" + industry + "&user_email=" + user_email + "&user_pass=" + user_pass + "&countrycode=" + countrycode + "&mobile=" + mobile + "&sex=" + sex + "&company=" + company + "&desig=" + desig + "&country=" + country + "&state=" + state + "&twitterhan=" + twitterhan + "&edu=" + edu + "&aboutme=" + aboutme + "&exper=" + exper + "&webdetail=" + webdetail + "&bloglink=" + bloglink + "&Urole=" + userRole +"&func=addSignUpData&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
         }
         else
         {
          var data = "fname=" + fname + "&lname=" + lname + "&industry=" + industry + "&user_email=" + user_email + "&user_pass=" + user_pass + "&countrycode=" + countrycode + "&mobile=" + mobile + "&sex=" + sex + "&company=" + company + "&desig=" + desig + "&country=" + country + "&state=" + state + "&twitterhan=" + twitterhan + "&edu=" + edu + "&aboutme=" + aboutme + "&exper=" + exper + "&webdetail=" + webdetail + "&bloglink=" + bloglink + "&Urole=" + userRole + "&additionIndustry="+additional_value+"&func=addSignUpData&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
         }
        
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", LoginManager.SignUpSubmitSuccess, LoginManager.SignUpSubmitFailure);
    },
    SignUpSubmitSuccess: function (returnData) {
        LoginManager.Bck = "signup";
        var arg = JSON.parse(returnData);
        // $("#isLogin").val("true");
        if (arg.IsSuccess == "true") {
            LoginManager.confirmMobileUserPage(arg.lastId, arg.userKey, arg.userRole);
        } else {
            MaraMentor.showToastMsg(arg.ErrorMessage);
        }
    },
    SignUpSubmitFailure: function (returnData) {
        var arg = JSON.parse(returnData);
        MaraMentor.showToastMsg(arg.ErrorMessage);
    },
    /******************** Sign up validation *****************/
    signup_empty_field: function (myfieldName) {
        var errormsg =''
        var phoneno = /^\d{10}$/;
        //var emailRegEx = /^([a-zA-Z0-9])([a-zA-Z0-9\._-])*@(([a-zA-Z0-9])+(\.))+([a-zA-Z]{2,4})+$/ ;
        //var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var emailRegEx = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
        var fname = document.getElementById(myfieldName[0]).value;
        if (LoginManager.role != "2") {
         var lname = document.getElementById(myfieldName[1]).value;
    }
        var user_email = document.getElementById(myfieldName[2]).value;
        var user_pass = document.getElementById(myfieldName[3]).value;
        var user_pass1 = document.getElementById(myfieldName[4]).value;
        var industry = document.getElementById(myfieldName[5]).value;
        var mobile = document.getElementById(myfieldName[6]).value;
        var sex = document.getElementById(myfieldName[7]).value;
        var country = document.getElementById(myfieldName[10]).value;
        if (LoginManager.role != "2") {
         // var countrycode = document.getElementById(myfieldName[18]).value;
            var aboutme = document.getElementById(myfieldName[14]).value;
     }
        var sex1 = document.getElementById(myfieldName[19]).value;
    
  var term = document.getElementById(myfieldName[20]);
        var flag = true;
        if (term.checked) {
   $("#term").css('border', '');
        } else {
           errormsg = "Please select terms and conditions.";
            $("#term").css('border', '2px solid #ff0000');
            flag = false;
        }
        if (fname == '') {
            var errormsg = "";
            errormsg = "Please don't leave required(*) field empty.";
            $("#fname").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#fname").css('border', '');
        }
        if (lname == '') {
            errormsg = "Please don't leave required(*) field empty.";
            $("#lname").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#lname").css('border', '');
        }
        if (user_email == '') {
            errormsg = "Please enter email address.";
            $("#user_email").css('border', '2px solid #ff0000');
            flag = false;
        } else if (user_email.search(emailRegEx) == -1 && user_email != '') {
            errormsg = "Please check your email address.";
            $("#user_email").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#user_email").css('border', '');
        }
        if (user_pass == '') {
            errormsg = "Please don't leave required(*) field empty.";
            $("#user_pass").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#user_pass").css('border', '');
        }
        if (user_pass1 == '') {
            errormsg = "Please don't leave required(*) field empty.";
            $("#user_pass1").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#user_pass1").css('border', '');
        }
        if (user_pass.length < 6 ) {
            errormsg = "Password should not be less than 6 characters";
            $("#user_pass").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#user_pass").css('border', '');
        }
        if (user_pass != user_pass1) {
            errormsg = "Confirm password does not match.";
            $("#user_pass1").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#user_pass1").css('border', '');
        }
        if (industry == "") {
            errormsg = "Please don't leave required(*) field empty.";
            $("#industry").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#industry").css('border', '');
        }
        if (mobile == '') {
            errormsg = "Please don't leave required(*) field empty.";
            $("#mobile").css('border', '2px solid #ff0000');
            flag = false;
        } else if (isNaN(mobile) || mobile.indexOf(" ") != -1) {
            errormsg = "Please enter numeric value in mobile field.";
            $("#mobile").css('border', '2px solid #ff0000');
            flag = false;
        } else if ((mobile.length < 7) || (mobile.length > 15)) {
            errormsg = "Please enter valid mobile no.";
            $("#mobile").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#mobile").css('border', '');
        }
        /*if (countrycode == "") {
            errormsg = "Please select country code";
            $("#countrycode").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#countrycode").css('border', '');
        }*/
        if (country == "") {
            errormsg = "Please select country";
            $("#country").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#country").css('border', '');
        }
        if (aboutme == '') {
            errormsg = "Please don't leave required(*) field empty.";
            $("#aboutme").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#aboutme").css('border', '');
        }
        /*if(additional_ind >5)
  {
   errormsg = "You can choose maximum 5 additional industries.";
            $("#additional_industry").css('border', '2px solid #ff0000');
            flag = false;
  }
  else {
            $("#additional_industry").css('border', '');
        }*/
        
        if (!flag) {
            MaraMentor.showToastMsg(errormsg);
        }
        return flag;
    },

    signup_additional_validation: function (elem) {

        var additional_ind = $(".additional_ind:checked").length;
        var flag1 = true;
        if(additional_ind >5)
        {
            elem.checked = false;
            errormsg = "You can choose maximum 5 additional industries.";
            //$("#additional_industry").css('border', '2px solid #ff0000');
            flag1 = false;
        }
        /*else {
            $("#additional_industry").css('border', '');
        }*/
        if (!flag1) {
            MaraMentor.showToastMsg(errormsg);
        }
        return flag1;
    },


    /******************** Confirmation for mobile *******************/
    confirmMobileUserPage: function (login_id, verify, urole) {
        LoginManager.login_id = login_id;
        LoginManager.verify = verify;
        LoginManager.urole = urole;


        window.localStorage.setItem("isVerification", "true");
        window.localStorage.setItem("vLoginId", login_id);
        window.localStorage.setItem("verify", verify);
        window.localStorage.setItem("urole", urole);


        MaraMentor.MakeAjaxCallHTML("views/SMSverification.html", "", "GET", LoginManager.confirmMobileUserPageSuccess, LoginManager.confirmMobileUserPageFailure);
    },
    confirmMobileUserPageSuccess: function (response) {
        // MaraMentor.ChangePageContent(response);
        var myHtml = "";
        myHtml = response.replace(/\{\{urole\}\}/, LoginManager.urole)
            .replace(/\{\{login_id\}\}/, LoginManager.login_id);

        MaraMentor.ChangePageContent(myHtml);
        if (MaraMentor.tim) {
            clearInterval(MaraMentor.tim);
        }
        var amount = 60;
        var i = 1;

        //login_id1 = login_id;
        MaraMentor.tim = setInterval(function () {

            if (i === amount) {
                clearInterval(MaraMentor.tim);
                $("#counterMessage").text("Not recieved the message ?");
                $("#counterButton").html("Click Here");
                // $("#counterButton").click(LoginManager.sendVoiceCall(LoginManager.login_id));
                $("#counterButton").on("click", LoginManager.sendVoiceCall);
                $("#counterFAQ").show();
                return;
            }
            amount = amount - 1;
            //console.log(amount);
            $("#counterButton").html(amount + " Seconds");
        }, 1000);
    },
    confirmMobileUserPageFailure: function (response) {
        MaraMentor.showToastMsg(response);
    },
    sendVoiceCall: function () {
        var data = "loginId=" + LoginManager.login_id + "&func=VoiceUserKey&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", LoginManager.sendVoiceCallSuccessFunction, LoginManager.sendVoiceCallFailureFunction);

    },
    sendVoiceCallSuccessFunction: function (arg) {
        //   $("#isLogin").val("true");
        // $("#loading").hide();
    },
    sendVoiceCallFailureFunction: function (arg) {
        //$("#isLogin").val("true");
        //$("#loading").hide();
        MaraMentor.showToastMsg("Some error occured, please try again later.");
    },
    confirmUser: function (con_login_id) {
        // var loginURL = getServerUrl();
        // var isLoginPage = $("#isLogin").val("true");
        var ukey = $("#ukey").val();
        var urole = $("#urole").val();

        if (ukey == "") {
            MaraMentor.showToastMsg("Please enter verification code.");
            return false;
        }
        var apiFunction = "";
        if (LoginManager.Bck == "signup") {
            apiFunction = "confirmUser";
        } else if (LoginManager.Bck == "registerMobile") {
            apiFunction = "olduserConfirmUser";
        } else if (LoginManager.Bck == "editMobile") {
            apiFunction = "olduserConfirmUser";
        }
        var data = "loginId=" + con_login_id + "&urole=" + urole + "&ukey=" + ukey + "&func=" + apiFunction + "&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", LoginManager.confirmUserSuccessFunction, LoginManager.confirmUserFailurFunction);
    },
    confirmUserSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        //  var isLoginPage = $("#isLogin").val("true");
        if (arg.IsSuccess == "true") //)
        {
            //$('#sessionId').val(arg.confId);
            MaraMentor.sessionId = arg.confId;
            //MaraMentor.isLogin = "true";
            if (LoginManager.Bck == "signup" || LoginManager.Bck == "registerMobile") {
                if (LoginManager.urole == "mentor") {
                    LoginManager.SetMobileLoginHTML();
                } else {
                    $("#loading2").show();
                     $("#wrapContent").html("");
                    MaraMentor.isLogin = "true";

                    MaraMentor.ShowRatingPopup="true";

                    MaraMentor.IsLoadMore = "false";
                    MaraMentor.isAjaxCall = false;
                    MaraMentor.ShowLoader();

                    window.localStorage.setItem("isLogin", "true");
                    window.localStorage.setItem("isVerification", "false");
                    window.localStorage.setItem("sessionIdNew", MaraMentor.sessionId);
                    MaraMentor.isAjaxCall = false;
                    DashManager.GetDashboardData(1);

                    var isPushActivatedOnDevice=window.localStorage.getItem("pushNotification_"+MaraMentor.sessionId);
                    if(isPushActivatedOnDevice ==null || isPushActivatedOnDevice==""){

                        pushNotification = window.plugins.pushNotification;

                        pushNotification.register(
                                              pushSuccessHandler,
                                              pushErrorHandler, {
                                              "senderID":"377223944618",
                                              "ecb":"onNotificationGCM"
                                              });
                    }


                }
            } else {
                window.localStorage.setItem("isVerification", "false");
                MoreScreenManager.GetMoreScreenHtml();

            }

        } else {
            MaraMentor.showToastMsg(arg.ErrorMessage);
        }
    },
    confirmUserFailurFunction: function (arg) {
        MaraMentor.showToastMsg(arg.ErrorMessage);
    },

    /******************** Forgot Password HTML *******************/
    SetForgotPassHTML: function (arg) {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/forget_pass.html", "", "GET", LoginManager.SetForgotPassHTMLSuccess, LoginManager.SetForgotPassHTMLFailure);
    },
    SetForgotPassHTMLSuccess: function (resultData) {
        MaraMentor.PushPageRecord('forgotPassword');
        MaraMentor.ChangeHeaderText("Forgot Password");
        MaraMentor.ChangePageContent(resultData, "forgotPwd");
        MaraMentor.SetupAllCountries();
    },

    SetForgotPassHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },
    /*************** Show hide email and mobile number section ***********/
    get_forget_password_field_option: function (id) {
        if (id == "email_click") {
            $("#verf_type").val("email");
            $("#pass_verf_fields_email").css("display", "block");
            $("#pass_verf_fields_mobile").css("display", "none");
        }
        if (id == "mobile_click") {
            $("#verf_type").val("mobile");
            $("#pass_verf_fields_email").css("display", "none");
            $("#pass_verf_fields_mobile").css("display", "block");
        }
    },
    mentor_forget_password: function () {
        _loginPage = "false";

        //var emailRegEx =/^([a-zA-Z0-9])([a-zA-Z0-9\._-])*@(([a-zA-Z0-9])+(\.))+([a-zA-Z]{2,4})+$/ ;
        var emailRegEx = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
        var verf_type = $("#verf_type").val();
        if (verf_type == "") {
            MaraMentor.showToastMsg("Please enter the Email Address or Mobile No for Your Account Verification.");
            return false;
        } else {
            if (verf_type == "email") {
                var email = $("#forget_email").val();
                if (email == "") {
                    MaraMentor.showToastMsg("Please enter the email address");
                    $("#forget_email").css('border', '2px solid #ff0000');
                    return false;
                }

                if (email.search(emailRegEx) == -1 && email != '') {
                    MaraMentor.showToastMsg("Please enter valid email address");
                    return false;
                }
                $("#forget_email").css('');
                var data = "field=" + email + "&type=" + verf_type + "&func=forget_password&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
                MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", LoginManager.forgetPasswordSuccess, LoginManager.forgetPasswordFailure);

            } //end of if email
            else {
                var err = '';
                var countryCode = $("#countrycode").val();
                var MobileNo = $("#forget_mobile").val();

                if (countryCode == "") {
                    err += 'Please select country.';
                    $("#countrycode").css('border', '2px solid #ff0000');
                } else {
                    $("#countrycode").css('border', '');
                }
                if (MobileNo == '') {
                    err += 'Please enter the mobile no.';
                    $("#forget_mobile").css('border', '2px solid #ff0000');
                } else {
                    if (isNaN(MobileNo) || MobileNo.indexOf(" ") != -1) {
                        err += 'Please enter only numeric mobile no.';
                        $("#forget_mobile").css('border', '2px solid #ff0000');
                    }
                    if ((MobileNo.length < 1) || (MobileNo.length > 15)) {
                        err += 'Maximum 15 digits allowed in mobile no.';
                        $("#forget_mobile").css('border', '2px solid #ff0000');
                    }
                }


                if (err != "") {
                    MaraMentor.showToastMsg(err);
                    return false;
                } else {
                    $("#countrycode").css('border', '');
                    $("#forget_mobile").css('border', '');

                    var mob = countryCode + "-" + MobileNo;
                    var data = "field=" + mob + "&type=" + verf_type + "&func=forget_password&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
                    MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", LoginManager.forgetPasswordSuccess, LoginManager.forgetPasswordFailure);
                }
            } //end of else part
        } //end of else part if verification type is null

    }, //end of function

    forgetPasswordSuccess: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            //$("#loading").hide();
            MaraMentor.showToastMsg(arg.SuccessMessage);
            LoginManager.SetMobileLoginHTML();
        } else {
            MaraMentor.showToastMsg(arg.ErrorMessage);
        }
    },
    forgetPasswordFailure: function (arg) {
        MaraMentor.showToastMsg(arg.ErrorMessage);
    },
}
/******************** Login Data end **********************/


/******************** Dashboard Data start **********************/
var DashManager = {
    dashboardDataArray: [],
    pageNo: 1,
    RefreshDashboardData: function () {
        lastActId = DashManager.dashboardDataArray[0]["itemId"];
        var requestData = "u_id=" + MaraMentor.sessionId + "&last_activity_id=" + lastActId + "&func=dashboardLatestActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall2(MaraMentor.serverURL, requestData, "GET", DashManager.RefreshDashboardDataSuccess, DashManager.RefreshDashboardDataError);
    },
    RefreshDashboardDataSuccess: function (resultData) {
        var myObjectdashboard = JSON.parse(resultData);
        //alert(JSON.stringify(resultData));
        if (DashManager.dashboardDataArray != "") {
            DashManager.dashboardDataArray = DashManager.dashboardDataArray.concat(myObjectdashboard.dashboardContent);
            DashManager.dashboardTotalArray = DashManager.dashboardTotalArray + parseInt(myObjectdashboard.dashboardTotal);
            //DashManager.dashboardTotalPages = parseInt(myObjectdashboard.dashtotalpages);
            //alert(1);
        } else {
            DashManager.dashboardDataArray = DashManager.dashboardDataArray.concat(myObjectdashboard.dashboardContent);
            DashManager.dashboardTotalArray = DashManager.dashboardTotalArray + parseInt(myObjectdashboard.dashboardTotal);
            //DashManager.dashboardTotalPages = parseInt(myObjectdashboard.dashtotalpages);
            //alert(2);
        }
        //alert(DashManager.dashboardTotalArray);
        DashManager.SetDashboardHTML();


        try {
            window.localStorage.setItem("dashboardData", JSON.stringify(myObjectdashboard.dashboardContent));
            window.localStorage.setItem("dashboardTotal", JSON.stringify(myObjectdashboard.dashboardTotal));
        } catch (err) {

        }
    },
    RefreshDashboardDataError: function (resultData) {

    },
    GetDashboardData: function (pageNo) {
        DashManager.pageNo = pageNo;
        var requestData = "u_id=" + MaraMentor.sessionId + "&pageNo=" + DashManager.pageNo + "&func=dashboard&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "GET", DashManager.GetDashboardDataSuccess, DashManager.GetDashboardDataError);
        /*setTimeout(function () {
            NotificationManager.Notification("");
        }, 100);*/

    },

    GetDashboardDataSuccess: function (resultData) {
        var myObjectdashboard = JSON.parse(resultData);
        if (DashManager.pageNo == 1) {
            DashManager.dashboardDataArray = myObjectdashboard.dashboardContent;
            DashManager.dashboardTotalArray = parseInt(myObjectdashboard.dashboardTotal);
            DashManager.dashboardTotalPages = parseInt(myObjectdashboard.dashtotalpages);
        } else {
            if (DashManager.dashboardDataArray != "") {
                DashManager.dashboardDataArray = DashManager.dashboardDataArray.concat(myObjectdashboard.dashboardContent);
                DashManager.dashboardTotalArray = DashManager.dashboardTotalArray + parseInt(myObjectdashboard.dashboardTotal);
                DashManager.dashboardTotalPages = parseInt(myObjectdashboard.dashtotalpages);
            }
        }
        // used in case of signup
        MaraMentor.displayName = myObjectdashboard.display_name;
        MaraMentor.userImage = myObjectdashboard.loginUserImage;
        MaraMentor.userRole = myObjectdashboard.loginUserRole;
        MaraMentor.userIndustry = myObjectdashboard.loginUserIndustry;
        MaraMentor.userCountry = myObjectdashboard.loginUserCountry;

         try {
                    window.localStorage.setItem("userImage", MaraMentor.userImage);
                    window.localStorage.setItem("displayName", MaraMentor.displayName);
                    window.localStorage.setItem("userRole", MaraMentor.userRole);
                    window.localStorage.setItem("userIndustry", MaraMentor.userIndustry);
                    window.localStorage.setItem("userCountry", MaraMentor.userCountry);
                    window.localStorage.setItem("isLogin", "true");
                } catch (err) {

                }

        DashManager.SetDashboardHTML();
        try {
            window.localStorage.setItem("dashboardData", JSON.stringify(myObjectdashboard.dashboardContent));
            window.localStorage.setItem("dashboardTotal", JSON.stringify(myObjectdashboard.dashboardTotal));
        } catch (err) {

        }
    },

    GetDashboardDataError: function (resultData) {


    },

    SetDashboardHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/dashboard.html", "", "GET", DashManager.SetDashboardHTMLSuccess, DashManager.SetDashboardHTMLFailure);
    },

    SetDashboardHTMLSuccess: function (result) {

        MaraMentor.AcitivateMenu("home");

        MaraMentor.backPages.length = 0;
        MaraMentor.PushPageRecord('dashboard');
        MaraMentor.ChangeHeaderText("Dashboard");
        MaraMentor.ShowHeaderFooter();

        var dashboardDataArraySet = new Array();
        var dashboardPostStatusDiv = $(result).filter("#postStatus").html();
        dashTemplate = $(result).filter("#updates").html();

        fragmentDash = '';
        sessionId = LoginManager.loginUserId;

        for (var d = 0, len = DashManager.dashboardTotalArray; d < len; d++) {
            var likeActivity = "";
            var likeImage = "";
            var postLikeUnlike = "";
            var deleteImage = "";
            var twitterImage = "";
            //var isContinue= "false";
            if (DashManager.dashboardDataArray[d]["user_id"] == MaraMentor.sessionId) {
                deleteImage = "<img src='assets/images/delete.png' onclick='DeleteMyActivityManager.DeleteMyActivity(" + DashManager.dashboardDataArray[d]["itemId"] + "," + DashManager.dashboardDataArray[d]["user_id"] + "," + d + ",\"dashboard\")' />"
            }
            if (DashManager.dashboardDataArray[d]["type"] == "activity_liked") {
                continue;
            }
            if (DashManager.dashboardDataArray[d]["type"] == "twitter") {
                twitterImage += "<img src='assets/images/twitter.png' />"
            }
            if (DashManager.dashboardDataArray[d]["isLiked"] == "0") {
                likeActivity = "Like";
                likeImage = "assets/images/like.png";
                postLikeUnlike = "PostLikeManager.PostLike";
            } else {
                likeActivity = "Unlike"
                likeImage = "assets/images/unlike.png";
                postLikeUnlike = "PostLikeManager.PostLike";
            }
            if (DashManager.dashboardDataArray[d]["contwrap1"]) {
                var dashboardContent = DashManager.dashboardDataArray[d]["contwrap1"];
            } else {
                var dashboardContent = "";
            }

             var TotalLikeShow = "";

             TotalLikeShow='<span onclick="DashManager.PopupLikeUserList('+ DashManager.dashboardDataArray[d]["itemId"] + ')" id="likeCount_'+DashManager.dashboardDataArray[d]["itemId"]+'" >'+DashManager.dashboardDataArray[d]["totalLike"]+' Likes</span>';
             try{
                var imgurl = $(dashboardContent).find(".image-popup-no-margins");
                imgurl = $(imgurl[0]).prop("src");
             }
             catch(e){

             }
             var activityClick = "SingleActivityManager.SingleActivityData("+DashManager.dashboardDataArray[d]["itemId"]+","+d+",'dashboard')";

           if(imgurl!=undefined){
             activityClick = "DashManager.PopupImage('"+imgurl+"')";
           }



            fragmentDash += dashTemplate.replace(/\{\{likeImage\}\}/, likeImage)
                .replace(/\{\{totalLikes\}\}/, DashManager.dashboardDataArray[d]["totalLike"])
                .replace(/\{\{totalComment\}\}/, DashManager.dashboardDataArray[d]["totalComment"])
                .replace(/\{\{userImage\}\}/, DashManager.dashboardDataArray[d]["userImage"])
                .replace(/\{\{user_fullname\}\}/, DashManager.dashboardDataArray[d]["user_fullname"])
                .replace(/\{\{resAction\}\}/, DashManager.dashboardDataArray[d]["resAction"])
                .replace(/\{\{contwrap1\}\}/, dashboardContent)
                .replace(/\{\{resDateTime1\}\}/, DashManager.dashboardDataArray[d]["resDateTime1"])
                .replace(/\{\{itemId\}\}/g, DashManager.dashboardDataArray[d]["itemId"])
                .replace(/\{\{itemIndex\}\}/g, d)
                .replace(/\{\{postLikeUnlike\}\}/g, postLikeUnlike)
                .replace(/\{\{likeActivity\}\}/, likeActivity)
                .replace(/\{\{deleteImage\}\}/, deleteImage)
                .replace(/\{\{totalLikeShow\}\}/, TotalLikeShow)
                .replace(/\{\{activityClick\}\}/, activityClick)
                .replace(/\{\{twitter\}\}/, twitterImage);

        } //for loop
        var loadMoreDiv = "";
        if (DashManager.dashboardTotalPages > DashManager.pageNo) {
            DashManager.pageNo = DashManager.pageNo + 1;
            loadMoreDiv = "<div class='loadMore' id='loadMoreButton' onclick='DashManager.GetDashboardData(DashManager.pageNo)'></div>";

        }
        MaraMentor.IsLoadMore ="false";
        MaraMentor.ChangePageContent(dashboardPostStatusDiv + fragmentDash + loadMoreDiv);
        MaraMentor.isDashboard = "true";
        // For each loop on data and html
    },
    //START: ImagePopup
    PopupImageClose: function( ) {
        //document.getElementById('imagespopupwindow').style.display='none';
        $('#imagespopupwindow').popupclose();
        //document.getElementById('imagespopupwindow_black_overlay').style.display='none';
        if(MaraMentor.isLogin=="true"){
        }
        $( '#imagespopupwindow_black_overlay' ).hide();
    },
    PopupImage: function( imageSource ) {
        $("#imagespopupwindow_black_overlay").show();
        $('#imagespopupwindow').css({'text-align' :  'center'});
        $("#imagespopupwindow" ).html( "<a href = 'javascript:void(0)'  align='absmiddle' ><img class='imagePopup' id='imagePopup' src='" + imageSource + "' width='100%'></a>");
        $('#imagespopupwindow').show();
        //var windowHeight = $(document).height();
        //$("#imagespopupwindow_black_overlay").css("height",windowHeight+"px");

        var windowHeight1 = $(window).height();
        $('#imagespopupwindow').css("height",(windowHeight1*.9)+"px");

    },
    //START: Popup comments window

    //Close popup window
    PopupClose: function( ) {
        document.getElementById('commentspopupwindow').style.display='none';
        $('.overlay').hide();
        //myScroll = new IScroll('#wrapper', { mouseWheel: true, scrollbars: true });
    },

    //Open and populate comments window
    PopupComments: function(topic_Id, itemIndex, itemSource) {

        //Popout the window
        //$('#commentspopupwindow').zoomIn(500);
        $('#commentspopupwindow').zoomIn(500);
        $('.overlay').show();
        $( "#commentspopupwindow" ).html( "<div style='margin-top:44%;margin-left:44%;'><img src='assets/images/loading1.gif'></div>");

    //***********************************
        //Store parameters
        DashManager.itemIndex = itemIndex;
        DashManager.itemSource = itemSource
        SingleActivityManager.itemIndex = itemIndex;
        SingleActivityManager.itemSource = itemSource;
        var topicId = topic_Id;
        var userId = MaraMentor.sessionId;
        var requestData = "topicId=" + topicId + "&userId=" + userId + "&func=singleActivityDetail&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";

        //Make ajax call for singleActivityDetail to get activity data
        MaraMentor.MakeAjaxCall2(MaraMentor.serverURL, requestData, "POST", DashManager.PopupActivityDataSuccess, DashManager.PopupActivityDataError)
    },

    //on success: singleActivityDetail ajax we get response as JSON
   PopupActivityDataSuccess: function (resultData) {
       var myObjectSingle = JSON.parse(resultData); // Store response to object
       DashManager.singleDataArray = myObjectSingle.singleRes;                  //Main Activity Data
       DashManager.singleChildTotalArray = myObjectSingle.singleChildTotal;     //Total number of comments
       DashManager.singleChildArray = myObjectSingle.singleRes.child;           //Child elements

       SingleActivityManager.singleDataArray = myObjectSingle.singleRes;                  //Main Activity Data
       SingleActivityManager.singleChildTotalArray = myObjectSingle.singleChildTotal;     //Total number of comments
       SingleActivityManager.singleChildArray = myObjectSingle.singleRes.child;           //Child elements


      // if (DashManager.singleChildTotalArray > 0) {
           //Populate the popup
           DashManager.SetSingleActivityPopupHTML();
       //}
   },

   //on failure of singleActivityDetail ajax call
   PopupActivityDataError: function (resultData) {

   },

   SetSingleActivityPopupHTML: function () {
       //call to get the html template
       MaraMentor.MakeAjaxCallHTML("views/comment_popup.html", "", "GET", DashManager.SetSingleActivityPopupHTMLSuccess, DashManager.SetSingleActivityPopupHTMLFailure);
   },

   SetSingleActivityPopupHTMLSuccess: function (result) {
       /*MaraMentor.PushPageRecord('activity');
       MaraMentor.ChangeHeaderText('Activity');
       */
       SingleActivityTemplate = result;
       mainSingleActivity = $(SingleActivityTemplate).filter("#main_activity");
       childActivity = $(SingleActivityTemplate).filter("#child_activity");
       commenTemplate = $(SingleActivityTemplate).find("#commenTemplate");

       mainSingleActivity = mainSingleActivity.html();
       childActivity = childActivity.html();
       commenTemplate = commenTemplate.html();
       // var live_str = $('<div>',{html:SingleActivityTemplate});
       // var example = live_str.find('#commenTemplate').html();

       sessionId = LoginManager.loginUserId;
       var mainCont = ""
       /******************* inner content start..***/
       if (DashManager.singleDataArray["main_type"] == "new_member") {
           mainCont = "Became a registered member";
       } else if (DashManager.singleDataArray['main_type'] == 'new_avatar') {
           mainCont = "Changed their profile picture";
       } else if (DashManager.singleDataArray['main_type'] == 'twitter') {
           mainCont = '<div class="tweet">';
           mainCont += DashManager.singleDataArray["contwrap1"];
           mainCont += '</div>';

       } else {
           mainCont = '<div >';
           mainCont += DashManager.singleDataArray["contwrap1"];
           mainCont += '</div>';
       }
       /*********end******************/
       var likeActivity = "";
       var likeImage = "";

       if (DashManager.singleDataArray["isLiked"] == "0") {
           likeActivity = "Like";
           likeImage = "assets/images/like.png";
           postLikeUnlike = "PostLikeManager.PostLike";
       } else {
           likeActivity = "Unlike";
           likeImage = "assets/images/unlike.png";
           postLikeUnlike = "PostLikeManager.PostLike";
       }
       mainSingleActivity = mainSingleActivity.replace(/\{\{itemId\}\}/g, DashManager.singleDataArray["main_id"])
           .replace(/\{\{main_user_id\}\}/, DashManager.singleDataArray["main_user_id"])
           .replace(/\{\{main_type\}\}/, DashManager.singleDataArray["main_type"])
           .replace(/\{\{main_content\}\}/, DashManager.singleDataArray["main_content"])
           .replace(/\{\{main_primary_link\}\}/, DashManager.singleDataArray["main_primary_link"])
           .replace(/\{\{resDateTime\}\}/, DashManager.singleDataArray["resDateTime"])
           .replace(/\{\{userImage\}\}/, DashManager.singleDataArray["userImage"])
           .replace(/\{\{user_fullname\}\}/, DashManager.singleDataArray["user_fullname"])
           .replace(/\{\{upload_dir\}\}/, DashManager.singleDataArray["upload_dir"])
           .replace(/\{\{totalLike\}\}/, DashManager.singleDataArray["totalLike"])
           .replace(/\{\{likeActivity\}\}/, likeActivity)
           .replace(/\{\{likeImage\}\}/, likeImage)
           .replace(/\{\{totalComment\}\}/, DashManager.singleDataArray["totalComment"])
           .replace(/\{\{mainSingleCont\}\}/, mainCont)
           .replace(/\{\{likeTotal\}\}/, DashManager.singleDataArray["likeTotal"])
           .replace(/\{\{contwrap1\}\}/, DashManager.singleDataArray["contwrap1"])
           .replace(/\{\{itemIndex\}\}/g, DashManager.itemIndex)
           .replace(/\{\{itemSource\}\}/g, DashManager.itemSource)
           .replace(/\{\{postLikeUnlike\}\}/g, postLikeUnlike);


       var fragmentSingleActivityChild = '';
       for (var s = 0, lens = DashManager.singleChildTotalArray; s < lens; s++) {
           var deleteText = "";
           var deleteFunction = "";

           var editText = "";
           var editFunction = "";

           var likeActivity = "";
           var likeImage = "";
           var likeType = "";
           if (DashManager.singleChildArray[s]["comment_like_button"] == "like") {
               commentLikeActivity = "Like";
               commentLikeType = "like";
               //likeImage = "assets/images/like.png";
               //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'like')";
           } else {
               commentLikeActivity = "Unlike";
               commentLikeType = "unlike";
               //likeImage = "assets/images/unlike.png";
               //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'unlike')";
           }

           if (MaraMentor.sessionId == DashManager.singleChildArray[s]["user_id"]) {
               deleteText = "Delete";
               deleteFunction = "DeletePostCommentManager.DeletePostComment(" + DashManager.singleChildArray[s]["id"] + "," + DashManager.singleDataArray["main_id"] + ")";

               editText = "Edit";
               editFunction = "EditPostCommentManager.EditPostCommentHtml(" + DashManager.singleChildArray[s]["id"] + "," + DashManager.singleDataArray["main_id"] + ")";
           }
           fragmentSingleActivityChild += childActivity.replace(/\{\{commentId\}\}/g, DashManager.singleChildArray[s]["id"])
               .replace(/\{\{commenterId\}\}/g, MaraMentor.sessionId)
               .replace(/\{\{component\}\}/, DashManager.singleChildArray[s]["component"])
               .replace(/\{\{type\}\}/g, DashManager.singleChildArray[s]["type"])
               .replace(/\{\{content\}\}/, DashManager.singleChildArray[s]["content"])
               .replace(/\{\{userImage\}\}/, DashManager.singleChildArray[s]["userImage"])
               .replace(/\{\{userName\}\}/, DashManager.singleChildArray[s]["userName"])
               .replace(/\{\{contwrapChild\}\}/, DashManager.singleChildArray[s]["contwrapChild"])
               .replace(/\{\{resDateTimeChild\}\}/, DashManager.singleChildArray[s]['resDateTimeChild'])
               .replace(/\{\{totalCommentLikes\}\}/, DashManager.singleChildArray[s]['total_comment_likes'])
               .replace(/\{\{deleteText\}\}/, deleteText)
               .replace(/\{\{deleteFunction\}\}/, deleteFunction)
               .replace(/\{\{editText\}\}/, editText)
               .replace(/\{\{editFunction\}\}/, editFunction)
               .replace(/\{\{likeType\}\}/, commentLikeType)
               .replace(/\{\{commentLikeActivity\}\}/, commentLikeActivity);;

       }
       var t_text = $( "#commentspopupwindow" ).html();
       //width = $("#commentspopupwindow").width() + 50;

       postComment = '<div class="popupSinglePost">'
                    + ' <div class="Field" style="overflow:hidden;position:absolute">'
                    + '     <input type="text" id="postComment" placeholder="Write Your Comment">'
                    + ' </div>'
                    + ' <div class="btnPost right" onclick="PopupPostCommentManager.PostComment('+DashManager.singleDataArray["main_id"]+', \'' + DashManager.itemSource + '\')">Post</div>'
                    + '     <div class="clear">'
                    + ' </div>'
                    + '</div>'
                    + '<div id="commenTemplate" style="display:none" >'
                    + commenTemplate
                    + '</div>';





        if(DashManager.singleDataArray["totalLike"] > 1) {
         if (DashManager.singleDataArray["isLiked"] != "0") {
            DashManager.ownlike = " You and <a href = 'javascript:void(0)' onclick='DashManager.PopupLikeUserList("+DashManager.singleDataArray["main_id"]+")'>"+(DashManager.singleDataArray["totalLike"]- 1) +" people </a> like this.";
         } else
         {
            DashManager.ownlike = "<a href = 'javascript:void(0)' onclick='DashManager.PopupLikeUserList("+DashManager.singleDataArray["main_id"]+")'>"+DashManager.singleDataArray["totalLike"]+" people </a> like this.";
         }
        } else if(DashManager.singleDataArray["totalLike"]  == 1) {

       if (DashManager.singleDataArray["isLiked"] != "0") {
            DashManager.ownlike = " You like this.";
         } else
         {
            DashManager.ownlike = "<a href = 'javascript:void(0)' onclick='DashManager.PopupLikeUserList("+DashManager.singleDataArray["main_id"]+")'>"+DashManager.singleDataArray["totalLike"]+" people </a> like this.";
         }
        }
        else
        {
            DashManager.ownlike = DashManager.singleDataArray["totalLike"]+" people </a> like this.";
        }
       if(fragmentSingleActivityChild==""){
        fragmentSingleActivityChild="<div id='noComments' style='text-align:center; font-weight:bold'>No Comments</div>"
       }
       allActivity = "<span><h2 class='nospace peoLikes'>"+DashManager.ownlike+"</h2></span><span class='colsepopup'> <a href = 'javascript:void(0)' onclick='DashManager.PopupClose()'>Done</a></span>" +
                      "<div id='commentsDiv'>" + fragmentSingleActivityChild +  "</div>" + postComment;

       $('#commentspopupwindow').css({'text-align' :  ''});
       $( "#commentspopupwindow" ).html( allActivity );
       $("#commentsDiv").html(fragmentSingleActivityChild).fadeIn();


       //Popout the window
       // $('#commentspopupwindow').zoomIn(500);

       //MaraMentor.ChangePageContent(allActivity);
       // For each loop on data and html
   },

   SetSingleActivityPopupHTMLFailure: function () {

   },


    //END: Popup comments window
     //START: Popup like window

    //Close popup window

    //Open and populate comments window
    PopupLikeUserList: function(item_id) {

        //Popout the window
        //$('#commentspopupwindow').zoomIn(500);

    //***********************************
        //Store parameters
        DashManager.item_id = item_id;
        var user_id = MaraMentor.sessionId;
        var requestData = "login_id="+user_id+"&item_id="+DashManager.item_id+ "&func=getUserLikeList&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";

       MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DashManager.PopupLikeUserListSuccess, DashManager.PopupLikeUserListFailure);
    },

    //on success: singleActivityDetail ajax we get response as JSON
   PopupLikeUserListSuccess: function (resultData) {
        var myObjectLikeUserList = JSON.parse(resultData);
        DashManager.likeListArray = myObjectLikeUserList.likeListArray;
        DashManager.TotalLikeArray = myObjectLikeUserList.TotalLikeArray;
        DashManager.item_id = myObjectLikeUserList.item_id;



        /*SingleActivityManager.singleDataArray = myObjectLikeUserList.singleRes;
        SingleActivityManager.singleChildTotalArray = myObjectLikeUserList.singleChildTotal;
        SingleActivityManager.singleChildArray = myObjectLikeUserList.singleRes.child;*/

        DashManager.SetPopupLikeUserListHTML();
   },
   PopupLikeUserListFailure: function (resultData) {

   },

   SetPopupLikeUserListHTML: function () {
       //call to get the html template
       MaraMentor.MakeAjaxCallHTML("views/like_user_list.html", "", "GET", DashManager.SetPopupLikeUserListHTMLSuccess, DashManager.SetPopupLikeUserListHTMLFailure);
   },

    SetPopupLikeUserListHTMLSuccess: function (result) {
       messageTemplate = result;
        MaraMentor.AcitivateMenu("home");
       // MaraMentor.backPages.length = 0;
       // MaraMentor.PushPageRecord('inbox');
     //   MaraMentor.ChangeHeaderText('View Like');
        headingWrap = $(messageTemplate).filter("#headingWrap");
        ViewLikeWrap = $(messageTemplate).filter("#ViewLikeWrap");
        ViewLikeWrap = ViewLikeWrap.html();
        headingWrap = headingWrap.html();
        var ViewLikeWrap1 = "";
        DashManager.ownlike = "";
        DashManager.totallike = "";


        DashManager.ownlike = "<a href = 'javascript:void(0)' onclick='DashManager.PopupLikeUserList("+DashManager.item_id+")'>"+DashManager.TotalLikeArray+" people </a> like this.";


        for (var v = 0,lenm=DashManager.TotalLikeArray; v<lenm; v++)
        {

            ViewLikeWrap1 += ViewLikeWrap.replace(/\{\{likeUserID\}\}/,DashManager.likeListArray[v]['likeUserID'])
            .replace(/\{\{likeUserName\}\}/,DashManager.likeListArray[v]['likeUserName'])
            .replace(/\{\{likeUserImage\}\}/,DashManager.likeListArray[v]['likeUserImage']);


        }

     if(DashManager.TotalLikeArray ==0 ){
                ViewLikeWrap1="<div>No likes for this post.</div>"
            }

       MaraMentor.PushPageRecord("singleActiviyLikes");
       width = 700;

       allLike = "<div class='upDation'><h2 class='nospace peoLikes'>People who liked this post </h2>" +
                  "<div id='commentsDiv' style='clear:both; height: 74%; width: " + width +"; overflow: auto;'>"+ViewLikeWrap1 +  "</div></div>";
        MaraMentor.ChangePageContent(allLike);
        MaraMentor.isScrollPostion= true;
        MaraMentor.scrolltop = 0;
        MaraMentor.ChangeHeaderText("Activity Likes");

       //Popout the window
      //  $('#commentspopupwindow').zoomIn(500);

       //MaraMentor.ChangePageContent(allActivity);
       // For each loop on data and html
   },

   SetPopupLikeUserListHTMLFailure: function () {

   },
    //END: Popup like window
    SetDashboardHTMLFailure: function () {

    }

}
/******************** Dashboard Data end **********************/

/******************** Profile Data start **********************/
var ProfileManager = {
    pageNo: 1,
    Profile: function (profile_id, p_page) {
        this.pageNo = p_page;
        ProfileManager.user_id = MaraMentor.sessionId;
        var requestData = "m_id=" + profile_id + "&u_id=" + ProfileManager.user_id + "&p_page=" + this.pageNo + "&func=userprofile&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ProfileManager.ProfileSuccess, ProfileManager.ProfileFailure)
    },
    ProfileSuccess: function (resultData) {
        var myObjectProfile = JSON.parse(resultData);
        ProfileManager.profileId = myObjectProfile.profileId;
        //ProfileManager.profileDataArray = myObjectProfile.ProfileContent;

        //var data1 = JSON.parse(resultData);
        var fullProfileJson = JSON.stringify(myObjectProfile.ProfileContent).replace(/null/g, '""');
        ProfileManager.profileDataArray = JSON.parse(fullProfileJson);

        if (ProfileManager.pageNo > 1 && ProfileManager.profileActivityArray != "") {

            ProfileManager.profileActivityArray = ProfileManager.profileActivityArray.concat(myObjectProfile.ProfileActivityContent);
            ProfileManager.profileTotalArray = ProfileManager.profileTotalArray + parseInt(myObjectProfile.ProfileActivityContTotal);
            ProfileManager.totalPages = parseInt(myObjectProfile.proTotalpages);

        } else {

            ProfileManager.profileActivityArray = myObjectProfile.ProfileActivityContent;
            ProfileManager.profileTotalArray = parseInt(myObjectProfile.ProfileActivityContTotal);
            ProfileManager.totalPages = parseInt(myObjectProfile.proTotalpages);
        }
        ProfileManager.SetProfileHTML();
    },
    ProfileFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Sorry could not process your request now, please try again later');
        }
    },
    SetProfileHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/profile-1.html", "", "GET", ProfileManager.SetProfileHTMLSuccess, ProfileManager.SetProfileHTMLFailure);
    },
    SetProfileHTMLSuccess: function (result) {
        MaraMentor.PushPageRecord('profile');
        MaraMentor.ChangeHeaderText('Profile');

        profileTemplate = result;

        profileMain = $(profileTemplate).filter("#mainProfile");
        profileActivity = $(profileTemplate).filter("#activityTemplate");

        profileMain = profileMain.html();
        profileActivity = profileActivity.html();

        var followClick = "";
        var followImage = "";
        var followText = "";
        var privateMessage = "";
        var role = "";

        if (ProfileManager.profileDataArray["role"] == "mentor") {
            role = "Mentor";

        } else {
            role = "Mentee";
        }

        if (ProfileManager.profileDataArray["role"] == "mentor" && ProfileManager.profileId != MaraMentor.sessionId) {
            if (ProfileManager.profileDataArray["Follow"] == "Follow") {
                followClick = "FollowMentorManager.FollowMentor(" + ProfileManager.profileId + ",'profile',0)";
                followImage = "assets/images/follow_grey.png";
                followText = "Follow";
            } else {
                followClick = "UnfollowMentorManager.UnfollowMentor(" + ProfileManager.profileId + ",'profile',0)";
                followImage = "assets/images/follow.png";
                followText = "Unfollow";
            }
        }
        if (ProfileManager.profileId == 1505) {
            followClick = "";
            followImage = "";
            followText = "";
        }
        if (ProfileManager.profileId == MaraMentor.sessionId) {
            MaraMentor.userImage = ProfileManager.profileDataArray["uesrImage"];
            window.localStorage.setItem("userImage", MaraMentor.userImage);
        }
        if (ProfileManager.profileId != MaraMentor.sessionId) {
            privateMessage = "<span class='message' onclick=\"PrivateMessageUserListManager.SetComposePrivateMessageHTML(" + ProfileManager.profileId + ",'" + ProfileManager.profileDataArray["Fname"] + "',10)\"><img src='assets/images/send_msg.png' >Message</span>";
        }

        profileMain = profileMain.replace(/\{\{Fname\}\}/, ProfileManager.profileDataArray["Fname"])
            .replace(/\{\{Lname\}\}/, ProfileManager.profileDataArray["Lname"])
            .replace(/\{\{Industry\}\}/, ProfileManager.profileDataArray["Industry"])
            .replace(/\{\{Mobile\}\}/, ProfileManager.profileDataArray["Mobile"])
            .replace(/\{\{Gender\}\}/, ProfileManager.profileDataArray["Gender"])
            .replace(/\{\{Experience\}\}/, ProfileManager.profileDataArray["Experience"])
            .replace(/\{\{about_me\}\}/, ProfileManager.profileDataArray["about_me"])
            .replace(/\{\{about_ctn\}\}/, ProfileManager.profileDataArray["about_ctn"])
            .replace(/\{\{Education\}\}/, ProfileManager.profileDataArray["Education"])
            .replace(/\{\{Twitter_handle\}\}/, ProfileManager.profileDataArray["Twitter_handle"])
            .replace(/\{\{Designation\}\}/, ProfileManager.profileDataArray["Designation"])
            .replace(/\{\{Company\}\}/, ProfileManager.profileDataArray["Company"])
            .replace(/\{\{Website_details\}\}/, ProfileManager.profileDataArray["Website_details"])
            .replace(/\{\{Blog_link_if_any\}\}/, ProfileManager.profileDataArray["Blog_link_if_any"])
            .replace(/\{\{country\}\}/, ProfileManager.profileDataArray["country"])
            .replace(/\{\{state\}\}/, ProfileManager.profileDataArray["state"])
            .replace(/\{\{uesrImage\}\}/, ProfileManager.profileDataArray["uesrImage"])
            .replace(/\{\{followClick\}\}/, followClick)
            .replace(/\{\{followImage\}\}/, followImage)
            .replace(/\{\{follow\}\}/, followText)
            .replace(/\{\{userId\}\}/g, ProfileManager.profileId)
            .replace(/\{\{privateMessage\}\}/g, privateMessage)
            .replace(/\{\{role\}\}/g, role);

        // profile activity data start..

        var resultProfileActivity = "";

        for (var p = 0, lenp = ProfileManager.profileTotalArray; p < lenp; p++) {
            var mySpan = "";
            if (ProfileManager.profileActivityArray[p]["user_id"] == ProfileManager.profileId) {
                mySpan = "<span class='del_act' >Delete</span>";
            } else {
                mySpan = "";
            }

            var likeActivity = "";
            var likeImage = "";
            var postLikeUnlike = "";
            //var isContinue= "false";
            if (ProfileManager.profileActivityArray[p]["type"] == "activity_liked") {
                continue;
            }

            if (ProfileManager.profileActivityArray[p]["isLiked"] == "0") {
                likeActivity = "Like";
                likeImage = "assets/images/like.png";
                postLikeUnlike = "PostLikeManager.PostLike";
            } else {
                likeActivity = "Unlike"
                likeImage = "assets/images/unlike.png";
                postLikeUnlike = "PostLikeManager.PostLike";
            }
            //var isContinue= "false";
            var deleteImage = "";
            var twitterImage = ""
            if (ProfileManager.profileActivityArray[p]["type"] == "twitter") {
                twitterImage += "<img src='assets/images/twitter.png' />"
            }
            if (ProfileManager.profileActivityArray[p]["user_id"] == MaraMentor.sessionId) {
                deleteImage += "<img src='assets/images/delete.png' onclick='DeleteMyActivityManager.DeleteMyActivity(" + ProfileManager.profileActivityArray[p]["itemIdNew"] + "," + ProfileManager.profileActivityArray[p]["user_id"] + "," + p + ",\"profile\")' />"
            }
            if (ProfileManager.profileActivityArray[p]["contwrap2"]) {
                var pContent = ProfileManager.profileActivityArray[p]["contwrap2"];
            } else {
                var pContent = "";
            }

            var TotalLikeShow = "";
                if(ProfileManager.profileActivityArray[p]["totalLike"] > 0)
                {
                TotalLikeShow='<span id="likeCount_'+ProfileManager.profileActivityArray[p]["itemIdNew"]+'" onclick="ProfileManager.PopupLikeUserList('+ProfileManager.profileActivityArray[p]["itemIdNew"]+')">'+ProfileManager.profileActivityArray[p]["totalLike"]+' Likes</span>';
                } else {
                TotalLikeShow='<span id="likeCount_'+ProfileManager.profileActivityArray[p]["itemIdNew"]+'" >'+ProfileManager.profileActivityArray[p]["totalLike"]+' Likes</span>';
                }

            resultProfileActivity += profileActivity.replace(/\{\{totalLikes\}\}/g, ProfileManager.profileActivityArray[p]["totalLike"])
                .replace(/\{\{totalComment\}\}/g, ProfileManager.profileActivityArray[p]["totalComment"])
                .replace(/\{\{user_id\}\}/g, ProfileManager.profileActivityArray[p]["user_id"])
                .replace(/\{\{user_act_type\}\}/, ProfileManager.profileActivityArray[p]["type"])
                .replace(/\{\{user_fullname\}\}/, ProfileManager.profileActivityArray[p]["user_fullname"])
                .replace(/\{\{resAction\}\}/, ProfileManager.profileActivityArray[p]["resAction"])
                .replace(/\{\{contwrap\}\}/, pContent)
                .replace(/\{\{resDateTime\}\}/, ProfileManager.profileActivityArray[p]["resDateTime"])
                .replace(/\{\{uesrImage\}\}/g, ProfileManager.profileDataArray["uesrImage"])
                .replace(/\{\{profileId\}\}/, ProfileManager.profileId)
                .replace(/\{\{itemId\}\}/g, ProfileManager.profileActivityArray[p]["itemIdNew"])
                .replace(/\{\{likeImage\}\}/, likeImage)
                .replace(/\{\{span\}\}/, mySpan)
                .replace(/\{\{itemIndex\}\}/g, p)
                .replace(/\{\{totalLikeShow\}\}/, TotalLikeShow)
                .replace(/\{\{postLikeUnlike\}\}/g, postLikeUnlike)
                .replace(/\{\{likeActivity\}\}/g, likeActivity)
                .replace(/\{\{deleteImage\}\}/g, deleteImage)
                .replace(/\{\{twitter\}\}/g, twitterImage);
        }
        //alert(profileActivity);
        mainProfileHtml = profileMain + resultProfileActivity;
        //end
        var loadMoreDiv = "";
        if (ProfileManager.totalPages > ProfileManager.pageNo) {
            ProfileManager.pageNo = ProfileManager.pageNo + 1;
            loadMoreDiv = "<div class='loadMore' id='loadMoreButton' onclick='ProfileManager.Profile(" + ProfileManager.profileId + "," + ProfileManager.pageNo + ")'></div>";
        }
        MaraMentor.IsLoadMore = "false";
        MaraMentor.ChangePageContent(mainProfileHtml + loadMoreDiv);

        MaraMentor.IsLoadMore = "false";
        if (ProfileManager.profileDataArray["role"] != "mentor") {
            $(".follow").remove();
        }
        // For each loop on data and html
    },
    //popup code start here
     //START: Popup comments window

    //Close popup window
    PopupClose: function( ) {
        document.getElementById('commentspopupwindow').style.display='none';

    },

    //Open and populate comments window
    PopupComments: function(topic_Id, itemIndex, itemSource) {

        //Popout the window
         $('#commentspopupwindow').css({'text-align' :  'center'});
        $('#commentspopupwindow').zoomIn(500);
        $( "#commentspopupwindow" ).html( "<div style='margin-top:44%;margin-left:44%;'><img src='assets/images/loading1.gif'></div>");

    //***********************************
        //Store parameters
        ProfileManager.itemIndex = itemIndex;
        ProfileManager.itemSource = itemSource
        SingleActivityManager.itemIndex = itemIndex;
        SingleActivityManager.itemSource = itemSource;

        var topicId = topic_Id;
        var userId = MaraMentor.sessionId;
        var requestData = "topicId=" + topicId + "&userId=" + userId + "&func=singleActivityDetail&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";

        //Make ajax call for singleActivityDetail to get activity data
        MaraMentor.MakeAjaxCall2(MaraMentor.serverURL, requestData, "POST", ProfileManager.PopupActivityDataSuccess, ProfileManager.PopupActivityDataError)
    },

    //on success: singleActivityDetail ajax we get response as JSON
   PopupActivityDataSuccess: function (resultData) {
       var myObjectSingle = JSON.parse(resultData); // Store response to object
       ProfileManager.singleDataArray = myObjectSingle.singleRes;                   //Main Activity Data
       ProfileManager.singleChildTotalArray = myObjectSingle.singleChildTotal;      //Total number of comments
       ProfileManager.singleChildArray = myObjectSingle.singleRes.child;            //Child elements

      SingleActivityManager.singleDataArray = myObjectSingle.singleRes;                  //Main Activity Data
      SingleActivityManager.singleChildTotalArray = myObjectSingle.singleChildTotal;     //Total number of comments
      SingleActivityManager.singleChildArray = myObjectSingle.singleRes.child;      //Child elements
      ProfileManager.SetSingleActivityPopupHTML();
   },

   //on failure of singleActivityDetail ajax call
   PopupActivityDataError: function (resultData) {

   },

   SetSingleActivityPopupHTML: function () {
       //call to get the html template
       MaraMentor.MakeAjaxCallHTML("views/comment_popup.html", "", "GET", ProfileManager.SetSingleActivityPopupHTMLSuccess, ProfileManager.SetSingleActivityPopupHTMLFailure);
   },

   SetSingleActivityPopupHTMLSuccess: function (result) {

       SingleActivityTemplate = result;
       mainSingleActivity = $(SingleActivityTemplate).filter("#main_activity");
       childActivity = $(SingleActivityTemplate).filter("#child_activity");
       commenTemplate = $(SingleActivityTemplate).find("#commenTemplate");

       mainSingleActivity = mainSingleActivity.html();
       childActivity = childActivity.html();
       commenTemplate = commenTemplate.html();
       // var live_str = $('<div>',{html:SingleActivityTemplate});
       // var example = live_str.find('#commenTemplate').html();

       sessionId = LoginManager.loginUserId;
       var mainCont = ""
       /******************* inner content start..***/
       if (ProfileManager.singleDataArray["main_type"] == "new_member") {
           mainCont = "Became a registered member";
       } else if (ProfileManager.singleDataArray['main_type'] == 'new_avatar') {
           mainCont = "Changed their profile picture";
       } else if (ProfileManager.singleDataArray['main_type'] == 'twitter') {
           mainCont = '<div class="tweet">';
           mainCont += ProfileManager.singleDataArray["contwrap1"];
           mainCont += '</div>';

       } else {
           mainCont = '<div >';
           mainCont += ProfileManager.singleDataArray["contwrap1"];
           mainCont += '</div>';
       }
       /*********end******************/
       var likeActivity = "";
       var likeImage = "";

       if (ProfileManager.singleDataArray["isLiked"] == "0") {
           likeActivity = "Like";
           likeImage = "assets/images/like.png";
           postLikeUnlike = "PostLikeManager.PostLike";
       } else {
           likeActivity = "Unlike";
           likeImage = "assets/images/unlike.png";
           postLikeUnlike = "PostLikeManager.PostLike";
       }
       mainSingleActivity = mainSingleActivity.replace(/\{\{itemId\}\}/g, ProfileManager.singleDataArray["main_id"])
           .replace(/\{\{main_user_id\}\}/, ProfileManager.singleDataArray["main_user_id"])
           .replace(/\{\{main_type\}\}/, ProfileManager.singleDataArray["main_type"])
           .replace(/\{\{main_content\}\}/, ProfileManager.singleDataArray["main_content"])
           .replace(/\{\{main_primary_link\}\}/, ProfileManager.singleDataArray["main_primary_link"])
           .replace(/\{\{resDateTime\}\}/, ProfileManager.singleDataArray["resDateTime"])
           .replace(/\{\{userImage\}\}/, ProfileManager.singleDataArray["userImage"])
           .replace(/\{\{user_fullname\}\}/, ProfileManager.singleDataArray["user_fullname"])
           .replace(/\{\{upload_dir\}\}/, ProfileManager.singleDataArray["upload_dir"])
           .replace(/\{\{totalLike\}\}/, ProfileManager.singleDataArray["totalLike"])
           .replace(/\{\{likeActivity\}\}/, likeActivity)
           .replace(/\{\{likeImage\}\}/, likeImage)
           .replace(/\{\{totalComment\}\}/, ProfileManager.singleDataArray["totalComment"])
           .replace(/\{\{mainSingleCont\}\}/, mainCont)
           .replace(/\{\{likeTotal\}\}/, ProfileManager.singleDataArray["likeTotal"])
           .replace(/\{\{contwrap1\}\}/, ProfileManager.singleDataArray["contwrap1"])
           .replace(/\{\{itemIndex\}\}/g, ProfileManager.itemIndex)
           .replace(/\{\{itemSource\}\}/g, ProfileManager.itemSource)
           .replace(/\{\{postLikeUnlike\}\}/g, postLikeUnlike);


       var fragmentSingleActivityChild = '';
       for (var s = 0, lens = ProfileManager.singleChildTotalArray; s < lens; s++) {
           var deleteText = "";
           var deleteFunction = "";

           var editText = "";
           var editFunction = "";

           var likeActivity = "";
           var likeImage = "";
           var likeType = "";
           if (ProfileManager.singleChildArray[s]["comment_like_button"] == "like") {
               commentLikeActivity = "Like";
               commentLikeType = "like";
               //likeImage = "assets/images/like.png";
               //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'like')";
           } else {
               commentLikeActivity = "Unlike";
               commentLikeType = "unlike";
               //likeImage = "assets/images/unlike.png";
               //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'unlike')";
           }

           if (MaraMentor.sessionId == ProfileManager.singleChildArray[s]["user_id"]) {
               deleteText = "Delete";
               deleteFunction = "DeletePostCommentManager.DeletePostComment(" + ProfileManager.singleChildArray[s]["id"] + "," + ProfileManager.singleDataArray["main_id"] + ")";

               editText = "Edit";
               editFunction = "EditPostCommentManager.EditPostCommentHtml(" + ProfileManager.singleChildArray[s]["id"] + "," + ProfileManager.singleDataArray["main_id"] + ")";
           }
           fragmentSingleActivityChild += childActivity.replace(/\{\{commentId\}\}/g, ProfileManager.singleChildArray[s]["id"])
               .replace(/\{\{commenterId\}\}/g, MaraMentor.sessionId)
               .replace(/\{\{component\}\}/, ProfileManager.singleChildArray[s]["component"])
               .replace(/\{\{type\}\}/g, ProfileManager.singleChildArray[s]["type"])
               .replace(/\{\{content\}\}/, ProfileManager.singleChildArray[s]["content"])
               .replace(/\{\{userImage\}\}/, ProfileManager.singleChildArray[s]["userImage"])
               .replace(/\{\{userName\}\}/, ProfileManager.singleChildArray[s]["userName"])
               .replace(/\{\{contwrapChild\}\}/, ProfileManager.singleChildArray[s]["contwrapChild"])
               .replace(/\{\{resDateTimeChild\}\}/, ProfileManager.singleChildArray[s]['resDateTimeChild'])
               .replace(/\{\{totalCommentLikes\}\}/, ProfileManager.singleChildArray[s]['total_comment_likes'])
               .replace(/\{\{deleteText\}\}/, deleteText)
               .replace(/\{\{deleteFunction\}\}/, deleteFunction)
               .replace(/\{\{editText\}\}/, editText)
               .replace(/\{\{editFunction\}\}/, editFunction)
               .replace(/\{\{likeType\}\}/, commentLikeType)
               .replace(/\{\{commentLikeActivity\}\}/, commentLikeActivity);;

       }
       var t_text = $( "#commentspopupwindow" ).html();
       //width = $("#commentspopupwindow").width() + 50;
       width = 700;

       postComment = '<div class="popupSinglePost">'
                    + ' <div class="Field" style="overflow:hidden;position:absolute">'
                    + '     <input type="text" id="postComment" placeholder="Write Your Comment">'
                    + ' </div>'
                    + ' <div class="btnPost right" onclick="PopupPostCommentManager.PostComment('+ProfileManager.singleDataArray["main_id"]+', \'' + ProfileManager.itemSource + '\')">Post</div>'
                    + '     <div class="clear">'
                    + ' </div>'
                    + '</div>'
                    + '<div id="commenTemplate" style="display:none" >'
                    + commenTemplate
                    + '</div>';


        if(ProfileManager.singleDataArray["totalLike"] > 1) {
           if (ProfileManager.singleDataArray["isLiked"] != "0") {
                ProfileManager.ownlike = " you and <a href = 'javascript:void(0)' onclick='ProfileManager.PopupLikeUserList("+ProfileManager.singleDataArray["main_id"]+")'>"+(ProfileManager.singleDataArray["totalLike"]- 1) +" people </a> like this.";
           } else
           {
                 ProfileManager.ownlike = "<a href = 'javascript:void(0)' onclick='ProfileManager.PopupLikeUserList("+ProfileManager.singleDataArray["main_id"]+")'>"+ProfileManager.singleDataArray["totalLike"]+" people </a> like this.";
           }

       } else if(ProfileManager.singleDataArray["totalLike"] == 1) {
            if (ProfileManager.singleDataArray["isLiked"] != "0") {
                ProfileManager.ownlike = " you like this.";
           } else
           {
                 ProfileManager.ownlike = "<a href = 'javascript:void(0)' onclick='ProfileManager.PopupLikeUserList("+ProfileManager.singleDataArray["main_id"]+")'>"+ProfileManager.singleDataArray["totalLike"]+" people </a> like this.";
           }
       }
       else
       {
             ProfileManager.ownlike = ProfileManager.singleDataArray["totalLike"]+" people </a> like this.";
       }


      allActivity = "<span><h2 class='nospace peoLikes'>"+ProfileManager.ownlike+"</h2></span><span class='colsepopup'> <a href = 'javascript:void(0)' onclick='ProfileManager.PopupClose()'>Done</a></span>" +
      "<div id='commentsDiv' style='clear:both; height: 68%; width: " + width +"; overflow: auto;'>" + fragmentSingleActivityChild +  "</div>" + postComment;

        $('#commentspopupwindow').css({'text-align' :  ''});
       $( "#commentspopupwindow" ).html( allActivity );
       $("#commentsDiv").html(fragmentSingleActivityChild).fadeIn();


   },

   SetSingleActivityPopupHTMLFailure: function () {

   },


    //END: Popup comments window
     //START: Popup like window
    //Open and populate comments window
    PopupLikeUserList: function(item_id) {
    //***********************************
        //Store parameters
        ProfileManager.item_id = item_id;
        var user_id = MaraMentor.sessionId;
        var requestData = "login_id="+user_id+"&item_id="+ProfileManager.item_id+ "&func=getUserLikeList&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";

       MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ProfileManager.PopupLikeUserListSuccess, ProfileManager.PopupLikeUserListFailure);
    },

    //on success: singleActivityDetail ajax we get response as JSON
   PopupLikeUserListSuccess: function (resultData) {
        var myObjectLikeUserList = JSON.parse(resultData);
        ProfileManager.likeListArray = myObjectLikeUserList.likeListArray;
        ProfileManager.TotalLikeArray = myObjectLikeUserList.TotalLikeArray;
        ProfileManager.item_id = myObjectLikeUserList.item_id;
        ProfileManager.SetPopupLikeUserListHTML();
   },
   PopupLikeUserListFailure: function (resultData) {

   },

   SetPopupLikeUserListHTML: function () {
       //call to get the html template
       MaraMentor.MakeAjaxCallHTML("views/like_user_list.html", "", "GET", ProfileManager.SetPopupLikeUserListHTMLSuccess, ProfileManager.SetPopupLikeUserListHTMLFailure);
   },

   SetPopupLikeUserListHTMLSuccess: function (result) {
       messageTemplate = result;
        MaraMentor.AcitivateMenu("more");
       // MaraMentor.backPages.length = 0;
       // MaraMentor.PushPageRecord('inbox');
     //   MaraMentor.ChangeHeaderText('View Like');
        headingWrap = $(messageTemplate).filter("#headingWrap");
        ViewLikeWrap = $(messageTemplate).filter("#ViewLikeWrap");
        ViewLikeWrap = ViewLikeWrap.html();
        headingWrap = headingWrap.html();
        var ViewLikeWrap1 = "";
        DashManager.ownlike = "";
        DashManager.totallike = "";


        DashManager.ownlike = "<a href = 'javascript:void(0)' onclick='DashManager.PopupLikeUserList("+DashManager.item_id+")'>"+DashManager.TotalLikeArray+" people </a> like this.";


        for (var v = 0,lenm=DashManager.TotalLikeArray; v<lenm; v++)
        {

            ViewLikeWrap1 += ViewLikeWrap.replace(/\{\{likeUserID\}\}/,DashManager.likeListArray[v]['likeUserID'])
            .replace(/\{\{likeUserName\}\}/,DashManager.likeListArray[v]['likeUserName'])
            .replace(/\{\{likeUserImage\}\}/,DashManager.likeListArray[v]['likeUserImage']);


        }

        if(DashManager.TotalLikeArray ==0 ){
            ViewLikeWrap1="<div>No likes for this post.</div>"
        }
       width = 700;

       allLike = "<div class='upDation'><h2 class='nospace peoLikes'>People who liked this post </h2>" +
                 "<div id='commentsDiv' style='clear:both; height: 74%; width: " + width +"; overflow: auto;'>"+ViewLikeWrap1 +  "</div></div>";
        MaraMentor.PushPageRecord("singleActiviyLikes_profile");
        MaraMentor.ChangePageContent(allLike);
        MaraMentor.ChangeHeaderText("Activity Likes");

       //Popout the window
      //  $('#commentspopupwindow').zoomIn(500);

       //MaraMentor.ChangePageContent(allActivity);
       // For each loop on data and html
   },

   SetPopupLikeUserListHTMLFailure: function () {

   }, //END: Popup like window
    //end of popup code
    //* FULL PROFILE CODING *//
    FullProfile: function (profile_id) {
        var p_page = '1';
        ProfileManager.profile_id = profile_id;
        ProfileManager.uId = MaraMentor.sessionId;

        var requestData = "m_id=" + ProfileManager.profile_id + "&u_id=" + ProfileManager.uId + "&func=mobileviewFullProfile&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ProfileManager.FullProfileSuccess, ProfileManager.FullProfileFailure)
    },
    FullProfileSuccess: function (resultData) {
        var data1 = JSON.parse(resultData);
        var fullProfileJson = JSON.stringify(data1).replace(/null/g, '""');
        var myObjectFullProfile = JSON.parse(fullProfileJson);

        ProfileManager.profileId = myObjectFullProfile.profileId;
        ProfileManager.profileFullArray = myObjectFullProfile.fullarry;
        ProfileManager.FullSetProfileHTML();
    },
    FullProfileFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Sorry could not process your request, please try again later.');
        }
    },
    FullSetProfileHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/full_profile.html", "", "GET", ProfileManager.SetFullProfileHTMLSuccess, ProfileManager.SetProfileHTMLFailure);
    },

    SetFullProfileHTMLSuccess: function (result) {
        MaraMentor.PushPageRecord('fullProfile');
        profileTemplate = result;

        profileMain = $(profileTemplate).filter("#mainProfile");
        profileActivity = $(profileTemplate).filter("#activityTemplate");
        // profile data start..........
        profileMain = profileMain.html();
        profileActivity = profileActivity.html();
        //alert(ProfileManager.profileId+"g");
        profileMain = profileMain.replace(/\{\{Fname\}\}/, ProfileManager.profileFullArray["Fname"])
            .replace(/\{\{Lname\}\}/, ProfileManager.profileFullArray["Lname"])
            .replace(/\{\{Fname\}\}/, ProfileManager.profileFullArray["Fname"])
            .replace(/\{\{Industry\}\}/, ProfileManager.profileFullArray["Industry"])
            .replace(/\{\{Mobile\}\}/, ProfileManager.profileFullArray["Mobile"])
            .replace(/\{\{Gender\}\}/, ProfileManager.profileFullArray["Gender"])
            .replace(/\{\{Experience\}\}/, ProfileManager.profileFullArray["Experience"])
            .replace(/\{\{about_me\}\}/, ProfileManager.profileFullArray["about_me"])
            .replace(/\{\{about_ctn\}\}/, ProfileManager.profileFullArray["about_ctn"])
            .replace(/\{\{Education\}\}/, ProfileManager.profileFullArray["Education"])
            .replace(/\{\{Twitter_handle\}\}/, ProfileManager.profileFullArray["Twitter_handle"])
            .replace(/\{\{Designation\}\}/, ProfileManager.profileFullArray["Designation"])
            .replace(/\{\{Company\}\}/, ProfileManager.profileFullArray["Company"])
            .replace(/\{\{Website_details\}\}/, ProfileManager.profileFullArray["Website_details"])
            .replace(/\{\{Blog_link_if_any\}\}/, ProfileManager.profileFullArray["Blog_link_if_any"])
            .replace(/\{\{country\}\}/, ProfileManager.profileFullArray["country"])
            .replace(/\{\{state\}\}/, ProfileManager.profileDataArray["state"])
            .replace(/\{\{uesrImage\}\}/, ProfileManager.profileFullArray["uesrImage"])
            .replace(/\{\{role\}\}/, ProfileManager.profileFullArray["role"])
            .replace(/\{\{userId\}\}/g, ProfileManager.profileId);


        MaraMentor.ChangePageContent(profileMain);
        // For each loop on data and html
    },
    SetFullProfileHTMLFailure: function () {

    },
}
/******************** Profile Data end **********************/

/******************** SingleActivity start **********************/
var SingleActivityManager = {
    singleActivityDataArray: [],

    SingleActivityData: function (topic_Id, itemIndex, itemSource) {

        MaraMentor.scrollYposition = myScroll.y;
        MaraMentor.scrollXposition = myScroll.x;

        SingleActivityManager.itemIndex = itemIndex;
        SingleActivityManager.itemSource = itemSource
        var topicId = topic_Id;
        var userId = MaraMentor.sessionId;
        var requestData = "topicId=" + topicId + "&userId=" + userId + "&func=singleActivityDetail&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", SingleActivityManager.SingleActivityDataSuccess, SingleActivityManager.SingleActivityDataError)
    },

    SingleActivityDataSuccess: function (resultData) {
        var myObjectSingle = JSON.parse(resultData);
        SingleActivityManager.singleDataArray = myObjectSingle.singleRes;
        SingleActivityManager.singleChildTotalArray = myObjectSingle.singleChildTotal;
        SingleActivityManager.singleChildArray = myObjectSingle.singleRes.child;
        SingleActivityManager.SetSingleActivityHTML();
    },

    SingleActivityDataError: function (resultData) {

    },

    SetSingleActivityHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/single_activity.html", "", "GET", SingleActivityManager.SetSingleActivityHTMLSuccess, SingleActivityManager.SetSingleActivityHTMLFailure);
    },

    SetSingleActivityHTMLSuccess: function (result) {
        MaraMentor.PushPageRecord('activity');
        MaraMentor.ChangeHeaderText('Activity');
        SingleActivityTemplate = result;
        mainSingleActivity = $(SingleActivityTemplate).filter("#main_activity");
        childActivity = $(SingleActivityTemplate).filter("#child_activity");

        mainSingleActivity = mainSingleActivity.html();
        childActivity = childActivity.html();

        sessionId = LoginManager.loginUserId;
        var mainCont = ""
        /******************* inner content start..***/
        if (SingleActivityManager.singleDataArray["main_type"] == "new_member") {
            mainCont = "Became a registered member";
        } else if (SingleActivityManager.singleDataArray['main_type'] == 'new_avatar') {
            mainCont = "Changed their profile picture";
        } else if (SingleActivityManager.singleDataArray['main_type'] == 'twitter') {
            mainCont = '<div class="tweet">';
            mainCont += SingleActivityManager.singleDataArray["contwrap1"];
            mainCont += '</div>';

        } else {
            mainCont = '<div >';
            mainCont += SingleActivityManager.singleDataArray["contwrap1"];
            mainCont += '</div>';
        }
        /*********end******************/
        var likeActivity = "";
        var likeImage = "";

        if (SingleActivityManager.singleDataArray["isLiked"] == "0") {
            likeActivity = "Like";
            likeImage = "assets/images/like.png";
            postLikeUnlike = "PostLikeManager.PostLike";
        } else {
            likeActivity = "Unlike";
            likeImage = "assets/images/unlike.png";
            postLikeUnlike = "PostLikeManager.PostLike";
        }
        mainSingleActivity = mainSingleActivity.replace(/\{\{itemId\}\}/g, SingleActivityManager.singleDataArray["main_id"])
            .replace(/\{\{main_user_id\}\}/, SingleActivityManager.singleDataArray["main_user_id"])
            .replace(/\{\{main_type\}\}/, SingleActivityManager.singleDataArray["main_type"])
            .replace(/\{\{main_content\}\}/, SingleActivityManager.singleDataArray["main_content"])
            .replace(/\{\{main_primary_link\}\}/, SingleActivityManager.singleDataArray["main_primary_link"])
            .replace(/\{\{resDateTime\}\}/, SingleActivityManager.singleDataArray["resDateTime"])
            .replace(/\{\{userImage\}\}/, SingleActivityManager.singleDataArray["userImage"])
            .replace(/\{\{user_fullname\}\}/, SingleActivityManager.singleDataArray["user_fullname"])
            .replace(/\{\{upload_dir\}\}/, SingleActivityManager.singleDataArray["upload_dir"])
            .replace(/\{\{totalLike\}\}/, SingleActivityManager.singleDataArray["totalLike"])
            .replace(/\{\{likeActivity\}\}/, likeActivity)
            .replace(/\{\{likeImage\}\}/, likeImage)
            .replace(/\{\{totalComment\}\}/, SingleActivityManager.singleDataArray["totalComment"])
            .replace(/\{\{mainSingleCont\}\}/, mainCont)
            .replace(/\{\{likeTotal\}\}/, SingleActivityManager.singleDataArray["likeTotal"])
            .replace(/\{\{contwrap1\}\}/, SingleActivityManager.singleDataArray["contwrap1"])
            .replace(/\{\{itemIndex\}\}/g, SingleActivityManager.itemIndex)
            .replace(/\{\{itemSource\}\}/g, SingleActivityManager.itemSource)
            .replace(/\{\{postLikeUnlike\}\}/g, postLikeUnlike);
        var fragmentSingleActivityChild = '';
        for (var s = 0, lens = SingleActivityManager.singleChildTotalArray; s < lens; s++) {
            var deleteText = "";
            var deleteFunction = "";

            var editText = "";
            var editFunction = "";

            var likeActivity = "";
            var likeImage = "";
            var likeType = "";
            if (SingleActivityManager.singleChildArray[s]["comment_like_button"] == "like") {
                commentLikeActivity = "Like";
                commentLikeType = "like";
                //likeImage = "assets/images/like.png";
                //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'like')";
            } else {
                commentLikeActivity = "Unlike";
                commentLikeType = "unlike";
                //likeImage = "assets/images/unlike.png";
                //postCommentLikeUnlike = "LikeUnlikePostCommentManager.LikeUnlikePostComment(" +SingleActivityManager.singleChildArray[s]["id"] + "," + MaraMentor.sessionId + ",'unlike')";
            }

            if (MaraMentor.sessionId == SingleActivityManager.singleChildArray[s]["user_id"]) {
                deleteText = "Delete";
                deleteFunction = "DeletePostCommentManager.DeletePostComment(" + SingleActivityManager.singleChildArray[s]["id"] + "," + SingleActivityManager.singleDataArray["main_id"] + ")";

                editText = "Edit";
                editFunction = "EditPostCommentManager.EditPostCommentHtml(" + SingleActivityManager.singleChildArray[s]["id"] + "," + SingleActivityManager.singleDataArray["main_id"] + ")";
            }
            fragmentSingleActivityChild += childActivity.replace(/\{\{commentId\}\}/g, SingleActivityManager.singleChildArray[s]["id"])
                .replace(/\{\{commenterId\}\}/g, MaraMentor.sessionId)
                .replace(/\{\{component\}\}/, SingleActivityManager.singleChildArray[s]["component"])
                .replace(/\{\{type\}\}/g, SingleActivityManager.singleChildArray[s]["type"])
                .replace(/\{\{content\}\}/, SingleActivityManager.singleChildArray[s]["content"])
                .replace(/\{\{userImage\}\}/, SingleActivityManager.singleChildArray[s]["userImage"])
                .replace(/\{\{userName\}\}/, SingleActivityManager.singleChildArray[s]["userName"])
                .replace(/\{\{contwrapChild\}\}/, SingleActivityManager.singleChildArray[s]["contwrapChild"])
                .replace(/\{\{resDateTimeChild\}\}/, SingleActivityManager.singleChildArray[s]['resDateTimeChild'])
                .replace(/\{\{totalCommentLikes\}\}/, SingleActivityManager.singleChildArray[s]['total_comment_likes'])
                .replace(/\{\{deleteText\}\}/, deleteText)
                .replace(/\{\{deleteFunction\}\}/, deleteFunction)
                .replace(/\{\{editText\}\}/, editText)
                .replace(/\{\{editFunction\}\}/, editFunction)
                .replace(/\{\{likeType\}\}/, commentLikeType)
                .replace(/\{\{commentLikeActivity\}\}/, commentLikeActivity);;

        }
        allActivity = mainSingleActivity + "<div id='commentsDiv'>" + fragmentSingleActivityChild + "</div>";

        MaraMentor.ChangePageContent(allActivity);

        MaraMentor.isScrollPostion = true;
        MaraMentor.scrolltop =0;
        // For each loop on data and html
    },

    SetSingleActivityHTMLFailure: function () {

    }
}
/******************** SingleActivity end **********************/

/******************** showEditProfile start **********************/
var ShowEditProfileManager = {
    ShowEditProfileDataArray: [],

    ShowEditProfileData: function () {
        var userId1 = MaraMentor.sessionId;
        var requestData = "userId=" + userId1 + "&func=showEditUserProfile&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ShowEditProfileManager.ShowEditProfileDataSuccess, ShowEditProfileManager.ShowEditProfileDataError);
    },
    ShowEditProfileForm:function(){
        document.getElementById('industries').style.display ="none";
        document.getElementById('editProfileFormDiv').style.display ="block";
        MaraMentor.RefreshScrollBar();
    },
    ShowAditionalIndustries:function(){
        MaraMentor.PushPageRecord("showEditProfileAdditionalIndustry");
        document.getElementById('industries').style.display ="block";
        document.getElementById('editProfileFormDiv').style.display ="none";
        MaraMentor.RefreshScrollBar();
        myScroll.scrollTo(0,0,0);
    },
    ShowEditProfileDataSuccess: function (resultData) {
        //var myObjectShowProfile = JSON.parse(resultData);
        var data1 = JSON.parse(resultData);
        var fullProfileJson = JSON.stringify(data1).replace(/null/g, '""');
        var myObjectShowProfile = JSON.parse(fullProfileJson);

        ShowEditProfileManager.ShowProfileArray = myObjectShowProfile.ShowProfileArr;
        ShowEditProfileManager.SetShowEditProfileHTML();
    },

    ShowEditProfileDataError: function (resultData) {

    },

    SetShowEditProfileHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/edit_profile_new.html", "", "POST", ShowEditProfileManager.SetShowEditProfileHTMLSuccess, ShowEditProfileManager.SetShowEditProfileHTMLFailure);
    },

    SetShowEditProfileHTMLSuccess: function (result) {

        MaraMentor.PushPageRecord("showEditProfile");
        MaraMentor.ChangeHeaderText("Edit Your Profile");
        ShowEditProfileTemplate = result;
        sessionId = LoginManager.loginUserId;
        /************get the industry*************/
        var industryDiv = '';
        industryDiv += '<select name="industry" id="industry">';
        industryDiv += '<option value="0" >Select Industry</option>';
        for (var ii = 0, lenp = ShowEditProfileManager.ShowProfileArray["industryTotal"]; ii < lenp; ii++) {
            selectedInd = (ShowEditProfileManager.ShowProfileArray["industryRes"][ii]["ind_name"].trim() == ShowEditProfileManager.ShowProfileArray["industry"].trim()) ? 'selected="selected"' : "";

            industryDiv += '<option value="' + ShowEditProfileManager.ShowProfileArray["industryRes"][ii]["ind_name"].trim() + '" ' + selectedInd + ' >';
            industryDiv += ShowEditProfileManager.ShowProfileArray["industryRes"][ii]["ind_label"];
            industryDiv += '</option>';
        }
        industryDiv += '</select>';
        /************get the industry end *************/
        /************country div start*************/
        var countryDiv = '';

        countryDiv+="<input id='country' type='hidden' name='country' value='"+ShowEditProfileManager.ShowProfileArray["country"]+"'/><input id='acountrytext' type='text' name='acountrytext' placeholder='Country Name' value='"+ShowEditProfileManager.ShowProfileArray["country"]+"'/>";
        /************end*************/
        /************get the radio button gender*************/
        var mobileType = '';
        var radioDiv = '';
        var Gender1 = '';

        var gender_male_status = '';
        var gender_female_status = '';
        if (ShowEditProfileManager.ShowProfileArray["Gender"] == 'Male') {
            gender_male_status = 'checked="checked"';
        } else {
            gender_female_status = 'checked="checked"';
        }



/************get the additional industry*************/
         var addIndustriesListDiv = "";

               for (var j = 0; j< MaraMentor.mentor_additional_industry_arr.length; j++) {

                if(ShowEditProfileManager.ShowProfileArray["additionIndustry"] == false) {
                    addIndustriesListDiv +='<div style="width:100%;float:left"><input style="margin-left:10px;top:-10px;position: relative;" type="checkbox" name="chk_additi_ind[]" id="'+j+'" class="edit_additional_ind" value="'+MaraMentor.mentor_additional_industry_arr[j]['addit_indus_ID']+'" onChange="EditProfileManager.editProfile_additional_validation(this)" /><label for="'+j+'" style="font-size:12px;display:block;">'+MaraMentor.mentor_additional_industry_arr[j]['addit_ind_label']+'</label></div>';
                } else {
                     var a = ShowEditProfileManager.ShowProfileArray["additionIndustry"].indexOf(MaraMentor.mentor_additional_industry_arr[j]['addit_indus_ID'] );
                       if (a != -1)
                       {
                        addIndustriesListDiv +='<div style="width:100%;float:left"><input style="margin-left:10px;top:-10px;position: relative;" type="checkbox" name="chk_additi_ind[]" id="'+j+'" class="edit_additional_ind" value="'+MaraMentor.mentor_additional_industry_arr[j]['addit_indus_ID']+'" onChange="EditProfileManager.editProfile_additional_validation(this)" checked /><label for="'+j+'" style="font-size:12px;display:block;">'+MaraMentor.mentor_additional_industry_arr[j]['addit_ind_label']+'</label></div>';
                       }
                       else
                       {
                        addIndustriesListDiv +='<div style="width:100%;float:left"><input style="margin-left:10px;top:-10px;position: relative;" type="checkbox" name="chk_additi_ind[]" id="'+j+'" class="edit_additional_ind" value="'+MaraMentor.mentor_additional_industry_arr[j]['addit_indus_ID']+'" onChange="EditProfileManager.editProfile_additional_validation(this)" /><label for="'+j+'" style="font-size:12px;display:block;">'+MaraMentor.mentor_additional_industry_arr[j]['addit_ind_label']+'</label></div>';
                       }
              }
        }
    /************get the additional industry end *************/



        radioDiv = '<input type="radio" name="Gender" id="sex" value="Male" ' + gender_male_status + '><label for="sex">Male</label>';
        radioDiv += '<input type="radio" name="Gender" id="sex1" value="Female"  ' + gender_female_status + ' ><label for="sex1">Female</label>';

        /************end*************/
        ShowEditProfileTemplate = ShowEditProfileTemplate.replace(/\{\{industryRes\}\}/, ShowEditProfileManager.ShowProfileArray["industryRes"])
            .replace(/\{\{industryTotal\}\}/, ShowEditProfileManager.ShowProfileArray["industryTotal"])
            .replace(/\{\{countryRes\}\}/, ShowEditProfileManager.ShowProfileArray["countryRes"])
            .replace(/\{\{countryTotal\}\}/, ShowEditProfileManager.ShowProfileArray["countryTotal"])
            .replace(/\{\{first_name\}\}/, ShowEditProfileManager.ShowProfileArray["first_name"])
            .replace(/\{\{lname\}\}/, ShowEditProfileManager.ShowProfileArray["lname"])
            .replace(/\{\{industry\}\}/, ShowEditProfileManager.ShowProfileArray["industry"])
            .replace(/\{\{mobile\}\}/, ShowEditProfileManager.ShowProfileArray["mobile"])
            .replace(/\{\{Gender\}\}/, ShowEditProfileManager.ShowProfileArray["Gender"])
            .replace(/\{\{company\}\}/, ShowEditProfileManager.ShowProfileArray["company"])
            .replace(/\{\{desig\}\}/, ShowEditProfileManager.ShowProfileArray["desig"])
            .replace(/\{\{country\}\}/, ShowEditProfileManager.ShowProfileArray["country"])
            .replace(/\{\{state\}\}/, ShowEditProfileManager.ShowProfileArray["state"])
            .replace(/\{\{twitterhan\}\}/, ShowEditProfileManager.ShowProfileArray["twitterhan"])
            .replace(/\{\{edu\}\}/, ShowEditProfileManager.ShowProfileArray["edu"])
            .replace(/\{\{aboutme\}\}/, ShowEditProfileManager.ShowProfileArray["aboutme"])
            .replace(/\{\{exper\}\}/, ShowEditProfileManager.ShowProfileArray["exper"])
            .replace(/\{\{webdetail\}\}/, ShowEditProfileManager.ShowProfileArray["webdetail"])
            .replace(/\{\{industryDiv\}\}/, industryDiv)
            .replace(/\{\{radioDiv\}\}/, radioDiv)
            .replace(/\{\{countryDiv\}\}/, countryDiv)
            .replace(/\{\{bloglink\}\}/, ShowEditProfileManager.ShowProfileArray["bloglink"]);
        MaraMentor.ChangePageContent(ShowEditProfileTemplate);
        MaraMentor.setupCountriesOnly();
        $("#replaceIndustry").html($(addIndustriesListDiv));
        // For each loop on data and html
    },

    SetShowEditProfileHTMLFailure: function () {

    }
}
/******************** showEditProfile end **********************/

/******************** EditProfilePage start **********************/
var EditProfileManager = {

    EditProfileData: function () {
        var myfieldName = new Array("fname", "lname", "industry", "mobile", "sex", "company", "desig", "country", "state", "twitterhan", "edu", "aboutme", "exper", "webdetail", "bloglink", "countrycode");
        var res = EditProfileManager.EditProfilePageValidationData(myfieldName);
        var res1 = EditProfileManager.editProfile_additional_validation(myfieldName);
        if (res == false || res1 == false) {
            return;
        }
        var pass1 = $("#user_pass").val();
        var pass2 = $("#user_pass1").val();
        var old_password = $("#old_password").val();
        var errmsg = ''
        var new_password = "";
        if (pass1 == "" && pass2 == "") {
            new_password = "";
        } else {
            if (old_password == "") {
                var errmsg = "Please fill your old password"
            } else {
                if (pass1 != pass2) {
                    var errmsg = "Password does not match with confirm pasword"
                } else {
                    if ((pass1.indexOf(" ") > 0) || (pass1.length < 6)) {
                        $("#user_pass").val('');
                        $("#user_pass1").val('');
                        var errmsg = "Password length should be minimum 6 (without spaces)"
                    }
                }
            }
        }
        if (errmsg != "") {
            $("#user_pass1").val('');
            $("#user_pass").val('');
            $("#old_password").css('border', '2px solid #ff0000');
            $("#user_pass").css('border', '2px solid #ff0000');
            $("#user_pass1").css('border', '2px solid #ff0000');
            //$("#errorDesc").html(errmsg);
            //alert(errmsg);
            MaraMentor.showToastMsg(errmsg);
            return false;
        } else {

            new_password = pass1.trim();

            old_password = old_password.trim();

        }

        // additional industry code start here..
        var additional_ind = $(".edit_additional_ind");
        var additi_ind_vals = "";
        for (var i = 0, n = additional_ind.length; i < n; i++) {
            if (additional_ind[i].checked) {
                additi_ind_vals += additional_ind[i].value + ",";
            }
        }
        var additional_value = additi_ind_vals.replace(/,(?=[^,]*$)/, '');
        //end

        var m_id = MaraMentor.sessionId;
        var _fname = $("#fname").val();
        var _fname1 = _fname.indexOf("@", _fname);
        if (_fname1 == 0) {
            var fname = _fname.substring(1, _fname.length);
        } else {
            var fname = $("#fname").val();
        }
        var _lname = $("#lname").val();
        var _lname1 = _lname.indexOf("@", _lname);
        if (_lname1 == 0) {
            var lname = _lname.substring(1, _lname.length);
        } else {
            var lname = $("#lname").val();
        }
        var industry = $("#industry").val();
        var countrycode = $("#countrycode").val();
        var mobile = $("#mobile").val();
        var _mobileType = $("#mobileType").val();
        var sexm = $("#sex:checked").val();

        if (_mobileType == "android") {
            var sex = $("#sex").val();
        } else {
            var sexf = $("#sex1:checked").val();
            var sexm = $("#sex:checked").val();
            if (sexf) {
                var sex = sexf;
            }
            if (sexm) {
                var sex = sexm;
            }
        }
        var _company = $("#company").val();
        var _company1 = _company.indexOf("@", _company);
        if (_company1 == 0) {
            var company = _company.substring(1, _company.length);
        } else {
            var company = $("#company").val();
        }
        var _desig = $("#desig").val();
        var _desig1 = _desig.indexOf("@", _desig);
        if (_desig1 == 0) {
            var desig = _desig.substring(1, _desig.length);
        } else {
            var desig = $("#desig").val();
        }
        var country = $("#country").val();
        var _state = $("#state").val();
        var _state1 = _state.indexOf("@", _state);
        if (_state1 == 0) {
            var state = _state.substring(1, _state.length);
        } else {
            var state = $("#state").val();
        }
        var _twitterhan = $("#twitterhan").val();
        var _twitterhan1 = _twitterhan.indexOf("@", _twitterhan);
        if (_twitterhan1 == 0) {
            var twitterhan = _twitterhan.substring(1, _twitterhan.length);
        } else {
            var twitterhan = $("#twitterhan").val();
        }
        var _edu = $("#edu").val();
        var _edu1 = _edu.indexOf("@", _edu);
        if (_edu1 == 0) {
            var edu = _edu.substring(1, _edu.length);
        } else {
            var edu = $("#edu").val();
        }
        var _aboutme = $("#aboutme").val();
        var _aboutme1 = _aboutme.indexOf("@", _aboutme);
        if (_aboutme1 == 0) {
            var aboutme = _aboutme.substring(1, _aboutme.length);
        } else {
            var aboutme = $("#aboutme").val();
        }
        var _exper = $("#exper").val();
        var _exper1 = _exper.indexOf("@", _exper);
        if (_exper1 == 0) {
            var exper = _exper.substring(1, _exper.length);
        } else {
            var exper = $("#exper").val();
        }
        var _webdetail = $("#webdetail").val();
        var _webdetail1 = _webdetail.indexOf("@", _webdetail);
        if (_webdetail1 == 0) {
            var webdetail = _webdetail.substring(1, _webdetail.length);
        } else {
            var webdetail = $("#webdetail").val();
        }
        var _bloglink = $("#bloglink").val();
        var _bloglink1 = _bloglink.indexOf("@", _bloglink);
        if (_bloglink1 == 0) {
            var bloglink = _bloglink.substring(1, _bloglink.length);
        } else {
            var bloglink = $("#bloglink").val();
        }
        EditProfileManager.Fname = fname;
        EditProfileManager.Lname = lname;
        EditProfileManager.Country = country;
        EditProfileManager.Industry = industry;
        var requestData = "m_id=" + m_id + "&fname=" + fname + "&lname=" + lname + "&industry=" + industry + "&countrycode=" + countrycode + "&mobile=" + mobile + "&sex=" + sex + "&company=" + company + "&desig=" + desig + "&country=" + country + "&state=" + state + "&twitterhan=" + twitterhan + "&edu=" + edu + "&aboutme=" + aboutme + "&exper=" + exper + "&webdetail=" + webdetail + "&bloglink=" + bloglink + "&password=" + new_password + "&old_password=" + old_password + "&additional_industry=" + additional_value + "&func=editProfile&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", EditProfileManager.EditProfileDataSuccess, EditProfileManager.EditProfileDataError);
    },
    EditProfileDataSuccess: function (resultData) {
        var myObjectEditProfile = JSON.parse(resultData);
        if (myObjectEditProfile.IsSuccess === "true") {
            //ProfileManager.Profile(sessionId,1)
            MaraMentor.displayName = EditProfileManager.Fname + " " + EditProfileManager.Lname;
            MaraMentor.userIndustry = EditProfileManager.Industry;
            MaraMentor.userCountry = EditProfileManager.Country;

            window.localStorage.setItem("displayName", MaraMentor.displayName);
            window.localStorage.setItem("userIndustry", MaraMentor.userIndustry);
            window.localStorage.setItem("userCountry", MaraMentor.userCountry);
            MoreScreenManager.GetMoreScreenHtml();
        }
        else
          {
           //MaraMentor.errMsg = myObjectEditProfile.ErrorMessage;
           MaraMentor.showToastMsg(myObjectEditProfile.ErrorMessage)
          }

        // ShowEditProfileManager.ShowProfileArray = myObjectShowProfile.ShowProfileArr;

    },

    EditProfileDataError: function (resultData) {
        var myObjectEditProfile = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectEditProfile.ErrorMessage);
    },
    EditProfilePageValidationData: function (myfieldName) {
        var phoneno = /^\d{10}$/;
        var fname = document.getElementById(myfieldName[0]).value;
        var lname = document.getElementById(myfieldName[1]).value;
        var industry = document.getElementById(myfieldName[2]).value;
        //var mobile = document.getElementById(myfieldName[3]).value;
        var sex = document.getElementById(myfieldName[4]).value;
        // var countrycode = document.getElementById(myfieldName[15]).value;
        var country = document.getElementById(myfieldName[7]).value;
        var aboutme = document.getElementById(myfieldName[11]).value;
        var edit_additional_ind = $(".edit_additional_ind:checked").length;
        var flag = true;
        if (fname == '') {
            var errorMsg = "Please don't leave required(*) field empty.";
            $("#fname").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#fname").css('border', '');
        }
        if (lname == '') {
            var errorMsg = "Please don't leave required(*) field empty.";
            $("#lname").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#lname").css('border', '');
        }
        if (industry == 0) {
            var errorMsg = "Please select industry";
            $("#industry").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#industry").css('border', '');
        }
        if (country == 0) {
            var errorMsg = "Please select Country";
            $("#country").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#country").css('border', '');
        }

        if (aboutme == "") {
            var errorMsg = "Please don't leave required(*) field empty.";
            $("#aboutme").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#aboutme").css('border', '');
        }

        if (edit_additional_ind > 5) {
            var errormsg = "You can choose maximum 5 additional industries.";
            $("#additional_industry").css('border', '2px solid #ff0000');
            flag = false;
        } else {
            $("#additional_industry").css('border', '');
        }

        if (!flag) {
            MaraMentor.showToastMsg(errorMsg);
        }
        return flag;
    },
     editProfile_additional_validation: function (elem) {

            var edit_additional_ind = $(".edit_additional_ind:checked").length;

            var flag1 = true;

            if(edit_additional_ind >5)
            {
                elem.checked = false;
                errormsg = "You can choose maximum 5 additional industries.";
             //   $("#additional_industry").css('border', '2px solid #ff0000');
                flag1 = false;
            }
            /*else {
                $("#additional_industry").css('border', '');
            }*/
            if (!flag1) {
                MaraMentor.showToastMsg(errormsg);
            }
            return flag1;
        },
}
/******************** EditProfile end **********************/

/******************** Mentor List Data start **********************/
var MentorListManager = {
    pageNo: 1,
    query: "",
    currentPage: "0",
    advPageNo: 1,
    MentorList: function (pageNo, currentPage) {
    	wrapAnimation = _noAnimation;
        var userId = MaraMentor.sessionId;
        var scrhMent = $("#search_user").val();
        if (currentPage === '0') {
            if (!scrhMent || scrhMent === 'undefined') {
                scrhMent = "";
            }
           MentorListManager.query = "";
        } else {
            if (!scrhMent || scrhMent === 'undefined' || scrhMent === '') {
                MaraMentor.showToastMsg("Please enter user name for Search.");
                return false;
            }
            MentorListManager.query = scrhMent;
        }

        MentorListManager.currentPage = currentPage;
        MentorListManager.pageNo = pageNo;
        var requestData = "pageNo=" + MentorListManager.pageNo + "&userId=" + userId + "&scrhMent=" + MentorListManager.query + "&func=mentorList&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MentorListManager.MentorListSuccess, MentorListManager.MentorListFailure)
    },

    MentorListSuccess: function (resultData) {

        var myObjectAdvMentorList = JSON.parse(resultData);
        MentorListManager.runMentorRow = myObjectAdvMentorList.advMentorStatus;
        MentorListManager.advMentorStatus = myObjectAdvMentorList.advMentorStatus;
        MentorListManager.advPageNo = myObjectAdvMentorList.advPageNo;
        MentorListManager.advTotalPage = myObjectAdvMentorList.advTotalPage;
        MentorListManager.search_user = myObjectAdvMentorList.search_user;
        MentorListManager.SetMentorListHTML();
    },

    MentorListFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request, please try again later');
        }
    },
    SetMentorListHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/mentor.html", "", "GET", MentorListManager.SetMentorListHTMLSuccess, MentorListManager.SetMentorListHTMLFailure);
    },

    SetMentorListHTMLSuccess: function (result) {

        MaraMentor.backPages.length = 0;
        MaraMentor.AcitivateMenu("mentors");
        MaraMentor.PushPageRecord("mentorList");
        MaraMentor.ChangeHeaderText("Mentors");

        RunAdvMentorSrchTemplate = result;
        runMentorListSrch = $(RunAdvMentorSrchTemplate).filter("#advanceSearch");
        runMentorListSrch = runMentorListSrch.html();
        runMentorload = $(RunAdvMentorSrchTemplate).filter("#mentorList");
        runMentorload = runMentorload.html();
        var i = 0;
        if (MentorListManager.runMentorRow == "no") {
            var runMentorload1 = '';
            runMentorload1 += runMentorload.replace(/\{\{noRes\}\}/, 'Sorry, no members were found');
        } else {
            var runMentorload1 = '';
            var UsersLists = '';
            runMentorload1 += '<div class="UsersListContainer">';
            for (x in MentorListManager.runMentorRow) {
                var followClick = "";
                var followImage = "";
                var followText = "";
                if (MentorListManager.runMentorRow[x].user_id == 1505) {
                     followClick = "";
                     followImage = "";
                     followText = "";
                }
                else {
                    if (MentorListManager.runMentorRow[x].Follow == "Follow") {
                        followClick = "FollowMentorManager.FollowMentor(" + MentorListManager.runMentorRow[x].user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow.png";
                        followText = "Follow";
                    } else {
                        followClick = "UnfollowMentorManager.UnfollowMentor(" + MentorListManager.runMentorRow[x].user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow_grey.png";
                        followText = "Unfollow";
                    }
                }

                if (MentorListManager.runMentorRow[x].user_id == 1505) {
                    followClick = "";
                    followImage = "";
                    followText = "";
                }

                UsersLists += runMentorload.replace(/\{\{userId\}\}/g, MentorListManager.runMentorRow[x].user_id)
                    .replace(/\{\{firstName\}\}/, MentorListManager.runMentorRow[x].first_name)
                    .replace(/\{\{last_name\}\}/, MentorListManager.runMentorRow[x].last_name)
                    .replace(/\{\{country\}\}/, MentorListManager.runMentorRow[x].country)
                    .replace(/\{\{industry\}\}/, MentorListManager.runMentorRow[x].industry)
                    .replace(/\{\{totalAll\}\}/g, MentorListManager.runMentorRow[x].totalMentees)
                    .replace(/\{\{userImage\}\}/, MentorListManager.runMentorRow[x].userImage)
                    .replace(/\{\{followClick\}\}/, followClick)
                    .replace(/\{\{followImage\}\}/, followImage)
                    .replace(/\{\{Follow\}\}/, followText);
                i++;

            }

            runMentorload1 += '</div><div class="clear"></div>';

            if (parseInt(MentorListManager.advPageNo) == 1 && parseInt(MentorListManager.advTotalPage) > 1) {
                runMentorload1 += '<div class="loadMore" id="loadMoreButton" onclick="MentorListManager.MentorListNextPage(\'' + MentorListManager.search_user + '\')"></div>';
            }

        }
        if (i == 0) {
            runMentorload1 = "<div class='upDation'>Sorry, no members were found </div>";
        }
        MaraMentor.IsLoadMore = 'false';
        mainMentorListHtml = runMentorListSrch + runMentorload1;

        if (parseInt(MentorListManager.advPageNo) == 1) {
            MaraMentor.ChangePageContent(mainMentorListHtml);
        }



        $('.UsersListContainer').append(UsersLists);

        if (parseInt(MentorListManager.advPageNo) >= parseInt(MentorListManager.advTotalPage)) {

            $(".loadMore").remove();
        }
        MaraMentor.RefreshScrollBar();

    },
    MentorListNextPage: function (search_user) {

        var newPage = parseInt(MentorListManager.advPageNo) + 1;
        var userId = MaraMentor.sessionId;
        var requestData = "pageNo=" + newPage + "&userId=" + userId + "&scrhMent=" + search_user + "&func=mentorList&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MentorListManager.MentorListSuccess, MentorListManager.MentorListFailure)
    },

    SetMentorListHTMLFailure: function () {},
    mentorNextPage: function () {
        var newPage = $('#currentPage').val();
        newPage++;
        mentorList(newPage, 0);
    },
    AdvSrchMentorPage: function () {
    	wrapAnimation = _noAnimation;
        var loginId = MaraMentor.sessionId;
        var requestData = "loginId=" + loginId + "&searchFor=mentor&func=mobileIndustryCountry&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MentorListManager.AdvSrchMentorPageSuccess, MentorListManager.AdvSrchMentorPageFailure)
    },
    AdvSrchMentorPageSuccess: function (resultData) {
        var myObjectAdvSrch = JSON.parse(resultData);
        MentorListManager.IsSuccess = myObjectAdvSrch.IsSuccess;

        if (myObjectAdvSrch.IsSuccess == "true") {
            MentorListManager.country_arr = myObjectAdvSrch.countries;
            MentorListManager.industry_arr = myObjectAdvSrch.industries;
        } else {
            MentorListManager.country_arr = new Array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, The Democratic Republic of the", "Cook Islands", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar ", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy ", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands ", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria ", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory", "Panama", "Papua New Guinea", "Paraguay", "Peru ", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts and Nevis ", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu ", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
            MentorListManager.industry_arr = new Array("Agriculture", "Construction and Infrastructure", "Education and Training", "Information and Communication Technology (ICT)", "Investment and banking", "Leisure and Hospitality", "Manufacturing", "Media and Entertainment", "Mining and drilling", "Power and Energy", "Social Enterprise");
        } //end of else part


        MentorListManager.SetAdvSrchMentorPageHTML();
    },
    AdvSrchMentorPageFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request please try again later');
        }

    },
    SetAdvSrchMentorPageHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/advSrch.html", "", "GET", MentorListManager.SetAdvSrchMentorPageHTMLSuccess, MentorListManager.SetAdvSrchMentorPageHTMLFailure);
    },

    SetAdvSrchMentorPageHTMLSuccess: function (result) {
        //alert(result);
        advSrechFormTemplate = result;
        MaraMentor.PushPageRecord('advMentorSearch');
        MaraMentor.ChangeHeaderText("Search");
        /************country array div start*************/
        var countryArrDiv = '';
        countryArrDiv += '<select name="country" id="country" >';
        countryArrDiv += '<option value="0" >Select</option>';
        for (var c = 0, lenC = MentorListManager.country_arr.length; c < lenC; c++) {
            if (MentorListManager.country_arr[c] == "Select") {
                continue;
            }
            countryArrDiv += '<option value="' + MentorListManager.country_arr[c] + '">' + MentorListManager.country_arr[c] + '</option>';
        }
        countryArrDiv += '</select>';
        /************end*************/
        /************industry array div start*************/
        var industryArrDiv = '';
        industryArrDiv += '<select name="industry" id="industry" >';
        industryArrDiv += '<option value="0" >Select</option>';
        for (var i = 0, len = MentorListManager.industry_arr.length; i < len; i++) {
            if (MentorListManager.industry_arr[i] == "Select") {
                continue;
            }
            industryArrDiv += '<option value="' + MentorListManager.industry_arr[i] + '">' + MentorListManager.industry_arr[i] + '</option>';
        }
        industryArrDiv += '</select>';
        /************end*************/
        advSrechFormTemplate = advSrechFormTemplate.replace(/\{\{country_arr\}\}/, MentorListManager.country_arr)
            .replace(/\{\{industry_arr\}\}/, MentorListManager.industry_arr)
            .replace(/\{\{countryArrDiv\}\}/, countryArrDiv)
            .replace(/\{\{industryArrDiv\}\}/, industryArrDiv);
        //end of for loop
        MaraMentor.ChangePageContent(advSrechFormTemplate);
        // For each loop on data and html
    },

    SetAdvSrchMentorPageHTMLFailure: function () {

    },
    RunAdvMentorSrch: function (pageNo, currentPage) {
    	wrapAnimation = _noAnimation;

        var search_user = $("#search_user").val();
        var country = $("#country").val();
        var industry = $("#industry").val();
        var userId = MaraMentor.sessionId;

        if (search_user == "" && industry == "" && country == "") {
            MaraMentor.showToastMsg("Please do not leave name field empty.");
            return false;
        }
        if (search_user == "" && industry == "0" && country == "0") {
            MaraMentor.showToastMsg("Please select any option.");
            return false;
        }
        var requestData = "pageNo=" + pageNo + "&search_user=" + search_user + "&country=" + country + "&industry=" + industry + "&userId=" + userId + "&func=advanceMentorScrh&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        //alert(requestData);
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MentorListManager.RunAdvMentorSrchSuccess, MentorListManager.RunAdvMentorSrchFailure)
    },

    RunAdvMentorSrchSuccess: function (resultData) {

        var myObjectAdvMentorList = JSON.parse(resultData);
        MentorListManager.runMentorRow = myObjectAdvMentorList.advMentorStatus;
        MentorListManager.advMentorStatus = myObjectAdvMentorList.advMentorStatus;
        MentorListManager.advPageNo = myObjectAdvMentorList.advPageNo;
        MentorListManager.advTotalPage = myObjectAdvMentorList.advTotalPage;
        MentorListManager.search_user = myObjectAdvMentorList.search_user;
        MentorListManager.country = myObjectAdvMentorList.country;
        MentorListManager.industry = myObjectAdvMentorList.industry;
        MentorListManager.SetRunAdvMentorSrchHTML();
    },

    RunAdvMentorSrchFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request please try again later');
        }

    },
    SetRunAdvMentorSrchHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/mentor.html", "", "GET", MentorListManager.SetRunAdvMentorSrchHTMLSuccess, MentorListManager.SetRunAdvMentorSrchHTMLFailure);
    },

    SetRunAdvMentorSrchHTMLSuccess: function (result) {

        RunAdvMentorSrchTemplate = result;
        runMentorListSrch = $(RunAdvMentorSrchTemplate).filter("#advanceSearch");
        runMentorListSrch = runMentorListSrch.html();
        runMentorload = $(RunAdvMentorSrchTemplate).filter("#mentorList");
        runMentorload = runMentorload.html();
        var runMentorload1 = "";
        var i = 0;
        if (MentorListManager.runMentorRow == "no") {
            runMentorload1 = "<div class='upDation'>Sorry, no members were found </div>";
        } else {
            var runMentorload1 = '';
            var UsersLists = '';
            runMentorload1 += '<div class="UsersListContainer">';
            for (x in MentorListManager.runMentorRow) {
                var followClick = "";
                var followImage = "";
                var followText = "";
                if (MentorListManager.runMentorRow[x].user_id == 1505) {
                     followClick = "";
                     followImage = "";
                     followText = "";
                }
                else {
                    if (MentorListManager.runMentorRow[x].Follow == "Follow") {
                        followClick = "FollowMentorManager.FollowMentor(" + MentorListManager.runMentorRow[x].user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow.png";
                        followText = "Follow";
                    } else {
                        followClick = "UnfollowMentorManager.UnfollowMentor(" + MentorListManager.runMentorRow[x].user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow_grey.png";
                        followText = "Unfollow";
                    }
                }

                if (MentorListManager.runMentorRow[x].user_id == 1505) {
                    followClick = "";
                    followImage = "";
                    followText = "";
                }

                UsersLists += runMentorload.replace(/\{\{userId\}\}/g, MentorListManager.runMentorRow[x].user_id)
                    .replace(/\{\{firstName\}\}/, MentorListManager.runMentorRow[x].first_name)
                    .replace(/\{\{last_name\}\}/, MentorListManager.runMentorRow[x].last_name)
                    .replace(/\{\{country\}\}/, MentorListManager.runMentorRow[x].country)
                    .replace(/\{\{industry\}\}/, MentorListManager.runMentorRow[x].industry)
                    //.replace(/\{\{Follow\}\}/, MentorListManager.runMentorRow[x].Follow)
                    .replace(/\{\{Follow\}\}/, followText)
                    .replace(/\{\{totalAll\}\}/g, MentorListManager.runMentorRow[x].totalMentees)
                    .replace(/\{\{userImage\}\}/, MentorListManager.runMentorRow[x].userImage)
                    .replace(/\{\{followClick\}\}/, followClick)
                    .replace(/\{\{followImage\}\}/, followImage);
                i++;

            }

            runMentorload1 += '</div>';

            if (parseInt(MentorListManager.advPageNo) == 1 && parseInt(MentorListManager.advTotalPage) > 1) {
                runMentorload1 += '<div class="loadMore" id="loadMoreButton" onclick="MentorListManager.mentorSearchNextPage(MentorListManager.search_user, MentorListManager.country, MentorListManager.industry, MentorListManager.advPageNo)"></div>';
            }



        }

        mainMentorListHtml = runMentorListSrch + runMentorload1;

        if (parseInt(MentorListManager.advPageNo) == 1) {
            MaraMentor.ChangePageContent(mainMentorListHtml);
            // $("#wrapContent").html(mainMentorListHtml);
        } else {
            //MaraMentor.ChangePageContent("<div class='upDation'>Sorry, no members were found</div>");
            //$("#wrapContent").html("<div class='upDation'>Sorry, no members were found</div>");
        }

        if (MentorListManager.runMentorRow == "no") {
            MaraMentor.ChangePageContent(runMentorload1);
        } else {


            $('.UsersListContainer').append(UsersLists).trigger("create");

            if (parseInt(MentorListManager.advPageNo) >= parseInt(MentorListManager.advTotalPage)) {
                $(".loadMore").remove();
            }
            MaraMentor.RefreshScrollBar();
        }

         MaraMentor.isMentorSearch = "true";
        MaraMentor.IsLoadMore = "false";
        MaraMentor.scrolltop = 1;


    },
    SetRunAdvMentorSrchHTMLFailure: function () {},
    mentorSearchNextPage: function (search_user, country, industry, pageNO) {
        //var pageNO = $("#currentPage").val();
        var newPage = parseInt(MentorListManager.advPageNo) + 1;
        var userId = MaraMentor.sessionId;
        var requestData = "pageNo=" + newPage + "&search_user=" + search_user + "&country=" + country + "&industry=" + industry + "&userId=" + userId + "&func=advanceMentorScrh&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MentorListManager.RunAdvMentorSrchSuccess, MentorListManager.RunAdvMentorSrchFailure)
    }
}
/******************** MentorList Data end **********************/

/******************** Mentee List Data start **********************/
var MenteeListManager = {

    advanceSearchMenteePage: function () {
    	wrapAnimation = _noAnimation;
        var loginId = MaraMentor.sessionId;
        var requestData = "loginId=" + loginId + "&searchFor=mentee" + "&func=mobileIndustryCountry&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";

        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MenteeListManager.advanceSearchMenteePageSuccess, MenteeListManager.advanceSearchMenteePageFailure);
    },
    advanceSearchMenteePageSuccess: function (resultData) {
        ///alert('yes');
        var myObjectAdvSrchMentee = JSON.parse(resultData);
        //alert(myObjectAdvSrchMentee.industries);
        MenteeListManager.IsSuccess = myObjectAdvSrchMentee.IsSuccess;
        if (myObjectAdvSrchMentee.IsSuccess == "true") {
            MenteeListManager.industry_arr = myObjectAdvSrchMentee.industries;
            MenteeListManager.country_arr = myObjectAdvSrchMentee.countries;
        } else {
            MenteeListManager.country_arr = new Array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, The Democratic Republic of the", "Cook Islands", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar ", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy ", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands ", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria ", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory", "Panama", "Papua New Guinea", "Paraguay", "Peru ", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts and Nevis ", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu ", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

            MenteeListManager.industry_arr = new Array("Agriculture", "Construction and Infrastructure", "Education and Training", "Information and Communication Technology (ICT)", "Investment and banking", "Leisure and Hospitality", "Manufacturing", "Media and Entertainment", "Mining and drilling", "Power and Energy", "Social Enterprise");
        } //end of else partmenteeSearchNextPage


        MenteeListManager.SetAdvSrchMenteePageHTML();
    },
    advanceSearchMenteePageFailure: function (resultData) {

        MaraMentor.showToastMsg('Could not process your request please try again later');

    },
    SetAdvSrchMenteePageHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/advSrchMentee.html", "", "GET", MenteeListManager.SetAdvSrchMenteePageHTMLSuccess, MenteeListManager.SetAdvSrchMenteePageHTMLFailure);
    },

    SetAdvSrchMenteePageHTMLSuccess: function (result) {
        //alert(result);
        advSrechFormTemplate = result;
        MaraMentor.PushPageRecord("advMenteeSearchpage");
        MaraMentor.ChangeHeaderText("Mentee Search");
        /************country array div start*************/
        var countryArrDiv = '';
        countryArrDiv += '<select name="country" id="country" >';
        countryArrDiv += '<option value="0" >Select</option>';
        for (var c = 0, lenC = MenteeListManager.country_arr.length; c < lenC; c++) {
            if (MenteeListManager.country_arr[c] == "Select") {
                continue;
            }
            countryArrDiv += '<option value="' + MenteeListManager.country_arr[c] + '">' + MenteeListManager.country_arr[c] + '</option>';
        }
        countryArrDiv += '</select>';
        /************end*************/
        /************industry array div start*************/
        var industryArrDiv = '';
        industryArrDiv += '<select name="industry" id="industry" >';
        industryArrDiv += '<option value="0" >Select</option>';
        for (var i = 0, len = MenteeListManager.industry_arr.length; i < len; i++) {
            if (MenteeListManager.industry_arr[i] == "Select") {
                continue;
            }
            industryArrDiv += '<option value="' + MenteeListManager.industry_arr[i] + '">' + MenteeListManager.industry_arr[i] + '</option>';
        }
        industryArrDiv += '</select>';
        /************end*************/
        advSrechFormTemplate = advSrechFormTemplate.replace(/\{\{country_arr\}\}/, MenteeListManager.country_arr)
            .replace(/\{\{industry_arr\}\}/, MenteeListManager.industry_arr)
            .replace(/\{\{countryArrDiv\}\}/, countryArrDiv)
            .replace(/\{\{industryArrDiv\}\}/, industryArrDiv);
        //end of for loop
        MaraMentor.ChangePageContent(advSrechFormTemplate);
        // For each loop on data and html
    },

    SetAdvSrchMenteePageHTMLFailure: function () {

    },
    AdvMenteeSrhRun: function (pageNo, currentPage) {
 	    	wrapAnimation = _noAnimation;

        var search_user = $("#search_user").val();
        var country = $("#country").val();
        var industry = $("#industry").val();

        var userId = MaraMentor.sessionId;
        if (search_user == "" && industry == "0" && country == "0") {
            MaraMentor.showToastMsg("Please select any option.");
            return false;
        }
        var requestData = "pageNo=" + pageNo + "&search_user=" + search_user + "&country=" + country + "&industry=" + industry + "&userId=" + userId + "&func=advanceMenteeScrh&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MenteeListManager.AdvMenteeSrhRunSuccess, MenteeListManager.AdvMenteeSrhRunFailure)
    },
    AdvMenteeSrhRunSuccess: function (resultData) {
        var myObjectAdvMenteeList = JSON.parse(resultData);
        MenteeListManager.advMenteeRes = myObjectAdvMenteeList.menteeRes;
        MenteeListManager.advMenteeStatus = myObjectAdvMenteeList.advMenteeStatus;
        MenteeListManager.pageNo = myObjectAdvMenteeList.pageNo;
        MenteeListManager.totalpage = myObjectAdvMenteeList.totalpage;
        MenteeListManager.search_user = myObjectAdvMenteeList.search_user;
        MenteeListManager.country = myObjectAdvMenteeList.country;
        MenteeListManager.industry = myObjectAdvMenteeList.industry;

        MenteeListManager.SetAdvMenteeSrhRunHTML();
    },
    AdvMenteeSrhRunFailure: function (resultData) {
        MaraMentor.showToastMsg('Could not process your request please try again later');
    },
    SetAdvMenteeSrhRunHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/mentee.html", "", "GET", MenteeListManager.SetAdvMenteeSrhRunHTMLSuccess, MenteeListManager.SetAdvMenteeSrhRunHTMLFailure);
    },

    SetAdvMenteeSrhRunHTMLSuccess: function (result) {
        // alert(result);
        AdvMenteeSrchRunTemplate = result;
        MenteeListSrchRun = $(AdvMenteeSrchRunTemplate).filter("#srchDiv");
        MenteeListSrchRun = MenteeListSrchRun.html();
        MenteeloadRun = $(AdvMenteeSrchRunTemplate).filter("#menteeload");
        MenteeloadRun = MenteeloadRun.html();
        // alert(MenteeloadRun);
        var MenteeloadRun1 = '';
        if (MenteeListManager.advMenteeStatus == "no") {

            MenteeloadRun1 = '<div class="upDation">Sorry, no members were found</div>';
        } else {
            // alert(JSON.stringify(MenteeListManager.advMenteeRes));
            // alert(MenteeListManager.advMenteeRes.length);
            var UsersLists = '';
            MenteeloadRun1 += '<div class="UsersListContainer">';
            for (var j = 0, lenMen = MenteeListManager.advMenteeStatus.length; j < lenMen; j++) {
                // alert(MenteeListManager.advMenteeRes[j]["userId"]);sdasdasd
                UsersLists += MenteeloadRun.replace(/\{\{firstName\}\}/, MenteeListManager.advMenteeStatus[j]["first_name"])
                    .replace(/\{\{userId\}\}/g, MenteeListManager.advMenteeStatus[j]["user_id"])
                    .replace(/\{\{last_name\}\}/, MenteeListManager.advMenteeStatus[j]["last_name"])
                    .replace(/\{\{country\}\}/, MenteeListManager.advMenteeStatus[j]["country"])
                    .replace(/\{\{industry\}\}/, MenteeListManager.advMenteeStatus[j]["industry"])
                    .replace(/\{\{userImage\}\}/, MenteeListManager.advMenteeStatus[j]["userImage"])
                //.replace(/\{\{Follow\}\}/, MenteeListManager.advMenteeStatus[j]["FollowRes"])
                .replace(/\{\{totalAll\}\}/, MenteeListManager.advMenteeStatus[j]["totalMentees"]);
                /* MenteeloadRun1 += MenteeloadRun.replace( /\{\{userId\}\}/, MenteeListManager.advMenteeRes[j].userId )
                .replace( /\{\{firstName\}\}/, MenteeListManager.advMenteeRes[j]["firstName"] )
                .replace( /\{\{last_name\}\}/, MenteeListManager.advMenteeRes[j]["last_name"] )
                .replace( /\{\{country\}\}/, MenteeListManager.advMenteeRes[j]["country"] )
                .replace( /\{\{industry\}\}/, MenteeListManager.advMenteeRes[j]["industry"] )
                .replace( /\{\{userImage\}\}/, MenteeListManager.advMenteeRes[j]["userImage"] )
                .replace( /\{\{Follow\}\}/, MenteeListManager.advMenteeRes[j]["FollowRes"] )
                .replace( /\{\{totalAll\}\}/, MenteeListManager.advMenteeRes[j]["totalMentors"]); */
            } //end of for loop
            MenteeloadRun1 += '</div>';

            if (parseInt(MenteeListManager.pageNo) == 1 && parseInt(MenteeListManager.totalpage) > 1) {
                MenteeloadRun1 += '<div class="loadMore" id="loadMoreButton" onclick="MenteeListManager.menteeSearchNextPage(MenteeListManager.search_user, MenteeListManager.country, MenteeListManager.industry, MenteeListManager.pageNo)"></div>';
            }



        }
        //alert(profileActivity);

        mainMenteeListHtml = MenteeloadRun1;
        MaraMentor.PushPageRecord("advMenteeSearchpageResult");
        MaraMentor.ChangeHeaderText("Mentee");
        //alert(mainMenteeListHtml);
        //end
        if (parseInt(MenteeListManager.pageNo) == 1) {
            //$("#wrapContent").html(mainMenteeListHtml);
            MaraMentor.ChangePageContent(mainMenteeListHtml);
        }
        if (MenteeListManager.advMenteeStatus == "no") {

            MenteeloadRun1 = '<div class="upDation">Sorry, no members were found</div>';
            MaraMentor.ChangePageContent(MenteeloadRun1);
        } else {

            $('.UsersListContainer').append(UsersLists).trigger("create");

            if (parseInt(MenteeListManager.pageNo) >= parseInt(MenteeListManager.totalpage)) {

                $(".loadMore").remove();
            }
             MaraMentor.RefreshScrollBar();

        }

        MaraMentor.isMenteeSearch = "true";
        MaraMentor.IsLoadMore = "false";
        MaraMentor.scrolltop = 1;

    },

    SetAdvMenteeSrhRunHTMLFailure: function () {

    },
    menteeSearchNextPage: function (search_user, country, industry, pageNO) {
        //var pageNO = $("#currentPage").val();
        // newPage = parseInt(pageNO) + 1;
        newPage = parseInt(MenteeListManager.pageNo) + 1;
        var userId = MaraMentor.sessionId;
        var requestData = "pageNo=" + newPage + "&search_user=" + search_user + "&country=" + country + "&industry=" + industry + "&userId=" + userId + "&func=advanceMenteeScrh&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", MenteeListManager.AdvMenteeSrhRunSuccess, MenteeListManager.AdvMenteeSrhRunFailure);
    },
}
/******************** MenteeList Data end **********************/

/******************** PostLike Data start **********************/
var PostLikeManager = {
    PostLike: function (itemId, pageLUrl, itemIndex) {
        PostLikeManager.sourcePage = pageLUrl;
        PostLikeManager.itemIndex = itemIndex;
        var userId = MaraMentor.sessionId;
        var requestData = "userId=" + userId + "&item_id=" + itemId + "&pageLUrl=" + pageLUrl + "&func=userLikeActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PostLikeManager.PostLikeSuccess, PostLikeManager.PostLikeFailure)
    },

    PostLikeSuccess: function (resultData) {
        //alert(resultData);
        var myObjectPostLike = JSON.parse(resultData);
        if (myObjectPostLike.IsSuccess == "true") {
            PostLikeManager.itemId = myObjectPostLike.itemId;
            PostLikeManager.pageUrl = myObjectPostLike.pageUrl;
            PostLikeManager.postLikeArr = myObjectPostLike.postAllRes;
            var isLiked = "";
            //alert(PostLikeManager.postLikeArr.returnResult);
            if (PostLikeManager.postLikeArr.returnResult == "Unlike") {
                $("#likeImage_" + PostLikeManager.itemId).attr("src", "assets/images/unlike.png");
                $("#likeText_" + PostLikeManager.itemId).text("Unlike");
                isLiked = "1";
            } else {
                $("#likeImage_" + PostLikeManager.itemId).attr("src", "assets/images/like.png");
                $("#likeText_" + PostLikeManager.itemId).text("Like");
                isLiked = "0";
            }

            $("#likeCount_" + PostLikeManager.itemId).text(PostLikeManager.postLikeArr.totalLikePost + " Likes");

                if (PostLikeManager.sourcePage == "profile") {
                ProfileManager.profileActivityArray[PostLikeManager.itemIndex]["totalLike"] = PostLikeManager.postLikeArr.totalLikePost;
                ProfileManager.profileActivityArray[PostLikeManager.itemIndex]["isLiked"] = isLiked;
            }
            if (PostLikeManager.sourcePage == "dashboard") {
                DashManager.dashboardDataArray[PostLikeManager.itemIndex]["totalLike"] = PostLikeManager.postLikeArr.totalLikePost;
                DashManager.dashboardDataArray[PostLikeManager.itemIndex]["isLiked"] = isLiked;
            }
            if (PostLikeManager.sourcePage == "singleActivity") {
            SingleActivityManager.singleDataArray["likeTotal"] = PostLikeManager.postLikeArr.totalLikePost;


            if (SingleActivityManager.itemSource == "profile") {
                ProfileManager.profileActivityArray[PostLikeManager.itemIndex]["totalLike"] = PostLikeManager.postLikeArr.totalLikePost;
                ProfileManager.profileActivityArray[PostLikeManager.itemIndex]["isLiked"] = isLiked;
            }
            if (SingleActivityManager.itemSource == "dashboard") {
                DashManager.dashboardDataArray[PostLikeManager.itemIndex]["totalLike"] = PostLikeManager.postLikeArr.totalLikePost;
                DashManager.dashboardDataArray[PostLikeManager.itemIndex]["isLiked"] = isLiked;
            }
        }

        } else {
            MaraMentor.showToastMsg(myObjectPostLike.ErrorMessage);
        }

    },

    PostLikeFailure: function (resultData) {

        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request please try again later');
        }

    },
}
/******************** PostLike Data end **********************/

/******************** PopupComment Data start **********************/
var PopupPostCommentManager = {
    PostComment: function (itemId, itemSource) {
        PopupPostCommentManager.itemSource = itemSource;

        var res = PopupPostCommentManager.non_empty_field('postComment');
        if (res == false) {
            MaraMentor.showToastMsg("Please do not leave the comment area blank");
            return;
        }

        if (MaraMentor.sessionId == "") {
            MaraMentor.showToastMsg("Could not process your request now,Pleae try again later");
            return;
        }

        var userId = MaraMentor.sessionId;
        PopupPostCommentManager.postComment = $("#postComment").val();

        var requestData = "userId=" + userId + "&post_comment=" + PopupPostCommentManager.postComment + "&itemId=" + itemId + "&func=userPostComment&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PopupPostCommentManager.PostCommentSuccess, PopupPostCommentManager.PostCommentFailure);
    },

    PostCommentSuccess: function (resultData) {
        var myObjectPostComment = JSON.parse(resultData);
        if (myObjectPostComment.IsSuccess == "true") {
            PopupPostCommentManager.itemId = myObjectPostComment.itemId;
            PopupPostCommentManager.commentRes = myObjectPostComment.commentRes;

            var commentTemplate = $("#commenTemplate").html();

            var deleteText = "";
            var deleteFunction = "";

            var editText = "";
            var editFunction = "";


            deleteText = "Delete";
            deleteFunction = "DeletePostCommentManager.DeletePostComment(" + PopupPostCommentManager.commentRes.CommentId + "," + PopupPostCommentManager.itemId + ")";

            editText = "Edit";
            editFunction = "EditPostCommentManager.EditPostCommentHtml(" + PopupPostCommentManager.commentRes.CommentId + "," + PopupPostCommentManager.itemId + ")";

            fragmentSingleActivityChild = "";

            fragmentSingleActivityChild += commentTemplate.replace(/\{\{commentId\}\}/g, PopupPostCommentManager.commentRes.CommentId)
                .replace(/\{\{commenterId\}\}/g, MaraMentor.sessionId)
                .replace(/\{\{user_id\}\}/, MaraMentor.sessionId)
                .replace(/\{\{contwrapChild\}\}/, PopupPostCommentManager.postComment)
                .replace(/\{\{userImage\}\}/, MaraMentor.userImage)
                .replace(/\{\{userName\}\}/, MaraMentor.displayName)
                .replace(/\{\{resDateTimeChild\}\}/, "Just now")
                .replace(/\{\{totalCommentLikes\}\}/, "0")
                .replace(/\{\{deleteText\}\}/, deleteText)
                .replace(/\{\{deleteFunction\}\}/, deleteFunction)
                .replace(/\{\{editText\}\}/, editText)
                .replace(/\{\{editFunction\}\}/, editFunction);

            $("#commentsDiv").prepend(fragmentSingleActivityChild);
            //$("#commentCount_" + PopupPostCommentManager.itemId).text(PopupPostCommentManager.commentRes.totalCommPost + " Comments");
            $("#commentCount_" + PopupPostCommentManager.itemId).text(PopupPostCommentManager.commentRes.totalCommPost + " Comments");
            $("#postComment").val("");

            if (PostCommentManager.itemSource == "profile") {
                ProfileManager.profileActivityArray[SingleActivityManager.itemIndex]["totalComment"] = PopupPostCommentManager.commentRes.totalCommPost;
            }
            if (PostCommentManager.itemSource == "dashboard") {
                DashManager.dashboardDataArray[SingleActivityManager.itemIndex]["totalComment"] = PopupPostCommentManager.commentRes.totalCommPost;
            }

            $("#noComments").hide();

        } else {
            MaraMentor.showToastMsg(myObjectPostComment.ErrorMessage);
        }
    },
    PostCommentFailure: function (resultData) {
        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request please try again later');
        }
    },
    non_empty_field: function (pcon) {
        var pcontent = document.getElementById(pcon).value;
        if (pcontent == '') {
            return false;
        }
    },
}
/******************** PopupPostComment Data end **********************/

/******************** PostComment Data start **********************/
var PostCommentManager = {
    PostComment: function (itemId, itemSource) {
        PostCommentManager.itemSource = itemSource;

        var res = PostCommentManager.non_empty_field('postComment');
        if (res == false) {
            MaraMentor.showToastMsg("Please do not leave the comment area blank");
            return;
        }

        if (MaraMentor.sessionId == "") {
            MaraMentor.showToastMsg("Could not process your request now,Pleae try again later");
            return;
        }

        var userId = MaraMentor.sessionId;
        PostCommentManager.postComment = $("#postComment").val();

        var requestData = "userId=" + userId + "&post_comment=" + PostCommentManager.postComment + "&itemId=" + itemId + "&func=userPostComment&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PostCommentManager.PostCommentSuccess, PostCommentManager.PostCommentFailure);
    },

    PostCommentSuccess: function (resultData) {
        var myObjectPostComment = JSON.parse(resultData);
        if (myObjectPostComment.IsSuccess == "true") {
            PostCommentManager.itemId = myObjectPostComment.itemId;
            PostCommentManager.commentRes = myObjectPostComment.commentRes;

            var commentTemplate = $("#commenTemplate").html();

            var deleteText = "";
            var deleteFunction = "";

            var editText = "";
            var editFunction = "";


            deleteText = "Delete";
            deleteFunction = "DeletePostCommentManager.DeletePostComment(" + PostCommentManager.commentRes.CommentId + "," + PostCommentManager.itemId + ")";

            editText = "Edit";
            editFunction = "EditPostCommentManager.EditPostCommentHtml(" + PostCommentManager.commentRes.CommentId + "," + PostCommentManager.itemId + ")";

            fragmentSingleActivityChild = "";

            fragmentSingleActivityChild += commentTemplate.replace(/\{\{commentId\}\}/g, PostCommentManager.commentRes.CommentId)
                .replace(/\{\{commenterId\}\}/g, MaraMentor.sessionId)
                .replace(/\{\{user_id\}\}/, MaraMentor.sessionId)
                .replace(/\{\{contwrapChild\}\}/, PostCommentManager.postComment)
                .replace(/\{\{userImage\}\}/, MaraMentor.userImage)
                .replace(/\{\{userName\}\}/, MaraMentor.displayName)
                .replace(/\{\{resDateTimeChild\}\}/, "Just now")
                .replace(/\{\{totalCommentLikes\}\}/, "0")
                .replace(/\{\{deleteText\}\}/, deleteText)
                .replace(/\{\{deleteFunction\}\}/, deleteFunction)
                .replace(/\{\{editText\}\}/, editText)
                .replace(/\{\{editFunction\}\}/, editFunction);

            $("#commentsDiv").prepend(fragmentSingleActivityChild);
            $("#commentCount_" + PostCommentManager.itemId).text(PostCommentManager.commentRes.totalCommPost + " Comments");

            $("#postComment").val("");

            if (PostCommentManager.itemSource == "profile") {
                ProfileManager.profileActivityArray[SingleActivityManager.itemIndex]["totalComment"] = PostCommentManager.commentRes.totalCommPost;
            }
            if (PostCommentManager.itemSource == "dashboard") {
                DashManager.dashboardDataArray[SingleActivityManager.itemIndex]["totalComment"] = PostCommentManager.commentRes.totalCommPost;
            }
            SingleActivityManager.singleDataArray["totalComment"] = PostCommentManager.commentRes.totalCommPost;
            MaraMentor.RefreshScrollBar();

        } else {
            MaraMentor.showToastMsg(myObjectPostComment.ErrorMessage);
        }
    },
    PostCommentFailure: function (resultData) {
        if (resultData.success == "true") {
            MaraMentor.showToastMsg('Could not process your request please try again later');
        }
    },
    non_empty_field: function (pcon) {
        var pcontent = document.getElementById(pcon).value;
        if (pcontent == '') {
            return false;
        }
    },
}
/******************** PostComment Data end **********************/

/******************** postUpdate Data start **********************/
var PostUpdateManager = {
    PostUpdate: function (pageSource) {
        var res1 = PostUpdateManager.non_empty_field('post_content');
        if (res1 == false) {
            MaraMentor.showToastMsg("Please enter some content to post");
            return;
        }
        var profile_id = MaraMentor.sessionId;
        var post_content = $("#post_content").val();
        PostUpdateManager.pageSource = pageSource;
        var requestData = "userId=" + profile_id + "&post_content=" + post_content + "&page_url=" + pageSource + "&func=userPostUpdateActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PostUpdateManager.PostUpdateSuccess, PostUpdateManager.PostUpdateFailure)
    },

    PostUpdateSuccess: function (resultData) {
        $("#post_content").val("");
        var myObjectPostUpdate = JSON.parse(resultData);
        if (myObjectPostUpdate.IsSuccess == "true") {
            DashManager.pageNo = 1;
            if (PostUpdateManager.pageSource = "dashboard") {
                DashManager.GetDashboardData(1);
            } else if (PostUpdateManager.pageSource = "profile") {
                PostUpdateManager.u_id = myObjectPostUpdate.userId;
                ProfileManager.Profile(PostUpdateManager.u_id, 1);
            }
        } else {
            MaraMentor.showToastMsg(myObjectPostUpdate.ErrorMessage);
        }
    },
    PostUpdateFailure: function (resultData) {
        MaraMentor.showToastMsg(myObjectPostUpdate.ErrorMessage);
    },
    non_empty_field: function (pcon) {
        var pcontent = document.getElementById(pcon).value;
        if (pcontent == '') {
            return false;
        }
    },
}
/******************** postUpdate Data end **********************/
/******************** deletePostComment  start **********************/
var DeletePostCommentManager = {
    DeletePostComment: function (cmt_id, topic_id) {
        DeletePostCommentManager.topicId = topic_id;
        var userId = MaraMentor.sessionId;
        var requestData = "userId=" + userId + "&topic_id=" + topic_id + "&cmt_id=" + cmt_id + "&func=deleteUserPostComment&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DeletePostCommentManager.DeletePostCommentSuccess, DeletePostCommentManager.DeletePostCommentFailure)
    },
    DeletePostCommentSuccess: function (resultData) {
        var myObjectDeletePostComment = JSON.parse(resultData);
        if (myObjectDeletePostComment.IsSuccess == "true") {
            DeletePostCommentManager.itmId = myObjectDeletePostComment.del_itemId;
            DeletePostCommentManager.comm = myObjectDeletePostComment.commt_id;
            $("#comment_" + DeletePostCommentManager.comm).remove();

            var previousComment = parseInt(SingleActivityManager.singleDataArray["totalComment"]);

            if (previousComment > 0) {
                previousComment = previousComment - 1;
            }

            $("#commentCount_" + DeletePostCommentManager.topicId).text(previousComment + " Comments");

            if (SingleActivityManager.itemSource == 'dashboard') {
                DashManager.dashboardDataArray[SingleActivityManager.itemIndex]["totalComment"] = previousComment;
            }
            if (SingleActivityManager.itemSource == 'profile') {
                ProfileManager.profileActivityArray[SingleActivityManager.itemIndex]["totalComment"] = previousComment;
            }
            SingleActivityManager.singleDataArray["totalComment"] = previousComment;

        } else {
            MaraMentor.showToastMsg(myObjectDeletePostComment.ErrorMessage);
        }
    },
    DeletePostCommentFailure: function (resultData) {
        MaraMentor.showToastMsg(myObjectDeletePostComment.ErrorMessage);
    },
}
/******************** deletePostComment  end **********************/
/******************** followMentor  code  start **********************/
var FollowMentorManager = {
    pageSource: "",
    FollowMentor: function (leader_id, getPage, itemIndex) {
        FollowMentorManager.mentorId = leader_id;
        FollowMentorManager.itemIndex = itemIndex;
        FollowMentorManager.pageSource = getPage;
        var loginUid = MaraMentor.sessionId;
        var requestData = "leader_id=" + leader_id + "&loginUid=" + loginUid + "&getPage=" + getPage + "&func=mentorFollowUser&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", FollowMentorManager.FollowMentorSuccess, FollowMentorManager.FollowMentorFailure)
    },
    FollowMentorSuccess: function (resultData) {
        var myObjectFollowMentor = JSON.parse(resultData);
        if (myObjectFollowMentor.IsSuccess == "true") {
            $("#followText_" + FollowMentorManager.mentorId).text("Unfollow");
            $("#followImage_" + FollowMentorManager.mentorId).attr("src", "assets/images/follow_grey.png");

            $("#followClick_" + FollowMentorManager.mentorId).attr("onclick", "UnfollowMentorManager.UnfollowMentor(" + FollowMentorManager.mentorId + ",'mentor'," + FollowMentorManager.itemIndex + ")");

            var prevFollower = $("#followCount_" + FollowMentorManager.mentorId).attr("value");
            newFollower = parseInt(prevFollower) + 1;
            $("#followCount_" + FollowMentorManager.mentorId).attr("value", newFollower).text(newFollower);


            if (FollowMentorManager.pageSource == "mentor") {
                //MentorListManager.mentorListArr[FollowMentorManager.itemIndex].Follow = "Unfollow";
            }
            if (FollowMentorManager.pageSource == "advcMentor") {
                //MentorListManager.runMentorRow[FollowMentorManager.itemIndex].Follow = "Unfollow";
            }
        } else {
            var myObjectFollowMentor = JSON.parse(resultData);
            MaraMentor.showToastMsg(myObjectFollowMentor.ErrorMessage);
        }

    },
    FollowMentorFailure: function (resultData) {
        var myObjectFollowMentor = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectFollowMentor.ErrorMessage);
    },
}
/******************** followMentor end **********************/
/******************** unfollowMentor  code  start **********************/
var UnfollowMentorManager = {
    UnfollowMentor: function (leader_id, getPage, itemIndex) {
        UnfollowMentorManager.mentorId = leader_id;
        UnfollowMentorManager.itemSource = getPage;
        UnfollowMentorManager.itemIndex = itemIndex;
        var loginUid = MaraMentor.sessionId;
        var requestData = "leader_id=" + leader_id + "&loginUid=" + loginUid + "&getPage=" + getPage + "&func=mentorUnfollowUser&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", UnfollowMentorManager.UnfollowMentorSuccess, UnfollowMentorManager.UnfollowMentorFailure)
    },
    UnfollowMentorSuccess: function (resultData) {

        var myObjectUnfollowMentor = JSON.parse(resultData);
        if (myObjectUnfollowMentor.IsSuccess == "true") {
            $("#followText_" + UnfollowMentorManager.mentorId).text("Follow");
            var prevFollower = $("#followCount_" + UnfollowMentorManager.mentorId).attr("value");
            newFollower = parseInt(prevFollower) - 1;

            $("#followCount_" + UnfollowMentorManager.mentorId).attr("value", newFollower).text(newFollower);

            $("#followImage_" + UnfollowMentorManager.mentorId).attr("src", "assets/images/follow.png");

            $("#followClick_" + UnfollowMentorManager.mentorId).attr("onclick", "FollowMentorManager.FollowMentor(" + UnfollowMentorManager.mentorId + ",'mentor'," + UnfollowMentorManager.itemIndex + ")");
            if (UnfollowMentorManager.itemSource == "mentor") {
                //MentorListManager.mentorListArr[UnfollowMentorManager.itemIndex].Follow = "Follow";
            }
            if (UnfollowMentorManager.itemSource == "advcMentor") {
                //MentorListManager.runMentorRow[UnfollowMentorManager.itemIndex].Follow = "Follow";
            }
        } else {
            MaraMentor.showToastMsg(myObjectUnfollowMentor.ErrorMessage);
        }

    },
    UnfollowMentorFailure: function (resultData) {
        var myObjectUnfollowMentor = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectUnfollowMentor.ErrorMessage);
    },
}
/******************** followMentor end **********************/
/******************** DeleteMyActivity  code  start **********************/
var DeleteMyActivityManager = {
    DeleteMyActivity: function (actvity_id, userID, ItemIndex, Pagetype) {
        DeleteMyActivityManager.itemIndex = ItemIndex;
        DeleteMyActivityManager.Pagetype = Pagetype;
        var userId = userID;
        var requestData = "actvity_id=" + actvity_id + "&userId=" + userId + "&func=DeleteMyActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DeleteMyActivityManager.DeleteMyActivitySuccess, DeleteMyActivityManager.DeleteMyActivityFailure)
    },
    DeleteMyActivitySuccess: function (resultData) {

        var myObjectDeleteMyActivity = JSON.parse(resultData);
        if (myObjectDeleteMyActivity.IsSuccess == "true") {
            $("#activity_" + myObjectDeleteMyActivity.actvity_id).remove();
            if (DeleteMyActivityManager.Pagetype == "dashboard") {
                DashManager.dashboardDataArray.splice(DeleteMyActivityManager.itemIndex, 1);
                DashManager.dashboardTotalArray = DashManager.dashboardTotalArray - 1;
            }
            if (DeleteMyActivityManager.Pagetype == "profile") {
                ProfileManager.profileActivityArray.splice(DeleteMyActivityManager.itemIndex, 1);
                ProfileManager.profileTotalArray = ProfileManager.profileTotalArray - 1;
            }

        } else {
            MaraMentor.showToastMsg(myObjectDeleteMyActivity.ErrorMessage);
        }
    },
    DeleteMyActivityFailure: function (resultData) {
        var myObjectDeleteMyActivity = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectDeleteMyActivity.ErrorMessage);
    },
}
/******************** DeleteMyActivity end **********************/
/******************** likeUnlikePostComment  code  start **********************/
var LikeUnlikePostCommentManager = {
    LikeUnlikePostComment: function (comment_id, commenter_userId, type) {
        LikeUnlikePostCommentManager.commentId = comment_id;
        var userId = MaraMentor.sessionId;
        var requestData = "comment_id=" + comment_id + "&commenter_userId=" + commenter_userId + "&userId=" + userId + "&type=" + type + "&func=likeUnlikePostComment&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", LikeUnlikePostCommentManager.LikeUnlikePostCommentSuccess, LikeUnlikePostCommentManager.LikeUnlikePostCommentFailure)
    },
    LikeUnlikePostCommentSuccess: function (resultData) {
        var myObjectLikeUnlikePostComment = JSON.parse(resultData);
        if (myObjectLikeUnlikePostComment.IsSuccess == "true") {
            if (myObjectLikeUnlikePostComment.action == 'like') {
                //Now Unlike
                $("#commentLike_" + LikeUnlikePostCommentManager.commentId).attr("onclick", "LikeUnlikePostCommentManager.LikeUnlikePostComment(" + LikeUnlikePostCommentManager.commentId + "," + MaraMentor.sessionId + ",'unlike')").text("Unlike");

            } else {
                //Now Like
                $("#commentLike_" + LikeUnlikePostCommentManager.commentId).attr("onclick", "LikeUnlikePostCommentManager.LikeUnlikePostComment(" + LikeUnlikePostCommentManager.commentId + "," + MaraMentor.sessionId + ",'like')").text("Like");
            }

            $("#commentLikeCount_" + LikeUnlikePostCommentManager.commentId).text("Likes (" + myObjectLikeUnlikePostComment.total_likes + ") . ");

        } else {
            MaraMentor.showToastMsg(myObjectLikeUnlikePostComment.ErrorMessage);
        }
    },
    LikeUnlikePostCommentFailure: function (resultData) {
        var myObjectLikeUnlikePostComment = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectLikeUnlikePostComment.ErrorMessage);
    },
}
/******************** likeUnlikePostComment end **********************/

/******************** EditPostComment  code  start **********************/
var EditPostCommentManager = {
    EditPostComment: function (cmt_id, topic_id) {
        var txt = $("#commentTxtBox_" + cmt_id).val();
        var wSpace = txt.indexOf(" ")

        if (txt == "") {
            $("#errorDesc").html("Comment can not be blank");
            $("#popupDialog").popup("open");
            return false
        }
        var userId = MaraMentor.sessionId;
        var requestData = "userId=" + userId + "&comment=" + txt + "&topic_id=" + topic_id + "&cmt_id=" + cmt_id + "&func=editUserPostComment&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", EditPostCommentManager.EditPostCommentSuccess, EditPostCommentManager.EditPostCommentFailure)
    },
    EditPostCommentSuccess: function (resultData) {
        var myObjectEditPostComment = JSON.parse(resultData);
        if (myObjectEditPostComment.IsSuccess == "true") {
            var cmt_id = myObjectEditPostComment.cmt_id;
            var txt = myObjectEditPostComment.comment;
            $("#comment_text_" + cmt_id).html(txt.trim());
            //document.getElementById("EditBtnComment_" + myObjectEditPostComment.cmt_id).style.display = "block";
            $("#comment_edit_" + cmt_id).html("");
            document.getElementById("comment_content_" + cmt_id).style.display = "block";
        } else {
            MaraMentor.showToastMsg(myObjectEditPostComment.ErrorMessage);
        }
    },
    EditPostCommentFailure: function (resultData) {
        var myObjectEditPostComment = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectEditPostComment.ErrorMessage);
    },
    CancelEditPostCommentHtml: function (cmt_id, topic_id) {

        document.getElementById("comment_content_" + cmt_id).style.display = "block";
        $("#comment_edit_" + cmt_id).html("");
    },
    EditPostCommentHtml: function (cmt_id, topic_id) {
        document.getElementById("comment_content_" + cmt_id).style.display = "none";
        var txt = $("#comment_text_" + cmt_id).html();
        var data = '<textarea id="commentTxtBox_' + cmt_id + '" name="commentTxtBox_' + cmt_id + '">' + txt.trim() + '</textarea>' +
            '<img src="assets/images/tick.png" class="tick" onclick="EditPostCommentManager.EditPostComment(\'' + cmt_id + '\',\'' + topic_id + '\')">' +
            '<img src="assets/images/cross.png" class="cross" onclick="EditPostCommentManager.CancelEditPostCommentHtml(\'' + cmt_id + '\',\'' + topic_id + '\')">';
        $("#comment_edit_" + cmt_id).html(data);
        // Header Footer Fix for login screen
        /*$('input[type="text"], textarea, input[type="password"], select').focus(function (){
             if(MaraMentor.isLogin == "true"){
                $("#footer").css("display", "none");
            }
        });
        $('input[type="text"], textarea, input[type="password"],select').blur(function () {
            if(MaraMentor.isLogin == "true"){
                $("#footer").css("display", "block");
            }
        });  */
    }
}
/******************** EditPostComment end **********************/

/******************** MoreScreen  code  start **********************/
var MoreScreenManager = {
    GetMoreScreenHtml: function () {

        MaraMentor.MakeAjaxCallHTML("views/more.html", "", "GET", MoreScreenManager.MoreScreenHtmlSuccess, MoreScreenManager.MoreScreenHtmlError);
    },
    MoreScreenHtmlSuccess: function (result) {
        var finalHTML = "";

        MaraMentor.backPages.length = 0;
        MaraMentor.PushPageRecord('more');
        MaraMentor.AcitivateMenu("more");
        MaraMentor.ChangeHeaderText("More");

        finalHTML = result.replace(/\{\{userImage\}\}/, MaraMentor.userImage)
            .replace(/\{\{fullName\}\}/, MaraMentor.displayName)
            .replace(/\{\{role\}\}/, MaraMentor.userRole)
            .replace(/\{\{country\}\}/, MaraMentor.userCountry)
            .replace(/\{\{industry\}\}/, MaraMentor.userIndustry);

        MaraMentor.ChangePageContent(finalHTML);

    },
    MoreScreenHtmlError: function (result) {

    },

    SetUpExternalLinks: function(){
        $("#wrapContent").find("a").each(function(index){
          var link = $.trim($(this).attr("href"));
          if(link != "javascript:void(0)" && link!="#" && link != "javascript:void(0);"){
            $(this).attr("href","javascript:void(0)");
            $(this).bind("click", function(e){openUrl(link)});
          }
        })
    },
    /************** Fetch pages of website *****************/

    SetSitePageHTML: function (PageName) {
        MoreScreenManager.pageName = PageName;
        var data = "page_type=" + PageName + "&func=StaticPageContent&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, data, "POST", MoreScreenManager.SetSitePageHTMLSuccess, MoreScreenManager.SetSitePageHTMLFailure);
    },

    SetSitePageHTMLSuccess: function (resultData) {
        var myPage = JSON.parse(resultData);
        if (myPage.IsSuccess == "true") {
            MaraMentor.ChangePageContent(myPage.Content);

            MaraMentor.PushPageRecord("staticPages");
            MaraMentor.ChangeHeaderText(MoreScreenManager.pageName);
        }
    },
    SetSitePageHTMLFailure: function (resultData) {

    }
}
/******************** MoreScreen  code  start **********************/

/******************** inbox   code  start **********************/
var InboxPrivateMessageManager = {
    InboxPrivateMessage: function (pageNo) {
        //alert(pageNo);
        wrapAnimation = _noAnimation;
        var user_id = MaraMentor.sessionId;
        var box_nm = 'inbox';
        //var pageNo = 1;
        var requestData = "user_id=" + user_id + "&box_nm=" + box_nm + "&pageNo=" + pageNo + "&func=inboxPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", InboxPrivateMessageManager.InboxPrivateMessageSuccess, InboxPrivateMessageManager.InboxPrivateMessageFailure)
    },
    InboxPrivateMessageSuccess: function (resultData) {
        var myObjectInboxPrivateMessage = JSON.parse(resultData);
        if(myObjectInboxPrivateMessage.IsSuccess=="true")
        {
            InboxPrivateMessageManager.msgDataArray = myObjectInboxPrivateMessage.msgArray;
            InboxPrivateMessageManager.pageNo = myObjectInboxPrivateMessage.pageNo;
            InboxPrivateMessageManager.totalArr = InboxPrivateMessageManager.msgDataArray.length;
            InboxPrivateMessageManager.totalMsgpages = myObjectInboxPrivateMessage.totalMsgpages;
        }
        else
        {
            MaraMentor.showToastMsg(myObjectInboxPrivateMessage.ErrorMessage);
        }
        InboxPrivateMessageManager.SetInboxPrivateMessageHTML();
    },
    InboxPrivateMessageFailure: function (resultData) {
        var myObjectInboxPrivateMessage = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectInboxPrivateMessage.ErrorMessage);
    },
    SetInboxPrivateMessageHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/message_list.html", "", "GET", InboxPrivateMessageManager.SetInboxPrivateMessageHTMLSuccess, InboxPrivateMessageManager.SetInboxPrivateMessageHTMLFailure);
    },
    SetInboxPrivateMessageHTMLSuccess: function (result) {
        messageTemplate = result;
        MaraMentor.AcitivateMenu("messages");
        MaraMentor.backPages.length = 0;
        MaraMentor.PushPageRecord('inbox');
        MaraMentor.ChangeHeaderText('Messages');
        tabDiv = $(messageTemplate).filter("#tabing");
        mainMessage = $(messageTemplate).filter("#messageWrap");

        // mesaage data start..........
        mainMessage = mainMessage.html();
  tabDiv = tabDiv.html();
  var box_nm = 'inbox';
  var boxCon1 = (box_nm == "inbox") ? 'current' : '';
  tabDiv = tabDiv.replace(/\{\{boxCon1\}\}/, boxCon1);
  var formDiv = '';
  var mainMessage1 = "";
  var mainMessage2 = "";
        var i = 0;
        if (InboxPrivateMessageManager.msgDataArray.msg == "no") {
            mainMessage2 = "<div class='upDation'>Sorry, no messages were found. </div>";
        } else {
            var mainMessage2 = '';
   mainMessage2 += '<div class="UsersListContainer">';
  for (var m = 0, lenm=InboxPrivateMessageManager.totalArr; m < lenm; m++)
  {
   var formDiv = '<span>From:</span><h2 class="nospace">'+InboxPrivateMessageManager.msgDataArray[m]['from_user']+'</h2>';
   mainMessage1 += mainMessage.replace(/\{\{id\}\}/, InboxPrivateMessageManager.msgDataArray[m]['id'])
            .replace(/\{\{thread_id\}\}/, InboxPrivateMessageManager.msgDataArray[m]['thread_id'])
            .replace(/\{\{sender_id\}\}/, InboxPrivateMessageManager.msgDataArray[m]['sender_id'])
            .replace(/\{\{subject\}\}/, InboxPrivateMessageManager.msgDataArray[m]['subject'])
            .replace(/\{\{message\}\}/, InboxPrivateMessageManager.msgDataArray[m]['message'])
   .replace(/\{\{date_sent\}\}/, InboxPrivateMessageManager.msgDataArray[m]['date_sent'])
   .replace(/\{\{sender_name\}\}/, InboxPrivateMessageManager.msgDataArray[m]['sender_name'])
   .replace(/\{\{from_user\}\}/, formDiv)
   .replace(/\{\{subjectContent\}\}/, InboxPrivateMessageManager.msgDataArray[m]['subjectContent'])
   .replace(/\{\{sessionId\}\}/, MaraMentor.sessionId)
    .replace(/\{\{messageContent\}\}/, InboxPrivateMessageManager.msgDataArray[m]['messageContent'])
            .replace(/\{\{userImage_url\}\}/, InboxPrivateMessageManager.msgDataArray[m]['userImage_url']);
    i++;
  }


         mainMessage2 += '</div>';
            if (parseInt(InboxPrivateMessageManager.pageNo) == 1 && parseInt(InboxPrivateMessageManager.totalMsgpages) > 1) {
                mainMessage2 += '<div class="loadMore" id="loadMoreButton" onclick="InboxPrivateMessageManager.InboxNextPage()"></div>';
            }
  }
        if (i == 0) {
            mainMessage2 = "<div class='upDation'>Sorry, no messages were found.</div>";
        }
  mainMessageListHtml = tabDiv + mainMessage2;

        if (parseInt(InboxPrivateMessageManager.pageNo) == 1) {
            MaraMentor.ChangePageContent(mainMessageListHtml);
        }

        $('.UsersListContainer').append(mainMessage1).trigger("create");

        if (parseInt(InboxPrivateMessageManager.pageNo) >= parseInt(InboxPrivateMessageManager.totalMsgpages)) {

            $(".loadMore").remove();
        }
        MaraMentor.IsLoadMore = "false";
        MaraMentor.RefreshScrollBar();

    },
    SetInboxPrivateMessageHTMLFailure: function () {

    },
   InboxNextPage: function () {
        var newPage = parseInt(InboxPrivateMessageManager.pageNo)+1;
        var user_id = MaraMentor.sessionId;
        var box_nm = 'inbox';
        var requestData = "user_id=" + user_id + "&box_nm=" + box_nm + "&pageNo=" + newPage + "&func=inboxPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", InboxPrivateMessageManager.InboxPrivateMessageSuccess, InboxPrivateMessageManager.InboxPrivateMessageFailure)
    },
}
/******************** inbox end **********************/

/******************** sentbox   code  start **********************/
var SentboxPrivateMessageManager = {
    SentboxPrivateMessage: function (pageNo) {
    	wrapAnimation = _noAnimation;
        var user_id = MaraMentor.sessionId;
        var box_nm = 'sentbox';
        var requestData = "user_id=" + user_id + "&box_nm=" + box_nm + "&pageNo=" + pageNo +
            "&func=inboxPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", SentboxPrivateMessageManager.SentboxPrivateMessageSuccess, SentboxPrivateMessageManager.SentboxPrivateMessageFailure)
    },
    SentboxPrivateMessageSuccess: function (resultData) {
        var myObjectSentboxPrivateMessage = JSON.parse(resultData);
        if (myObjectSentboxPrivateMessage.IsSuccess == "true") {

            SentboxPrivateMessageManager.msgDataArray = myObjectSentboxPrivateMessage.msgArray;
            SentboxPrivateMessageManager.pageNo = myObjectSentboxPrivateMessage.pageNo;
            SentboxPrivateMessageManager.totalMsgpages = myObjectSentboxPrivateMessage.totalMsgpages;
        } else {
            MaraMentor.showToastMsg(myObjectSentboxPrivateMessage.ErrorMessage);
        }
        SentboxPrivateMessageManager.SetSentboxPrivateMessageHTML();
    },
    SentboxPrivateMessageFailure: function (resultData) {
        var myObjectSentboxPrivateMessage = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectSentboxPrivateMessage.ErrorMessage);
    },
    SetSentboxPrivateMessageHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/message_list.html", "", "GET", SentboxPrivateMessageManager.SetSentboxPrivateMessageHTMLSuccess,SentboxPrivateMessageManager.SetSentboxPrivateMessageHTMLFailure);
    },
    SetSentboxPrivateMessageHTMLSuccess: function (result) {
        messageTemplate = result;
  MaraMentor.AcitivateMenu("messages");
        MaraMentor.PushPageRecord('sentbox');
        MaraMentor.ChangeHeaderText('Messages');
  tabDiv = $(messageTemplate).filter("#tabing");
        mainMessage = $(messageTemplate).filter("#messageWrap");

        // mesaage data start..........
        mainMessage = mainMessage.html();
  tabDiv = tabDiv.html();
  var box_nm = 'sentbox';
  var boxCon1 = '';
  var boxCon2 = (box_nm == "sentbox") ? 'current' : '';
  tabDiv = tabDiv.replace(/\{\{boxCon2\}\}/, boxCon2);
  var mainMessage1 = "";
  var formDiv = '';
  var mainMessage2 = '';
  var i = 0;
  if (SentboxPrivateMessageManager.msgDataArray.msg == "no") {
            mainMessage2 = "<div class='upDation'>Sorry, no messages were found. </div>";
        } else {
            var mainMessage2 = '';
   mainMessage2 += '<div class="UsersListContainer">';

  for (var m = 0, lenm=SentboxPrivateMessageManager.msgDataArray.length; m < lenm; m++)
  {

   //var formDiv = '<span>To:</span><h2 class="nospace">'+SentboxPrivateMessageManager.msgDataArray[m]['from_user']+'</h2>';
   // To start here

   var toUser ='';
   for (t in SentboxPrivateMessageManager.msgDataArray[m]['from_user'])
   {
    if(SentboxPrivateMessageManager.msgDataArray[m]['from_user'][t] === null)
    {
     toUser += '';
    } else {
     toUser += SentboxPrivateMessageManager.msgDataArray[m]['from_user'][t]+',';
    }
   }
   toUser1 = toUser.replace(/,(?=[^,]*$)/, '');
   var formDiv = '<span>To:</span><h2 class="nospace">'+toUser1+'</h2>';
   // end
   mainMessage1 += mainMessage.replace(/\{\{id\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['id'])
            .replace(/\{\{thread_id\}\}/,SentboxPrivateMessageManager.msgDataArray[m]['thread_id'])
            .replace(/\{\{sender_id\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['sender_id'])
            .replace(/\{\{subject\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['subject'])
            .replace(/\{\{message\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['message'])
   .replace(/\{\{date_sent\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['date_sent'])
   .replace(/\{\{sender_name\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['sender_name'])
   .replace(/\{\{from_user\}\}/, formDiv)
   .replace(/\{\{subjectContent\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['subjectContent'])
   .replace(/\{\{sessionId\}\}/, MaraMentor.sessionId)
    .replace(/\{\{messageContent\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['messageContent'])
            .replace(/\{\{userImage_url\}\}/, SentboxPrivateMessageManager.msgDataArray[m]['userImage_url']);
   i++;
  }
  mainMessage2 += '</div>';


            if (parseInt(SentboxPrivateMessageManager.pageNo) == 1 && parseInt(SentboxPrivateMessageManager.totalMsgpages) > 1) {
                mainMessage2 += '<div class="loadMore" id="loadMoreButton" onclick="SentboxPrivateMessageManager.SentNextPage()"></div>';
            }
  // load more div here..
  }

        if (i == 0) {
            mainMessage2 = "<div class='upDation'>Sorry, no messages were found.</div>";
        }

        mainMentorListHtml = tabDiv + mainMessage2;
        MaraMentor.IsLoadMore = "false";
        if (parseInt(SentboxPrivateMessageManager.pageNo) == 1) {
            MaraMentor.ChangePageContent(mainMentorListHtml);
        }
         $('.UsersListContainer').append(mainMessage1);
         MaraMentor.RefreshScrollBar();
        MaraMentor.IsLoadMore = "false";
        MaraMentor.scrolltop = 1;

        if (parseInt(SentboxPrivateMessageManager.pageNo) >= parseInt(SentboxPrivateMessageManager.totalMsgpages)) {

            $(".loadMore").remove();
        }
        //MaraMentor.ChangePageContent(tabDiv+mainMessage1+mainMessage2);
  //end
        // For each loop on data and html
    },
    SetSentboxPrivateMessageHTMLFailure: function () {

    },
    SentNextPage: function () {
        var newPage2 = parseInt(SentboxPrivateMessageManager.pageNo)+1;
        var user_id = MaraMentor.sessionId;
        var box_nm = 'sentbox';
        var requestData = "user_id=" + user_id + "&box_nm=" + box_nm + "&pageNo=" + newPage2 +
            "&func=inboxPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", SentboxPrivateMessageManager.SentboxPrivateMessageSuccess, SentboxPrivateMessageManager.SentboxPrivateMessageFailure)
    },
}
/******************** sentbox end **********************/

/******************** privateMessageUserList   code  start **********************/
var PrivateMessageUserListManager = {
    PrivateMessageUserList: function () {
    	wrapAnimation = _noAnimation;
        var loginId = MaraMentor.sessionId;
        var box_nm = 'compose';
        var scrh1 = $("#search_user").val();
        if (!scrh1) {
            scrh1 = "";
        }
        var requestData = "loginId=" + loginId + "&scrh1=" + scrh1 + "&box_nm=" + box_nm + "&func=mobileComposeMessageUser&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PrivateMessageUserListManager.PrivateMessageUserListSuccess, PrivateMessageUserListManager.PrivateMessageUserListFailure)
    },
    PrivateMessageUserListSuccess: function (resultData) {
        var myObjectPrivateMsgUserList = JSON.parse(resultData);
        if (myObjectPrivateMsgUserList.IsSuccess == "true") {
            PrivateMessageUserListManager.conValues = myObjectPrivateMsgUserList.conValues;
            PrivateMessageUserListManager.pageNo = myObjectPrivateMsgUserList.pageNo;
            PrivateMessageUserListManager.totalPage = myObjectPrivateMsgUserList.totalPage;
            PrivateMessageUserListManager.scrh1 = myObjectPrivateMsgUserList.scrh1;
            MaraMentor.PushPageRecord('composeNew');
            MaraMentor.ChangeHeaderText("Compose Message");
            PrivateMessageUserListManager.SetPrivateMessageUserListHTML();

        } else {
            MaraMentor.showToastMsg(myObjectPrivateMsgUserList.ErrorMessage);
        }
    },
    PrivateMessageUserListFailure: function (resultData) {
        var myObjectPrivateMsgUserList = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectPrivateMsgUserList.ErrorMessage);
    },
    SetPrivateMessageUserListHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/comp_user_list.html", "", "GET", PrivateMessageUserListManager.SetPrivateMessageUserListHTMLSuccess, PrivateMessageUserListManager.SetPrivateMessageUserListHTMLFailure);
    },
    SetPrivateMessageUserListHTMLSuccess: function (result) {

        MaraMentor.AcitivateMenu("messages");
         MaraMentor.PushPageRecord('composeNew');
         MaraMentor.ChangeHeaderText("Compose Message");
        messageTemplate1 = result;
        tabDiv = $(messageTemplate1).filter("#tabingWrap");
        userListSection = $(messageTemplate1).filter("#userListWrap");
        userListSection = userListSection.html();
        tabDiv = tabDiv.html();
        var searchDiv1 ='';
        var searchDiv1 = '<h2 class="nospace">Search User Name</h2>'+
        '<div class="SearchForm">'+
        '<span class="form"><input  type="text" name="search_user" id="search_user" class="profile-textfield" value="'+PrivateMessageUserListManager.scrh1+'" placeholder="Search by name" /></span>'+
            '<span class="searchUser" onclick="PrivateMessageUserListManager.PrivateMessageUserList()">Search</span>'+
        '</div>';
        var box_nm = 'compose';
        var boxCon3 = (box_nm == "compose") ? 'current' : '';
        tabDiv = tabDiv.replace(/\{\{boxCon2\}\}/,"").replace(/\{\{boxCon1\}\}/,"").replace(/\{\{boxCon3\}\}/, boxCon3)
        .replace(/\{\{searchDiv\}\}/, searchDiv1);
        var userListSection1 = "";
        var j = 0;
        if (PrivateMessageUserListManager.conValues == "no") {
            var runInboxload1 = '';
            runInboxload1 += userListSection.replace(/\{\{noRes\}\}/, 'Sorry, no members were found');
        } else {

            var runInboxload1 = '';
            var userListSection1 = '';
            runInboxload1 += '<div class="msgingListWrap">';
        if(PrivateMessageUserListManager.scrh1 == '') {
            for (var cu = 0, lencu = PrivateMessageUserListManager.conValues.length; cu < lencu; cu++)
            {
                if(PrivateMessageUserListManager.conValues[cu]['userId'] != MaraMentor.sessionId) {
                userListSection1 += userListSection.replace(/\{\{userId\}\}/, PrivateMessageUserListManager.conValues[cu]['userId'])
                .replace(/\{\{Fname\}\}/g, PrivateMessageUserListManager.conValues[cu]['Fname'])
                .replace(/\{\{Lname\}\}/, PrivateMessageUserListManager.conValues[cu]['Lname'])
                .replace(/\{\{Industry\}\}/, PrivateMessageUserListManager.conValues[cu]['Industry'])
                .replace(/\{\{country\}\}/, PrivateMessageUserListManager.conValues[cu]['country'])
                .replace(/\{\{userImage\}\}/, PrivateMessageUserListManager.conValues[cu]['userImage'])
                .replace(/\{\{totalfollowId\}\}/, PrivateMessageUserListManager.conValues[cu]['totalfollowId'])
                .replace(/\{\{con_user_id\}\}/, PrivateMessageUserListManager.conValues[cu]['con_user_id'])
                .replace(/\{\{subjectContent\}\}/, PrivateMessageUserListManager.conValues[cu]['subjectContent'])
                .replace(/\{\{sessionId\}\}/, MaraMentor.sessionId);
                }
                j++;
            }
        } else {
                for (y in PrivateMessageUserListManager.conValues) {
                if(PrivateMessageUserListManager.conValues[y].userId != MaraMentor.sessionId) {
                userListSection1 += userListSection.replace(/\{\{userId\}\}/, PrivateMessageUserListManager.conValues[y].userId)
                .replace(/\{\{Fname\}\}/g, PrivateMessageUserListManager.conValues[y].Fname)
                .replace(/\{\{Lname\}\}/, PrivateMessageUserListManager.conValues[y].Lname)
                .replace(/\{\{Industry\}\}/, PrivateMessageUserListManager.conValues[y].Industry)
                .replace(/\{\{country\}\}/, PrivateMessageUserListManager.conValues[y].country)
                .replace(/\{\{userImage\}\}/, PrivateMessageUserListManager.conValues[y].userImage)
                .replace(/\{\{totalfollowId\}\}/, PrivateMessageUserListManager.conValues[y].totalfollowId)
                .replace(/\{\{con_user_id\}\}/, PrivateMessageUserListManager.conValues[y].con_user_id)
                .replace(/\{\{subjectContent\}\}/, PrivateMessageUserListManager.conValues[y].subjectContent)
                .replace(/\{\{sessionId\}\}/, MaraMentor.sessionId);
                }
                j++;
            }
        }
        runInboxload1 += '</div>';
        }
        if (j == 0) {
            runInboxload1 = "<div class='upDation'>Sorry, no members were found </div>";
        }

    //    MaraMentor.ChangePageContent(mainMessage1);
        // load more div here..
        var loadMoreDiv="";
        if(PrivateMessageUserListManager.totalMsgpages>PrivateMessageUserListManager.pageNo){
          //  ProfileManager.pageNo = ProfileManager.pageNo +1;
            loadMoreDiv="<div class='loadMore' id='loadMoreButton' onclick='MentorListManager.mentorNextPage("+PrivateMessageUserListManager.pageNo+")'></div>";
        }
        MaraMentor.IsLoadMore ="false";
        MaraMentor.ChangePageContent(tabDiv+userListSection1+loadMoreDiv+runInboxload1);

        //end
        // For each loop on data and html
    },
    SetPrivateMessageUserListHTMLFailure: function () {

    },
    SetComposePrivateMessageHTML: function (sendId, niceName, pagNm) {
        PrivateMessageUserListManager.sendId = sendId;
        PrivateMessageUserListManager.niceName = niceName;
        PrivateMessageUserListManager.pagNm = pagNm;
        PrivateMessageUserListManager.reciptId = MaraMentor.sessionId;
        // If role = 1 then Sign up as a Mentor, If role = 2 then Sign up as a Mentee
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/comp_form.html", "", "GET", PrivateMessageUserListManager.SetComposePrivateMessageHTMLSuccess, PrivateMessageUserListManager.SetComposePrivateMessageHTMLFailure);
    },
    SetComposePrivateMessageHTMLSuccess: function (result) {
        MaraMentor.AcitivateMenu("messages");
        MaraMentor.PushPageRecord('composeNew');
        MaraMentor.ChangeHeaderText("Compose Message");
        compFormSection = $(result).filter("#compFormWrap");
        compFormSection = compFormSection.html();

        compFormSection1 = compFormSection.replace(/\{\{niceName\}\}/, PrivateMessageUserListManager.niceName);
        MaraMentor.ChangePageContent(compFormSection1);
    },
     SetComposePrivateMessageHTMLFailure: function (resultData) {
        if (resultData.success == "true") {}
    },
    ComposePrivateMessage: function () {

        var subject = $("#subject").val();
        var message_content = $("#message_content").val();
        if (subject == "") {
            MaraMentor.showToastMsg("Please do not leave the subject blank");
            return false;
        } else if (message_content == "") {
            MaraMentor.showToastMsg("Please do not leave the message blank");
            return false;
        }

        var sendto = PrivateMessageUserListManager.sendId;
        var reciptId = PrivateMessageUserListManager.reciptId;
        var requestData = "sendto=" + sendto + "&reciptId=" + reciptId + "&subject=" + subject + "&message_content=" + message_content + "&func=composePrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", PrivateMessageUserListManager.ComposePrivateMessageSuccess, PrivateMessageUserListManager.ComposePrivateMessageFailure)
    },
    ComposePrivateMessageSuccess: function (resultData) {
        var myObjectComposePrivateMessage = JSON.parse(resultData);
        if (myObjectComposePrivateMessage.IsSuccess == "true") {
            SentboxPrivateMessageManager.SentboxPrivateMessage(1);
        } else {
            MaraMentor.showToastMsg(myObjectComposePrivateMessage.ErrorMessage);
        }
    },
    ComposePrivateMessageFailure: function (resultData) {
        var myObjectComposePrivateMessage = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectComposePrivateMessage.ErrorMessage);
    },
}
/******************** privateMessageUserList end **********************/


/******************** ViewSinglePrivateMessage   code  start **********************/
var ViewSinglePrivateMessageManager = {
    ViewSinglePrivateMessage: function (msg_id, loginUserId) {
        ViewSinglePrivateMessageManager.var1 = msg_id;
        ViewSinglePrivateMessageManager.var2 = loginUserId;
        $("#currMsgId").val(msg_id);
        var requestData = "msg_id=" + msg_id + "&loginUserId=" + loginUserId + "&func=singleViewPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ViewSinglePrivateMessageManager.ViewSinglePrivateMessageSuccess, ViewSinglePrivateMessageManager.ViewSinglePrivateMessageFailure)
    },
    ViewSinglePrivateMessageSuccess: function (resultData) {
        var myObjectViewSinglePrivateMessage = JSON.parse(resultData);
        if (myObjectViewSinglePrivateMessage.IsSuccess == "true") {
           ViewSinglePrivateMessageManager.singeMsgArray = myObjectViewSinglePrivateMessage.singeMsgArray;
            MaraMentor.PushPageRecord('viewSingleMessage');
            MaraMentor.ChangeHeaderText("Message");
            ViewSinglePrivateMessageManager.SetViewSinglePrivateMessageHTML();

        } else {
            MaraMentor.showToastMsg(myObjectViewSinglePrivateMessage.ErrorMessage);
        }
    },
    ViewSinglePrivateMessageFailure: function (resultData) {
        var myObjectViewSinglePrivateMessage = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectViewSinglePrivateMessage.ErrorMessage);
    },
    SetViewSinglePrivateMessageHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/detail_message.html", "", "GET", ViewSinglePrivateMessageManager.SetViewSinglePrivateMessageHTMLSuccess, ViewSinglePrivateMessageManager.SetViewSinglePrivateMessageHTMLFailure);
    },
     SetViewSinglePrivateMessageHTMLSuccess: function (result) {
        viewMessageTemplate = result;
        MaraMentor.AcitivateMenu("messages");
        MaraMentor.PushPageRecord('viewSingleMessage');
        MaraMentor.ChangeHeaderText("Message");
        viewMessageSection = $(viewMessageTemplate).filter("#viewMsgWrap");
        viewMessageSection = viewMessageSection.html();

        lenv = ViewSinglePrivateMessageManager.singeMsgArray.length;
        var replay_box= '';
        var headerTile = '';
        headerTile=
        '<h3 class="nospace">'+ ViewSinglePrivateMessageManager.singeMsgArray[0]['subject']+'</h3>';
        replay_box='<div class="rply_box" id="reply_cont">'+
        '<div class="left msgImage">';
        replay_box+='<img src="'+ViewSinglePrivateMessageManager.singeMsgArray[lenv-1]['loginUserImage_url']+'" />';
        replay_box+='</div>'+'<div class="left msgContentDetail">'+'<h2 class="nospace">Send A Reply</h2>'+'<span class="composeForm"><textarea name="message_content" id="message_content" placeholder="Write your reply" rows=5 cols=20 ></textarea></span>'+'<div class="sendMsg" onClick="ReplyPrivateMessageManager.ReplyPrivateMessage(';replay_box+=ViewSinglePrivateMessageManager.singeMsgArray[lenv-1]['thread_id']+','+MaraMentor.sessionId+',\''+ViewSinglePrivateMessageManager.singeMsgArray[0]['subject']+'\')">Send </div>'+'</div>'+'<div class="clear"></div>'+'</div>';
        var viewMessageSection1 = "";
        for (var v = 0, lenv = ViewSinglePrivateMessageManager.singeMsgArray.length; v < lenv; v++)
        {
            viewMessageSection1 += viewMessageSection.replace(/\{\{thread_id\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['thread_id'])
            .replace(/\{\{sender_id\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['sender_id'])
            .replace(/\{\{subject\}\}/g, ViewSinglePrivateMessageManager.singeMsgArray[v]['subject'])
            .replace(/\{\{message\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['message'])
            .replace(/\{\{date_sent\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['date_sent'])
            .replace(/\{\{userImage\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['userImage'])
            .replace(/\{\{sendUserImage_url\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['sendUserImage_url'])
            .replace(/\{\{loginUserImage_url\}\}/g, ViewSinglePrivateMessageManager.singeMsgArray[v]['loginUserImage_url'])
            .replace(/\{\{user_name\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['user_name'])
            .replace(/\{\{loginUserId\}\}/, MaraMentor.sessionId)
            .replace(/\{\{resDateTime4\}\}/, ViewSinglePrivateMessageManager.singeMsgArray[v]['resDateTime4']);
        }
        MaraMentor.ChangePageContent(headerTile+viewMessageSection1+replay_box);
        //end
        // For each loop on data and html
    },
    SetViewSinglePrivateMessageHTMLFailure: function () {

    },
}
/******************** ViewSinglePrivateMessage end **********************/

/******************** replyPrivateMessage   code  start **********************/
var ReplyPrivateMessageManager = {
    ReplyPrivateMessage: function (thread_id, sender_id, msgSubject) {

        var message_content = $("#message_content").val();
        if (message_content == "") {
            MaraMentor.showToastMsg("Please do not leave the message blank");
            return false;
        }
        var requestData = "thread_id=" + thread_id + "&sender_id=" + sender_id + "&msgSubject=" + msgSubject + "&message_content=" + message_content + "&func=replyPrivateMessage&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", ReplyPrivateMessageManager.ReplyPrivateMessageSuccess, ReplyPrivateMessageManager.ReplyPrivateMessageFailure)
    },
    ReplyPrivateMessageSuccess: function (resultData) {
        var myObjectReplyPrivateMessage = JSON.parse(resultData);
        if (myObjectReplyPrivateMessage.IsSuccess == "true") {
            SentboxPrivateMessageManager.SentboxPrivateMessage(1);
        } else {
            MaraMentor.showToastMsg(myObjectReplyPrivateMessage.ErrorMessage);
        }
    },
    ReplyPrivateMessageFailure: function (resultData) {
        var myObjectReplyPrivateMessage = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectReplyPrivateMessage.ErrorMessage);
    }
}
/******************** replyPrivateMessage end **********************/

/******************** UserConnection   code  start **********************/
var UserConnectionManager = {
    UserConnection: function (pageNo) {
        var userId = MaraMentor.sessionId;
        var requestData = "userId=" + userId + "&pageNo="+pageNo+   "&func=userConnection&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", UserConnectionManager.UserConnectionSuccess, UserConnectionManager.UserConnectionFailure)
    },
    UserConnectionSuccess: function (resultData) {
        var myObjectUserConnection = JSON.parse(resultData);
        UserConnectionManager.connRes = myObjectUserConnection.connRes;
        UserConnectionManager.pageNo = myObjectUserConnection.pageNo;
        UserConnectionManager.TotalPage = myObjectUserConnection.TotalPage;
        UserConnectionManager.SetConnectionMentorListHTML();

    },
    UserConnectionFailure: function (resultData) {
        var myObjectUserConnection = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectUserConnection.ErrorMessage);
    },
    SetConnectionMentorListHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCallHTML("views/userConnection.html", "", "GET", UserConnectionManager.SetConnectionMentorListHTMLSuccess, UserConnectionManager.SetConnectionMentorListHTMLFailure);
    },
    SetConnectionMentorListHTMLSuccess: function (result) {
        MaraMentor.backPages.length = 0;
        MaraMentor.AcitivateMenu("connections");
        MaraMentor.PushPageRecord("connection");
        MaraMentor.ChangeHeaderText("Connection");

        RunAdvMentorSrchTemplate = result;
        runMentorListSrch = $(RunAdvMentorSrchTemplate).filter("#advanceSearch");
        runMentorListSrch = runMentorListSrch.html();
        runMentorload = $(RunAdvMentorSrchTemplate).filter("#mentorload");
        runMentorload = runMentorload.html();
        var i = 0;
        if (UserConnectionManager.connRes == "no") {
            var runMentorload1 = '';
            runMentorload1 += runMentorload.replace(/\{\{noRes\}\}/, 'Sorry, this member has no following.');
        } else {
            var runMentorload1 = '';
            var UsersLists = '';
            runMentorload1 += '<div class="UsersListContainer">';
            for (x in UserConnectionManager.connRes.users) {
                var followClick = "";
                var followImage = "";
                var followText = "";
                if (UserConnectionManager.connRes.users[x].con_user_id == 1505) {
                    followClick = "";
                    followImage = "";
                    followText = "";
                }
                else {
                    if (UserConnectionManager.connRes.users[x].Follow == "Follow") {
                        followClick = "FollowMentorManager.FollowMentor(" + UserConnectionManager.connRes.users[x].con_user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow.png";
                        followText = "Follow";
                    } else {
                        followClick = "UnfollowMentorManager.UnfollowMentor(" + UserConnectionManager.connRes.users[x].con_user_id + ",'advcMentor'," + i + ")";
                        followImage = "assets/images/follow_grey.png";
                        followText = "Unfollow";
                    }
                }

                UsersLists += runMentorload.replace(/\{\{userId\}\}/g, UserConnectionManager.connRes.users[x].con_user_id)
                    .replace(/\{\{firstName\}\}/, UserConnectionManager.connRes.users[x].first_name)
                    .replace(/\{\{last_name\}\}/, UserConnectionManager.connRes.users[x].last_name)
                    .replace(/\{\{country\}\}/, UserConnectionManager.connRes.users[x].country)
                    .replace(/\{\{industry\}\}/, UserConnectionManager.connRes.users[x].industry)
                   // .replace(/\{\{Follow\}\}/, UserConnectionManager.connRes.users[x].Follow)
                    .replace(/\{\{totalAll\}\}/g, UserConnectionManager.connRes.users[x].totalfollowId)
                    .replace(/\{\{userImage\}\}/, UserConnectionManager.connRes.users[x].userImage)
                    .replace(/\{\{followClick\}\}/, followClick)
                     .replace(/\{\{Follow\}\}/, followText)
                    .replace(/\{\{followImage\}\}/, followImage);
                i++;
            }

            runMentorload1 += '</div>';
            if (parseInt(UserConnectionManager.pageNo) == 1 && parseInt(UserConnectionManager.TotalPage) > 1) {
                runMentorload1 += '<div class="loadMore" id="loadMoreButton" onclick="UserConnectionManager.UserConnectionNextPage(1)"></div>';
            }

        }
        if (i == 0) {
            runMentorload1 = "<div class='upDation'>Sorry, this member has no following.</div>";
        }

        mainMentorListHtml = runMentorListSrch + runMentorload1;
        MaraMentor.IsLoadMore ="false";
        if (parseInt(UserConnectionManager.pageNo) == 1) {
            MaraMentor.ChangePageContent(mainMentorListHtml);
        }



        $('.UsersListContainer').append(UsersLists).trigger("create");

        if (parseInt(UserConnectionManager.pageNo) >= parseInt(UserConnectionManager.TotalPage)) {

            $(".loadMore").remove();
        }
         MaraMentor.RefreshScrollBar();
         MaraMentor.IsLoadMore = "false";
         MaraMentor.scrolltop = 1;
    },
    SetConnectionMentorListHTMLFailure: function (result) {
    },
    UserConnectionNextPage: function (newPage) {
       // var newPage = UserConnectionManager.PageNo;
        newPage++;
        UserConnectionManager.UserConnection(newPage);
    },
}
/******************** UserConnection end *******************************/

/******************** GlobalSearch Manager Start*****************************/
var GlobalSearchManager = {
    query: "",
    CurrentPage: 1,
    SearchGlobal: function (pageNo) {
    	wrapAnimation = _noAnimation
        GlobalSearchManager.CurrentPage = pageNo;
        var q = $("#scrhGlobal").val();
        if (q != "") {
            GlobalSearchManager.query = q;
            this.GetGlobalSearchData();
        } else {
            MaraMentor.showToastMsg("Please input some value");
        }
    },
    GetGlobalSearchData: function (arg) {
    	wrapAnimation = _noAnimation
        if(arg==1){
           GlobalSearchManager.query="";
           GlobalSearchManager.DataArray = "";
            GlobalSearchManager.TotalResult =0
        }
        //alert(1);
        if (GlobalSearchManager.query != "") {
            //var serverURL = "https://mentorapp.mara.com/api/advanceUsersSearchWithoutRole/?search_user=" + GlobalSearchManager.query + "&country=&industry=&page=" + GlobalSearchManager.CurrentPage;
            // var serverURL = "http://localhost/wordpress/mara-updated/api/advanceUsersSearchWithoutRole/?search_user=" + GlobalSearchManager.query + "&country=&industry=&page=" + GlobalSearchManager.CurrentPage;
            var serverURL = "https://dmentor.mara.com/api/advanceUsersSearchWithoutRole/?search_user=" + GlobalSearchManager.query + "&country=&industry=&page=" + GlobalSearchManager.CurrentPage;
           MaraMentor.MakeAjaxCall(serverURL, "", "POST", GlobalSearchManager.GetGlobalSearchDataSuccess, GlobalSearchManager.GetGlobalSearchDataError)
        } else {
            GlobalSearchManager.GetGlobalSearchHTML();
            //var wrapperHeight = $(window).height()-($("#header").height()+$("#footer").height());
            //alert(wrapperHeight);
            //$("div.postStatus").css("height",wrapperHeight);
        }
    },
    GetGlobalSearchDataSuccess: function (result) {
        result = JSON.parse(result);
        if (result.status == "ok" && result.authors != "non") {
            GlobalSearchManager.DataArray = result.authors;
            GlobalSearchManager.TotalResult = parseInt(result.totalPages);
            //GlobalSearchManager.CurrentPage = parseInt(result.pageNo);
            GlobalSearchManager.GetGlobalSearchHTML();
        }
    },
    GetGlobalSearchDataError: function () {

    },
    GetGlobalSearchHTML: function () {
        MaraMentor.MakeAjaxCallHTML("views/globalSearch.html", "", "GET", GlobalSearchManager.GetGlobalSearchHTMLSucess, GlobalSearchManager.GetGlobalSearchHTMLError);
    },
    GetGlobalSearchHTMLSucess: function (result) {

        MaraMentor.PushPageRecord('globalSearch');
        MaraMentor.ChangeHeaderText("Search");
        var header = $(result).filter("#advanceSearch").html();
        var loadMoreDiv = "";

        var searchTemplate = $(result).filter("#mentorList").html();
        var searchResult = "";

        var i = 0;
        if (GlobalSearchManager.DataArray != "no") {
            for (x in GlobalSearchManager.DataArray) {
                var role = "";
                var follower = "";

                if (GlobalSearchManager.DataArray[x].role == "mentor") {
                    role = "Mentor";
                    followers = "Followers " + GlobalSearchManager.DataArray[x].totalMentors;
                } else {
                    role = "Mentee";
                    followers = "Following " + GlobalSearchManager.DataArray[x].totalMentors;
                }


                searchResult += searchTemplate.replace(/\{\{userId\}\}/g, GlobalSearchManager.DataArray[x].user_id)
                    .replace(/\{\{firstName\}\}/, GlobalSearchManager.DataArray[x].first_name)
                    .replace(/\{\{lastName\}\}/, GlobalSearchManager.DataArray[x].last_name)
                    .replace(/\{\{country\}\}/, GlobalSearchManager.DataArray[x].country)
                    .replace(/\{\{industry\}\}/, GlobalSearchManager.DataArray[x].industry)
                    .replace(/\{\{totalAll\}\}/g, followers)
                    .replace(/\{\{userImage\}\}/, GlobalSearchManager.DataArray[x].userImage)
                    .replace(/\{\{role\}\}/, role)

                i++;
            }
        } //end of for loop
        if (GlobalSearchManager.TotalResult > GlobalSearchManager.CurrentPage) {
            GlobalSearchManager.CurrentPage = GlobalSearchManager.CurrentPage + 1;
            loadMoreDiv = "<div class='loadMore' id='loadMoreButton' onclick='GlobalSearchManager.SearchGlobal(" + GlobalSearchManager.CurrentPage + ")'></div>"
        }
        if (GlobalSearchManager.DataArray == "no") {
            searchResult = "<div class='upDation'>Sorry, no member found</div>"
        }

         if (GlobalSearchManager.CurrentPage <= 2){
         MaraMentor.ChangePageContent(header + searchResult + loadMoreDiv);
          } else {
             $('.loadMore').remove();
             $('.wrapContent').append(searchResult+loadMoreDiv);
          }
        MaraMentor.RefreshScrollBar();

        MaraMentor.isGlobalSearch = "true";
        MaraMentor.IsLoadMore = "false";
        MaraMentor.scrolltop = 1;
        $("#scrhGlobal").val(GlobalSearchManager.query);

    },
    GetGlobalSearchHTMLError: function () {

    },
    /*************** Show hide search options ***********/
    get_search_options: function (id) {
        //$("#searchOptions").css("display", "block");
        $("#searchOptions").toggle();
    },
}
/******************** GlobalSearch Manager End*****************************/

/******************* Debate Room Comment Start ****************/
var DebateRoom = {
    serverUrl: MaraMentor.serverURL,
    debateRoomForums: function () {
        //var requestData ={loginId:LoginManager.sessionId, func:"debateRoomForums",api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"};
        //?loginId="+MaraMentor.sessionId+"&func=debateRoomForums&api_key=92b7d3ab2b864e8eee752fe8e979960c";
        var requestData = "loginId=" + MaraMentor.sessionId + "&func=debateRoomForums&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debateRoomForumsSuccessFunction, DebateRoom.debateRoomForumsFailurFunction);
    },
    debateRoomForumsSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);

        if (arg.IsSuccess == "true") {

            MaraMentor.PushPageRecord('debateRoomForum');
            MaraMentor.ChangeHeaderText('Forum');
            // alert(JSON.stringify(arg.industries));
            var topicIndustries = arg.industries;
            var ctnData = '';
            ctnData += '<div class="postStatus" id="postStatus">' +
                '<div class="debate_search">' +
                '<input type="text" placeholder="Search for Topic" name="debateSearchTxtBox" id="debateSearchTxtBox">' +
                '<div class="btnSearch right" id="debateSearchBtn" value="Search" onclick="DebateRoom.debateRoomSearchFunc()">Search</div>' +
                '<div class="clear"></div>' +
                '</div></div>' +
    '<div class="tabing tabingSelction">'+
    '<span>'+
    '<a href="javascript:void(0)" onclick="DebateRoom.debateRoomLatestTopic(\'latest\',\'1\')" > Latest Topices</a>'+
    '</span>'+
    '<span class="current">'+
    '<a href="javascript:void(0)" onclick="DebateRoom.debateRoomForums()" >Category Topices</a></span>'+
    '<div class="clear"></div>'+'</div>';

            ctnData += '<div class="upDation">';
            for (x in topicIndustries) {
                if (x != "remove") {
                    var post_title = topicIndustries[x].post_title;
                    var ID = topicIndustries[x].ID;
                    var post_date = topicIndustries[x].post_date;
                    var post_content = topicIndustries[x].post_content;
                    var post_name = topicIndustries[x].post_name;
                    var totalTopic = topicIndustries[x].totalTopic;
                    var totalPosts = topicIndustries[x].totalPosts;
                    var freshness = topicIndustries[x].freshness;
                    //alert(topicIndustries[x].post_title);
                    ctnData += '<div class="debatePage" onclick="DebateRoom.debateRoomSingleFourm(\'' + ID + '\',\'topic\',\'1\',\'0\')">' +
                        '<h2 class="nospace">' + post_title + '</h2>' +
                        '<div class="postUpdate">' +
                        '</div>' +
                        '<div class="left howMuchPost">' +
                        'Topic <b>' + totalTopic + '</b> &nbsp; Posts  <b>' + totalPosts + '</b>' +
                        '</div>' +
                        '<div class="left howMuchtimes">' +
                        freshness +
                        '</div>' +
                        '<div class="clear"></div>' +
                        '</div>';
                }


            } //end of for loop
            ctnData += '</div>'; //Updation Div End

            //$('#header').html('<div class="logdetail">'+'<h2>'+'Debate Room</h2>'+'</div>'+'<div class="inner-logo" onclick="handleBack()">'+'<img alt="" src="s40-theme/images/icon.png" >'+'</div>');
            // $('#wrapContent').html(ctnData);
            MaraMentor.ChangePageContent(ctnData);
        } else {
            // var popup = new Popup(arg.ErrorMessage, "OK", null);
            // popup.show();

        }


    },
    debateRoomForumsFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();

    },

    debateRoomSingleFourm: function (forumID, orderBy, pageNo, currentPage) {
        DebateRoom.mForumID = forumID;
        DebateRoom.morderBy = orderBy;
        DebateRoom.mpageNo = pageNo;
        DebateRoom.mcurrentPage = currentPage;

        var requestData = "loginId=" + MaraMentor.sessionId + "&forumID=" + forumID + "&pageNo=" + pageNo + "&orderBy=" + orderBy + "&func=debateRoomSingleFourm&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        //var data ={loginId:session,forumID:forumID,orderBy:orderBy,pageNo:pageNo,func:"debateRoomSingleFourm",api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"};
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debateRoomSingleFourmSuccessFunction, DebateRoom.debateRoomSingleFourmFailurFunction);
    }, //end of function

    debateRoomSingleFourmSuccessFunction: function (arg) {
        // alert(arg);
        arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            MaraMentor.PushPageRecord('debateRoomSingleForum');
            var ForumTopics = arg.Topics.topicsData;
            var ctnData = '';
            //alert(JSON.stringify(ForumTopics));
            var forumID = arg.Topics.forumID;
            var forumTitle = arg.Topics.forumTitle

            ctnData += '<div class="createTopic" onclick="DebateRoom.CreateDebateRoomTopicPage(\'' + forumID + '\',\'' + forumTitle + '\')">Create Topic</div>';

            ctnData += '<div class="upDation">';
            for (x in ForumTopics) {
                if (x != "remove") {
                    var post_title = ForumTopics[x].post_title;
                    var ID = ForumTopics[x].ID;
                    var voted = ForumTopics[x].voted;
                    var totalPosts = ForumTopics[x].totalPosts;
                    var freshness = ForumTopics[x].freshness;

                    ctnData += '<div class="debatePage" onclick="DebateRoom.DebateRoomSingleTopicPage(\'' + ID + '\',\'1\')">' +
                        '<h2 class="nospace">' + post_title + '</h2>' +
                        '<div class="postUpdate">' +
                        '</div>' +
                        '<div class="left howMuchPost">' +
                        'Replies <b>' + totalPosts + '</b> &nbsp; votes  <b>' + voted + '</b>' +
                        '</div>' +
                        '<div class="left howMuchtimes">' + freshness +
                        '</div>   ' +
                        '<div class="clear"></div>' +
                        '</div>';
                }


            } //end of for loop
            ctnData += '</div>';
            ctnData += '<input type="hidden" name="pageNo" id="pageNo" value="' + arg.Topics.pageNo + '" >' +
                '<input type="hidden" name="forumID" id="forumID" value="' + arg.Topics.forumID + '" >' +
                '<input type="hidden" name="orderBy" id="orderBy" value="' + arg.Topics.orderBy + '" >';
            var TotalRecords = arg.Topics.TotalRecords;
            var ShowedRecords = parseInt(arg.Topics.pageNo) * 5;

            //alert("TotalRecords = "+TotalRecords+" ShowedRecords="+ShowedRecords);

            if (ShowedRecords < TotalRecords) {
                ctnData += '<div onclick="DebateRoom.debateRoomSingleFourmNextPage()"  class="load-more loadMore" id="loadMoreButton"></div>';
            }

            //$('#wrapContent').html(ctnData);
            MaraMentor.IsLoadMore = "false";
            MaraMentor.ChangePageContent(ctnData);
        } else {
            var popup = new Popup(arg.ErrorMessage, "OK", null);
            popup.show();
        }
    },


    debateRoomSingleFourmFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    },


    debateRoomSingleFourmNextPage: function () {
        var pageNo = parseInt($("#pageNo").val()) + 1;
        var forumID = $("#forumID").val();
        var orderBy = $("#orderBy").val();

        var requestData = "loginId=" + MaraMentor.sessionId + "&forumID=" + forumID + "&pageNo=" + pageNo + "&orderBy=" + orderBy + "&func=debateRoomSingleFourm&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debateRoomSingleFourmNextPageSuccessFunction, DebateRoom.debateRoomSingleFourmNextPageFailurFunction);

    }, //end of function


    debateRoomSingleFourmNextPageSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            var ForumTopics = arg.Topics.topicsData;
            var ctnData = '';
            for (x in ForumTopics) {
                if (x != "remove") {
                    var post_title = ForumTopics[x].post_title;
                    var ID = ForumTopics[x].ID;
                    var voted = ForumTopics[x].voted;
                    var totalPosts = ForumTopics[x].totalPosts;
                    var freshness = ForumTopics[x].freshness;

                    ctnData += '<div class="debatePage" onclick="DebateRoom.DebateRoomSingleTopicPage(\'' + ID + '\',\'1\')">' +
                        '<h2 class="nospace">' + post_title + '</h2>' +
                        '<div class="postUpdate">' +
                        '</div>' +
                        '<div class="left howMuchPost">' +
                        'Topic <b>' + totalPosts + '</b> &nbsp; votes  <b>' + voted + '</b>' +
                        '</div>' +
                        '<div class="left howMuchtimes">' + freshness +
                        '</div>   ' +
                        '<div class="clear"></div>' +
                        '</div>';
                }


            } //end of for loop
            $("#pageNo").val(parseInt(arg.Topics.pageNo));

            var TotalRecords = arg.Topics.TotalRecords;
            var ShowedRecords = parseInt(arg.Topics.pageNo) * 5;

            //alert("TotalRecords = "+TotalRecords+" ShowedRecords="+ShowedRecords);

            if (ShowedRecords >= TotalRecords) {
                $(".load-more").remove();
            }


            $('.upDation').append(ctnData);
            MaraMentor.IsLoadMore="false";
            MaraMentor.scrolltop = 1;
             MaraMentor.RefreshScrollBar();
        }

    },


    debateRoomSingleFourmNextPageFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    },



    //function to create debate room Topic
    CreateDebateRoomTopicPage: function (forumID, forumTitle) {

        var frm = '<div class="messageWrap">' +
            '<div class="CrateTopic">' +
            '<h3 class="nospace">Under ' + forumTitle + '</h3>' +
            '<div class="SearchForm">' +
            '<h2 class="nospace">Title</h2>' +
            '<span class="composeForm"><input type="text" id="topicTitle" name="topicTitle"  placeholder="Add a Topic Title"/></span>' +
            '</div>' +
            '<h2 class="nospace">Description</h2>' +
            '<div class="SearchForm">' +
            '<span class="composeForm"><textarea type="text" name="topicDescription" id="topicDescription" placeholder="Write Your  Topic Description"></textarea></span>' +
            '</div>' +
            '<div class="sendMsg" onclick="DebateRoom.CreateDebateRoomTopic(\'' + forumID + '\')">Create Topic</div>'+
        '<div class="clear"></div>' +
            '</div>' +
            '</div>';

        //$('.wrapContent').html(frm);
        MaraMentor.ChangePageContent(frm);
    }, //end of function

    CreateDebateRoomTopic: function (forumID) {
        if (!forumID) {
            return true;
        }

        var topicTitle = $("#topicTitle").val();
        var topicDescription = $("#topicDescription").val();

        if (topicTitle == "") {
         MaraMentor.showToastMsg("Please do not leave the Topic Title blank");
            return false;
        } else if (topicDescription == "") {
         MaraMentor.showToastMsg("Please do not leave the Topic Description blank");
            return false;
        }

        var requestData = "loginId=" + MaraMentor.sessionId + "&forumID=" + forumID + "&topicTitle=" + topicTitle + "&topicDescription=" + topicDescription + "&func=createDebateRoomTopic&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.CreateDebateRoomTopicSuccessFunction, DebateRoom.CreateDebateRoomTopicFailurFunction);

    },

    CreateDebateRoomTopicSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {

            DebateRoom.debateRoomSingleFourm(arg.forumID, 'latest', '1', '0');
        }
    },

    CreateDebateRoomTopicFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    },


    //debate room topic replay page where all topic reply displays
    DebateRoomSingleTopicPage: function (TopicID, pageNo) {
        DebateRoom.mTopicId = TopicID;
        DebateRoom.mPageNo = pageNo;

        var requestData = 'loginId=' + MaraMentor.sessionId + '&TopicID=' + TopicID + '&pageNo=' + pageNo + '&func=DebateRoomSingleTopicPage&api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"';
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.SingleTopicPageSuccessFunction, DebateRoom.SingleTopicPageFailurFunction);
    },

    SingleTopicPageSuccessFunction: function (arg) {
        arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            MaraMentor.PushPageRecord('debateRoomSingleTopicDetails');

            // var cc='djcn jasdn juasd juasd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd asju sjdh jsudh jusdh sjudh sjudh sju sjdu sjudh sjduh sjdh ujsdh sjdh sjud sjd ujd ';
            var ctn = '';
            var cc = arg.TopicContent;


            //var TopicContentData = DebateRoom.aboutMoreContent(0,8);

            var topicData = arg.topicData;
            ctn += '<div class="activityBar">' +
                '<div class="userDesc">' +
                '<div class="usersinglepageImg left">' +
                '<img src="' + arg.TopicAuthorImg + '" />' +
                '</div>' +
                '<div class="right singlepagestatus">' +
                '<h2 class="nospace">' + arg.TopicAuthorName + '</h2>' +
                '<div class="postTime">' + arg.TopicFreshness + '</div>' +
                '</div>' +
                '</div>' +
                '<div class="clear"></div>' +
                '</div>' +

            '<div class="upDation">' +
                '<div class="postAnImage">' +
                '<h1>' + arg.TopicTitle + '</h1>' +
                '<input type="hidden" id="HiddenContent" value="' + cc + '" /><div class="postMatter">';

            var TopicContentData = DebateRoom.ReadMoreLink(cc, 100, 'more', 'postMatter', 'HiddenContent');
            ctn += TopicContentData;
            ctn += '</div>' +
                '<div class="clear"></div>' +
                '</div>';


            ctn += '<div class="review">';

            if (arg.isTopicLiked == "yes") {
                ctn += '<div class="left likeOrComment satus-like_' + arg.TopicID + '">' +
                    '<span class="like status-like-cont">' +
                    '<img src="assets/images/like.png" /> Like (' + arg.TotalLikedTopics + ')</span>' +
                    '</div>';
            } else {
                ctn += '<div class="left likeOrComment satus-like_' + arg.TopicID + '">' +
                    '<div  onclick="DebateRoom.SingleTopicPostLike(\'' + arg.TopicID + '\',\'like\')">' +
                    '<span class="like status-like-cont">' +
                    '<img src="assets/images/like.png" /> Liked (' + arg.TotalLikedTopics + ')</span>' +
                    '</div>' +
                    '</div>';
            }

            ctn += '<div class="right singlepageLike"><span class="status-cmt-cont">' +
                '<img src="assets/images/comment.png" />Replies (' + arg.TotalReplies + ')</span>' +
                '</div>' +
                '<div class="clear"></div>' +
                '<div class="singlepost">' +
                '<div class="Field">' +
                '<input type="text" name="TopicTxtBox" id="TopicTxtBox" placeholder="Write Your Reply">' +
                '</div>' +
                '<div onclick="DebateRoom.postTopicReply(\'' + arg.TopicID + '\')" class="btnPost right">' +
                'Reply' +
                '</div>' +
                '<div class="clear"></div>' +
                '</div></div></div>';
            if (parseInt(arg.TotalReplies) > 0) {
                ctn += '<div class="commentShow">';

                for (x in topicData) {
                    if (x != "remove") {
                        var post_title = topicData[x].post_title;
                        var ID = topicData[x].ID;
                        var post_date = topicData[x].post_date;
                        var post_content = topicData[x].post_content;
                        var freshness = topicData[x].freshness;
                        var author_img = topicData[x].author_img;
                        var post_author = topicData[x].post_author;
                        var post_author_name = topicData[x].post_author_name;

                        ctn += '<div class="user_comment">' +
                            '<div class="SingleActivityImage left">' +
                            '<img width="50" src="' + author_img + '" />' +
                            '</div>' +
                            '<div class="right cmt_detail">' +
                            '<h2 class="nospace">' + post_author_name + '</h2>' +
                            '<input type="hidden" id="HiddenContent_' + ID + '" value="' + post_content + '" />' +
                            '<div class="CmtUpdate Cmt_' + ID + '">';
                        ctn += DebateRoom.ReadMoreLink(post_content, 50, 'more', 'Cmt_' + ID + '', 'HiddenContent_' + ID + '');
                        ctn += '</div>' +
                            '</div><div class="clear"></div></div>';

                    }
                } //end of for loop
                ctn += '</div>';
            } //end of if total replies > 0
            ctn += '<input type="hidden" name="pageNo" id="pageNo" value="' + arg.pageNo + '" >' +
                '<input type="hidden" name="TopicID" id="TopicID" value="' + arg.TopicID + '" >';

            var TotalRecords = arg.TotalRecords;
            var ShowedRecords = parseInt(arg.pageNo) * 5;

            if (ShowedRecords < TotalRecords) {
                ctn += '<div onclick="DebateRoom.DebateRoomSingleTopicPageNextPage()"  class="load-more loadMore" id="loadMoreButton"></div>';
            }
            ctn += '</div>' +
                '</div>';
            //$('#wrapContent').html(ctn);

            MaraMentor.isDebateRoomSingleTopic = "true";
            MaraMentor.ChangePageContent(ctn);
            MaraMentor.IsLoadMore ="false";
        } //end of issuccess true
    }, //end of function

    DebateRoomSingleTopicPageFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    },

    DebateRoomSingleTopicPageNextPage: function () {
        var pageNo = parseInt($("#pageNo").val()) + 1;
        var TopicID = $("#TopicID").val();
        var requestData = 'loginId=' + MaraMentor.sessionId + '&TopicID=' + TopicID + '&pageNo=' + pageNo + '&func=DebateRoomSingleTopicPage&api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"';
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DebateRoom.DebateRoomSingleTopicPageNextPageSuccessFunction, DebateRoom.DebateRoomSingleTopicPageNextPageFailurFunction)
    }, //end of function


    DebateRoomSingleTopicPageNextPageSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            var topicData = arg.topicData
            var ctn = '';
            for (x in topicData) {
                if (x != "remove") {
                    var post_title = topicData[x].post_title;
                    var ID = topicData[x].ID;
                    var post_date = topicData[x].post_date;
                    var post_content = topicData[x].post_content;
                    var freshness = topicData[x].freshness;
                    var author_img = topicData[x].author_img;
                    var post_author = topicData[x].post_author;
                    var post_author_name = topicData[x].post_author_name;
                    ctn += '<div class="user_comment">' +
                        '<div class="SingleActivityImage left">' +
                        '<img width="50" src="' + author_img + '" />' +
                        '</div>' +
                        '<div class="right cmt_detail">' +
                        '<h2 class="nospace">' + post_author_name + '</h2>' +
                        '<div class="CmtUpdate">' + post_content +
                        '</div></div><div class="clear"></div></div>';
                }


            } //end of for loop
            $("#pageNo").val(parseInt(arg.pageNo));

            var TotalRecords = arg.TotalRecords;
            var ShowedRecords = parseInt(arg.pageNo) * 5;
            if (ShowedRecords >= TotalRecords) {
                $(".loadMore").remove();
            }
            $('.commentShow').append(ctn);
            MaraMentor.RefreshScrollBar();
            MaraMentor.IsLoadMore = "false";
            MaraMentor.scrolltop = 1;
        }
    },


    DebateRoomSingleTopicPageNextPageFailurFunction: function (arg) {
        //                var popup = new Popup(arg.ErrorMessage, "OK", null);
        //                popup.show();

    },


    //function for add new topic reply by Talwinder Singh
    postTopicReply: function (TopicID) {
        var TopicContent = $("#TopicTxtBox").val();
        if (TopicContent == "") {
            //                var popup = new Popup("Please do not leave the Reply Title Blank", "OK", null);
            //                popup.show();
            return false;
        }
        var requestData = "loginId=" + MaraMentor.sessionId + "&TopicID=" + TopicID + "&TopicContent=" + TopicContent + "&func=postTopicReply&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debatePostTopicReplySuccessFunction, DebateRoom.debatePostTopicReplyFailurFunction);
    }, //end of function

    debatePostTopicReplySuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            DebateRoom.DebateRoomSingleTopicPage(arg.TopicID, '1');
        }
    },
    debatePostTopicReplyFailurFunction: function (arg) {
        //       var popup = new Popup(arg.ErrorMessage, "OK", null);
        //        popup.show();
    },
    debateRoomSearchFunc: function () {
        var debateSearchTxtBox = $("#debateSearchTxtBox").val();
        var pageNo = 1
        if (debateSearchTxtBox == "") {
            return false;
        }
        var requestData = 'loginId=' + MaraMentor.sessionId + '&debateSearchTxtBox=' + debateSearchTxtBox + '&pageNo=' + pageNo + '&func=debateRoomSearchFunc&api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"';
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DebateRoom.debateRoomSearchFuncSuccessFunction, DebateRoom.debateRoomSearchFuncFailurFunction);
    },
    debateRoomSearchFuncSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            var ctn = '';

            if(parseInt(arg.pageNo) == 1){
            ctn += '<div class="postStatus">' +
            //'<h2 class="nospace">Search For: '+arg.debateSearchTxtBox+'</h2>'+
            '<div class="debate_search">' +
                '<input type="text" name="debateSearchTxtBox" id="debateSearchTxtBox" value="' + arg.debateSearchTxtBox + '" placeholder="Search ">' +
                '<div class="btnSearch right"  onclick="DebateRoom.debateRoomSearchFunc()">Search</div>' +
                '<div class="clear"></div>' +
                '</div>' +
                '</div>';



            ctn += '<div class="upDation">';
            }
            var SearchData = arg.SearchData

            for (var x in SearchData) {
                if (x != "remove") {
                    var TopicID = SearchData[x].TopicID;
                    var TopicTitle = SearchData[x].TopicTitle;
                    var Type = SearchData[x].Type;
                    var forumID = SearchData[x].forumID;
                    var forumTitle = SearchData[x].forumTitle;
                    var postContent = SearchData[x].postContent;
                    var authorID = SearchData[x].authorID;
                    var authorImg = SearchData[x].authorImg;
                    var post_author_name = SearchData[x].post_author_name;
                    var postID = SearchData[x].postID;


                    if (Type == 'reply') {
                        var TopicTitleMsg = 'In reply to: ';

                    } else {
                        var TopicTitleMsg = 'Topic: ';
                    }




                    ctn += '<div class="debateBar">' +
                        '<div class="search_debate_pic left"  onclick="ProfileManager.profile(\'' + authorID + '\',1)"> <img src="' + authorImg + '" /></div>' +
                        '<div class=" left debateDesc" >' +
                        '<h1 class="nospace" >' + post_author_name + '</h1>' +
                        '<span >' + TopicTitleMsg + '</span>' +
                        '<h2 class="nospace" onclick="DebateRoom.DebateRoomSingleTopicPage(\'' + TopicID + '\',\'1\')">' + TopicTitle + '</h2>' +
                        '<input type="hidden" id="HiddenContent_' + TopicID + '" value="' + postContent + '" />' +
                        '<p class="nospace TOPIC-' + TopicID + '">';

                    ctn += DebateRoom.ReadMoreLink(postContent, 20, 'more', 'TOPIC-' + TopicID + '', 'HiddenContent_' + TopicID + '');

                    ctn += '</p>' +
                        '</div>' +
                        '<div class="clear"></div>' +
                        '</div>';



                }


            } //end of for loop
            if(parseInt(arg.pageNo) == 1){
            ctn += '</div>';
            ctn += '<input type="hidden" name="pageNo" id="pageNo" value="' + arg.pageNo + '" >' +
                '<input type="hidden" name="debateSearchTxt" id="debateSearchTxt" value="' + arg.debateSearchTxtBox + '" >';
            }
            else{
                    $("#pageNo").val(arg.pageNo);
                    $("#debateSearchTxt").val(arg.debateSearchTxtBox);
            }

            var TotalRecords = arg.TotalRecords;
            var ShowedRecords = parseInt(arg.pageNo) * 5;


            if(parseInt(arg.pageNo) == 1){
                if (ShowedRecords < TotalRecords) {
                    ctn += '<div onclick="DebateRoom.debateRoomSearchFuncNextPage()" class="load-more loadMore" id="loadMoreButton"></div>';
                }
                else{
                    $("#loadMoreButton").remove();
                }
            }
            else if(ShowedRecords > TotalRecords){
                $("#loadMoreButton").remove();
            }
            if(parseInt(arg.pageNo) == 1){
            ctn += '</div>' +
                '</div>';
            }


            //$('#wrapContent').html(ctn);
            if(parseInt(arg.pageNo) == 1){
                MaraMentor.ChangePageContent(ctn);
            }
            else{
                $(".upDation").append(ctn);
                MaraMentor.RefreshScrollBar();
            }
            MoreScreenManager.SetUpExternalLinks();
            MaraMentor.isDebateRoomSearch = "true";
            MaraMentor.IsLoadMore = "false";


        } //end of issuccess true
    },
    debateRoomSearchFuncFailurFunction: function (arg) {
        //           var popup = new Popup(arg.ErrorMessage, "OK", null);
        //            popup.show();
    },



    //Debate Room Load More Functionality

    debateRoomSearchFuncNextPage: function () {
        var pageNo = parseInt($("#pageNo").val()) + 1;
        var debateSearchTxtBox = $("#debateSearchTxt").val();
        var requestData = 'loginId=' + MaraMentor.sessionId + '&debateSearchTxtBox=' + debateSearchTxtBox + '&pageNo=' + pageNo + '&func=debateRoomSearchFunc&api_key:"92b7d3ab2b864e8eee752fe8e979960c",screen:"Medium"';
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DebateRoom.debateRoomSearchFuncSuccessFunction, DebateRoom.debateRoomSearchFuncFailurFunction);

    }, //end of function


    debateRoomSearchFuncNextPageSuccessFunction: function (arg) {
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            var SearchData = arg.SearchData
            for (var x in SearchData) {
                if (x != "remove") {
                    var TopicID = SearchData[x].TopicID;
                    var TopicTitle = SearchData[x].TopicTitle;
                    var Type = SearchData[x].Type;
                    var forumID = SearchData[x].forumID;
                    var forumTitle = SearchData[x].forumTitle;
                    var postContent = SearchData[x].postContent;
                    var authorID = SearchData[x].authorID;
                    var authorImg = SearchData[x].authorImg;
                    var post_author_name = SearchData[x].post_author_name;
                    var postID = SearchData[x].postID;
                    if (Type == 'reply') {
                        var TopicTitleMsg = 'In reply to: ';

                    } else {
                        var TopicTitleMsg = 'Topic: ';
                    }
                    ctn += '<div class="debateBar">' +
                        '<div class="search_debate_pic left"  onclick="ProfileManager.profile(\'' + authorID + '\',1)"> <img src="' + authorImg + '" /></div>' +
                        '<div class=" left debateDesc" onclick="DebateRoom.DebateRoomSingleTopicPage(\'' + TopicID + '\',\'1\')">' +
                        '<h1 class="nospace" >' + post_author_name + '</h1>' +
                        '<span>' + TopicTitleMsg + '</span><h2 class="nospace"> ' + TopicTitle + '</h2>' +
                        '<p class="nospace">' + postContent + '</p>' +
                        '</div>' +
                        '<div class="clear"></div>' +
                        '</div>';
                }


            } //end of for loop
            $("#pageNo").val(parseInt(arg.pageNo));

            var TotalRecords = arg.TotalRecords;
            var ShowedRecords = parseInt(arg.pageNo) * 5;

            //alert("TotalRecords = "+TotalRecords+" ShowedRecords="+ShowedRecords);

            if (ShowedRecords >= TotalRecords) {
                $(".loadMore").remove();
            }

            $('#wrapContent .upDation').append(ctn);
            MaraMentor.IsLoadMore = "false";
            MaraMentor.scrolltop = 1;
             MaraMentor.RefreshScrollBar();

        }

    }, //end of function


    debateRoomSearchFuncNextPageFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();

    },




    // functions for single topic like
    SingleTopicPostLike: function (TopicID, Type) {
        var requestData = "loginId=" + MaraMentor.sessionId + "&TopicID=" + TopicID + "&Type=" + Type + "&func=SingleTopicPostLike&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", DebateRoom.SingleTopicPostLikeSuccessFunction, DebateRoom.SingleTopicPostLikeFailurFunction);
    },

    SingleTopicPostLikeSuccessFunction: function (arg) {
        // alert(JSON.stringify(arg));
        var arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
            var Data = arg.Data;
            var TopicID = Data.TopicID
            var TotalLikes = Data.TotalLikes;

            $(".satus-like_" + TopicID + " div").remove();
            var DT = '<div><span class="like status-like-cont">' +
                '<img src="assets/images/like.png" /> Like (' + TotalLikes + ')' +
                '</span></div>';
            $(".satus-like_" + TopicID).append(DT);
            MaraMentor.RefreshScrollBar();
        } //end of if issuccess==true
    }, //end of function
    SingleTopicPostLikeFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    }, //end of functin

    //end of single topic like functionality

    //function by Talwinder singh for read more and less
    ReadMoreLink: function (txt, ReqWords, TType, ClassReplace, hiddenFieldID) {

        var totalWords = txt.length;
        if (totalWords > parseInt(ReqWords)) {
            var data = txt.substr(0, ReqWords) + " <a href=\"javascript:void(0)\" onclick=\"DebateRoom.ReadMoreLessFunc('" + ReqWords + "','" + TType + "','" + ClassReplace + "','" + hiddenFieldID + "') \">more...</a>";
            //var data = txt.substr(0, ReqWords) + " <a href=\"javascript:void(0)\" onclick=\"DebateRoom.ReadMoreLessFunc('"+txt1+"') \">more...</a>";
        } else {
            var data = txt;
        }

        return data;
    }, //end of function

    ReadMoreLessFunc: function (ReqWords, TType, ClassReplace, hiddenFieldID) {
        //ReadMoreLessFunc: function (txt) {
        var txt = $("#" + hiddenFieldID).val();
        var totalWords = txt.length;
        var data = '';
        if (TType == 'more') {
            data = txt + " <a href=\"javascript:void(0)\" onclick=\"DebateRoom.ReadMoreLessFunc('" + ReqWords + "', \'less\','" + ClassReplace + "','" + hiddenFieldID + "')\"> Less...</a>";
        } else {

            data = txt.substr('0', ReqWords) + " <a href=\"javascript:void(0)\" onclick=\"DebateRoom.ReadMoreLessFunc('" + ReqWords + "', \'more\','" + ClassReplace + "','" + hiddenFieldID + "')\"> More...</a>";
        }

        $("." + ClassReplace).html(data);
        MaraMentor.RefreshScrollBar();
    },

    debateRoomLatestTopic: function (orderBy, pageNo) {
        
        DebateRoom.lorderBy = orderBy;
        DebateRoom.lpageNo = pageNo;
        var requestData = "loginId=" + MaraMentor.sessionId + "&pageNo=" + pageNo + "&orderBy=" + orderBy + "&func=latestForumTopic&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
  MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debateRoomLatestTopicSuccessFunction, DebateRoom.debateRoomLatestTopicFailurFunction);
    }, //end of function

   debateRoomLatestTopicSuccessFunction: function (arg) {
        arg = JSON.parse(arg);
        if (arg.IsSuccess == "true") {
   DebateRoom.lorderBy = arg.LatestTopics.orderBy;
   DebateRoom.lpageNo = arg.LatestTopics.pageNo;  
   MaraMentor.PushPageRecord('debateRoomSingleForum');
            var LatestTopics = arg.LatestTopics.topicsData;
            var ctnData, ctnHeader,ctnFooter;
           
   ctnData = ctnHeader = ctnFooter = '';
             ctnHeader = '<div class="postStatus" id="postStatus">' +
                '<div class="debate_search">' +
                '<input type="text" placeholder="Search for Topic" name="debateSearchTxtBox" id="debateSearchTxtBox">' +
                '<div class="btnSearch right" id="debateSearchBtn" value="Search" onclick="DebateRoom.debateRoomSearchFunc()">Search</div>' +
                '<div class="clear"></div>' +
                '</div></div>' +
    '<div class="tabing tabingSelction">'+
    '<span class="current">'+
    '<a href="javascript:void(0)" onclick="DebateRoom.debateRoomLatestTopic(\'latest\',\'1\')" > Latest Topices</a>'+
    '</span>'+
    '<span >'+
    '<a href="javascript:void(0)" onclick="DebateRoom.debateRoomForums()" >Category Topices</a></span>'+
    '<div class="clear"></div>'+'</div>';
            for (l in LatestTopics) {
                if (l != "remove") {
                 if(LatestTopics[l].ID==undefined)
                  continue;
                    var latest_post_title = LatestTopics[l].post_title;
                    var latest_ID = LatestTopics[l].ID;
                    var latset_voted = LatestTopics[l].voted;
                    var latest_totalPosts = LatestTopics[l].totalPosts;
                    var latest_freshness = LatestTopics[l].freshness;
                    ctnData += '<div class="debatePage" onclick="DebateRoom.DebateRoomSingleTopicPage(\'' + latest_ID + '\',\'1\')">' +
                        '<h2 class="nospace">' + latest_post_title + '</h2>' +
                        '<div class="postUpdate">' +
                        '</div>' +
                        '<div class="left howMuchPost">' +
                        'Replies <b>' + latest_totalPosts + '</b> &nbsp; votes  <b>' + latset_voted + '</b>' +
                        '</div>' +
                        '<div class="left howMuchtimes">' + latest_freshness +
                        '</div>   ' +
                        '<div class="clear"></div>' +
                        '</div>';
                }
            } //end of for loop
            ctnData += '</div>';
   //ctnFooter = '<input type="hidden" name="lpageNo" id="lpageNo" value="'+arg.LatestTopics.pageNo + '" ><input type="hidden" name="lorderBy" id="lorderBy" value="'+arg.LatestTopics.orderBy+'" >'; 
            var latestTotalRecords = arg.LatestTopics.TotalRecords;
            var latestShowedRecords = parseInt(arg.LatestTopics.pageNo) * 5;
   busyLoading = false;
    
            //alert("TotalRecords = "+TotalRecords+" ShowedRecords="+ShowedRecords);

            if (parseInt(latestShowedRecords) < parseInt(latestTotalRecords)) {
             var loadMoreClass= "loadMore";
             var loadMoreText = "Load More"
             if(MaraMentor.deviceOS==7){
              loadMoreClass ="loadMoreBB7";
              loadMoreText =  "";
             }
                ctnFooter = '<div onclick="DebateRoom.debateRoomLatestTopicNextPage()" id="loadMoreButton"  class="load-more '+loadMoreClass+'"  data-theme="d">'+loadMoreText+'</div>';
            }

   if (parseInt(arg.LatestTopics.pageNo) <= 1 ){
   
            //$('#wrapContent').html(ctnData); var loadMoreClass= "loadMore";
            var loadMoreText = "Load More"
            if(MaraMentor.deviceOS==7){
             loadMoreClass ="loadMoreBB7";
             loadMoreText =  "";
            }
   
             ctnData = '<div class="latestupDation">' + ctnData +'</div>';
             MaraMentor.ChangePageContent(ctnHeader + ctnData + ctnFooter);
         } else {
          $('#loadMoreButton').remove();
          $('.latestupDation').append(ctnData+ctnFooter);
   }

        } else {
            var popup = new Popup(arg.ErrorMessage, "OK", null);
            popup.show();
        }

    },
    debateRoomLatestTopicFailurFunction: function (arg) {
        var popup = new Popup(arg.ErrorMessage, "OK", null);
        popup.show();
    },
 debateRoomLatestTopicNextPage: function () {
        var lpageNo = parseInt(DebateRoom.lpageNo) + 1;
        //var forumID = $("#forumID").val();
  var requestData = "loginId=" + MaraMentor.sessionId + "&pageNo=" + lpageNo + "&orderBy=" + DebateRoom.lorderBy + "&func=latestForumTopic&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
  MaraMentor.MakeAjaxCall(DebateRoom.serverUrl, requestData, "POST", DebateRoom.debateRoomLatestTopicSuccessFunction, DebateRoom.debateRoomLatestTopicFailurFunction);
  busyLoading = false;
    }, //end of function
}
/**************************Debate room ends***************************/

/******************** Notification   code  start **********************/
var NotificationManager = {
    lastViewedNotificationId:0,
    notifiTotalArray:0,

    Notification: function (pageSource) {
        NotificationManager.pageSource = pageSource;
        var loginId = MaraMentor.sessionId;
        var requestData = "loginId=" + loginId + "&func=getUserLiveNotifications&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        if (pageSource == "icon") {
            MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "GET", NotificationManager.NotificationSuccess, NotificationManager.NotificationFailure)
        } else {
            MaraMentor.MakeAjaxCall2(MaraMentor.serverURL, requestData, "GET", NotificationManager.NotificationSuccess, NotificationManager.NotificationFailure)
        }
    },
    NotificationSuccess: function (resultData) {

          //Store latest received notifications data to object
        var myObjectNotification = JSON.parse(resultData);

        if(!myObjectNotification.Data.length)
            return;

        console.log(myObjectNotification);
        NotificationManager.notificationArray = myObjectNotification.Data;    //Populate notifications array
        NotificationManager.notifiTotalArray = myObjectNotification.Data.length;//myObjectNotification.TotalNotifi; //Store total notification count
        console.log(NotificationManager.notificationArray);
         //if last viewed notificationID is already stored
         //AND notification array has elements
         //Process and show count of only newer notification count
         if (NotificationManager.lastViewedNotificationId !== 0 && typeof NotificationManager.notificationArray !== 'undefined')
         {
            notificationCount = 0;

            //Loop into total notifications array
            for (var n = 0, len = NotificationManager.notifiTotalArray; n < NotificationManager.notificationArray.length; n+=1)
            {
             //Count the newer notifications only
                if(parseInt(NotificationManager.notificationArray[n].notificationID) > parseInt(NotificationManager.lastViewedNotificationId) )
                    notificationCount ++;
            }

            //Show the newer notification count
            //$('#notificationCount').text(notificationCount);
            //MaraMentor.navBar.setNotificationButtonCount(String(notificationCount));
             $('#notificationCount').text(notificationCount);
           }
           else //Otherwise just show the total notification count
           {
             if(NotificationManager.notificationArray)
              //$('#notificationCount').text(NotificationManager.notificationArray.length);
              //MaraMentor.navBar.setNotificationButtonCount(String(NotificationManager.notificationArray.length));
              $('#notificationCount').text(NotificationManager.notificationArray.length);
           }

        if (NotificationManager.pageSource == "icon")
         NotificationManager.SetNotificationHTML();

        /* old code BU
        var myObjectNotification = JSON.parse(resultData);
        NotificationManager.notificationArray = myObjectNotification.Data;
        NotificationManager.notifiTotalArray = myObjectNotification.TotalNotifi;
        if (NotificationManager.pageSource == "icon") {
            NotificationManager.SetNotificationHTML();
        }
        //$('#notificationCount').text(NotificationManager.notificationArray.length);
        MaraMentor.navBar.setNotificationButtonCount(String(NotificationManager.notificationArray.length));
        */

    },
    NotificationFailure: function (resultData) {
        var myObjectNotification = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectNotification.ErrorMessage);
    },
    SetNotificationHTML: function () {
        //call to get the html template
        MaraMentor.MakeAjaxCall2("views/notification.html", "", "GET", NotificationManager.SetNotificationHTMLSuccess, NotificationManager.SetNotificationHTMLFailure);
        NotificationManager.Notification("");
    },
    SetNotificationHTMLSuccess: function (result) {

        notificationDiv = $(result).filter("#notifi").html();

        resultNotification = '';

         //Check notification array has elements
        if (typeof NotificationManager.notificationArray !== 'undefined'){
            NotificationManager.lastViewedNotificationId = NotificationManager.notificationArray[0].notificationID;
            window.localStorage.setItem("lastNotificaionId",NotificationManager.lastViewedNotificationId);
       }
        if (!NotificationManager.notificationArray) {
            MaraMentor.showToastMsg("No new notification");
            $('#notificationCount').text("0");

            return;
        }

        for (var n = 0, len = NotificationManager.notifiTotalArray; n < NotificationManager.notificationArray.length; n++) {

            var TypeCont = '';
            var is_new_class = '';
            if(NotificationManager.notificationArray[n].is_new == 1 ) {
             is_new_class = "notification_is_new";
            }else {
                is_new_class = "notification_not_new";
            }
            TypeCont = '<div class="'+is_new_class+'">' ;

            if (NotificationManager.notificationArray[n].Type == "new_follow") {
                //TypeCont="<span >connection";
                TypeCont += '<div class="notification" onclick="ProfileManager.Profile(' + NotificationManager.notificationArray[n].senderId + ',1);">';

            } else if (NotificationManager.notificationArray[n].Type == "new_activity_comment") {
                //TypeCont="singleActivity";
                TypeCont += '<div class="notification" onclick="SingleActivityManager.SingleActivityData(' + NotificationManager.notificationArray[n].AId + ',0)">';

            } else if (NotificationManager.notificationArray[n].Type == "friendship_accepted") {
                //  TypeCont="sender id profile page";
                TypeCont += '<div class="notification" onclick="ProfileManager.Profile(' + NotificationManager.notificationArray[n].senderId + ',1)">';

            } else if (NotificationManager.notificationArray[n].Type == "friendship_request") {
                //TypeCont="sender id profile page";
                TypeCont += '<div class="notification" onclick="ProfileManager.Profile(' + NotificationManager.notificationArray[n].senderId + ',1)">';

            } else if (NotificationManager.notificationArray[n].Type == "new_message") {
                //  TypeCont="viewSingleMessage";
                TypeCont += '<div class="notification" onclick="ViewSinglePrivateMessageManager.ViewSinglePrivateMessage(' + NotificationManager.notificationArray[n].AId + ',' + MaraMentor.sessionId + ')">';

            }
            TypeCont += '<img src="' + NotificationManager.notificationArray[n].senderImg + '">';
            TypeCont += '<h2 class="nospace">' + NotificationManager.notificationArray[n].notificationMsg + '</h2>';
            TypeCont += '<div class="CmtTime left">' + NotificationManager.notificationArray[n].date + '</div>';
            TypeCont += '</div><div class="clear"></div></div>';


            resultNotification += notificationDiv.replace(/\{\{notificationMsg\}\}/, NotificationManager.notificationArray[n].notificationMsg)
                .replace(/\{\{noti_date\}\}/, NotificationManager.notificationArray[n].date)
                .replace(/\{\{senderId\}\}/, NotificationManager.notificationArray[n].senderId)
                .replace(/\{\{TypeCont\}\}/, TypeCont)
                .replace(/\{\{senderName\}\}/, NotificationManager.notificationArray[n].senderName);
        }
        if (TypeCont != '') {
            MaraMentor.PushPageRecord("notifications");
            MaraMentor.ChangeHeaderText("Notifications");
        }
        MaraMentor.ChangePageContent(resultNotification);
        //MaraMentor.navBar.setNotificationButtonCount("0");
         $('#notificationCount').text("0");
        // For each loop on data and html
    },

    SetNotificationHTMLFailure: function () {}

}
/******************** Notification end **********************/
/******************** addExterActivity   code  start **********************/
var AddExterActivityManager = {
    AddExterActivityPage: function (act_type) {
        MaraMentor.isDashboard="false";
        var uId = MaraMentor.sessionId;
        var bpfbActivity = act_type;
        var addcls1 = (bpfbActivity == "act_photos") ? 'class="current"' : '';
        var addcls2 = (bpfbActivity == "act_videos") ? 'class="current"' : '';
        var addcls3 = (bpfbActivity == "act_link") ? 'class="current"' : '';
        elm = '<div class="background"><div class="form-bg">';

        if (bpfbActivity == 'act_videos') {
            elm += '<div class="shareVideo" >' +
                '<h5>Share Youtube Video</h5>' +
                '<div class="inputField"><input  type="text" name="bpfb_post_content" id="bpfb_post_content"   /></div>' +
                '<div class="btnPost left" onclick="AddExterActivityManager.AddBpfbActivity(\'' + bpfbActivity + '\')"  data-theme="d">Post</div>' + '</div>';
            MaraMentor.PushPageRecord("extraActivity");
            MaraMentor.ChangeHeaderText('Share Video');
        } else if (bpfbActivity == 'act_photos') {
            elm += '<div class="capture">' +
                '<button onclick="AddExterActivityManager.CapturePhoto();" class="btnPost" >Capture and Share</button>' +
                '</div>' +
                '<div class="capture">' +
                '<button onclick="AddExterActivityManager.GetPhoto();" class="btnPost" >Share from Gallery</button>' +
                '</div>';
            MaraMentor.PushPageRecord("extraActivity");
            MaraMentor.ChangeHeaderText('Share Picture');

        } else if (bpfbActivity == 'act_link') {
            elm += '<div class="shareVideo">' +
                '<h5>Share Links</h5>' +
                '<div class="inputField"><input type="text" name="bpfb_post_content" id="bpfb_post_content" placeholder="http://www.example.com"   /></div>' +
                '<div class="btnPost left" onclick="AddExterActivityManager.AddBpfbActivity(\'' + bpfbActivity + '\')" data-theme="d" >Post</div>' + '</div>';
            MaraMentor.PushPageRecord("extraActivity");
            MaraMentor.ChangeHeaderText('Share Link');
        }
        elm += '<div>' + '<ul class="act-tab"><li ' + addcls1 + ' >' +
            '<img alt="" src="assets/images/photo.png"   onclick="AddExterActivityManager.AddExterActivityPage(\'act_photos\')"/>' +
            '</li>' + '<li ' + addcls2 + ' ><img alt="" src="assets/images/video.png"  onclick="AddExterActivityManager.AddExterActivityPage(\'act_videos\')"/></li>' +
            '<li ' + addcls3 + ' ><img alt="" src="assets/images/link.png"   onclick="AddExterActivityManager.AddExterActivityPage(\'act_link\')"/>' +
            '</li></ul></div>';
        elm += '<div class="clear"></div></div></div>';
        //$('#wrapContent').html(elm).trigger("create");
        //MaraMentor.PushPageRecord("extraActivity");
        MaraMentor.ChangePageContent(elm);


        /* $('#header').html('<div class="logdetail">'+'<h2>'+'Add Activity</h2>'+'</div>'+'<div class="inner-logo" onclick="backPage()">'+'<img alt="" src="s40-theme/images/icon.png" >'+'</div>'); */
    },
    AddBpfbActivity: function (act) {


        var user_id = MaraMentor.sessionId;
        var act_path = $("#bpfb_post_content").val();
        var pattern90 = /^(?:https?:\/\/(?:www\.)?|www\.)[a-z0-9]+(?:[-.][a-z0-9]+)*\.[a-z]{2,5}(?::[0-9]{1,5})?(?:\/\S*)?$/;

        if (act_path == "") {
            MaraMentor.showToastMsg("Please enter some content to post.");
            return false;
        } else if (act_path != "") {
            if (!pattern90.test(act_path)) {
                MaraMentor.showToastMsg("Please enter valid link only.");
                return false;
            }
        }
        if (act == "act_videos") {
            var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
            var p1 = /^(?:https?:\/\/)?(?:m\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;

            if (act_path.match(p) || act_path.match(p1)) {
                var requestData = "activity_type=" + act + "&user_id=" + user_id + "&act_path=" + encodeURIComponent(act_path) + "&func=addBpfbActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
                MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", AddExterActivityManager.AddBpfbActivitySuccess, AddExterActivityManager.AddBpfbActivityFailure)
                return true;
            } else {
                MaraMentor.showToastMsg("Please only enter links to YouTube Videos.");
                return false;
            }
        }
        var requestData = "activity_type=" + act + "&user_id=" + user_id + "&act_path=" + act_path + "&func=addBpfbActivity&api_key=92b7d3ab2b864e8eee752fe8e979960c&screen=Medium";
        MaraMentor.MakeAjaxCall(MaraMentor.serverURL, requestData, "POST", AddExterActivityManager.AddBpfbActivitySuccess, AddExterActivityManager.AddBpfbActivityFailure)
    },
    AddBpfbActivitySuccess: function (resultData) {
        var myObjectAddBpfbActivity = JSON.parse(resultData);
        if (myObjectAddBpfbActivity.IsSuccess == "true") {
            DashManager.GetDashboardData(1);
        } else {
            MaraMentor.showToastMsg(myObjectAddBpfbActivity.ErrorMessage);
        }
    },
    AddBpfbActivityFailure: function (resultData) {
        var myObjectAddBpfbActivity = JSON.parse(resultData);
        MaraMentor.showToastMsg(myObjectAddBpfbActivity.ErrorMessage);
    },
    CapturePhoto: function () {
        // Take picture using device camera and retrieve image as base64-encoded string
        navigator.camera.getPicture(AddExterActivityManager.OnPhotoDataSuccess, AddExterActivityManager.OnFail, {
            //allowEdit: true,
            quality: 30,
            sourceType: Camera.PictureSourceType.CAMERA,
            destinationType: Camera.DestinationType.FILE_URI,
            encodingType: Camera.EncodingType.JPEG,
            //correctOrientation: true
        });
    },
    GetPhoto: function () {
        source = Camera.PictureSourceType.PHOTOLIBRARY;
        // Retrieve image file location from specified source
        navigator.camera.getPicture(AddExterActivityManager.OnPhotoURISuccess, AddExterActivityManager.OnFail, {
            //allowEdit: true,
            quality: 45,
            destinationType: destinationType.FILE_URI,
            sourceType: source,
            encodingType: Camera.EncodingType.JPEG
        });
    },
    OnPhotoDataSuccess: function (imageData) {
        //alert('reached');
        setTimeout(function () {
            // do your thing here!
            //$("#loading").show();
            MaraMentor.ShowLoader();
        }, 1);
        //$("#loading").show();

        var options = new FileUploadOptions();
        options.headers = {
            Connection: "close"
        }
        options.fileKey = "file";
        options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
        options.mimeType = "image/jpeg";

        var params = new Object();
        params.user_id = MaraMentor.sessionId;
        params.activity_type = "act_photos";
        params.func = "addBpfbActivity";
        params.api_key = "92b7d3ab2b864e8eee752fe8e979960c";
        params.screen = "Medium";
        params.source = "camera";
        options.params = params;
        options.chunkedMode = false;

        var ft = new FileTransfer();
        ft.upload(imageData, MaraMentor.serverURL, AddExterActivityManager.Win, AddExterActivityManager.Fail, options);
    },
    OnPhotoURISuccess: function (imageUri) {


        //$("#loading").show();
        MaraMentor.ShowLoader();
        //var loginURL = getServerUrl()


        var options = new FileUploadOptions();
        options.fileKey = "file";
        options.fileName = imageUri.substr(imageUri.lastIndexOf('/') + 1);
        options.mimeType = "image/jpeg";

        var params = new Object();
        params.user_id = MaraMentor.sessionId;
        params.activity_type = "act_photos";
        params.func = "addBpfbActivity";
        params.api_key = "92b7d3ab2b864e8eee752fe8e979960c";
        params.screen = "Medium";
        params.source = "gallery";

        options.params = params;
        options.chunkedMode = false;
        picName = options.fileName;
        if((picName).indexOf(".") === -1)
             {
                 options.fileName = options.fileName+".jpg";

             }
             else
             {
                 fileExtension = picName.substr(picName.lastIndexOf('.')+1);
                 //alert(fileExtension);
                 if(fileExtension===".jpg" || fileExtension===".png" || fileExtension ===".jpeg")
                 {

                 }
                 else
                 {
                      options.fileName = options.fileName+".jpg";
                 }
             }

        var ft = new FileTransfer();
        console.log(imageUri);
        ft.upload(imageUri, MaraMentor.serverURL, AddExterActivityManager.Win, AddExterActivityManager.Fail, options);


        /*
        var picName = "";
         var fileExtension ="";
            $("#loading").show();

            var options = new FileUploadOptions();
            options.headers = { Connection: "close" };
            options.fileKey="file";
            options.fileName=imageUri.substr(imageUri.lastIndexOf('/')+1);
            options.mimeType="image/jpeg";
            picName = options.fileName;
            alert(picName);
             if((picName).indexOf(".") === -1)
             {
                 options.fileName = options.fileName+".jpg";

             }
             else
             {
                 fileExtension = picName.substr(picName.lastIndexOf('.')+1);
                 alert(fileExtension);
                 if(fileExtension===".jpg" || fileExtension===".png" || fileExtension ===".jpeg")
                 {

                 }
                 else
                 {
                      options.fileName = options.fileName+".jpg";
                 }
             }
            alert(options.fileName);
            var params = new Object();
            params.user_id = MaraMentor.sessionId;
            params.activity_type = "act_photos";
            params.func = "addBpfbActivity";
            params.api_key = "92b7d3ab2b864e8eee752fe8e979960c";
            params.screen = "Medium";
            params.source = "gallery";

            options.params = params;
            options.chunkedMode = false;

            var ft = new FileTransfer();
            ft.upload(imageUri, MaraMentor.serverURL, AddExterActivityManager.Win, AddExterActivityManager.Fail, options);*/

    },
    Win: function (r) {
        MaraMentor.HideLoader();
        MaraMentor.showToastMsg("Image is uploaded successfully.");
        DashManager.GetDashboardData(1);
    },
    Fail: function (error) {
        MaraMentor.HideLoader();
        MaraMentor.showToastMsg("Could not process request now,please try again.");
    },
    OnFail: function (message) {
       if(message!="no image selected")
        {
          MaraMentor.showToastMsg("Could not process request now,please try again.");
        }
        $("#loading").hide();
        MaraMentor.HideLoader();
    },
}
/******************** addExterActivity end *******************************/
/******************** uploadProfileImage   code  start **********************/
var UploadProfileImageManager = {
    UploadProfileImage: function () {
        var uId = MaraMentor.sessionId;
        var elm = '<div class="background html-content"><div class="form-bg">';
        elm += '<div class="capture">' +
            '<h5>Upload Photo by Camera</h5>' +
            '<button onclick="UploadProfileImageManager.UploadProfilePhotoByCamera();" class="btnPost" >Capture Photo</button>' +
            '</div>' +
            '<div class="fromGallery">' +
            '<h5 >Upload Photo by Gallery</h5>' +
            '<button onclick="UploadProfileImageManager.UploadProfilePhotoByGallery();" class="btnPost" >From Gallery</button>' +
            '</div>';
        elm += '<div class="clear"></div></div></div>';
        //$('#wrapContent').html(elm).trigger("create");
        MaraMentor.ChangePageContent(elm);
        MaraMentor.PushPageRecord("profilePicture");
        MaraMentor.ChangeHeaderText('Add Profile Picture');
    },
    UploadProfilePhotoByCamera: function () {
        //$("#loading").show();
        // Take picture using device camera and retrieve image as base64-encoded string
        navigator.camera.getPicture(UploadProfileImageManager.OnuploadProfilePhotoSuccess, UploadProfileImageManager.OnuploadProfilePhotoFail, {
            allowEdit: true,
            quality: 45,
            encodingType: Camera.EncodingType.JPEG,
            targetWidth: 150,
            targetHeight: 150,
        });
    },
    UploadProfilePhotoByGallery: function () {
        source = Camera.PictureSourceType.PHOTOLIBRARY;
        // Retrieve image file location from specified source
        navigator.camera.getPicture(UploadProfileImageManager.OnuploadProfilePhotoSuccess, UploadProfileImageManager.OnuploadProfilePhotoFail, {
            allowEdit: true,
            quality: 45,
            destinationType: destinationType.FILE_URI,
            sourceType: source,
            encodingType: Camera.EncodingType.JPEG,
            targetWidth: 150,
            targetHeight: 150,
        });
    },
    OnuploadProfilePhotoSuccess: function (imageData) {
        //$("#loading").show();
        MaraMentor.ShowLoader()
        var options = new FileUploadOptions();
        options.headers = {
            Connection: "close"
        }
        options.fileKey = "file";
        options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
        options.mimeType = "image/jpeg";
        //alert(options.fileName);
        picName = options.fileName;
        if((picName).indexOf(".") === -1)
             {
                 options.fileName = options.fileName+".jpg";

             }
             else
             {
                 fileExtension = picName.substr(picName.lastIndexOf('.')+1);
                 //alert(fileExtension);
                 if(fileExtension===".jpg" || fileExtension===".png" || fileExtension ===".jpeg")
                 {

                 }
                 else
                 {
                      options.fileName = options.fileName+".jpg";
                 }
             }


        var params = new Object();
        params.user_id = MaraMentor.sessionId;
        params.func = "mobileUploadProfilePhoto";
        params.api_key = "92b7d3ab2b864e8eee752fe8e979960c";
        params.screen = "Medium";
        options.params = params;
        options.chunkedMode = false;

        var ft = new FileTransfer();
        ft.upload(imageData, MaraMentor.serverURL, UploadProfileImageManager.UploadProfilePhotoWin, UploadProfileImageManager.UploadProfileUploadPhotoFail, options);
    },
    OnuploadProfilePhotoFail: function (message) {
        MaraMentor.HideLoader();
        if(message!="no image selected")
        {
          MaraMentor.showToastMsg("Could not process request now,please try again.");
        }

    },
    UploadProfilePhotoWin: function (arg) {
        console.log(arg);
        //arg=JSON.parse(arg);
        MaraMentor.HideLoader();
        //alert(arg);
        MaraMentor.showToastMsg("Profile Picture is uploaded successfully.");
        //MoreScreenManager.GetMoreScreenHtml();
        ProfileManager.Profile(MaraMentor.sessionId, '1');

    },
    UploadProfileUploadPhotoFail: function (error) {
        //$("#loading").hide();
        MaraMentor.HideLoader();
        MaraMentor.showToastMsg("Could not process request now,please try again.");

    },
    UploadProfilePhotoFail: function (error) {
        //$("#loading").hide();
        MaraMentor.HideLoader();
        MaraMentor.showToastMsg("Could not process request now,please try again.");

    },
}
/******************** uploadProfileImage end *******************************/

PushNotificationManger = {
    NotificationSuccess: function (result) {
        var result = JSON.parse(result);
        //alert(result);
        if (result.status == "true" || result.message == "Device already registered") {
            window.localStorage.setItem("pushNotification_" + MaraMentor.sessionId, "true");
        } else {
            window.localStorage.setItem("pushNotification_" + MaraMentor.sessionId, "");
        }
    },
    NotificationFailure: function () {
        window.localStorage.setItem("pushNotification_" + MaraMentor.sessionId, "");
        //alert(window.localStorage.getItem("pushNotification_"+MaraMentor.sessionId));
        // console.log("error");
    },

}


function onNotificationGCM(e) {
    switch( e.event )
    {
    case 'registered':
        if ( e.regid.length > 0 )
        {
            // Your GCM push server needs to know the regID before it can push to this device
            // here is where you might want to send it the regID for later use.
            //alert("regID = " + e.regid);
            //console.log(e.regid);
            var myURL = "https://mentor.mara.com/rp-register-device/?task=register&platform=android&userid=" + MaraMentor.sessionId + "&devicetoken=" + e.regid;
            MaraMentor.MakeAjaxCall2(myURL, "", "GET", PushNotificationManger.NotificationSuccess, PushNotificationManger.NotificationFailure);

        }
    break;

    case 'message':
        // if this flag is set, this notification happened while we were in the foreground.
        // you might want to play a sound to get the user's attention, throw up a dialog, etc.
        if ( e.foreground )
        {
            //$("#app-status-ul").append('<li>--INLINE NOTIFICATION--' + '</li>');

            // if the notification contains a soundname, play it.
            //alert("Forground");
            //var my_media = new Media("/android_asset/www/"+e.soundname);
            //my_media.play();
        }
        else
        {  // otherwise we were launched because the user touched a notification in the notification tray.
            if ( e.coldstart )
            {
               //alert("COLDSTART NOTIFICATION");
               //console.log("COLDSTART NOTIFICATION");
            }
            else
            {
                //alert("BACKGROUND NOTIFICATION");
                //console.log("BACKGROUND NOTIFICATION");
              //$("#app-status-ul").append('<li>--BACKGROUND NOTIFICATION--' + '</li>');
            }
        }
        //alert(e.payload.message);
        //alert(e.payload.msgcnt );
        //$("#app-status-ul").append('<li>MESSAGE -> MSG: ' + e.payload.message + '</li>');
        //$("#app-status-ul").append('<li>MESSAGE -> MSGCNT: ' + e.payload.msgcnt + '</li>');
    break;

    case 'error':
        //console.log(e.msg);
    break;

    default:
        //console.log("Unknown, an event was received and we do not know what it is");
        //alert("unknown");
    break;
  }
}

function pushErrorHandler(error) {
    window.localStorage.setItem("pushNotification_" + MaraMentor.sessionId, "");
   // console.log("push error");
   // alert("Push error: "+error);
}

function pushSuccessHandler(result){
  //alert("Push Success: "+result);
  //console.log(result);
}


//pinch and zoom function

var angle = 0;

var newAngle;

var scale = 1;

var newScale;



function saveChanges() {

    angle = newAngle;

    scale = newScale;

}

function getAngleAndScale(e) {

    // Don't zoom or rotate the whole screen

    e.preventDefault();

    // Rotation and scale are event properties

    newAngle = angle + e.rotation;

    newScale = scale * e.scale;

    // Combine scale and rotation into a single transform

    var tString = "scale(" + newScale + ")";

    document.getElementById("imagePopup").style.webkitTransform = tString;

}