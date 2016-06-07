<?php 
include('header.php');
$sql_vendor = "SELECT * FROM ".TABLE_PREFIX."user_vendor_details WHERE `vendor_id` = '".$_REQUEST['id']."' AND `profile_complete` = 'Y'";
$sql_vendor = mysql_query($sql_vendor) or die(mysql_error());
$row_vendor = mysql_fetch_array($sql_vendor);

$area_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendor['area_id']."'"));
$city_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendor['city_id']."'"));
$country_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `country_id` = '".$row_vendor['country_id']."'"));
$venue_details = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."user_vendor_details_venue WHERE `user_id` = '".$row_vendor['user_id']."'"));

$vendor_rating = "SELECT SUM(overall_rating) AS total_rating, COUNT(rating_id) AS reviewer_num FROM ".TABLE_PREFIX."vendor_ratings
                              WHERE vendor_id = '".$row_vendor['vendor_id']."' AND status != 'N' GROUP BY vendor_id";

$vendor_rating = mysql_query($vendor_rating) or die(mysql_error());
$vendor_rating = mysql_fetch_array($vendor_rating); 
                  
if($vendor_rating['reviewer_num'] > 0)
{
  $alltotal_rating = $vendor_rating['total_rating']/$vendor_rating['reviewer_num'];
  $reviewer_num = $vendor_rating['reviewer_num'];
} 
else
{
  $alltotal_rating = 0;
  $reviewer_num = 0;
}

?>
<script type="text/javascript">
  $(document).on('click','#contact_info_show',function(){

    var contact_info = <?php echo stripslashes($row_vendor['business_phone']);?>;
    if(contact_info!="")
    {
      //$('#show_contact').toggle();
      $('#show_contact').html(contact_info);
    }
  });
</script>

<script type="text/javascript">
    $(document).on('click','.favourite_click',function(){
      var login = "<?php echo $_SESSION['LOGIN'];?>";
      var login_id = "<?php echo $_SESSION['LOGIN_ID'];?>";
      var login_type = "<?php echo $_SESSION['LOGIN_TYPE'];?>";

      var fav_vendor_id = $(this).attr("alt");

      if(login == "TRUE" && parseInt(login_id) > 0 && login_type != "")
      {
        if(login_type=="User")
        {
          $.post('ajax/make_favourite.php',{fav_vendor_id:fav_vendor_id,type:'Vendor'},function(result){
            if(result==1)
            {
              alert('Thank you for marking this venue as your favourite.');
              return true;
            }
            else if(result==2)
            {
              alert('You have already marked this venue as your favourite.');
              return true;
            }
            else
            {
              alert('No article for this category.');
              return false;
            }
          });
        }
        else
        {
          alert("Only bride/groom can mark this venue as a favourite.");
          //$(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
          return false;
        }
      }
      else
      {
        alert("Log in to mark this venue as a favourite.");
        //$(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
        $("#login, .overlay2").fadeIn();
        return false;
      }

    });
</script>

<section class="venfor-details">
<div class="vendor-details-body">
    <h2 class="heading"><?php echo stripslashes($row_vendor['business_name']);?></h2>
    <div class="slider-box">
    <?php /*?> <p class="no-top-margin"><?php echo stripslashes($row_vendor['business_name']);?></p>   <?php */?>
   
      <section class="slider">
        <div id="slider" class="flexslider big-img-sec">
          <ul class="slides getBig gallery">
            <?php
            $sql_gallery_photo = "SELECT * FROM ".TABLE_PREFIX."vendor_photo_gallery WHERE `user_id` = '".$venue_details['user_id']."' ORDER BY `photo_id` DESC";
            $sql_gallery_photo = mysql_query($sql_gallery_photo) or die(mysql_error());
            while($row_gallery_photo = mysql_fetch_array($sql_gallery_photo))
            {
              ?>
              <li> 
              <a class="fancybox" title="<?php echo $row_gallery_photo['alt_tag'];?>" data-fancybox-group="gallery" href="<?php echo $row_gallery_photo['photo_link'];?>"><img src="<?php echo $row_gallery_photo['photo_link'];?>"  alt="<?php echo $row_gallery_photo['alt_tag'];?>"/></a>
                <br />
                <p><?php echo $row_gallery_photo['photographer_name'];?></p>
              </li>

              <?php
            }
            ?>
          </ul>
        </div>
        <div id="carousel" class="flexslider flx">
          <ul class="slides">
            <?php
            $sql_gallery_photo2 = "SELECT * FROM ".TABLE_PREFIX."vendor_photo_gallery WHERE `user_id` = '".$venue_details['user_id']."' ORDER BY `photo_id` DESC";
            $sql_gallery_photo2 = mysql_query($sql_gallery_photo2) or die(mysql_error());
            while($row_gallery_photo2 = mysql_fetch_array($sql_gallery_photo2))
            {
              ?>
              <li> <img src="<?php echo $row_gallery_photo2['photo_link'];?>"  alt="<?php echo $row_gallery_photo2['alt_tag'];?>" /> </li>

              <?php
            }
            ?>
          </ul>
        </div>
      </section>
        
      
      <div class="clearfix"></div>
    </div>
    <h2>You May Also Like These Relevant Venues</h2>
    <section class="vendors2 vd">
      <div class="viewport setwidth"> 
        <img width="11" height="19" id="left" alt="left" src="images/left-arr.png"> <img width="11" height="19" id="right" alt="right" src="images/right-arr.png">
        <div class="scroll-area">
          <div class="scrollable"> 
            <?php
            $sql_vendor_relevant = "SELECT * FROM ".TABLE_PREFIX."user_vendor_details WHERE 
                                      `vendor_id` != '".$_REQUEST['id']."' AND 
                                      `profile_complete` = 'Y' AND 
                                      `city_id` = '".$row_vendor['city_id']."'";
            $sql_vendor_relevant = mysql_query($sql_vendor_relevant) or die(mysql_error());
            while($row_vendor_relevant = mysql_fetch_array($sql_vendor_relevant))
            {
              $area_name2 = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendor_relevant['area_id']."'"));
              $city_name2 = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendor_relevant['city_id']."'"));
              $country_name2 = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `country_id` = '".$row_vendor_relevant['country_id']."'"));
              ?>
              <a class="blk" href="venue-details.php?id=<?php echo $row_vendor_relevant['vendor_id'];?>">
                <div class="blk-box"><img width="127" height="118" alt="fg" src="<?php echo $row_vendor_relevant['profile_photo'];?>"></div>
                <span><?php echo substr(stripcslashes($row_vendor_relevant['business_name']),0,15);?></span> <?php echo $area_name2['area_name'];?> 
              </a> 
              <?php 
            }
            ?>
              
          </div>
        </div>
      </div>
    </section>
    <?php 
    $sql_booked = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_booked_dates WHERE `vendor_id` = '".$_REQUEST['id']."' AND `status` = 'Y'";
    $sql_booked = mysql_query($sql_booked) or die(mysql_error());
    $k=0;
    $booked_dates = "";
    while($row_booked = mysql_fetch_array($sql_booked))
    {
      if($k==0)
      {
        $booked_dates = "'".$row_booked['booked_date']."'";
      }
      else
      {
        $booked_dates = $booked_dates.",'".$row_booked['booked_date']."'";
      }
      $k++;
    }

    $sql_closed = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_closed_dates WHERE `vendor_id` = '".$_REQUEST['id']."' AND `status` = 'Y'";
    $sql_closed = mysql_query($sql_closed) or die(mysql_error());
    $k=0;
    $closed_dates = "";
    while($row_closed = mysql_fetch_array($sql_closed))
    {
      if($k==0)
      {
        $closed_dates = "'".$row_closed['closed_date']."'";
      }
      else
      {
        $closed_dates = $closed_dates.",'".$row_closed['closed_date']."'";
      }
      $k++;
    }
    
    function createDateRangeArray($strDateFrom,$strDateTo,$closed_dates)
    {
        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            if($closed_dates=="")
            {
              $closed_dates = "'".date('Y-m-d',$iDateFrom)."'";
            }
            else
            {
              $closed_dates = $closed_dates.",'".date('Y-m-d',$iDateFrom)."'";
            }

            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                
                $closed_dates = $closed_dates.",'".date('Y-m-d',$iDateFrom)."'";
            }
        }
        return $closed_dates;
    }

    $sql_closed_section = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_closed_dates_section WHERE `vendor_id` = '".$_REQUEST['id']."' AND `status` = 'Y'";
    $sql_closed_section = mysql_query($sql_closed_section) or die(mysql_error());

    while($row_closed_section = mysql_fetch_array($sql_closed_section))
    {
      $closed_dates=createDateRangeArray($row_closed_section['section_closed_date_from'],$row_closed_section['section_closed_date_to'],$closed_dates);
    }
    
    ?>
    <script type="text/javascript">
      /*$(function() {

        var array = [<?php echo $booked_dates;?>];
        $( ".datepicker" ).datepicker({
          minDate: 0,
          numberOfMonths: 3,
          dateFormat: 'dd-mm-yy',
          beforeShowDay: function(date){
              var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
              return [ array.indexOf(string) == -1 ]
          }
        });
      });*/


      $(function() {
        var closedDays = [<?php echo $closed_dates;?>];
        var bookedDays = [<?php echo $booked_dates;?>];


        //var bookedDays = [<?php echo $booked_dates;?>];
        $( ".datepicker" ).datepicker({
          minDate: 0,
          numberOfMonths: 3,
          dateFormat: 'yy-mm-dd',
          beforeShowDay: function(date){
              var dateAsString = date.getFullYear().toString() + '-' + ("0" + (date.getMonth() + 1)).slice(-2) + '-' + ("0" + date.getDate()).slice(-2);
              return ($.inArray(dateAsString, closedDays) > -1 ? [false, 'closed_days'] : ($.inArray(dateAsString, bookedDays) > -1 ? [false, 'booked_days'] : [true, '']));
          }
        });
      });

          
    </script>
    <h2><?php echo stripslashes($row_vendor['business_name']);?> Details</h2>
    <div class="tabs-holder">
      <div class="tab selected" id="tab1">Overview</div>
      <div class="tab" id="tab2">Amenities</div>
      <div class="tab" id="tab3">Videos</div>
      <div class="tab" id="tab4">Reviews</div>
      <div class="tab" id="tab5">Suppliers</div>
      <div class="tab" id="tab6">Availability</div>
    </div>
    <div class="tab1 tb selected">

      <?php echo stripslashes($venue_details['overview']);?>
    </div>
    <div class="tab2 tb">
      
      <?php echo stripslashes($venue_details['amenities']);?>
      
      <div class="clearfix"></div>
    </div>
    <div class="tab3 tb tabvideosection">
      <div class="video-blocks-row">
        <?php
        $sql_gallery_video = "SELECT * FROM ".TABLE_PREFIX."vendor_video_gallery WHERE `user_id` = '".$venue_details['user_id']."' ORDER BY `video_id` DESC";
        $sql_gallery_video = mysql_query($sql_gallery_video) or die(mysql_error());
        $v=1;
        while($row_gallery_video = mysql_fetch_array($sql_gallery_video))
        {
          ?>
            <div class="video-blocks">
              <iframe src="<?php echo stripslashes($row_gallery_video['video_link']);?>" width="100%" frameborder="0" allowfullscreen></iframe>
              <p><?php echo $row_gallery_video['video_title'];?></p>
            </div>
            <?php
        }
        ?>
        </div>
      <div class="clearfix"></div>
    </div>
    <div class="tab4 tb">
        <?php
        $sql_published = "SELECT * FROM ".TABLE_PREFIX."vendor_ratings WHERE `status` != 'N' AND `vendor_id` = '".$row_vendor['vendor_id']."' ORDER BY `rating_id` DESC";
        $sql_published = mysql_query($sql_published) or die(mysql_error());
        $published_num = mysql_num_rows($sql_published);

        if($published_num > 0)
        {
            while($row_published = mysql_fetch_array($sql_published))
            {
                $reviewer_details = mysql_fetch_array(mysql_query("SELECT `user_firstname`,`user_lastname` FROM ".TABLE_PREFIX."user_client_details WHERE `user_id` = '".$row_published['user_id']."'"));
                ?>

                <div class="review-blocks">
                  <div class="reviews each_user_rating" data-value="<?php echo $row_published['overall_rating'];?>"></div>
                    <div class="text">
                    <p><?php echo stripslashes($row_published['rating_comments']);?></p>
                    <p>By: <a ><?php echo $reviewer_details['user_firstname']." ".$reviewer_details['user_lastname'];?></a></p>
                    </div>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="no-result">No review to publish.</div>
            <?php
        }
        ?>
    </div>
    <div class="tab5 tb">
      
      <?php echo stripslashes($venue_details['preferred_suppliers']);?>
      
      <div class="clearfix"></div>
    </div>
    <div class="tab6 tb">
        <div class="datepicker custome12picker"></div>  
        <div class="datepick-notes">
          <div class="datepick-closed"><span></span> Closed</div>
          <div class="datepick-booked"><span></span> Booked</div>
          <div class="datepick-available"><span></span> Available</div>
        </div> 
      
      
      <div class="clearfix"></div>
    </div>
    <?php /*?>    <a href="javascript:void(0);" class="insta-quote-btn setwidths">Get Instant-Quote</a> 
    <a href="javascript:void(0);" class="return-search setwidths contact-form-open">Contact Venues</a><?php */?>
  </div>
  <div class="rightpanel"> 
    <a href="venue-listing.php" class="back-to-property"><img src="images/back2.png" width="12" height="13" alt="back"> Back to property listings</a>
    <hr>
    <div class="adara">
      <div class="img"><img src="<?php echo $row_vendor['profile_photo'];?>" width="84" height="81" alt="<?php echo $row_vendor['profile_image_alt_tag'];?>"></div>
      <div class="txt newtextbox">
        <?php /*?><h1><?php echo stripslashes($row_vendor['business_name']);?></h1><?php */?>
        <p><?php echo stripslashes($row_vendor['vendor_address']);?></p>
        <a href="javascript:void(0);"><?php echo $area_name['area_name'].", ".$country_name['country_name'];?></a> </div>
      <div class="clearfix"></div>
    </div>
    <div class="adara-bot"> 
      <?php if($row_vendor['business_website']!=""){?><a target="_blank" href="<?php echo stripslashes($row_vendor['business_website']);?>"><img src="images/d1.png" width="14" height="14" alt="d1"> Website</a> <?php }?>
      <a href="javascript:void(0);" id="contact_info_show"><img src="images/d2.png" width="11" height="17" alt="d2"> 
      <span id="show_contact">Contact Info</span>
      <span id="show_contact1" style="display:none;"><?php echo stripslashes($row_vendor['business_phone']);?></span></a> 
      <a href="javascript:void(0);" alt="<?php echo $row_vendor['vendor_id'];?>" class="favourite_click"><img src="images/d3.png" width="12" height="15" alt="<?php echo $row_vendor['vendor_id'];?>" class="favourite_click"> Favorite</a> 
    
    
      <div class="social2"> 
        <?php if($row_vendor['facebook_link']!=""){?><a target="_blank" href="<?php echo stripslashes($row_vendor['facebook_link']);?>"><img src="images/fb1.png" width="8" height="13" alt="facebook"></a> <?php }?>
        <?php if($row_vendor['twitter_link']!=""){?><a target="_blank" href="<?php echo stripslashes($row_vendor['twitter_link']);?>"><img src="images/tw.png" width="14" height="12" alt="twitter"></a> <?php }?>
        <?php if($row_vendor['googleplus_link']!=""){?><a target="_blank" href="<?php echo stripslashes($row_vendor['googleplus_link']);?>"><img src="images/g+.png" width="16" height="17" alt="google+"></a><?php }?>
      </div>
    </div>

    <div class="bot-tab">
      <?php
      $style_arr = explode('@||@', $venue_details['style']);
      if(count($style_arr) > 0)
      {
        foreach ($style_arr as $k => $val) 
        {
          $style_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_styles_category WHERE `category_id` = '".$val."'"));
          if($k==0)
          {
            $all_styles = $style_name['category_name'];
          }
          else
          {
            $all_styles = $all_styles.", ".$style_name['category_name'];
          }
        }
      }

      //$row_capacity = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_guest_capacity_category WHERE `category_id` = '".$venue_details['max_capacity']."'"));
      //$row_capacity2 = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_guest_capacity_category WHERE `category_id` = '".$venue_details['max_capacity_reception']."'"));
      ?>
      <div class="row">
        <div class="td">Style</div>
        <div class="td">:</div>
        <div class="td"><?php echo stripslashes($all_styles);?></div>
      </div>
      
      <?php
      if($venue_details['service_type']!="Reception Only")
      {
        ?>
        <div class="row">
          <div class="td">Ceremony</div>
          <div class="td">:</div>
          <div class="td"><?php echo stripslashes($venue_details['ceremony']);?></div>
        </div>
        <div class="row">
          <div class="td">Ceremony Max Capacity</div>
          <div class="td">:</div>
          <div class="td"><?php echo stripslashes($venue_details['max_capacity']);?> Guests</div>
        </div>
        <?php
      }
      if($venue_details['service_type']!="Ceremony Only")
      {
        ?>
        <div class="row">
          <div class="td">Reception</div>
          <div class="td">:</div>
          <div class="td"><?php echo stripslashes($venue_details['reception']);?></div>
        </div>
        <div class="row">
          <div class="td">Reception Max Capacity</div>
          <div class="td">:</div>
          <div class="td"><?php echo stripslashes($venue_details['max_capacity_reception']);?> Guests</div>
        </div>
        <?php
      }
      ?>
      
      
      <div class="row">
        <div class="td">Catering Options</div>
        <div class="td">:</div>
        <div class="td"><?php echo stripslashes($venue_details['catering_options']);?></div>
      </div>
      <div class="row">
        <div class="td">Alcohol Options</div>
        <div class="td">:</div>
        <div class="td"><?php echo stripslashes($venue_details['alcohol_options']);?></div>
      </div>
      <div class="row">
        <div class="td">Time Restrictions</div>
        <div class="td">:</div>
        <div class="td"><?php echo stripslashes($venue_details['time_restrictions']);?></div>
      </div>
    </div>
    <!--<a class="price-range-btn"><span>Price Range :</span> £<?php //echo number_format($venue_details['min_budget']);?>-£<?php //echo number_format($venue_details['max_budget']);?> GBP</a>-->
    <div class="reviews-2" id="show_rating"> 
      Reviews <span>(<?php echo $reviewer_num;?>)</span>
      <!--<div class="social2"> 
        <a href=""><img src="images/fb1.png" width="8" height="13" alt="facebook"></a> 
        <a href=""><img src="images/tw.png" width="14" height="12" alt="twitter"></a> 
        <a href=""><img src="images/g+.png" width="16" height="17" alt="google+"></a>
      </div>-->
    </div>
    <a href="choose-services.php?vid=<?php echo $row_vendor['vendor_id'];?>" class="insta-quote-btn">PRICE THIS VENUE</a> 
    <a href="javascript:void(0);" class="return-search setwidths contact-form-open">Contact Venue</a> 
    <!--<a href="" class="claim-business-btn">Claim This Business</a>--> 
    <a href="javascript:void(0);" class="write-review review-form-open"><img src="images/st.png" width="18" height="18" alt="star"> Write A Review</a>
    <div class="fb">
      <span class='st_facebook_large' displayText='Facebook'></span>
      <span class='st_twitter_large' displayText='Tweet'></span>
      <span class='st_googleplus_large' displayText='Google +'></span>
      <span class='st_linkedin_large' displayText='LinkedIn'></span>
      <span class='st_pinterest_large' displayText='Pinterest'></span>
    </div>
    <div class="map set-bot-margin">
      <div class="map-in">
        <!--<img src="images/map.png" width="318" height="271" alt="map">-->
        <iframe src="<?php echo $venue_details['address_map_link'];?>" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>
    </div>
  <?php /*?>  <a href="venue-listing.php" class="return-search">Return to search</a> <?php */?>
  </div>

  

  <div class="clearfix"></div>
</section>


<div class="lightboxOverlay"></div>
<div class="lightbox">
	<div class="social-media">
    <img src="images/fb.png" width="190" height="19" alt="fb">
    

  </div>
  <img src="" id="bigImg">
</div>






<!--------------------------------------Contact Form------------------------------------------------------------>
<div class="all-Overlay"></div>
<div class="contactForm-23456" style="display:none">
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="venue-contact-form" id="venue-contact-form">
    <input type="hidden" name="venue_contact_email_to" value="<?php echo stripslashes($row_vendor['business_email']);?>">
    <input type="hidden" name="venue_vendor_id" value="<?php echo $row_vendor['vendor_id'];?>">
	  <div class="rel"><img src="images/close-btn.jpg" width="39" height="39" alt="close"> </div>
    
    <div>
      <h2>Contact</h2>
      <div id="venue_contact_msg"></div>
    
      <div>First Name <input type="text" class="text" name="venue_contact_first_name" id="venue_contact_first_name"></div>
      <div>Last Name <input type="text" class="text" name="venue_contact_last_name" id="venue_contact_last_name"></div>
      <div>Email <input type="text" class="text" name="venue_contact_email" id="venue_contact_email"></div>
      <div>Phone No <input type="text" class="text" name="venue_contact_phone" id="venue_contact_phone"></div>
      <div>Pending Wedding date <input type="text" class="date datepicker" readonly name="venue_contact_wedding_date" id="venue_contact_wedding_date"></div>
      <div>Leave a Message <textarea name="venue_contact_message" id="venue_contact_message"></textarea></div>
      <div><input  type="button" id="venue_contact_button" class="insta-quote-btn" value="Send"></div>
    </div>
  </form>

</div>
<script>
	$(".contact-form-open").click(function(e) {
        $(".contactForm-23456, .all-Overlay").fadeIn();
    });
	$(".contactForm-23456 .rel img").click(function(e) {
        $(this).parent().parent().parent().fadeOut();
		$(".all-Overlay").fadeOut();
  });

  $(function() {
    $( ".datepicker" ).datepicker({minDate: 0,dateFormat: 'dd-mm-yy'});
  });

  $(document).on('click','#venue_contact_button',function(){
    var req_email_patteren = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var contactno_patteren = /^[0-9\s]+$/;
    var alphabet_patteren = /^[a-zA-Z\s.]+$/;

    var venue_contact_first_name = $('#venue_contact_first_name').val();
    var venue_contact_last_name = $('#venue_contact_last_name').val();
    var venue_contact_email = $('#venue_contact_email').val();
    var venue_contact_phone = $('#venue_contact_phone').val();
    var venue_contact_wedding_date = $('#venue_contact_wedding_date').val();
    var venue_contact_message = $('#venue_contact_message').val();

    if(venue_contact_first_name =="")
    {
        alert('Please Insert you first Name.');
        $('#venue_contact_first_name').focus();
        return false;
    }

    else if(!alphabet_patteren.test(venue_contact_first_name))
    {
        alert('This Is Not a valid first Name.');
        $('#venue_contact_first_name').focus();
        return false;
    }
    if(venue_contact_last_name =="")
    {
        alert('Please Insert you Last Name.');
        $('#venue_contact_last_name').focus();
        return false;
    }
    else if(!alphabet_patteren.test(venue_contact_last_name))
    {
        alert('This Is Not a valid Last Name.');
        $('#venue_contact_last_name').focus();
        return false;
    }
    else if(venue_contact_email == "")
    {
        alert('Email is required.');
        $('#venue_contact_email').focus();
        return false;
    }
    else if(!req_email_patteren.test(venue_contact_email))
    {
        alert('This is not a Valid Email, Please Correct This.');
        $('#venue_contact_email').focus();
        return false;
    }
    else if(venue_contact_phone == "")
    {
        alert('Phone No. is required.');
        $('#venue_contact_phone').focus();
        return false;
    }
    else if(!contactno_patteren.test(venue_contact_phone))
    {
        alert('This is not a Valid Contact Number, Please Correct This.');
        $('#venue_contact_phone').focus();
        return false;
    }
    else if(venue_contact_wedding_date == "")
    {
        alert('Please enter wedding date.');
        $('#venue_contact_wedding_date').focus();
        return false;

    }
    else if(venue_contact_message == "")
    {
        alert('Please enter some message.');
        $('#venue_contact_message').focus();
        return false;

    }
    else
    {
      $.post('ajax/venue_contact_form_submit.php',$("#venue-contact-form").serialize(),function(result){
        if(result==1)
        {
          
          $('#venue_contact_msg').html("<div class='form-success' id='venue_contact_success'>Thank you. We'll get back to you soon.</div>");
          $(".contactForm-23456, .all-Overlay").fadeOut();
          return true;
        }
        else if(result==2)
        {
          
          $('#venue_contact_msg').html("<div class='form-failed' id='venue_contact_failed'>Sorry.Please try again.</div>");
          return true;
        }
        else
        {
          $('#venue_contact_msg').html('<div class="form-failed" id="venue_contact_failed">Please fill up all the mendatory fields.</div>');
          return false;
        }
      });
    }
  });
</script>
<!--------------------------------------Contact Form------------------------------------------------------------>


<!--------------------------------------Rating Form------------------------------------------------------------>
<div class="ratingForm-23456">
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="venue-rating-form" id="venue-rating-form">
    <input type="hidden" name="vendor_id" value="<?php echo $row_vendor['vendor_id'];?>">
    <input type="hidden" name="rating_user_id" value="<?php echo $_SESSION['LOGIN_ID'];?>">
	  <div class="rel"><img src="images/close-btn.jpg" width="39" height="39" alt="close"> </div>
    <h2>Ratings</h2>
    <div id="venue_rating_msg"></div>
    <p>Please rate this vendor based on the following categories</p>
    <div class="rating-left">
      <div class="table">
      		<div class="row">
              	<div class="cell">Quality of Service</div>
                  <div class="cell all_ratings" id="give_service_quality_rating">
                    <input type="hidden" name="service_quality_rating" id="service_quality_rating">
                   </div> 
              </div>
              <div class="row">
              	<div class="cell">Responsiveness</div>
                  <div class="cell all_ratings" id="give_responsiveness_rating">
                    <input type="hidden" name="responsiveness_rating" id="responsiveness_rating">
                   </div>
              </div>
              <div class="row">
              	<div class="cell">Professionalism</div>
                  <div class="cell all_ratings" id="give_professionalism_rating">
                    <input type="hidden" name="professionalism_rating" id="professionalism_rating">
                   </div>
              </div>
              <div class="row">
              	<div class="cell">Value</div>
                  <div class="cell all_ratings" id="give_value_rating">
                    <input type="hidden" name="value_rating" id="value_rating">
                   </div>
              </div>
              <div class="row">
              	<div class="cell">Flexibility</div>
                  <div class="cell all_ratings" id="give_flexibility_rating">
                    <input type="hidden" name="flexibility_rating" id="flexibility_rating">
                   </div>
              </div>
      </div>
    </div>
    <div class="rating-right">
    		<div>Overall Rating</div>
            <div id="given_overall_rating">
              <input type="hidden" name="overall_rating" id="overall_rating">
            </div>
             <div id="show_overall">0/5.0</div>       
    </div>
    <div class="clearfix"></div>
    <div>Please provide comments and feedback that future couples would find helpful: (required)</div>
    <div><textarea name="rating_comments" id="rating_comments"></textarea></div>
    <div>
    	<input type="button" id="rating_button" class="insta-quote-btn" value="submit">
    </div>
  </form>
</div>
<script>
  $(".review-form-open").click(function(e) {
      var login = "<?php echo $_SESSION['LOGIN'];?>";
      var login_id = "<?php echo $_SESSION['LOGIN_ID'];?>";
      var login_type = "<?php echo $_SESSION['LOGIN_TYPE'];?>";
      if(login == "TRUE" && parseInt(login_id) > 0 && login_type != "")
      {
        if(login_type=="User")
        {
          $(".ratingForm-23456, .all-Overlay").fadeIn();
        }
        else
        {
          alert("Only bride/groom can rate a vendor.");
          $(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
          return false;
        }
      }
      else
      {
        alert("Login First To Give A Review.");
        $(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
        $("#login, .overlay2").fadeIn();
        return false;
      }
        
    });
	$(".ratingForm-23456 .rel img").click(function(e) {
        $(this).parent().parent().parent().fadeOut();
		$(".all-Overlay").fadeOut();
    });
</script>
<script>
  $(document).ready(function(){
    $.fn.raty.defaults.path = 'images';

    $(function() {
      $('#give_service_quality_rating').raty({
        //half: true, 
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        /*round : { down: .25, full: .5, up: .75 },*/
        click: function(score, evt) {
          //alert(score);
          $('#service_quality_rating').val(parseFloat(score));
        }
      });

      $('#give_responsiveness_rating').raty({
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        click: function(score, evt) {
          $('#responsiveness_rating').val(parseFloat(score));
        }
      });

      $('#give_professionalism_rating').raty({
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        click: function(score, evt) {
          $('#professionalism_rating').val(parseFloat(score));
        }
      });

      $('#give_value_rating').raty({
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        click: function(score, evt) {
          $('#value_rating').val(parseFloat(score));
        }
      });

      $('#give_flexibility_rating').raty({
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        click: function(score, evt) {
          $('#flexibility_rating').val(parseFloat(score));
        }
      });

      $('#show_rating').raty({
        starOff : 'star-disabled.png',
        starOn : 'star.png',
        readOnly: true, 
        score: <?php echo $alltotal_rating;?>
      });
      
    });
  });
</script>
<script>
  $(document).on('click','.all_ratings',function(){

      var service_quality_rating = $('#service_quality_rating').val();
      var responsiveness_rating = $('#responsiveness_rating').val();
      var professionalism_rating = $('#professionalism_rating').val();
      var value_rating = $('#value_rating').val();
      var flexibility_rating = $('#flexibility_rating').val();
      var overall_rating = 0;
      var finaloverall_rating = 0;

      overall_rating = Number(service_quality_rating)+Number(responsiveness_rating)+Number(professionalism_rating)+Number(value_rating)+Number(flexibility_rating);
      finaloverall_rating = (overall_rating/5);

      $('#overall_rating').val(finaloverall_rating);
      $('#show_overall').html(finaloverall_rating+'/5');
  });

  $(document).on('click','#rating_button',function(){

    var login = "<?php echo $_SESSION['LOGIN'];?>";
    var login_id = "<?php echo $_SESSION['LOGIN_ID'];?>";
    var login_type = "<?php echo $_SESSION['LOGIN_TYPE'];?>";

    if(login == "TRUE" && parseInt(login_id) > 0 && login_type != "")
    {
      if(login_type=="User")
      {
        if($('#service_quality_rating').val() =="")
        {
            alert('Please give service quality rating.');
            return false;
        }
        else if($('#responsiveness_rating').val() =="")
        {
            alert('Please give responsiveness rating.');
            return false;
        }
        else if($('#professionalism_rating').val() =="")
        {
            alert('Please give professionalism rating.');
            return false;
        }
        else if($('#value_rating').val() =="")
        {
            alert('Please give value rating.');
            return false;
        }
        else if($('#flexibility_rating').val() =="")
        {
            alert('Please give flexibility rating.');
            return false;
        }
        else if($('#rating_comments').val() == "")
        {
            alert('Please enter some message regarding rating.');
            $('#rating_comments').focus();
            return false;

        }
        else
        {
            $.post('ajax/venue_rating_submit.php',$("#venue-rating-form").serialize(),function(result){
              if(result==1)
              {
                
                $('#venue_rating_msg').html("<div class='form-success' id='venue_rating_success'>Thank you for your valuable rating.</div>");
                return true;
              }
              else if(result==2)
              {
                
                $('#venue_rating_msg').html("<div class='form-failed' id='venue_rating_failed'>You have already given your rating for this vendor.</div>");
                return true;
              }
              else
              {
                $('#venue_rating_msg').html('<div class="form-failed" id="venue_rating_failed">Please fill up all the fields.</div>');
                return false;
              }
            });
        }
      }
      else
      {
        alert("Only bride/groom can rate a vendor.");
        $(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
        return false;
      }
    }
    else
    {
      alert("Login First To Give A Review.");
      $(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
      $("#login, .overlay2").fadeIn();
      return false;
    }

  });
</script>

<script>
  $(document).ready(function(){
    $.fn.raty.defaults.path = 'images';

    $(function() {

        $(".each_user_rating").each(function(){

            var dv = $(this).attr('data-value');
                $(this).raty({
                readOnly: true, 
                score: dv,
                half: true,
                starOff : 'star-disabled.png',
                starOn : 'star.png'
            });
        });

    
      
    });
  });
</script>
<!--------------------------------------rating Form------------------------------------------------------------>


<script defer src="FlexSlider-master/jquery.flexslider.js"></script> 
<script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 106,//125,
        itemMargin: 2,
        asNavFor: '#slider'
      });

      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
	  
	  
  	/*  $(".getBig li img").click(function(e) {
  		  	$(".lightboxOverlay, .lightbox").fadeIn();
  			$("#bigImg").attr("src", $(this).attr("src"));
      });
	
	    $(".lightboxOverlay").click(function(e) {
        $(this).fadeOut();
    		$(".lightbox").fadeOut();
      });*/
	
	
	    $(".flex-next, .flex-prev").html("");
	  
    });

</script> 
<!-- Syntax Highlighter --> 
<script type="text/javascript" src="FlexSlider-master/demo/js/shCore.js"></script> 
<script type="text/javascript" src="FlexSlider-master/demo/js/shBrushXml.js"></script> 
<script type="text/javascript" src="FlexSlider-master/demo/js/shBrushJScript.js"></script> 
<script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
<!-- Optional FlexSlider Additions --> 
<script src="FlexSlider-master/demo/js/jquery.easing.js"></script> 
<script src="FlexSlider-master/demo/js/jquery.mousewheel.js"></script> 
<script defer src="FlexSlider-master/demo/js/demo.js"></script>
            
<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox")
    		.attr('rel', 'gallery')
    			.fancybox({
        		beforeShow: function () {
            	if (this.title) {
                // New line
                this.title += '<br />';
                this.title += '<div class="light-boxpopup-social"><span class="st_facebook_large" displayText="Facebook"></span><span class="st_twitter_large" displayText="Tweet"></span><span class="st_pinterest_large" displayText="Pinterest"></span></div>';
            }
        },
        afterShow: function() {
            // Render tweet button
            twttr.widgets.load();
        },
        helpers : {
            title : {
                type: 'inside'
            }
        }  
    });


		});
	</script>
    
    
<?php 
include('footer.php');?>
