// OpenSim Intercom v0.1 by djphil (CC-BY-NC-SA 4.0)

string  url     = "http://domain.com/osintercom";
integer face    = 0;
integer weight  = 512;
integer height  = 512;
key     aviuuid;
string  aviname;
key     tinyid;

init(string name, key uuid)
{
    aviname = name;
    aviuuid = uuid;

    list command = llParseString2List(name, [" "], []);
    string fn    = llList2String(command, 0);
    string ln    = llList2String(command, 1);
    string fu    = url + "?nickname=" + llEscapeURL(fn + " " + ln);
    
    llSetTimerEvent(5.0);
    llRegionSayTo(uuid, PUBLIC_CHANNEL, "Please wait " + name + " ...");

    llClearPrimMedia(face);
    llSetPrimMediaParams(face, [
        PRIM_MEDIA_CURRENT_URL, fu,
        PRIM_MEDIA_HOME_URL, fu,
        PRIM_MEDIA_CONTROLS, 0,
        PRIM_MEDIA_ALT_IMAGE_ENABLE, TRUE,
        PRIM_MEDIA_AUTO_SCALE, FALSE,
        PRIM_MEDIA_AUTO_ZOOM, FALSE,
        PRIM_MEDIA_AUTO_PLAY, TRUE,
        PRIM_MEDIA_FIRST_CLICK_INTERACT, TRUE,
        PRIM_MEDIA_WIDTH_PIXELS, weight,
        PRIM_MEDIA_HEIGHT_PIXELS, height
    ]);
}

default
{
    state_entry()
    {
        llClearPrimMedia(face); 
        llSay(PUBLIC_CHANNEL, "Initialisation ...");
    }

    touch_start(integer number)
    {
        integer detectedface = llDetectedTouchFace(0);
        
        if (detectedface == TOUCH_INVALID_FACE)
        {
            llSay(PUBLIC_CHANNEL, "The touched face could not be determined");
        }

        else if (detectedface == face)
        {
            init(llDetectedName(0), llDetectedKey(0));
        }

        else
        {
            llSay(PUBLIC_CHANNEL, llDetectedName(0) + ", you touch face n° " + (string)detectedface);
            llResetScript(); 
        }
    }

    http_response(key id, integer status, list metadata, string body)
    {
        if (id == tinyid)
        {
            llRegionSayTo(aviuuid, PUBLIC_CHANNEL, "Intercom @ " + body);
        }
    }
    
    timer()
    {
        llSetTimerEvent(0.0);
        llRegionSayTo(aviuuid, PUBLIC_CHANNEL, "Click one more time " + aviname + " ...");
        tinyid = llHTTPRequest("http://tinyurl.com/api-create.php?url=" + url, [], "");
    }
    
    on_rez(integer start_param) {llResetScript();}
}