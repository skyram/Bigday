<?php 
include('header.php');

//session_destroy();
$sql_vendor = "SELECT `business_name`,`area_id` FROM ".TABLE_PREFIX."user_vendor_details WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `profile_complete` = 'Y'";
$sql_vendor = mysql_query($sql_vendor) or die(mysql_error());
$row_vendor = mysql_fetch_array($sql_vendor);

$area_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendor['area_id']."'"));
//$city_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendor['city_id']."'"));
//$country_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `country_id` = '".$row_vendor['country_id']."'"));
//print_r($_SESSION);
$business_name = $row_vendor['business_name'];

if($_REQUEST['flag']=="step1")
{
  //$_SESSION['venue_service_id'] = $_REQUEST['venue_service_id'];
  //print_r($_REQUEST);

  if(count($_REQUEST['venue_service_id']) > 0)
  {
    unset($_SESSION['ceremony_selected']);
    unset($_SESSION['reception_selected']);
    unset($_SESSION['venue_service_id']);

    unset($_SESSION['step2']);
    unset($_SESSION['step3']);
    unset($_SESSION['step4']);

    $_SESSION['venue_service_id'] = array_values(array_filter($_REQUEST['venue_service_id']));

    foreach ($_SESSION['venue_service_id'] as $k => $val) 
    {
      $sql_services = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_sevrices WHERE venue_service_id = '".$val."'";
      $sql_services = mysql_query($sql_services) or die(mysql_error());
      $row_services = mysql_fetch_array($sql_services);

      if($row_services['service_name']=="Reception")
      {
        $_SESSION['reception_selected'] = 'Yes';
      }
      if($row_services['service_name']=="Ceremony")
      {
        $_SESSION['ceremony_selected'] = 'Yes';
      }
    }
    
  }

  if($_REQUEST['weekday_id']>0){$_SESSION['weekday_id'] = $_REQUEST['weekday_id'];}
  if($_REQUEST['season_id']>0){$_SESSION['season_id'] = $_REQUEST['season_id'];}
  if($_REQUEST['guest_count_ceremony']>0){$_SESSION['guest_count_ceremony'] = $_REQUEST['guest_count_ceremony'];}
  if($_REQUEST['guest_count_reception']>0){$_SESSION['guest_count_reception'] = $_REQUEST['guest_count_reception'];}
  if($_REQUEST['selected_hours_reception']>0){$_SESSION['selected_hours_reception'] = $_REQUEST['selected_hours_reception'];}
  if($_REQUEST['wedding_coordinator']!=""){$_SESSION['wedding_coordinator'] = $_REQUEST['wedding_coordinator'];}else{$_SESSION['wedding_coordinator']='Yes';}

  if(isset($_SESSION['venue_service_id']) && isset($_SESSION['weekday_id']) && isset($_SESSION['season_id']) && isset($_SESSION['wedding_coordinator']) &&
      (isset($_SESSION['guest_count_ceremony']) || isset($_SESSION['guest_count_reception'])))
  {
    
    $_SESSION['step1'] = "TRUE";

    if($_SESSION['ceremony_selected'] == 'Yes' && $_SESSION['reception_selected'] == 'Yes')
    {
      echo "<script>location.href='ceremony.php?vid=$_REQUEST[vid]';</script>";
    }
    elseif($_SESSION['ceremony_selected'] == 'Yes' && (!isset($_SESSION['reception_selected']) || $_SESSION['reception_selected']=='' || $_SESSION['reception_selected']!='Yes'))
    {
      echo "<script>location.href='ceremony.php?vid=$_REQUEST[vid]';</script>";
    }
    elseif($_SESSION['reception_selected'] == 'Yes' && (!isset($_SESSION['ceremony_selected']) || $_SESSION['ceremony_selected']=='' || $_SESSION['ceremony_selected']!='Yes'))
    {
      echo "<script>location.href='cocktail-hour.php?vid=$_REQUEST[vid]';</script>";
    }

  }
  else
  {
    echo "<script>location.href='choose-services.php?vid=$_REQUEST[vid]';</script>";
  }

}
?>

<script type="text/javascript">

  $(document).on('change','.vendor_services',function(){

    var chosen_services = '';
    var ceremony = 0;
    var reception = 0;
    var vid = $('#vid').val();

    if($('#venue_service1').val() > 0)
    {
      ceremony = $('#venue_service1').val();
    }
    if($('#venue_service2').val() > 0)
    {
      reception = $('#venue_service2').val();
    }

    if(ceremony > 0 && reception > 0)
    {
      chosen_services = ceremony+','+reception;
    }
    else if(ceremony > 0 && reception == '')
    {
      chosen_services = ceremony;
    }
    else if(reception > 0 && ceremony == '')
    {
      chosen_services = reception;
    }
    
    //if(vid>0 && chosen_services!='')
    //{
      $.post("ajax/service_basis_choose_services_options.php",{vid:vid,chosen_services:chosen_services},
        function(result){
          $('#service_basis_sections').html(result);
      });
    //}

  });

  function check_validation1()
  {
    var vendor_services_ln = 0;
    var day_ln = 0;

    $('.day').each(function () {
      if ($(this).is(':checked')) {
        day_ln = 1;
      }
    });

    if($('#venue_service1').val()==0 && $('#venue_service2').val()==0)
    {
      alert('Choose your service to use the venue for');
      $('.vendor_services').focus();
      return false;
    }
    if(day_ln==0)
    {
      alert('Choose your day of the week to get married');
      $('.day').focus();
      return false;
    }
    if($('#season_id').val()=="")
    {
      alert('Select which month do you plan to get married.');
      $('.season_id').focus();
      return false;
    }

    if($('#venue_service1').val()>0 && ($('#guest_count_ceremony').val()==""||$('#guest_count_ceremony').val()==0))
    {
      alert('Insert your guest count for ceremony.');
      $('.guest_count_ceremony').focus();
      return false;
    }
    if($('#venue_service1').val()>0 && (parseInt($('#guest_count_ceremony').val()) > parseInt($('#ceremony_max_guest').val())))
    {
      alert('Insert your guest count for ceremony within maximum capacity.');
      $('#guest_count_ceremony').val(parseInt($('#ceremony_max_guest').val()));
      $('.guest_count_ceremony').focus();
      return false;
    }
    if($('#venue_service2').val()>0 && ($('#guest_count_reception').val()==""||$('#guest_count_reception').val()==0))
    {
      alert('Insert your guest count for reception.');
      $('.guest_count_reception').focus();
      return false;
    }
    if($('#venue_service2').val()>0 && (parseInt($('#guest_count_reception').val()) > parseInt($('#reception_max_guest').val())))
    {
      alert('Insert your guest count for ceremony within maximum capacity.');
      $('#guest_count_reception').val(parseInt($('#reception_max_guest').val()));
      $('.guest_count_reception').focus();
      return false;
    }
  }
</script>

<section class="vendor steps">
  	<h1><?php echo $business_name;?> <a href="javascript:void(0);"><?php echo $area_name['area_name'];?></a> </h1>

    <?php include('instant_quote_header.php');?>
    
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="venue-rating-form" id="venue-rating-form" onsubmit="return check_validation1();">
        <input type="hidden" name="vid" id="vid" value="<?php echo $_REQUEST['vid'];?>">
        <input type="hidden" name="flag" value="step1">
 
        <h2>Choose Services
                	
          <input type="submit" class="save-continue" value="Save & Continue">
          <a class="back-to-venue-butt" href="venue-details.php?id=<?php echo $_REQUEST['vid'];?>">Back</a>
        </h2>

        <div class="options">
            <p>What services would you like to use the venue for? Select at least one service.</p>
              <?php
              $sql_choose_service1 = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_sevrices WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `status` = 'Y' AND `service_name` = 'Ceremony'";
              $sql_choose_service1 = mysql_query($sql_choose_service1) or die(mysql_error());
              $num_choose_service1 = mysql_num_rows($sql_choose_service1);
              $s = 1;
              if($num_choose_service1 > 0)
              {
                ?>
                <select class="options-text vendor_services" name="venue_service_id[]" id="venue_service1">
                  <option value="0">Select Ceremony</option>
                  <?php
                  while($row_choose_service1 = mysql_fetch_array($sql_choose_service1))
                  {

                      ?>
                      <option value="<?php echo $row_choose_service1['venue_service_id'];?>" <?php if(in_array($row_choose_service1['venue_service_id'], $_SESSION['venue_service_id'])){echo "selected";}?>><?php echo $row_choose_service1['service_type']." ".$row_choose_service1['service_name'];?></option>
                      <?php                      
                  }
                  ?>
                </select>
                <?php
              }    
              $sql_choose_service2 = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_sevrices WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `status` = 'Y' AND `service_name` = 'Reception'";
              $sql_choose_service2 = mysql_query($sql_choose_service2) or die(mysql_error());
              $num_choose_service2 = mysql_num_rows($sql_choose_service2);
              $s = 1;
              if($num_choose_service2 > 0)
              {
                ?>
                <select class="options-text vendor_services" name="venue_service_id[]" id="venue_service2">
                  <option value="0">Select Reception</option>
                  <?php
                  while($row_choose_service2 = mysql_fetch_array($sql_choose_service2))
                  {

                      ?>
                      <option value="<?php echo $row_choose_service2['venue_service_id'];?>" <?php if(in_array($row_choose_service2['venue_service_id'], $_SESSION['venue_service_id'])){echo "selected";}?>><?php echo $row_choose_service2['service_type']." ".$row_choose_service2['service_name'];?></option>
                      <?php                      
                  }
                  ?>
                  </select>
                  <?php
              }    
              ?>    
        </div>        
        
        <div class="options ">
            <p>Which day of the week do you plan to get married?</p>
            <ul>
              <?php

              $sql_choose_days = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_weekdays WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `status` = 'Y'";
              $sql_choose_days = mysql_query($sql_choose_days) or die(mysql_error());
              $num_choose_days = mysql_num_rows($sql_choose_days);
              $d = 1;
              if($num_choose_days > 0)
              {
                  while($row_choose_days = mysql_fetch_array($sql_choose_days))
                  {
                      ?>
                      <li>
                        <input type="radio" name="weekday_id" id="weekday_id<?php echo $row_choose_days['weekday_id'];?>" class="css-checkbox2 day" value="<?php echo $row_choose_days['weekday_id'];?>" <?php if($_SESSION['weekday_id']==$row_choose_days['weekday_id']){echo "checked";}?>/>
                        <label for="weekday_id<?php echo $row_choose_days['weekday_id'];?>" class="css-label2 radGroup1 radGroup1"><?php echo $row_choose_days['weekday_name'];?></label>
                      </li>
                      <?php

                      $d++;
                  }
              }
              ?>               
            </ul>
        </div>        
        <div class="options">
            <p>Which month do you plan to get married? </p>
            <input type="hidden" id="date">
            <div class="calender">
            	<div class="header">
           	    	<!--<img src="images/lft-arr.png" width="6" height="9" alt="left-arrow" class="rgt2"> 
           	    	<img src="images/right-ar.png" width="10" height="12" alt="right-arrow" class="lft2"> 
                	<span id="year"><?php echo date('Y');?></span>-->
                  <span id="year">Select Month</span>
              </div>
              <input type="hidden" name="season_id" id="season_id" value="<?php echo $_SESSION['season_id'];?>">
              <?php
              for($m=1;$m<=12;$m++)
              {
                $monthName = date("M", mktime(0, 0, 0, $m, 10));
                $monthName_full = date("F", mktime(0, 0, 0, $m, 10));

                $sql_month_exist = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_seasons WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `status` = 'Y' AND `season_name` = '".$monthName_full."'";
                $sql_month_exist = mysql_query($sql_month_exist) or die(mysql_error());
                $num_month_exist = mysql_num_rows($sql_month_exist);

                $row_month_exist = mysql_fetch_array($sql_month_exist);
                ?>
                <div class="box <?php if($num_month_exist > 0){echo 'enabled';}else{echo 'disabled';} if(isset($_SESSION['season_id']) && $_SESSION['season_id']==$row_month_exist['season_id']){echo " selected";}?>"  data-id="<?php echo $row_month_exist['season_id'];?>"><?php echo $monthName;?></div>
                <?php
              }

              ?>
            </div>
            <script>
            	$(window).bind('load',function(){
        				var month = "";
        				var year = "";
        				$(".enabled").click(function(e) {
                    month = $(this).html();
                    var season_id = $(this).attr('data-id');
          					//year = $("#year").html();
          					//$("#date").val(month+","+year);
          					$(".options .box").removeClass("selected");
          					$(this).addClass("selected");
                    $('#season_id').val(season_id);

                });
        				/*var cur_year = 2015;
        				$(".lft2").click(function(e) {
        					$("#year").html(++cur_year);
        					year = $("#year").html();
        					$("#date").val(month+","+year);
                        });
        				$(".rgt2").click(function(e) {
        					$("#year").html(--cur_year);
        					year = $("#year").html();
        					$("#date").val(month+","+year);
                        });*/
        			});
            </script>
        </div>     
        
        <div id="service_basis_sections">
          <?php
          if(isset($_SESSION['venue_service_id']) && count($_SESSION['venue_service_id']) > 0)
          {
            
            foreach ($_SESSION['venue_service_id'] as $k1 => $val1) 
            {
              if($k1 == 0)
              {
                $selected_service_ids = " AND (venue_service_id = '".$val1."'";
              }
              else
              {
                $selected_service_ids = $selected_service_ids." OR venue_service_id = '".$val1."'";
              }
            }
            $selected_service_ids = $selected_service_ids.")";
            

            $sql_services = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_sevrices  WHERE  status = 'Y' AND vendor_id = '".$_REQUEST['vid']."'".$selected_service_ids;
            $sql_services = mysql_query($sql_services) or die(mysql_error());
            $vendor_num = mysql_num_rows($sql_services);
            $v=1;
            if($vendor_num > 0)
            {
              while($row_services = mysql_fetch_array($sql_services))
              {
                if($row_services['service_name']=="Reception")
                {
                  if($row_services['max_allowed_hours'] > 0 && $row_services['max_allowed_hours'] > $row_services['service_included_hours'])
                  {
                    $extra_allowed = 'Each extra hour is &pound;'.$row_services['per_extra_hour_price'].' per hour.';
                    $max_limit_hr = $row_services['max_allowed_hours'];
                  }
                  else
                  {
                    $extra_allowed = '';
                    $max_limit_hr = $row_services['service_included_hours'];
                  }
                  ?>
                  <div class="options">
                    <input type="hidden" name="reception_max_guest" id="reception_max_guest" value="<?php echo $row_services['guest_capacity'];?>">
                      <p>What is your guest count for reception? </p>
                      <p><span>The maximum capacity for the reception for this venue is <?php echo $row_services['guest_capacity'];?> guests.</span></p>
                               
                      <input type="text" name="guest_count_reception" id="guest_count_reception" value="<?php echo $_SESSION['guest_count_reception'];?>" class="options-text guest_count_reception">
                  </div>
                  <div class="options">
                      <p>How many hours would you like to reserve for event time?</p>
                      <p><span>*The standard wedding package at this venue includes <?php echo $row_services['service_included_hours'];?> hours. <?php echo $extra_allowed;?></span></p>
                      <select class="options-text" name="selected_hours_reception" id="selected_hours_reception">
                        <?php
                        for($h=1;$h<=$max_limit_hr;$h++)
                        {
                          ?>
                          <option value="<?php echo $h;?>"<?php if($_SESSION['selected_hours_reception']==$h){echo "selected";}elseif($h==$row_services['service_included_hours']){echo "selected";}?>><?php echo $h;?></option>
                          <?php
                        }
                        ?>
                      </select>
                  </div>
                  <?php
                }
                else
                {
                  ?>
                  <div class="options">
                    <input type="hidden" name="ceremony_max_guest" id="ceremony_max_guest" value="<?php echo $row_services['guest_capacity'];?>">
                      <p>What is your guest count for ceremony? </p>
                      <p><span>The maximum capacity for the ceremony for this venue is <?php echo $row_services['guest_capacity'];?> guests.</span></p>
                      <input type="text" name="guest_count_ceremony" id="guest_count_ceremony" value="<?php echo $_SESSION['guest_count_ceremony'];?>" class="options-text guest_count_ceremony">
                  </div>
                  <?php
                }
              }
            }
            else
            {
              ?>
              <div class="no-result">No Result Found.</div>
              <?php
            }
          }

          ?>

        </div>      
        
        <?php
         $sql_venue_others = "SELECT `coordinator_package_included`,`coordinator_rate` FROM ".TABLE_PREFIX."vendor_venue_others WHERE `vendor_id` = '".$_REQUEST['vid']."'";
         $row_venue_others = mysql_fetch_array(mysql_query($sql_venue_others));
         

         if($row_venue_others['coordinator_package_included']=='Y' && !isset($_SESSION['wedding_coordinator']))
         {
            $disabled = 'disabled';
            $yeschecked = ' checked';
         }
         elseif(isset($_SESSION['wedding_coordinator']) && $_SESSION['wedding_coordinator']=='Yes')
         {
            $yeschecked = ' checked';
         }
         elseif(isset($_SESSION['wedding_coordinator']) && $_SESSION['wedding_coordinator']=='No')
         {
            $nochecked = 'checked';
         }
         else
         {
            $disabled = '';
            $yeschecked = ' checked';
            $nochecked = '';
         }
        ?>
        <div class="options thick-border">
            <p>Will you require a day-of wedding coordinator for extra help?</p>
            <ul>
                <li>
                  <input type="radio" name="wedding_coordinator" id="wedding_coordinator1" class="css-checkbox2" value="Yes" <?php echo $disabled.$yeschecked;?>/>
                  <label for="wedding_coordinator1"  class="css-label2 radGroup1 radGroup1">Yes</label>
                </li>
                <li>
                  <input type="radio" name="wedding_coordinator" id="wedding_coordinator2" class="css-checkbox2" value="No" <?php echo $disabled.$nochecked;?>/>
                  <label for="wedding_coordinator2" class="css-label2 radGroup1 radGroup1">No</label>
                </li>

                               
            </ul>
        </div>
        
        <input type="submit" class="save-continue" value="Save & Continue">
        <a class="back-to-venue-butt" href="venue-details.php?id=<?php echo $_REQUEST['vid'];?>">Back</a> 
    </form>
    <div class="clearfix"></div>
</section>

<?php include('footer.php');?>
