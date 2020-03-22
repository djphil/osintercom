// OpenSim Intercom v0.1 by djphil (CC-BY-NC-SA 4.0)

string base_url = "http://domain.com/osintercom/";
integer face = 4;
integer weight = 512;
integer height = 512;
integer tinurl = TRUE;
key tinuid;
key aviuid;

get_intercom(string name)
{
    string url = base_url + "?nickname=" + name;
    if (tinurl) tinuid = llHTTPRequest("http://tinyurl.com/api-create.php?url=" + url, [], "");

    llSetPrimMediaParams(face, [
        PRIM_MEDIA_AUTO_PLAY, TRUE,
        PRIM_MEDIA_AUTO_SCALE, TRUE,
        PRIM_MEDIA_FIRST_CLICK_INTERACT, TRUE,
        PRIM_MEDIA_ALT_IMAGE_ENABLE , TRUE,
        PRIM_MEDIA_AUTO_ZOOM, FALSE, 
        PRIM_MEDIA_CURRENT_URL, url,
        PRIM_MEDIA_HOME_URL, url,
        PRIM_MEDIA_HEIGHT_PIXELS, height,
        PRIM_MEDIA_WIDTH_PIXELS, weight
    ]);
}

default
{
    state_entry()
    {
        llOwnerSay("Initialisation ...");
    }

    attach(key uuid)
    {
        if (uuid != NULL_KEY)
        {
            aviuid = uuid;
            get_intercom(llEscapeURL(llKey2Name(uuid)));
        }
    }

    http_response(key uuid, integer status, list metadata, string body)
    {
        if (uuid == tinuid)
        {
            llRegionSayTo(aviuid, PUBLIC_CHANNEL, "\nIntercom @ " + body);
        }
    }

    changed(integer change)
    {
        if (change & CHANGED_OWNER)
        {
            llResetScript();
        }
    }
}
