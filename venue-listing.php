<?php 
include('header.php');
//unset($_SESSION['viewer']);
?>

<section class="vendor">
  
  <div class="vendor-leftpanel">
    <script type="text/javascript">
      $(document).on('click','#apply-button',function(){
        var regions = new Array();
        var styles = new Array();
        
        $('input[name="search_regions[]"]:checked').each(function() {
           regions.push(this.value);
        });

        $('input[name="search_styles[]"]:checked').each(function(){
          styles.push(this.value);
        });

        var budget = $('input[name="search_budget"]:checked').val();
        var capacity = $('input[name="search_capacity"]:checked').val();
        var service = $('input[name="search_service"]:checked').val();

        $.post('ajax/load_venue_vendors.php',{regions : regions, styles : styles, budget : budget, capacity : capacity, service : service},function(result){
            $('#show_vendors').html(result);
        });
      });

      $(document).on('click','.profile_viewer',function(){

         var vendor_id = $(this).attr('data-id');

         $.post("ajax/custom_profile_viewer.php",{vendor_id:vendor_id},
          function(result){
              location.href='venue-details.php?id='+vendor_id;
          });
      });

    </script>

    <script type="text/javascript">
      $(document).on('click','#clear-button',function(){
        location.href='venue-listing.php';
      });  
    </script>

    <h1>Filters</h1>
    
    <div class="venue-mobMenu">
    	<img width="24" height="19" src="images/list.png" alt="list">
    </div>
  	<div class="vendor-leftpanel-bar">
  		  <div class="buttons">
        	<div class="clear-button" id="clear-button">Clear</div>
          <div class="apply-button" id="apply-button">Apply</div>
            <div class="clearfix"></div>
        </div>
        <div class="buttons buttclickss angel-up">Regions</div>

        <div class="showhide-box incolspan">
        <?php
        $sql_vendor_countries = "SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `status` = 'Y' ORDER BY `country_name`";
        $sql_vendor_countries = mysql_query($sql_vendor_countries) or die(mysql_error());
        while($row_vendor_countries = mysql_fetch_array($sql_vendor_countries))
        {
            ?>
            <h3><?php echo stripslashes($row_vendor_countries['country_name']);?></h3>
            <div class="selection-area no-padding-top dialog-box-appoint">
                <?php
                $sql_vendor_cities = "SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `status` = 'Y' AND `country_id` = '".$row_vendor_countries['country_id']."' ORDER BY `city_name`";
                $sql_vendor_cities = mysql_query($sql_vendor_cities) or die(mysql_error());
                while($row_vendor_cities = mysql_fetch_array($sql_vendor_cities))
                {
                  $sql_vendor_area2 = "SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `status` = 'Y' AND `city_id` = '".$row_vendor_cities['city_id']."' AND `area_id` = '".$_REQUEST['aid']."'  ORDER BY `area_name`";
                  $sql_vendor_area2 = mysql_query($sql_vendor_area2) or die(mysql_error());
                  $area_num = mysql_num_rows($sql_vendor_area2);
                    ?>
                    <h2 class="no-padding"><?php echo stripslashes($row_vendor_cities['city_name']);?></h2>
                    <div class="collapse" <?php if($area_num>0){?>style="display: block;"<?php }?>>
                        <?php
                        $sql_vendor_area = "SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `status` = 'Y' AND `city_id` = '".$row_vendor_cities['city_id']."' ORDER BY `area_name`";
                        $sql_vendor_area = mysql_query($sql_vendor_area) or die(mysql_error());
                        while($row_vendor_areas = mysql_fetch_array($sql_vendor_area))
                        {
                            ?>
                            <div class="label">
                                <input type="checkbox" <?php if($row_vendor_areas['area_id']==$_REQUEST['aid']){echo "checked";}?> name="search_regions[]" id="search_regions<?php echo $row_vendor_areas['area_id'];?>" class="css-checkbox search_regions" value="<?php echo $row_vendor_areas['area_id'];?>"/>
                                <label for="search_regions<?php echo $row_vendor_areas['area_id'];?>" class="css-label radGroup1"><?php echo stripslashes($row_vendor_areas['area_name']);?></label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <?php
        }

        ?>
        </div>

        <!--<script>
      		$(window).bind('load',function(){
      			$(".vendor-leftpanel-bar h2").click(function(e) {
      				$(".collapse").slideUp();
                    $(this).next().slideDown();
                      //$(this).siblings('.collapse').slideToggle();
                  });
      		});
      	</script> -->   
        
        
        <div class="buttons border-top buttclickss">Styles</div>
        <div class="showhide-box">
        <div class="selection-area setHeight dialog-box-appoint">
      		
          <?php
          $sql_styles = "SELECT * FROM ".TABLE_PREFIX."vendor_styles_category WHERE `status` = 'Y' ORDER BY `category_name`";
          $sql_styles = mysql_query($sql_styles) or die(mysql_error());
          $selected_styles_arr = explode('@||@',$my_details['style']);
          while($row_styles = mysql_fetch_array($sql_styles))
          {
          ?>
            <div class="label">
              <input type="checkbox" <?php if($row_styles['category_id']==$_REQUEST['sid']){echo "checked";}?> name="search_styles[]" id="search_styles<?php echo $row_styles['category_id'];?>" class="css-checkbox" value="<?php echo $row_styles['category_id'];?>"/>
              <label for="search_styles<?php echo $row_styles['category_id'];?>" class="css-label radGroup200"><?php echo $row_styles['category_name'];?></label>
            </div> 
            
          <?php
          }
          ?>  
        </div>
        </div>
        
        <div class="buttons border-top buttclickss">Budgets</div>
        <div class="showhide-box">
		    <div class="selection-area setHeight dialog-box-appoint">
      
            <div class="label">
              <input type="radio" name="search_budget" id="search_budget0" class="css-checkbox2" value=""/>
              <label for="search_budget0" class="css-label2 radGroup1 radGroup1">All</label>
            </div>
            <?php
            $sql_budget = "SELECT * FROM ".TABLE_PREFIX."vendor_budget_category WHERE `status` = 'Y' ORDER BY `category_id`";
            $sql_budget = mysql_query($sql_budget) or die(mysql_error());
            while($row_budget = mysql_fetch_array($sql_budget))
            {
              ?>
              <div class="label">
                <input type="radio" <?php if($row_budget['category_id']==$_REQUEST['bid']){echo "checked";}?> name="search_budget" id="search_budget<?php echo $row_budget['category_id'];?>" class="css-checkbox2" value="<?php echo $row_budget['category_id'];?>"/>
                <label for="search_budget<?php echo $row_budget['category_id'];?>" class="css-label2 radGroup1 radGroup1"><?php echo $row_budget['category_name'];?></label>
              </div> 
              <?php
            }
            ?>       
        </div>
        </div>
        
        <div class="buttons border-top buttclickss">Guest</div>
        <div class="showhide-box">
        <div class="selection-area setHeight dialog-box-appoint">
        		<div class="label">
              <input type="radio" name="search_capacity" id="search_capacity0" class="css-checkbox2" value=""/>
              <label for="search_capacity0" class="css-label2 radGroup1 radGroup1">All</label>
            </div>
            <?php
            $sql_capacity = "SELECT * FROM ".TABLE_PREFIX."vendor_guest_capacity_category WHERE `status` = 'Y' ORDER BY `category_id`";
            $sql_capacity = mysql_query($sql_capacity) or die(mysql_error());
            while($row_capacity = mysql_fetch_array($sql_capacity))
            {
              ?>
              <div class="label">
                <input type="radio" <?php if($row_capacity['category_id']==$_REQUEST['gid']){echo "checked";}?> name="search_capacity" id="search_capacity<?php echo $row_capacity['category_id'];?>" class="css-checkbox2" value="<?php echo $row_capacity['category_id'];?>"/>
                <label for="search_capacity<?php echo $row_capacity['category_id'];?>" class="css-label2 radGroup1 radGroup1"><?php echo $row_capacity['category_name'];?></label>
              </div> 
              <?php
            }
            ?>
        </div>
        </div>
        <div class="buttons border-top buttclickss">Services</div>
        <div class="showhide-box">
        <div class="selection-area setHeight dialog-box-appoint">
        		<div class="label">
              <input type="radio" name="search_service" id="search_service0" class="css-checkbox2" value=""/>
              <label for="search_service0" class="css-label2 radGroup1 radGroup1">All</label>
            </div>
            <div class="label">
              <input type="radio" name="search_service" id="search_service1" class="css-checkbox2" value="Ceremony and Reception"/>
              <label for="search_service1" class="css-label2 radGroup1 radGroup1">Ceremony and Reception</label>
            </div>
            <div class="label">
              <input type="radio" name="search_service" id="search_service2" class="css-checkbox2" value="Ceremony Only"/>
              <label for="search_service2" class="css-label2 radGroup1 radGroup1">Ceremony Only</label>
            </div>
            <div class="label">
              <input type="radio" name="search_service" id="search_service3" class="css-checkbox2" value="Reception Only"/>
              <label for="search_service3" class="css-label2 radGroup1 radGroup1">Reception Only</label>
            </div>      
        </div>
        </div>
        
		    <!--<div class="buttons border-top">Rating</div>
        <div class="selection-area">
      		<div class="inside">   
         	  <div class="label">
              <input type="checkbox" name="checkboxG211" id="checkboxG211" class="css-checkbox" />
              <label for="checkboxG211" class="css-label radGroup211">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
              </label>
          	</div>
            <div class="label">
              <input type="checkbox" name="checkboxG212" id="checkboxG212" class="css-checkbox" />
              <label for="checkboxG212" class="css-label radGroup212">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
              </label>
          	</div>
            <div class="label">
              <input type="checkbox" name="checkboxG213" id="checkboxG213" class="css-checkbox" />
              <label for="checkboxG213" class="css-label radGroup213">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
              </label>
          	</div>
            <div class="label"><input type="checkbox" name="checkboxG214" id="checkboxG214" class="css-checkbox" />
              <label for="checkboxG214" class="css-label radGroup214">
                <img src="images/star.png" width="18" height="18" alt="star">
                <img src="images/star.png" width="18" height="18" alt="star">
              </label>
          	</div>
            <div class="label"><input type="checkbox" name="checkboxG215" id="checkboxG215" class="css-checkbox" />
              <label for="checkboxG215" class="css-label radGroup215">
                <img src="images/star.png" width="18" height="18" alt="star">
              </label>
          	</div>   
          </div>
        </div>-->
      
    </div>
      
  </div>

  <script>
    $(document).ready(function(){
      $.fn.raty.defaults.path = 'images';

      $(function() {

        $(".each_vendor_rating").each(function(){

              var dv = $(this).attr('data-value');
                  $(this).raty({
                  readOnly: true, 
                  score: dv,
                  half: false,
                  starOff : 'star-disabled.png',
                  starOn : 'star.png'
              });
          });
        
      });
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
              alert('Thank you for making this your favourite.');
              return true;
            }
            else if(result==2)
            {
              alert('You have already make this your favourite.');
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
          alert("Only bride/groom can make favourite.");
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

  <div class="vendor-body" id="show_vendors">
    	<h1>Wedding  <span>Venues</span>  Listing</h1>
      <div class="show_vendors-row">
        <?php
        if($_REQUEST['aid'] > 0)
        {
          $area_option = " AND uvd.area_id = '".$_REQUEST['aid']."'";
          $url_add = "aid=".$_REQUEST['aid']."&"; 
        }
        if($_REQUEST['sid'] > 0)
        {
          $style_option = " AND uvdv.style LIKE '%".$_REQUEST['sid']."%'";
          $url_add = "sid=".$_REQUEST['sid']."&"; 
        }
        if($_REQUEST['bid'] > 0)
        {
          $budget_option = " AND uvdv.budget_range = '".$_REQUEST['bid']."'";
          $url_add = "bid=".$_REQUEST['bid']."&"; 
        }
        if($_REQUEST['gid'] > 0)
        {
          $guest_option = " AND uvdv.guest_capacity = '".$_REQUEST['gid']."'";
          $url_add = "gid=".$_REQUEST['gid']."&"; 
        }
        $path = "venue-listing.php?".$url_add;
        /*$sql_vendors = "SELECT * FROM ".TABLE_PREFIX."user_vendor_details uvd, ".TABLE_PREFIX."user_vendor_details_venue uvdv WHERE 
                          uvd.profile_complete = 'Y' AND
                          uvdv.user_id = uvd.user_id ".$area_option." 
                          ORDER BY uvd.vendor_id DESC";
        $sql_vendors = mysql_query($sql_vendors) or die(mysql_error());*/

        $query = "SELECT COUNT(vendor_id) as num FROM ".TABLE_PREFIX."user_vendor_details uvd, ".TABLE_PREFIX."user_vendor_details_venue uvdv WHERE 
                          uvd.profile_complete = 'Y' AND
                          uvdv.user_id = uvd.user_id ".$area_option.$style_option.$budget_option.$guest_option." 
                          ORDER BY uvd.vendor_id DESC";
        $row = mysql_fetch_array(mysql_query($query));
        $total_pages = $row['num'];

        $adjacents = "2";
        $limit=15;
        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $page = ($page == 0 ? 1 : $page);

        if($page)
        //$start = ($page - 1) * $limit;
          $start = ($page * $limit) - $limit;
        else
        $start = 0;

        $sql = "SELECT * FROM ".TABLE_PREFIX."user_vendor_details uvd, ".TABLE_PREFIX."user_vendor_details_venue uvdv WHERE 
                          uvd.profile_complete = 'Y' AND
                          uvdv.user_id = uvd.user_id ".$area_option.$style_option.$budget_option.$guest_option." 
                          ORDER BY uvd.listing_scheme, rand() LIMIT $start, $limit";


        $result = mysql_query($sql)  or die(mysql_error());

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages/$limit);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if($lastpage > 1)
        {   
            $pagination .= "<div class='pagination'>";
            if ($page > 1)
            {
                $pagination.= "<a href='".$path."page=1' class='first'>« First</a>";
                $pagination.= "<a href='".$path."page=$prev' class='prev'></a>";
            }
            else
            {
                $pagination.= "<a class='first disabled' >« First</a>";
                $pagination.= "<a class='prev disabled'></a>";

            }
            if ($lastpage < 7 + ($adjacents * 2))
            {   
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                      $pagination.= "<a class='page current'>$counter</a>";
                    else
                      $pagination.= "<a href='".$path."page=$counter' class='page'>$counter</a>";                   
                }
            }

            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))       
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                          $pagination.= "<a class='page current'>$counter</a>";
                        else
                      $pagination.= "<a href='".$path."page=$counter' class='page'>$counter</a>";                   
                    }
                    $pagination.= "<a class='page'>...</a> ";
                    $pagination.= "<a href='".$path."page=$lpm1' class='page'>$lpm1</a>";
                    $pagination.= "<a href='".$path."page=$lastpage' class='page'>$lastpage</a>";       
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href='".$path."page=1' class='page'>1</a>";
                    $pagination.= "<a href='".$path."page=2' class='page'>2</a>";
                    $pagination.= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                      if ($counter == $page)
                          $pagination.= "<a class='page current'>$counter</a>";
                      else
                          $pagination.= "<a href='".$path."page=$counter' class='page'>$counter</a>";                   
                    }
                    $pagination.= "<a class='page'>...</a> ";
                    $pagination.= "<a href='".$path."page=$lpm1' class='page'>$lpm1</a>";
                    $pagination.= "<a href='".$path."page=$lastpage' class='page'>$lastpage</a>";       
                }
                else
                {
                  $pagination.= "<a href='".$path."page=1' class='page'>1</a>";
                  $pagination.= "<a href='".$path."page=2' class='page'>2</a>";
                  $pagination.= "<a class='page'>...</a>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                    if ($counter == $page)
                      $pagination.= "<a class='page current'>$counter</a>";
                    else
                      $pagination.= "<a href='".$path."page=$counter' class='page'>$counter</a>";                   
                    }
                }
            }

            if ($page < $counter - 1)
            {
              $pagination.= "<a href='".$path."page=$next' class='next'></a>";
              $pagination.= "<a href='".$path."page=$lastpage' class='last'>Last »</a>";
            }
            else
            {
              $pagination.= "<a class='next disabled'></a>";
              $pagination.= "<a class='last disabled'>Last »</a>";
            }
            $pagination.= "</div>\n";       
        }

        $v=1;
        while($row_vendors = mysql_fetch_array($result))
        {
            $area_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendors['area_id']."'"));
            $city_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendors['city_id']."'"));

            $vendor_rating = "SELECT SUM(overall_rating) AS total_rating, COUNT(rating_id) AS reviewer_num FROM ".TABLE_PREFIX."vendor_ratings
                                WHERE vendor_id = '".$row_vendors['vendor_id']."' AND status = 'Y' GROUP BY vendor_id";

            $vendor_rating = mysql_query($vendor_rating) or die(mysql_error());
            $vendor_rating = mysql_fetch_array($vendor_rating); 
            
            if($vendor_rating['reviewer_num'] > 0)
            {
              $alltotal_rating = $vendor_rating['total_rating']/$vendor_rating['reviewer_num'];
            } 
            else
            {
              $alltotal_rating = 0;
            }                 
            

            /*if($row_vendors['vendor_category'] ==1)
            {
              $venue_details = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."user_vendor_details_venue WHERE `user_id` = '".$row_vendors['user_id']."'"));
            }*/

            $style_arr = explode('@||@', $row_vendors['style']);
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
            ?>  
            <div class="vendor-blocks setheight">
              <a class="profile_viewer" data-id="<?php echo $row_vendors['vendor_id'];?>" href="javascript:void(0);">
            	    <div class="image">
                      <img src="<?php echo $row_vendors['featured_image'];?>" width="285" height="181" alt="<?php echo $row_vendors['featured_image_alt_tag'];?>">
            	        
                  </div>
                
                  <div class="block-head">
                  	<h2><?php echo stripslashes($row_vendors['business_name']);?></h2>
                      <div class="a"><?php echo $area_name['area_name'];?></div><!--Venue area is made city now-->
                  </div>
                	<div class="block-table">
                  	<div class="block-tr">
                      	<div class="block-td">Region</div>
                          <div class="block-td">:</div>
                          <div class="block-td"><?php echo $city_name['city_name'];?></div><!--Venue City is made region now-->
                      </div>
                      <div class="block-tr">
                      	<div class="block-td">Styles</div>
                          <div class="block-td">:</div>
                          <div class="block-td"><?php echo substr(stripslashes($all_styles),0,25);?></div>
                      </div>
                      <div class="block-tr">
                      	<div class="block-td">Budget</div>
                          <div class="block-td">:</div>
                          <div class="block-td"><strong>£<?php echo number_format($row_vendors['min_budget']);?>-£<?php echo number_format($row_vendors['max_budget']);?></strong></div>
                      </div>
                      <div class="block-tr">
                      	<div class="block-td">Rating</div>
                          <div class="block-td">:</div>
                          <div class="block-td each_vendor_rating" data-value="<?php echo $alltotal_rating;?>">
                          </div>
                      </div>
                  </div>
                          
              </a> 
              <img src="images/fav-ico.png" width="29" height="40" alt="<?php echo $row_vendors['vendor_id'];?>" class="fav-ico favourite_click">
            </div>  
            <?php
        }
        ?>
        <div class="clearfix"></div>
      </div>

      <div class="clearfix"></div>
      <?php echo $pagination;?>
      
  </div>
  <div class="clearfix"></div>
</section>

<?php 
include('footer.php');?>