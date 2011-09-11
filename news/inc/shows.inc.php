<?PHP


do{ // Used if we want to display some error to the user and halt the rest of the script

$user_query = cute_query_string($QUERY_STRING, array( "comm_start_from","start_from", "archive", "subaction", "id", "ucat"));
$user_post_query = cute_query_string($QUERY_STRING, array( "comm_start_from", "start_from", "archive", "subaction", "id", "ucat"), "post");
//####################################################################################################################
//                         Define Categories
//####################################################################################################################
$cat_lines = file("$cutepath/data/category.db.php");
foreach($cat_lines as $single_line){
        $cat_arr = explode("|", $single_line);
    $cat[$cat_arr[0]] = $cat_arr[1];
    $cat_icon[$cat_arr[0]]=$cat_arr[2];
}
//####################################################################################################################
//                         Define Users
//####################################################################################################################
$all_users = file("$cutepath/data/users.db.php");
foreach($all_users as $user)
{
        if(!eregi("<\?",$member_db_line)){
                $user_arr = explode("|",$user);
                        if($user_arr[4] != "")
                            {
                                    if($user_arr[7] != 1 and $user_arr[5] != ""){ $my_names[$user_arr[2]] = "<a href=\"mailto:$user_arr[5]\">$user_arr[4]</a>"; }
                                        else{ $my_names[$user_arr[2]] = "$user_arr[4]"; }
                    $name_to_nick[$user_arr[2]] = $user_arr[4];
                            }
                                else
                            {
                                    if($user_arr[7] != 1 and $user_arr[5] != ""){ $my_names[$user_arr[2]] = "<a href=\"mailto:$user_arr[5]\">$user_arr[2]</a>"; }
                                        else{ $my_names[$user_arr[2]] = "$user_arr[2]"; }
                    $name_to_nick[$user_arr[2]] = $user_arr[2];
                }

                if($user_arr[7] != 1){ $my_mails[$user_arr[2]] = $user_arr[5]; }
                else{ $my_mails[$user_arr[2]] = ""; }
                $my_passwords[$user_arr[2]] = $user_arr[3];
                                $my_users[] = $user_arr[2];
    }
}
//####################################################################################################################
//                         Add Comment
//####################################################################################################################
if($allow_add_comment){

        $name = trim($name);
        $mail = trim($mail);
    $id = (int) $id;  // Yes it's stupid how I didn't thought about this :/

    //----------------------------------
    // Check the lenght of comment, include name + mail
    //----------------------------------

        if( strlen($name) > 50 ){
               echo"<div style=\"text-align: center;\">Your name is too long!</div>";
                $CN_HALT = TRUE;
                break 1;
        }
        if( strlen($mail) > 50){
               echo"<div style=\"text-align: center;\">Your e-mail is too long!</div>";
                $CN_HALT = TRUE;
                break 1;
        }
        if( strlen($comments) > $config_comment_max_long and $config_comment_max_long != "" and $config_comment_max_long != "0"){
               echo"<div style=\"text-align: center;\">Your comment is too long!</div>";
                $CN_HALT = TRUE;
                break 1;
        }

    //----------------------------------
    // Get the IP
    //----------------------------------
        if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
        else $ip = "not detected";

    //----------------------------------
    // Flood Protection
    //----------------------------------
    if($config_flood_time != 0 and $config_flood_time != "" ){
        if(flooder($ip, $id) == TRUE ){
                echo("<div style=\"text-align: center;\">Flood protection activated !!!<br />you have to wait $config_flood_time seconds after your last comment before posting again at this article.</div>");
                         $CN_HALT = TRUE;
             break 1;
                }
    }

    //----------------------------------
    // Check if IP is blocked
    //----------------------------------
    $blockip = FALSE;
    $old_ips = file("$cutepath/data/ipban.db.php");
    $new_ips = fopen("$cutepath/data/ipban.db.php", "w");
    @flock ($new_ips,2);
    foreach($old_ips as $old_ip_line){
            $ip_arr = explode("|", $old_ip_line);
        if($ip_arr[0] != $ip){
                                fwrite($new_ips, $old_ip_line);
        }else{
                        $countblocks = $ip_arr[1] = $ip_arr[1] + 1;
                        fwrite($new_ips, "$ip_arr[0]|$countblocks||\n"); $blockip = TRUE;
        }
    }
    @flock ($new_ips,3);
    fclose($new_ips);
    if($blockip){
            echo("<div style=\"text-align: center;\">Sorry but you have been blocked from posting comments</div>");
         $CN_HALT = TRUE;
     break 1;
    }

    //----------------------------------
    // Check if the name is protected
    //----------------------------------
    $is_member = FALSE;
    foreach($all_users as $member_db_line)
    {
        if(!eregi("<\?",$member_db_line) and $member_db_line != ""){
                        $user_arr = explode("|",$member_db_line);

            //if the name is protected
            if((strtolower($user_arr[2]) == strtolower($name) or strtolower($user_arr[4]) == strtolower($name)) and    ($user_arr[3] != $CNpass and $user_arr[3] != md5($password))    and $name != "")
                {
                                //$comments         = replace_comment("add", $comments); //commented because will mess up the <br />
                                $comments        = preg_replace(array("'\"'", "'\''", "''"), array("&quot;", "&#039;", ""), $comments);
                            $name                = replace_comment("add", preg_replace("/\n/", "",$name));
                                $mail                 = replace_comment("add", preg_replace("/\n/", "",$mail));


             echo"<div style=\"text-align: center;\">This name is owned by a registered user and you must enter password to use it<br />
             <form name=passwordForm id=passwordForm method=\"post\" action=\"\">
             Password: <input type=\"password\" name=\"password\" />
             <input type=\"hidden\" name=\"name\" value=\"$name\" />
             <input type=\"hidden\" name=\"comments\" value=\"$comments\" />
             <input type=\"hidden\" name=\"mail\" value=\"$mail\" />
             <input type=\"hidden\" name=\"ip\" value=\"$ip\" />
             <input type=\"hidden\" name=\"subaction\" value=\"addcomment\" />
             <input type=\"hidden\" name=\"show\" value=\"$show\" />
             <input type=\"hidden\" name=\"ucat\" value=\"$ucat\" />
             $user_post_query
             <input type=\"submit\" /> \n <br>
             <input type=\"checkbox\" name=\"CNrememberPass\" value=1 /> Remember password in cookie (md5 format)
             </form>
              </div>";
                         $CN_HALT = TRUE;
             break 2;

                }

            if(strtolower($user_arr[2]) == strtolower($name)) $is_member = TRUE;

                //----------------------------------
                // Member wants to save his pass in cookie ?
                //----------------------------------
                    if($CNrememberPass == 1){
                                if(file_exists("$cutepath/remember.js")){
                                    echo"<script type=\"text/javascript\" src=\"$config_http_script_dir/remember.js\"></script>";
                                    echo"<script>CNRememberPass('".md5($password)."')</script>";
                                }
                                }

        }
        }

    //----------------------------------
    // Check if only members can comment
    //----------------------------------
        if($config_only_registered_comment == "yes" and !$is_member){
            echo"<div style=\"text-align: center;\">Sorry but only registered users can post comments, and '".htmlspecialchars($name)."' is not recognized as valid member.</div>";
                         $CN_HALT = TRUE;
             break 1;
    }

    //----------------------------------
    // Wrap the long words
    //----------------------------------
    if($config_auto_wrap > 1){
        $comments_arr = explode("\n", $comments);
        foreach($comments_arr as $line){
                $wraped_comm .= ereg_replace("([^ \/\/]{".$config_auto_wrap."})","\\1\n", $line) ."\n";
        }
            if(strlen($name) > $config_auto_wrap){ $name = substr($name, 0, $config_auto_wrap)." ..."; }
    $comments = $wraped_comm;
    }



    //----------------------------------
    // Do some validation check 4 name, mail..
    //----------------------------------
    $comments         = replace_comment("add", $comments);
    $name                = replace_comment("add", preg_replace("/\n/", "",$name));
        $mail                 = replace_comment("add", preg_replace("/\n/", "",$mail));

    if($name == " " or $name == ""){
            echo("<div style=\"text-align: center;\">You must enter name.<br /><a href=\"javascript:history.go(-1)\">go back</a></div>");
                $CN_HALT = TRUE;
                break 1;
        }
    if($mail == " " or $mail == ""){ $mail = "none"; }
    else{ $ok = FALSE;
            if(preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $mail)) $ok = TRUE;
        elseif($config_allow_url_instead_mail == "yes" and preg_match("/((http(s?):\/\/)|(www\.))([\w\.]+)([\/\w+\.-?]+)/", $mail)) $ok = TRUE;
        elseif($config_allow_url_instead_mail != "yes"){
                echo("<div style=\"text-align: center;\">This is not a valid e-mail<br /><a href=\"javascript:history.go(-1)\">go back</a></div>");
                        $CN_HALT = TRUE;
                        break 1;
        }
                else{
                echo("<div style=\"text-align: center;\">This is not a valid e-mail or site URL<br /><a href=\"javascript:history.go(-1)\">go back</a></div>");
                        $CN_HALT = TRUE;
                        break 1;
        }
    }

    if($comments == ""){
            echo("<div style=\"text-align: center;\">Sorry but the comment can not be blank<br /><a href=\"javascript:history.go(-1)\">go back</a></div>");
                        $CN_HALT = TRUE;
                        break 1;
    }

    $time = time()+($config_date_adjust*60);

    //----------------------------------
    // Add The Comment ... Go Go GO!
    //----------------------------------
    $old_comments = file("$comm_file");
    $new_comments = fopen("$comm_file", "w");
    @flock ($new_comments,2);
    $found = FALSE;
    foreach($old_comments as $old_comments_line)
    {
                $old_comments_arr = explode("|>|", $old_comments_line);
                if($old_comments_arr[0] == $id)
                {
                        $old_comments_arr[1] = trim($old_comments_arr[1]);
                        fwrite($new_comments, "$old_comments_arr[0]|>|$old_comments_arr[1]$time|$name|$mail|$ip|$comments||\n");
            $found = TRUE;
        }else{
                        fwrite($new_comments, $old_comments_line);
                }
        }
    if(!$found){/* // do not add comment if News ID is not found \\ fwrite($new_comments, "$id|>|$time|$name|$mail|$ip|$comments||\n");*/ }
    @flock ($new_comments,3);
    fclose($new_comments);

    //----------------------------------
    // Sign this comment in the Flood Protection
    //----------------------------------
    if($config_flood_time != "0" and $config_flood_time != "" ){

            $flood_file = fopen("$cutepath/data/flood.db.php", "a");
            @flock ($flood_file,2);
        fwrite($flood_file, time()."|$ip|$id|\n");
            @flock ($flood_file,3);
        fclose($flood_file);
    }
}
//####################################################################################################################
//                 Show Full Story
//####################################################################################################################
if($allow_full_story){

        $all_active_news = file("$news_file");

    foreach($all_active_news as $active_news)
    {
        $news_arr = explode("|", $active_news);
        if($news_arr[0] == $id and (!$catid or $catid == $news_arr[6]))
        {
            $found = TRUE;
            if($news_arr[4] == "" and (!eregi("\{short-story\}", $template_full)) ){ $news_arr[4] = $news_arr[3]; }

            if($my_names[$news_arr[1]]){ $my_author = $my_names[$news_arr[1]]; }
                    else{ $my_author = $news_arr[1]; }

                        $output = str_replace("{title}", $news_arr[2], $template_full);
                        $output = str_replace("{date}", date($config_timestamp_active, $news_arr[0]), $output);
                        $output = str_replace("{author}", $my_author, $output);
                        $output = str_replace("{short-story}", $news_arr[3], $output);
                        $output = str_replace("{full-story}", $news_arr[4], $output);
                if($news_arr[5] != ""){$output = str_replace("{avatar}", "<img alt=\"\" src=\"$news_arr[5]\" style=\"border: none;\" />", $output); }
                else{ $output = str_replace("{avatar}", "", $output); }
                        $output = str_replace("{avatar-url}", "$news_arr[5]", $output);
                        $output = str_replace("{comments-num}", countComments($news_arr[0], $archive), $output);
                        $output = str_replace("{category}", $cat[$news_arr[6]], $output);
                        $output = str_replace("{category-id}", $news_arr[6], $output);
                        if($cat_icon[$news_arr[6]] != ""){ $output = str_replace("{category-icon}", "<img style=\"border: none;\" alt=\"".$cat[$news_arr[6]]." icon\" src=\"".$cat_icon[$news_arr[6]]."\" />", $output); }
                        else{ $output = str_replace("{category-icon}", "", $output); }

                    if($config_comments_popup == "yes"){
                                $output = str_replace("[com-link]","<a href=\"#\" onclick=\"window.open('$config_http_script_dir/show_news.php?subaction=showcomments&amp;template=$template&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]', '_News', '$config_comments_popup_string');return false;\">", $output);
                        }else{
                                $output = str_replace("[com-link]","<a href=\"$PHP_SELF?subaction=showcomments&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]&amp;$user_query\">", $output);
                        }
                        $output = str_replace("[/com-link]","</a>", $output);
                        $output = str_replace("{author-name}", $name_to_nick[$news_arr[1]], $output);

            if($my_mails[$news_arr[1]] != ""){
                $output = str_replace("[mail]","<a href=\"mailto:".$my_mails[$news_arr[1]]."\">", $output);
                $output = str_replace("[/mail]","</a>", $output);
                        }else{
                $output = str_replace("[mail]","", $output);
                $output = str_replace("[/mail]","", $output);
            }
                        $output = str_replace("{news-id}", $news_arr[0], $output);
                        $output = str_replace("{archive-id}", $archive, $output);
                        $output = str_replace("{php-self}", $PHP_SELF, $output);
                        $output = str_replace("{cute-http-path}", $config_http_script_dir, $output);

                        $output = replace_news("show", $output);

                        echo $output;
        }
        }
        if(!$found){
            echo("<div style=\"text-align: center;\">Can not find an article with id: <strong>". @(int) htmlspecialchars($id)."</strong></div>");
                $CN_HALT = TRUE;
                break 1;
    }
}
//####################################################################################################################
//                 Show Comments
//####################################################################################################################
if($allow_comments){


    $comm_per_page = $config_comments_per_page;

        $total_comments = 0;
        $showed_comments = 0;
        $comment_number = 0;
        $showed = 0;
        $all_comments = file("$comm_file");

        foreach($all_comments as $comment_line)
        {
                $comment_line = trim($comment_line);
                $comment_line_arr = explode("|>|", $comment_line);
                if($id == $comment_line_arr[0])
                {
                        $individual_comments = explode("||", $comment_line_arr[1]);

                        $total_comments = @count($individual_comments) - 1;

            //show the page with our new comment, if we just added one
            /* causes some problems, will be updated !!!
                        if($allow_add_comment and true){
                                $comm_start_from = $total_comments-1;
                                if($config_reverse_comments == "yes"){
                                        $comm_start_from = 0;
                                }
                        }
                        */

            if($config_reverse_comments == "yes"){ $individual_comments = array_reverse($individual_comments); }
            foreach($individual_comments as $comment)
                        {

                                $comment_arr = explode("|", $comment);
                                if($comment_arr[0] != "")
                                {

                                if(isset($comm_start_from) and $comm_start_from != ""){
                                        if($comment_number < $comm_start_from){ $comment_number++; continue; }
                                    elseif($showed_comments == $comm_per_page){  break; }
                                }

                        $comment_number ++;
                                        $comment_arr[4] = stripslashes(rtrim($comment_arr[4]));

                                        if($comment_arr[2] != "none"){
                        if( preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $comment_arr[2])){ $url_target = "";$mail_or_url = "mailto:"; }
                        else{
                            $url_target = "target=\"_blank\"";
                            $mail_or_url = "";
                            if(substr($comment_arr[2],0,3) == "www"){ $mail_or_url = "http://"; }
                                                }

                            $output = str_replace("{author}", "<a $url_target href=\"$mail_or_url".stripslashes($comment_arr[2])."\">".stripslashes($comment_arr[1])."</a>", $template_comment);
                    }
                                        else{ $output = str_replace("{author}", $comment_arr[1], $template_comment); }

                        $comment_arr[4] = preg_replace("/\b((http(s?):\/\/)|(www\.))([\w\.]+)([-~\/\w+\.-?]+)\b/i", "<a href=\"http$3://$4$5$6\" target=\"_blank\">$2$4$5$6</a>", $comment_arr[4]);
                        $comment_arr[4] = preg_replace("/([\w\.]+)(@)([-\w\.]+)/i", "<a href=\"mailto:$0\">$0</a>", $comment_arr[4]);


                                        $output = str_replace("{mail}", "$comment_arr[2]",$output);
                                        $output = str_replace("{date}", date($config_timestamp_comment, $comment_arr[0]),$output);
                                        $output = str_replace("{comment-id}", $comment_arr[0],$output);
                                        $output = str_replace("{comment}", "<a name=\"".$comment_arr[0]."\"></a>$comment_arr[4]",$output);

                                        $output = replace_comment("show", $output);
                                        echo $output;
                                        $showed_comments++;
                                        if($comm_per_page != 0 and $comm_per_page == $showed_comments){ break; }
                                }
                        }
                }
        }

    //----------------------------------
    // Prepare the Comment Pagination
    //----------------------------------

    $prev_next_msg = $template_comments_prev_next;

    // Previous link
    if(isset($comm_start_from) and $comm_start_from != "" and $comm_start_from > 0){
            $prev = $comm_start_from - $comm_per_page;
        $prev_next_msg = preg_replace("'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"$PHP_SELF?comm_start_from=$prev&amp;archive=$archive&amp;subaction=showcomments&amp;id=$id&amp;$user_query\">\\1</a>", $prev_next_msg);
    }else{ $prev_next_msg = preg_replace("'\[prev-link\](.*?)\[/prev-link\]'si", "\\1", $prev_next_msg); $no_prev = TRUE; }

    // Pages
        if($comm_per_page){
    $pages_count = @ceil($total_comments/$comm_per_page);
    $pages_start_from = 0;
    $pages = "";
    for($j=1;$j<=$pages_count;$j++){
        if($pages_start_from != $comm_start_from){ $pages .= "<a href=\"$PHP_SELF?comm_start_from=$pages_start_from&amp;archive=$archive&amp;subaction=showcomments&amp;id=$id&amp;$user_query\">$j</a> "; }
                else{ $pages .= " <strong>$j</strong> "; }
        $pages_start_from += $comm_per_page;
        }
        $prev_next_msg = str_replace("{pages}", $pages, $prev_next_msg);
        }

    // Next link
    if($comm_per_page < $total_comments and $comment_number < $total_comments){
        $prev_next_msg = preg_replace("'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"$PHP_SELF?comm_start_from=$comment_number&amp;archive=$archive&amp;subaction=showcomments&amp;id=$id&amp;$user_query\">\\1</a>", $prev_next_msg);
    }else{ $prev_next_msg = preg_replace("'\[next-link\](.*?)\[/next-link\]'si", "\\1", $prev_next_msg); $no_next = TRUE;}

    if        (!$no_prev or !$no_next){
            echo $prev_next_msg;
    }



        $template_form = str_replace("{config_http_script_dir}", "$config_http_script_dir", $template_form);
    //----------------------------------
    // Check if the remember script exists
    //----------------------------------
    $CN_remember_include = '';
    $CN_remember_form = '';
    if(file_exists("$cutepath/remember.js")){
            $CN_remember_include = "<script type=\"text/javascript\" src=\"$config_http_script_dir/remember.js\"></script><script>CNreadCookie();</script>";
            $CN_remember_form = "onsubmit=\"return CNSubmitComment()\"";
    }


    $smilies_form = "\n<script type=\"text/javascript\">
        //<![CDATA[
        function insertext(text){
        document.comment.comments.value+=\" \"+ text;
        document.comment.comments.focus();
        }
        //]]></script>
        <noscript>Your browser is not Javascript enable or you have turn it off. We recommend you to activate, otherwise you will have to enter the emoticons representations manually.
        </noscript>".insertSmilies('short', FALSE);

    $template_form = str_replace("{smilies}", $smilies_form, $template_form);

    echo"<form  $CN_remember_form  method=\"post\" name=\"comment\" id=\"comment\" action=\"\">".$template_form."<div><input type=\"hidden\" name=\"subaction\" value=\"addcomment\" /><input type=\"hidden\" name=\"ucat\" value=\"$ucat\" /><input type=\"hidden\" name=\"show\" value=\"$show\" />$user_post_query</div></form>
                    \n $CN_remember_include";

}
//####################################################################################################################
//                 Active News
//####################################################################################################################

if($allow_active_news){

        $all_news = file("$news_file");
    if($reverse == TRUE){ $all_news = array_reverse($all_news); }

    $count_all = 0;
    if(isset($category) and $category != ""){
            foreach($all_news as $news_line){
                        $news_arr = explode("|", $news_line);
                        if($requested_cats and $requested_cats[$news_arr[6]] == TRUE){ $count_all ++; }
                else{ continue; }
                }
        }else{ $count_all = count($all_news); }

    $i = 0;
    $showed = 0;
        $repeat = TRUE;
    $url_archive = $archive;
    while($repeat != FALSE){

                foreach($all_news as $news_line){

                   $news_arr = explode("|", $news_line);
                if($category and $requested_cats[$news_arr[6]] != TRUE){ continue; }

        if(isset($start_from) and $start_from != ""){
                if($i < $start_from){ $i++; continue; }
            elseif($showed == $number){  break; }
        }

        if($my_names[$news_arr[1]]){ $my_author = $my_names[$news_arr[1]]; }
        else{ $my_author = $news_arr[1]; }

        $output = $template_active;
        $output = str_replace("{title}", $news_arr[2], $output);
        $output = str_replace("{date}", date($config_timestamp_active, $news_arr[0]), $output);
        $output = str_replace("{author}", $my_author, $output);
        if($news_arr[5] != ""){$output = str_replace("{avatar}", "<img alt=\"\" src=\"$news_arr[5]\" style=\"border: none;\" />", $output); }
        else{ $output = str_replace("{avatar}", "", $output); }
                $output = str_replace("{avatar-url}", "$news_arr[5]", $output);
        $output = str_replace("[link]","<a href=\"$PHP_SELF?subaction=showfull&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]&amp;$user_query\">", $output);
        $output = str_replace("[/link]","</a>", $output);
        $output = str_replace("{comments-num}", countComments($news_arr[0], $archive), $output);
        $output = str_replace("{short-story}", $news_arr[3], $output);
        $output = str_replace("{full-story}", $news_arr[4], $output);
                $output = str_replace("{category}", $cat[$news_arr[6]], $output);
                $output = str_replace("{category-id}", $news_arr[6], $output);
                if($cat_icon[$news_arr[6]] != ""){ $output = str_replace("{category-icon}", "<img alt=\"".$cat[$news_arr[6]]." icon\" style=\"border: none;\" src=\"".$cat_icon[$news_arr[6]]."\" />", $output); }
                else{ $output = str_replace("{category-icon}", "", $output); }

                $output = str_replace("{author-name}", $name_to_nick[$news_arr[1]], $output);

         if($my_mails[$news_arr[1]] != ""){
             $output = str_replace("[mail]","<a href=\"mailto:".$my_mails[$news_arr[1]]."\">", $output);
             $output = str_replace("[/mail]","</a>", $output);
                }else{
             $output = str_replace("[mail]","", $output);
             $output = str_replace("[/mail]","", $output);
         }

                $output = str_replace("{news-id}", $news_arr[0], $output);
                $output = str_replace("{archive-id}", $archive, $output);
                $output = str_replace("{php-self}", $PHP_SELF, $output);
                $output = str_replace("{cute-http-path}", $config_http_script_dir, $output);

        $output = replace_news("show", $output);


        if($news_arr[4] != "" or $action == "showheadlines"){//if full story
            if($config_full_popup == "yes"){

                    $output = preg_replace("/\\[full-link\\]/","<a href=\"#\" onclick=\"window.open('$config_http_script_dir/show_news.php?subaction=showfull&amp;id=$news_arr[0]&amp;archive=$archive&amp;template=$template', '_News', '$config_full_popup_string');return false;\">", $output);
            }else{
                    $output = str_replace("[full-link]","<a href=\"$PHP_SELF?subaction=showfull&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]&amp;$user_query\">", $output);
            }
                $output = str_replace("[/full-link]","</a>", $output);
                }else{
                        $output = preg_replace("'\\[full-link\\].*?\\[/full-link\\]'si","<!-- no full story-->", $output);
                }

                if($config_comments_popup == "yes"){
                        $output = str_replace("[com-link]","<a href=\"#\" onclick=\"window.open('$config_http_script_dir/show_news.php?subaction=showcomments&amp;template=$template&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]', '_News', '$config_comments_popup_string');return false;\">", $output);
                }else{
                        $output = str_replace("[com-link]","<a href=\"$PHP_SELF?subaction=showcomments&amp;id=$news_arr[0]&amp;archive=$archive&amp;start_from=$my_start_from&amp;ucat=$news_arr[6]&amp;$user_query\">", $output);
                }
                $output = str_replace("[/com-link]","</a>", $output);


                echo $output;
                $showed++;
        $i++;

        if($number != 0 and $number == $i){ break; }
            }
        $used_archives[$archive] = TRUE;
// Archives Looop
        if($i < $number and $only_active != TRUE){

                        if(!$handle = opendir("$cutepath/data/archives")){ die("<div style=\"text-align: center;\">Can not open directory $cutepath/data/archives</div>"); }
                         while (false !== ($file = readdir($handle)))
                 {
                                 if($file != "." and $file != ".." and eregi("news.arch", $file))
                     {
                                         $file_arr = explode(".",$file);
                        $archives_arr[$file_arr[0]] = $file_arr[0];
                                 }
                         }
                        closedir($handle);

            $archives_arr[$in_use]="";
            $in_use = max($archives_arr);

                        if($in_use != "" and !$used_archives[$in_use]){
                                $all_news = file("$cutepath/data/archives/$in_use.news.arch");
                                $archive = $in_use;
                $used_archives[$in_use] = TRUE;
                        }else{ $repeat = FALSE; }

            }else{ $repeat = FALSE; }
        }

// << Previous   &   Next >>

    $prev_next_msg = $template_prev_next;

    //----------------------------------
    // Previous link
    //----------------------------------
    if(isset($start_from) and $start_from != "" and $start_from > 0){
            $prev = $start_from - $number;
        $prev_next_msg = preg_replace("'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"$PHP_SELF?start_from=$prev&amp;ucat=$ucat&amp;archive=$url_archive&amp;subaction=$subaction&amp;id=$id&amp;$user_query\">\\1</a>", $prev_next_msg);
    }else{ $prev_next_msg = preg_replace("'\[prev-link\](.*?)\[/prev-link\]'si", "\\1", $prev_next_msg); $no_prev = TRUE; }

    //----------------------------------
    // Pages
    //----------------------------------
    if($number){
    $pages_count = @ceil($count_all/$number);
    $pages_start_from = 0;
    $pages = "";
    for($j=1;$j<=$pages_count;$j++){
        if($pages_start_from != $start_from){ $pages .= "<a href=\"$PHP_SELF?start_from=$pages_start_from&amp;ucat=$ucat&amp;archive=$url_archive&amp;subaction=$subaction&amp;id=$id&amp;$user_query\">$j</a> "; }
                else{ $pages .= " <strong>$j</strong> "; }
        $pages_start_from += $number;
        }
        $prev_next_msg = str_replace("{pages}", $pages, $prev_next_msg);
        }
    //----------------------------------
    // Next link  (typo here ... typo there... typos everywhere !)
    //----------------------------------
    if($number < $count_all and $i < $count_all){
        $prev_next_msg = preg_replace("'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"$PHP_SELF?start_from=$i&amp;ucat=$ucat&amp;archive=$url_archive&amp;subaction=$subaction&amp;id=$id&amp;$user_query\">\\1</a>", $prev_next_msg);
    }else{ $prev_next_msg = preg_replace("'\[next-link\](.*?)\[/next-link\]'si", "\\1", $prev_next_msg); $no_next = TRUE;}


    if        (!$no_prev or !$no_next){ echo $prev_next_msg; }
}
}while(0);
?>