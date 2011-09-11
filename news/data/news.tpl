<?PHP
///////////////////// TEMPLATE news /////////////////////
$template_active = <<<HTML
<div class="top"> </div>
<div class="body"> 
  <div class="avatar">{avatar}</div> 
  <div class="title_bg"> 
    <div class="title">{title}</div> 
    <div class="feed"><strong>Feed:</strong> {category}</div> 
  </div> 
  <div class="story">{short-story} <small>[full-link]<br /> 
    Read More ...[/full-link]</small> </div> 
   <div class="clear"></div>
  <div class="footer"> 
    <div class="date">{date} by {author}</div> 
    <div class="postedby">[com-link]Comments ({comments-num})[/com-link]</div> 
  </div> 
</div>
<div class="bottom"> </div>
<br class="div" />

HTML;


$template_full = <<<HTML
<div class="top"> </div>
<div class="body"> 
  <div class="avatar">{avatar}</div> 
  <div class="title_bg"> 
    <div class="title">{title}</div> 
    <div class="feed"><strong>Feed:</strong> {category}</div> 
    <div class="clear"> </div> 
  </div> 
  <div class="story">{full-story}  </div> 
  <div class="clear"></div> 
  <div class="footer"> 
    <div class="date">{date} by {author}</div> 
    <div class="postedby">Comments ({comments-num})</div> 
  </div> 
</div>
<div class="bottom"> </div>
<br class="div" />

HTML;


$template_comment = <<<HTML
<table border="0" width="400" height="40" cellspacing="" cellpadding="3">
    <tr>
      <td height="1" style="border-bottom-style: solid;border-bottom-width: 1px; border-bottom-color: black;"><div class="content">by <b>{author}</b> @ {date}</div></td>
    </tr>
    <tr>
      <td height="40" valign="top" ><div class="content">{comment}</div></td>
    </tr>
  </table>
HTML;


$template_form = <<<HTML
<div> Name:<br> 
  <input type="text" name="name" tabindex="1"> 
</div>
<div>Email (Optional):<br> 
  <input type="text" name="mail" tabindex="2"> 
</div>
<div>Comments:<br> 
  <textarea cols="40" rows="6" name="comments" tabindex="3"></textarea> 
</div>
<div> 
  <input type="submit" name="submit2" value="Add My Comment" accesskey="s"> 
  <input type=checkbox name=CNremember2  id=CNremember2 value=1> 
  <label for=CNremember2>Remember Me</label> 
  | <a href="javascript:CNforget();">Forget Me</a> </div>

HTML;


$template_prev_next = <<<HTML
<p align="center">[prev-link]<< Previous[/prev-link] {pages} [next-link]Next >>[/next-link]</p>
HTML;
$template_comments_prev_next = <<<HTML
<p align="center">[prev-link]<< Older[/prev-link] ({pages}) [next-link]Newest >>[/next-link]</p>
HTML;
?>
